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
            'hardwareAllocations' => AllocateHardware::with([
                'location',
                'computer.inventory',
                'diskDrive1.inventory',
                'diskDrive2.inventory',
                'processor.inventory',
                'vgaCard.inventory',
                'ram.inventory',
                'monitor.inventory'
            ])->get(),

            'otherAllocations' => AllocateOther::with(['location', 'item.inventory'])->get(),

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

            // dd($request->all());

            $request->validate([
                'item_type_id' => 'required|in:hardware,other',
                'from_location' => 'required|exists:locations,id',
                'from_desk_number' => 'required|integer',
                'to_location' => 'required|exists:locations,id',
                'to_desk_number' => 'required|integer',
                'components_from' => 'required|array|min:1',
                'components_from.*' => 'in:computer,processor,ram,vga_card,disk_drive1,disk_drive2,monitor',
                'note' => 'nullable|string',
            ]);

            if (
                $request->from_location == $request->to_location &&
                $request->from_desk_number == $request->to_desk_number
            ) {
                return back()->with('error', 'Source location and desk number must not be the same as the destination.')->withInput();
            }

            if (empty($request->components_from)) {
                return back()->with('error', 'Please select at least one component to transfer.')->withInput();
            }

            try {
                DB::beginTransaction();

                $from = AllocateHardware::where('location_id', $request->from_location)
                    ->where('desk_number', $request->from_desk_number)
                    ->firstOrFail();

                $to = AllocateHardware::firstOrNew(
                    ['location_id' => $request->to_location, 'desk_number' => $request->to_desk_number]
                );

                $fromLocationName = Location::find($from->location_id)->name . ' - Meja. ' . $from->desk_number;
                $toLocationName = Location::find($request->to_location)->name . ' - Meja. ' . $request->to_desk_number;

                foreach ($request->components_from as $componentKey) {
                    $fieldFrom = $componentKey . '_id';
                    if ($componentKey === 'disk_drive1') $fieldFrom = 'disk_drive_1_id';
                    if ($componentKey === 'disk_drive2') $fieldFrom = 'disk_drive_2_id';

                    $inventoryId = $from->{$fieldFrom};
                    if (!$inventoryId) continue;

                    $component = optional($from->{$componentKey});

                    TransferLog::create([
                        'item_id'       => $inventoryId,
                        'from_location' => $fromLocationName,
                        'to_location'   => $toLocationName,
                        'note'          => $request->note,
                        'transferred_by'=> auth()->id(),
                    ]);

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
                return back()->with('error', 'An unexpected error occurred while processing the transfer: ' . $e->getMessage())->withInput();
            }

        }

        if ($itemType === 'other') {

            // dd($request->all());

            $request->validate([
                'from_location' => 'required|exists:locations,id',
                'to_location'   => 'required|exists:locations,id',
                'other_item'    => 'required|exists:inventory_items,id', // sekarang langsung ke inventory_items.id
                'note'          => 'nullable|string',
            ]);

            if ($request->from_location == $request->to_location) {
                return back()->with('error', 'Source location and destination location must not be the same.')->withInput();
            }

            // Cari alokasi yang menunjuk ke inventory_item dan lokasi asal
            $allocation = AllocateOther::where('location_id', $request->from_location)
                ->where('item_id', $request->other_item)
                ->first();

            if (!$allocation) {
                return back()->with('error', 'No allocation found for the selected item in the source location.')->withInput();
            }

            // Nama lokasi untuk log
            $fromLocationName = $allocation->location->name;
            $toLocationName = Location::findOrFail($request->to_location)->name;

            // Simpan log transfer
            TransferLog::create([
                'item_id'       => $allocation->item_id,
                'from_location' => $fromLocationName,
                'to_location'   => $toLocationName,
                'note'          => $request->note,
                'transferred_by'=> auth()->id(),
            ]);

            // Update lokasi alokasi ke lokasi tujuan
            $allocation->location_id = $request->to_location;
            $allocation->save();

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
