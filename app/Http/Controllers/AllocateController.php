<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Inventory;
use Endroid\QrCode\QrCode;
use Illuminate\Http\Request;

use App\Models\AllocateOther;
use App\Models\InventoryItem;
use Endroid\QrCode\Logo\Logo;
use Barryvdh\DomPDF\Facade\Pdf;

use App\Models\AllocateHardware;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\Storage;

class AllocateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $locations = Location::all();
        $selectedLocation = null;
        $allocateHardwares = collect();
        $allocateOthers = collect();

        if ($request->filled('location')) {
            $selectedLocation = Location::find($request->location);

            // Pastikan location ditemukan
            if ($selectedLocation) {
                $allocateHardwares = AllocateHardware::with('location')
                    ->where('location_id', $selectedLocation->id)
                    ->orderBy('desk_number', 'asc')
                    ->paginate(5, ['*'], 'hardware_page');

                $allocateOthers = AllocateOther::with('location')
                    ->where('location_id', $selectedLocation->id)
                    ->orderBy('id', 'asc')
                    ->paginate(5, ['*'], 'other_page');
            }
        }

        return view('allocates.index', compact('allocateHardwares', 'allocateOthers', 'locations', 'selectedLocation'));
    }


    public function show(AllocateHardware $allocateHardware)
    {
        return view('allocates.show', compact('allocateHardware'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function createHardware()
    {
        return view('allocates.create-hardware', [
            'locations' => Location::all(),
            'computers' => InventoryItem::where('status_allocate', 'available')->whereHas('inventory', fn($q) => $q->where('category', 'Computer'))->get(),
            'diskDrives' => InventoryItem::where('status_allocate', 'available')->whereHas('inventory', fn($q) => $q->where('category', 'Disk Drive'))->get(),
            'processors' => InventoryItem::where('status_allocate', 'available')->whereHas('inventory', fn($q) => $q->where('category', 'Processor'))->get(),
            'vgaCards' => InventoryItem::where('status_allocate', 'available')->whereHas('inventory', fn($q) => $q->where('category', 'VGA'))->get(),
            'rams' => InventoryItem::where('status_allocate', 'available')->whereHas('inventory', fn($q) => $q->where('category', 'RAM'))->get(),
            'monitors' => InventoryItem::where('status_allocate', 'available')->whereHas('inventory', fn($q) => $q->where('category', 'Monitor'))->get(),
        ]);
    }

    public function createOther()
    {
        return view('allocates.create-other', [
            'locations' => Location::all(),
            'others' => InventoryItem::where('status_allocate', 'available')->whereHas('inventory', fn($q) => $q->where('category', 'Other'))->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeHardware(Request $request)
    {
        $validated = $request->validate([
            'location_id' => 'required|exists:locations,id',
            'desk_number' => 'nullable|integer|min:1',
            'computer_id' => 'nullable|exists:inventory_items,id',
            'disk_drive_1_id' => 'nullable|exists:inventory_items,id',
            'disk_drive_2_id' => 'nullable|exists:inventory_items,id',
            'processor_id' => 'nullable|exists:inventory_items,id',
            'vga_card_id' => 'nullable|exists:inventory_items,id',
            'ram_id' => 'nullable|exists:inventory_items,id',
            'monitor_id' => 'nullable|exists:inventory_items,id',
            'year_approx' => 'nullable|integer|min:2000|max:' . date('Y'),
            'ups_status' => 'nullable|in:Active,Inactive',
        ]);

        // Cek disk drive 1 dan 2 tidak boleh sama
        if ($validated['disk_drive_1_id'] && $validated['disk_drive_2_id'] && $validated['disk_drive_1_id'] == $validated['disk_drive_2_id']) {
            return back()->with('error', 'Disk Drive 2 tidak boleh sama dengan Disk Drive 1')->withInput();
        }

        // Cek kombinasi unik lokasi + nomor meja
        $exists = AllocateHardware::where('location_id', $validated['location_id'])
            ->where('desk_number', $validated['desk_number'])
            ->exists();

        if ($exists) {
            return back()->with('error', 'Desk number already used in this location.')->withInput();
        }

        $inventoryItemFields = [
            'computer_id', 'disk_drive_1_id', 'disk_drive_2_id',
            'processor_id', 'vga_card_id', 'ram_id', 'monitor_id'
        ];

        // Cek apakah inventory items valid dan belum dialokasikan
        foreach ($inventoryItemFields as $field) {
            if (!empty($validated[$field])) {
                $item = InventoryItem::find($validated[$field]);
                if (!$item) {
                    return back()->with('error', "Selected item for {$field} does not exist.")->withInput();
                }
                // Ganti pengecekan allocated ke status_allocate
                if ($item->status_allocate === 'allocated') {
                    return back()->with('error', "Selected item for {$field} is already allocated.")->withInput();
                }
            }
        }

        // Simpan allocate hardware
        $allocated = AllocateHardware::create([
            'location_id'      => $validated['location_id'],
            'desk_number'      => $validated['desk_number'] ?? null,
            'computer_id'      => $validated['computer_id'] ?? null,
            'disk_drive_1_id'  => $validated['disk_drive_1_id'] ?? null,
            'disk_drive_2_id'  => $validated['disk_drive_2_id'] ?? null,
            'processor_id'     => $validated['processor_id'] ?? null,
            'vga_card_id'      => $validated['vga_card_id'] ?? null,
            'ram_id'           => $validated['ram_id'] ?? null,
            'monitor_id'       => $validated['monitor_id'] ?? null,
            'year_approx'      => $validated['year_approx'] ?? null,
            'ups_status'       => $validated['ups_status'] ?? null,
            'qr_code'          => null,
            'updated_by'       => auth()->id(),
        ]);

        // Tandai inventory_items jadi allocated
        foreach ($inventoryItemFields as $field) {
            if (!empty($validated[$field])) {
                InventoryItem::where('id', $validated[$field])->update(['status_allocate' => 'allocated']);
            }
        }

        // Generate QR Code (sama seperti sebelumnya)
        $qrText = route('allocates.hardware.show', $allocated->id);
        $locationName = str_replace(' ', '', strtoupper($allocated->location->name));
        $deskNumber = $allocated->desk_number;
        $qrFileName = 'QR_' . $locationName . '-' . $deskNumber . '.png';

        $qrCode = new QrCode($qrText);
        $logo = new Logo(public_path('assets/img/logo-iflab.png'), 130);
        $writer = new PngWriter();
        $qrResult = $writer->write($qrCode, $logo);
        Storage::disk('public')->put("qrcodes/{$qrFileName}", $qrResult->getString());

        $allocated->update([
            'qr_code' => "qrcodes/{$qrFileName}"
        ]);

        return redirect()->route('allocates.index', [
            'location' => $allocated->location_id
        ])->with('success', "<strong>Computer Components</strong> successfully allocated to <strong>{$allocated->location->name}, Desk No. <strong>{$allocated->desk_number}</strong>.");
    }

    public function storeOther(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'location_id' => 'required|exists:locations,id',
            'item_id' => 'required|exists:inventory_items,id',
            'description' => 'nullable|string|max:255',
        ]);

        $item = InventoryItem::where('id', $validated['item_id'])
                ->where('status_allocate', 'available')
                ->first();

        if (!$item) {
            return back()->with('error', 'Selected item is not available or already allocated.')->withInput();
        }

        // Tandai jadi allocated
        $item->update(['status_allocate' => 'allocated']);


        // Buat alokasi
        AllocateOther::create([
            'location_id' => $validated['location_id'],
            'item_id' => $item->id,
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->route('allocates.index', ['location' => $validated['location_id']])
                        ->with('success', "Item <strong>{$item->inventory->name}</strong> successfully allocated.");
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function editHardware(AllocateHardware $allocateHardware)
    {
        return view('allocates.edit-hardware', [
            'allocateHardware' => $allocateHardware,
            'locations' => Location::all(),

            'computers' => InventoryItem::where(function($query) use ($allocateHardware) {
                $query->where('status_allocate', 'available')
                    ->orWhere('id', $allocateHardware->computer_id);
            })->whereHas('inventory', fn($q) => $q->where('category', 'Computer'))->get(),

            'diskDrives' => InventoryItem::where(function($query) use ($allocateHardware) {
                $query->where('status_allocate', 'available')
                    ->orWhere('id', $allocateHardware->disk_drive_1_id)
                    ->orWhere('id', $allocateHardware->disk_drive_2_id);
            })->whereHas('inventory', fn($q) => $q->where('category', 'Disk Drive'))->get(),

            'processors' => InventoryItem::where(function($query) use ($allocateHardware) {
                $query->where('status_allocate', 'available')
                    ->orWhere('id', $allocateHardware->processor_id);
            })->whereHas('inventory', fn($q) => $q->where('category', 'Processor'))->get(),

            'vgaCards' => InventoryItem::where(function($query) use ($allocateHardware) {
                $query->where('status_allocate', 'available')
                    ->orWhere('id', $allocateHardware->vga_card_id);
            })->whereHas('inventory', fn($q) => $q->where('category', 'VGA'))->get(),

            'rams' => InventoryItem::where(function($query) use ($allocateHardware) {
                $query->where('status_allocate', 'available')
                    ->orWhere('id', $allocateHardware->ram_id);
            })->whereHas('inventory', fn($q) => $q->where('category', 'RAM'))->get(),

            'monitors' => InventoryItem::where(function($query) use ($allocateHardware) {
                $query->where('status_allocate', 'available')
                    ->orWhere('id', $allocateHardware->monitor_id);
            })->whereHas('inventory', fn($q) => $q->where('category', 'Monitor'))->get(),
        ]);
    }

    public function editOther(AllocateOther $allocateOther)
    {
        return view('allocates.edit-other', [
            'allocateOther' => $allocateOther,
            'locations' => Location::all(),

            'others' => InventoryItem::where(function($query) use ($allocateOther) {
                $query->where('status_allocate', 'available')
                    ->orWhere('id', $allocateOther->item_id); // ini penting supaya item yang sudah dialokasikan tetap muncul
            })->whereHas('inventory', fn($q) => $q->where('category', 'Other'))->get(),
        ]);
    }
    
    /**
     * Update the specified resource in storage.
     */
    public function updateHardware(Request $request, AllocateHardware $allocateHardware)
    {
        $validated = $request->validate([
            'location_id' => 'required|exists:locations,id',
            'desk_number' => 'nullable|integer|min:1',
            'computer_id' => 'nullable|exists:inventory_items,id',
            'disk_drive_1_id' => 'nullable|exists:inventory_items,id',
            'disk_drive_2_id' => 'nullable|exists:inventory_items,id',
            'processor_id' => 'nullable|exists:inventory_items,id',
            'vga_card_id' => 'nullable|exists:inventory_items,id',
            'ram_id' => 'nullable|exists:inventory_items,id',
            'monitor_id' => 'nullable|exists:inventory_items,id',
            'year_approx' => 'nullable|integer|min:2000|max:' . date('Y'),
            'ups_status' => 'nullable|in:Active,Inactive',
        ]);

        // Cek disk drive 1 dan 2 tidak boleh sama
        if ($validated['disk_drive_1_id'] && $validated['disk_drive_2_id'] && $validated['disk_drive_1_id'] == $validated['disk_drive_2_id']) {
            return back()->with('error', 'Disk Drive 2 tidak boleh sama dengan Disk Drive 1')->withInput();
        }

        // Cek jika kombinasi lokasi + desk number sudah digunakan oleh alokasi lain
        $exists = AllocateHardware::where('location_id', $validated['location_id'])
            ->where('desk_number', $validated['desk_number'])
            ->where('id', '!=', $allocateHardware->id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Desk number already used in this location.')->withInput();
        }

        // Simpan item lama untuk cek perubahan status_allocate
        $inventoryFields = [
            'computer_id', 'disk_drive_1_id', 'disk_drive_2_id',
            'processor_id', 'vga_card_id', 'ram_id', 'monitor_id'
        ];

        $oldItems = [];
        foreach ($inventoryFields as $field) {
            $oldItems[$field] = $allocateHardware->$field;
        }

        // Update data alokasi hardware
        $allocateHardware->update($validated);

        // Update status_allocate untuk item lama yang sudah tidak dipakai lagi jadi 'available'
        foreach ($inventoryFields as $field) {
            $oldItemId = $oldItems[$field];
            $newItemId = $validated[$field] ?? null;

            if ($oldItemId && $oldItemId != $newItemId) {
                InventoryItem::where('id', $oldItemId)->update(['status_allocate' => 'available']);
            }
        }

        // Update status_allocate untuk item baru jadi 'allocated'
        foreach ($inventoryFields as $field) {
            $newItemId = $validated[$field] ?? null;

            if ($newItemId) {
                InventoryItem::where('id', $newItemId)->update(['status_allocate' => 'allocated']);
            }
        }

        // Rename QR Code jika lokasi atau desk_number berubah
        $oldLocation = $allocateHardware->location->name;
        $oldDesk = $allocateHardware->desk_number;

        $newLocation = Location::find($validated['location_id'])->name;
        $newDesk = $validated['desk_number'];

        if (($oldLocation !== $newLocation || $oldDesk != $newDesk) && $allocateHardware->qr_code) {
            $oldFileName = $allocateHardware->qr_code;
            $newFileName = 'qrcodes/QR_' . strtoupper(str_replace(' ', '', $newLocation)) . '-' . $newDesk . '.png';

            if (Storage::disk('public')->exists($oldFileName)) {
                Storage::disk('public')->move($oldFileName, $newFileName);
                $allocateHardware->update(['qr_code' => $newFileName]);
            }
        }

        $allocateHardware->update(array_merge(
            $validated,
            ['updated_by' => auth()->id()]
        ));

        return redirect()->route('allocates.index', [
            'location' => $allocateHardware->location_id
        ])->with('success', "<strong>Computer Components</strong> has been successfully updated at <strong>{$allocateHardware->location->name}, Desk No. {$allocateHardware->desk_number}</strong>.");
    }

    public function updateOther(Request $request, AllocateOther $allocateOther)
    {
        $validated = $request->validate([
            'location_id'  => 'required|exists:locations,id',
            'item_id'      => 'required|exists:inventory_items,id',
            'description'  => 'nullable|string|max:255',
        ]);

        // Jika item baru sama dengan yang lama, hanya update lokasi & deskripsi saja
        if ($allocateOther->item_id != $validated['item_id']) {
            // Cari item baru yang statusnya available
            $newItem = InventoryItem::where('id', $validated['item_id'])
                        ->where('status_allocate', 'available')
                        ->first();

            if (!$newItem) {
                return back()->with('error', 'Selected item is not available or already allocated.')->withInput();
            }

            // Bebaskan item lama
            $oldItem = $allocateOther->item;
            $oldItem->update(['status_allocate' => 'available']);

            // Tandai item baru jadi allocated
            $newItem->update(['status_allocate' => 'allocated']);

            // Update item_id di allocate_other
            $allocateOther->item_id = $newItem->id;
        }

        // Update lokasi dan deskripsi
        $allocateOther->location_id = $validated['location_id'];
        $allocateOther->description = $validated['description'] ?? null;

        $allocateOther->save();

        return redirect()->route('allocates.index', ['location' => $validated['location_id']])
            ->with('success', "Allocation has been successfully updated.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroyHardware(AllocateHardware $allocateHardware)
    {
        // Daftar field komponen yang merujuk ke inventory_items (per unit)
        $inventoryItemFields = [
            'computer_id', 'disk_drive_1_id', 'disk_drive_2_id',
            'processor_id', 'vga_card_id', 'ram_id', 'monitor_id'
        ];

        // Tandai ulang setiap inventory_item yang dialokasikan jadi 'available'
        foreach ($inventoryItemFields as $field) {
            if ($allocateHardware->$field) {
                $item = InventoryItem::find($allocateHardware->$field);
                if ($item) {
                    $item->update(['status_allocate' => 'available']);
                }
            }
        }

        // Hapus file QR code jika ada
        if ($allocateHardware->qr_code && Storage::disk('public')->exists($allocateHardware->qr_code)) {
            Storage::disk('public')->delete($allocateHardware->qr_code);
        }

        $locationName = $allocateHardware->location->name ?? 'Unknown Location';
        $deskNumber = $allocateHardware->desk_number ?? '-';

        // Hapus data alokasi
        $allocateHardware->delete();

        return redirect()->route('allocates.index', [
            'location' => $allocateHardware->location_id
        ])->with('success', "Deleted <strong>Computer Components</strong> from <strong>{$locationName}</strong>, Desk No. <strong>{$deskNumber}</strong>. Inventory items are now available.");
    }

    public function destroyOther(AllocateOther $allocateOther)
    {
        // Ambil inventory item yang dialokasikan
        $item = $allocateOther->item; // pastikan di model AllocateOther ada relasi item()

        if ($item) {
            // Tandai item kembali jadi 'available'
            $item->update(['status_allocate' => 'available']);
        }

        // Hapus data alokasi
        $allocateOther->delete();

        return redirect()->route('allocates.index', ['location' => $allocateOther->location_id])
                        ->with('success', "Deleted <strong>{$allocateOther->item->inventory->name}</strong> from <strong>{$allocateOther->location->name}</strong>. Item is now available again.");
    }

    public function exportPdf(Request $request)
    {
        $locationId = $request->location;

        $location = Location::find($locationId);
        if (!$location) {
            return redirect()->route('allocates.index')->with('error', 'Location not found.');
        }

        $allocateHardware = AllocateHardware::with([
            'computer', 'processor', 'diskDrive1', 'diskDrive2',
            'vgaCard', 'ram', 'monitor'
        ])
        ->where('location_id', $locationId)
        ->get();

        $allocateOther = AllocateOther::with([
            'item.inventory' // load item dan inventory terkait untuk detail nama dll
        ])
        ->where('location_id', $locationId)
        ->get();

        $pdf = Pdf::loadView('allocates.partials.allocated_report', compact('allocateHardware', 'allocateOther', 'location'))
            ->setPaper('a4', 'portrait');

        return $pdf->download("Allocations_{$location->name}.pdf");
    }
}
