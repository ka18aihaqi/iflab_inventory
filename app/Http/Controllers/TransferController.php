<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\TransferLog;

use Illuminate\Http\Request;
use App\Models\AllocateOther;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\AllocateHardware;
use Illuminate\Support\Facades\DB;

class TransferController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = TransferLog::query();

        // Jika ada filter tanggal
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $transferLogs = $query->latest()->paginate(10);

        return view('transfers.index', compact('transferLogs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('transfers.create', [
            'hardwareAllocations' => AllocateHardware::with(['location', 'computer', 'diskDrive1', 'diskDrive2', 'processor', 'vgaCard', 'ram', 'monitor'])->get(),
            'otherAllocations' => AllocateOther::with(['location', 'others'])->get(),
            'locations' => Location::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $itemType = $request->item_type_id;

        if (!$itemType) {
            return back()->withErrors(['item_type_id' => 'Item type not found.']);
        }

        if ($itemType === 'hardware') {
            if (
                $request->from_location == $request->to_location &&
                $request->from_desk_number == $request->to_desk_number
            ) {
                return back()->with('error', 'Source location and desk number must not be the same as the destination.')->withInput();
            }

            if (empty($request->components_from)) {
                return back()->with('error', 'Please select at least one component to transfer.')->withInput();
            }

            $request->validate([
                'from_location' => 'required|exists:locations,id',
                'from_desk_number' => 'required|integer',
                'to_location' => 'required|exists:locations,id',
                'to_desk_number' => 'required|integer',
                'components_from' => 'required|array',
                'components_from.*' => 'in:computer,processor,ram,vga_card,disk_drive1,disk_drive2,monitor',
                'note' => 'nullable|string',
            ]);

            try {
                DB::beginTransaction();

                $from = AllocateHardware::where('location_id', $request->from_location)
                    ->where('desk_number', $request->from_desk_number)
                    ->firstOrFail();

                $to = AllocateHardware::firstOrNew(
                    ['location_id' => $request->to_location, 'desk_number' => $request->to_desk_number]
                );

                $fromLocationName = $from->location->name . ' - Desk No. ' . $from->desk_number;
                $toLocationName = $to->location->name . ' - Desk No. ' . $request->to_desk_number;

                foreach ($request->components_from as $componentKey) {
                    $fieldFrom = $componentKey . '_id';

                    // Tangani nama field khusus
                    if ($componentKey === 'disk_drive1') $fieldFrom = 'disk_drive_1_id';
                    if ($componentKey === 'disk_drive2') $fieldFrom = 'disk_drive_2_id';

                    $inventoryId = $from->{$fieldFrom};

                    if (!$inventoryId) continue;

                    $component = optional($from->{$componentKey});
                    $itemName = $component->name ?? ucfirst($componentKey);
                    if ($component->description) {
                        $itemName .= " ({$component->description})";
                    }

                    // Simpan log transfer
                    TransferLog::create([
                        'item_name'     => $itemName,
                        'from_location' => $fromLocationName,
                        'to_location'   => $toLocationName,
                        'quantity'      => 1,
                        'note'          => $request->note,
                    ]);

                    // Pindahkan komponen dari 'from' ke 'to'
                    $to->{$fieldFrom} = $inventoryId;
                    $from->{$fieldFrom} = null;
                }

                $from->save();
                $to->save();

                DB::commit();

                return redirect()->route('transfers.index', ['date' => now()->toDateString()])
                    ->with('success', 'Transfer has been successfully recorded.');
            } catch (\Exception $e) {
                DB::rollBack();
                return back()->with('error', 'An unexpected error occurred while processing the transfer.')->withInput();
            }
        }

        if ($itemType === 'other') {
            if (
                $request->from_location == $request->to_location
            ) {
                return back()->with('error', 'Source location and desk number must not be the same as the destination.')->withInput();
            }

            $request->validate([
                'from_location' => 'required|exists:locations,id',
                'to_location' => 'required|exists:locations,id',
                'other_item'   => 'required|exists:inventories,id',
                'quantity'     => 'required|integer|min:1',
                'note'         => 'nullable|string',
            ]);

            // Ambil data alokasi dari lokasi asal
            $allocation = AllocateOther::where('location_id', $request->from_location)
                ->where('others_id', $request->other_item)
                ->firstOrFail();

            // Pastikan stok mencukupi
            if ($request->quantity > $allocation->quantity) {
                return back()
                ->with('error', 'Quantity exceeds available stock.')
                ->withInput();
            }

            // Ambil nama lokasi untuk log
            $fromLocationName = $allocation->location->name;
            $toLocationName = Location::findOrFail($request->to_location)->name;

            // Simpan log transfer
            TransferLog::create([
                'item_name'     => $allocation->others->name . 
                                ($allocation->others->description ? " ({$allocation->others->description})" : ''),
                'from_location' => $fromLocationName,
                'to_location'   => $toLocationName,
                'quantity'      => $request->quantity,
                'note'          => $request->note,
            ]);

            // Kurangi alokasi di lokasi asal
            $allocation->quantity -= $request->quantity;
            $allocation->save();

            // Tambahkan alokasi ke lokasi tujuan (buat baru jika belum ada)
            $toAllocation = AllocateOther::firstOrNew([
                'location_id' => $request->to_location,
                'others_id'   => $request->other_item,
            ]);

            $toAllocation->quantity += $request->quantity;
            $toAllocation->save();

            return redirect()->route('transfers.index', ['date' => now()->toDateString()])
                ->with('success', 'Transfer has been successfully recorded.');
        }

        return back()->with('error', 'Sorry, this item type is not supported yet.');
    }

    public function exportPdf(Request $request)
    {
        $date = $request->input('date');

        // Filter data berdasarkan tanggal (created_at)
        $query = TransferLog::query();

        if ($date) {
            $query->whereDate('created_at', $date);
        }

        $transfers = $query->orderBy('created_at', 'desc')->get();

        $pdf = Pdf::loadView('transfers.partials.transfer-logs', [
            'transfers' => $transfers,
            'date' => $date,
        ]);

        return $pdf->download('transfer-log-' . ($date ?? now()->format('Y-m-d')) . '.pdf');
    }
}
