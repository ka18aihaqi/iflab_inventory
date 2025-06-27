<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Inventory;
use Endroid\QrCode\QrCode;
use Illuminate\Http\Request;

use App\Models\AllocateOther;
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

            $allocateHardwares = AllocateHardware::with('location')
                ->where('location_id', $request->location)
                ->orderBy('desk_number', 'asc')
                ->paginate(5, ['*'], 'hardware_page');

            $allocateOthers = AllocateOther::with('location')
                ->where('location_id', $request->location)
                ->orderBy('id', 'asc')
                ->paginate(5, ['*'], 'other_page');
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
    public function create()
    {
        return view('allocates.create', [
            'locations' => Location::all(),
            'computers' => Inventory::whereHas('itemType', fn($q) => $q->where('name', 'Computer'))->get(),
            'diskDrives' => Inventory::whereHas('itemType', fn($q) => $q->where('name', 'Disk Drive'))->get(),
            'processors' => Inventory::whereHas('itemType', fn($q) => $q->where('name', 'Processor'))->get(),
            'vgaCards' => Inventory::whereHas('itemType', fn($q) => $q->where('name', 'VGA'))->get(),
            'rams' => Inventory::whereHas('itemType', fn($q) => $q->where('name', 'RAM'))->get(),
            'monitors' => Inventory::whereHas('itemType', fn($q) => $q->where('name', 'Monitor'))->get(),
            'others' => Inventory::whereHas('itemType', fn($q) => $q->where('name', 'Other Items'))->get(),
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
            'computer_id' => 'nullable|exists:inventories,id',
            'disk_drive_1_id' => 'nullable|exists:inventories,id',
            'disk_drive_2_id' => 'nullable|exists:inventories,id',
            'processor_id' => 'nullable|exists:inventories,id',
            'vga_card_id' => 'nullable|exists:inventories,id',
            'ram_id' => 'nullable|exists:inventories,id',
            'monitor_id' => 'nullable|exists:inventories,id',
            'year_approx' => 'nullable|integer|min:2000|max:' . date('Y'),
            'ups_status' => 'nullable|in:Active,Inactive',
        ]);

        // Cek kombinasi unik lokasi + nomor meja
        $exists = AllocateHardware::where('location_id', $validated['location_id'])
            ->where('desk_number', $validated['desk_number'])
            ->exists();

        if ($exists) {
            session()->flash('error', 'Desk number already used in this location.');
            return back()->withInput();
        }

        $inventoryFields = [
            'computer_id', 'disk_drive_1_id', 'disk_drive_2_id',
            'processor_id', 'vga_card_id', 'ram_id', 'monitor_id'
        ];

        // Check Stock
            foreach ($inventoryFields as $field) {
                if (!empty($validated[$field])) {
                    $inventory = Inventory::find($validated[$field]);

                    if (!$inventory || $inventory->stock <= 0) {
                        return back()->with('error', 'One of the components is out of stock and cannot be used.')->withInput();
                    }

                }
            }

        $allocated = AllocateHardware::create([
            'location_id'      => $validated['location_id'],
            'desk_number'      => $validated['desk_number'],
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
        ]);

        // Reduce Stock
            foreach ($inventoryFields as $field) {
                if (!empty($validated[$field])) {
                    Inventory::where('id', $validated[$field])->decrement('stock');
                }
            }

        // QR Code
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
        ])->with('success', "<strong>Computer Components</strong> has been successfully allocated to <strong>{$allocated->location->name}, Desk No. <strong>{$allocated->desk_number}</strong>.");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeOther(Request $request)
    {
        $validated = $request->validate([
            'location_id'  => 'required|exists:locations,id',
            'others_id'    => 'required|exists:inventories,id',
            'quantity'     => 'required|integer|min:1',
            'description'  => 'nullable|string|max:255',
        ]);

        // Ambil data inventory
        $inventory = Inventory::find($validated['others_id']);

        if ($inventory->stock < $validated['quantity']) {
            return back()->with('error', 'Not enough stock for the selected item.')->withInput();
        }

        // Cek apakah alokasi untuk location_id + others_id sudah ada
        $existingAllocation = AllocateOther::where('location_id', $validated['location_id'])
            ->where('others_id', $validated['others_id'])
            ->first();

        if ($existingAllocation) {
            // Tambah quantity
            $existingAllocation->increment('quantity', $validated['quantity']);

            // Jika ada deskripsi baru, update juga deskripsinya
            if (!empty($validated['description'])) {
                $existingAllocation->update(['description' => $validated['description']]);
            }
        } else {
            // Buat alokasi baru
            $existingAllocation = AllocateOther::create([
                'location_id' => $validated['location_id'],
                'others_id'   => $validated['others_id'],
                'quantity'    => $validated['quantity'],
                'description' => $validated['description'],
            ]);
        }

        // Kurangi stok
        $inventory->decrement('stock', $validated['quantity']);

        return redirect()->route('allocates.index', ['location' => $validated['location_id']])
                        ->with('success', "<strong>{$existingAllocation->others->name}</strong> has been successfully allocated to <strong>{$existingAllocation->location->name}</strong>.");
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function editHardware(AllocateHardware $allocateHardware)
    {
        return view('allocates.edit-hardware', [
            'allocateHardware' => $allocateHardware,
            'locations' => Location::all(),
            'computers' => Inventory::whereHas('itemType', fn($q) => $q->where('name', 'Computer'))->get(),
            'diskDrives' => Inventory::whereHas('itemType', fn($q) => $q->where('name', 'Disk Drive'))->get(),
            'processors' => Inventory::whereHas('itemType', fn($q) => $q->where('name', 'Processor'))->get(),
            'vgaCards' => Inventory::whereHas('itemType', fn($q) => $q->where('name', 'VGA'))->get(),
            'rams' => Inventory::whereHas('itemType', fn($q) => $q->where('name', 'RAM'))->get(),
            'monitors' => Inventory::whereHas('itemType', fn($q) => $q->where('name', 'Monitor'))->get(),
        ]);
    }

    public function editOther(AllocateOther $allocateOther)
    {
        return view('allocates.edit-other', [
            'allocateOther' => $allocateOther,
            'locations' => Location::all(),
            'others' => Inventory::whereHas('itemType', fn($q) => $q->where('name', 'Other Items'))->get(),
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
            'computer_id' => 'nullable|exists:inventories,id',
            'disk_drive_1_id' => 'nullable|exists:inventories,id',
            'disk_drive_2_id' => 'nullable|exists:inventories,id',
            'processor_id' => 'nullable|exists:inventories,id',
            'vga_card_id' => 'nullable|exists:inventories,id',
            'ram_id' => 'nullable|exists:inventories,id',
            'monitor_id' => 'nullable|exists:inventories,id',
            'year_approx' => 'nullable|integer|min:2000|max:' . date('Y'),
            'ups_status' => 'nullable|in:Active,Inactive',
        ]);

        // Cek jika kombinasi lokasi + desk number sudah digunakan oleh alokasi lain
        $exists = AllocateHardware::where('location_id', $validated['location_id'])
            ->where('desk_number', $validated['desk_number'])
            ->where('id', '!=', $allocateHardware->id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Desk number already used in this location.')->withInput();
        }

        // Daftar field komponen
        $inventoryFields = [
            'computer_id', 'disk_drive_1_id', 'disk_drive_2_id',
            'processor_id', 'vga_card_id', 'ram_id', 'monitor_id'
        ];

        // Loop untuk cek dan update stok
        foreach ($inventoryFields as $field) {
            $oldValue = $allocateHardware->$field;
            $newValue = $validated[$field] ?? null;

            if ($oldValue != $newValue) {
                // Kembalikan stok komponen lama
                if ($oldValue) {
                    Inventory::where('id', $oldValue)->increment('stock');
                }

                // Kurangi stok komponen baru jika tersedia
                if ($newValue) {
                    $inventory = Inventory::find($newValue);
                    if (!$inventory || $inventory->stock <= 0) {
                        return back()->with('error', "One of the components is out of stock and cannot be used.")->withInput();
                    }
                    $inventory->decrement('stock');
                }
            }
        }

        // Simpan lokasi dan desk number lama
        $oldLocation = $allocateHardware->location->name;
        $oldDesk = $allocateHardware->desk_number;

        // Update data
        $allocateHardware->update($validated);

        // Bandingkan apakah lokasi atau desk number berubah
        $newLocation = Location::find($validated['location_id'])->name;
        $newDesk = $validated['desk_number'];

        if ($oldLocation !== $newLocation || $oldDesk != $newDesk) {
            $oldFileName = $allocateHardware->qr_code;
            $newFileName = 'qrcodes/QR_' . strtoupper(str_replace(' ', '', $newLocation)) . '-' . $newDesk . '.png';

            if ($oldFileName && Storage::disk('public')->exists($oldFileName)) {
                Storage::disk('public')->move($oldFileName, $newFileName);
                $allocateHardware->update(['qr_code' => $newFileName]);
            }
        }

        return redirect()->route('allocates.index', [
            'location' => $allocateHardware->location_id
        ])->with('success', "<strong>Computer Components</strong> has been successfully updated at <strong>{$allocateHardware->location->name}, Desk No. {$allocateHardware->desk_number}</strong>.");

    }

    public function updateOther(Request $request, AllocateOther $allocateOther)
    {
        $validated = $request->validate([
            'location_id'  => 'required|exists:locations,id',
            'others_id'    => 'required|exists:inventories,id',
            'quantity'     => 'required|integer|min:1',
            'description'  => 'nullable|string|max:255',
        ]);

        $oldInventory = $allocateOther->others; // relasi ke inventory lama
        $oldQuantity = $allocateOther->quantity;

        // Kembalikan stok lama (rollback dulu)
        $oldInventory->increment('stock', $oldQuantity);

        // Ambil inventory baru
        $newInventory = Inventory::find($validated['others_id']);

        // Cek stok inventory baru cukup atau tidak
        if ($newInventory->stock < $validated['quantity']) {
            // Jika tidak cukup, kembalikan stok lama ke posisi sebelumnya
            $oldInventory->decrement('stock', $oldQuantity);
            return back()->with('error', 'Not enough stock for the selected item.')->withInput();
        }

        // Update data alokasi
        $allocateOther->update([
            'location_id' => $validated['location_id'],
            'others_id'   => $validated['others_id'],
            'quantity'    => $validated['quantity'],
            'description' => $validated['description'],
        ]);

        // Kurangi stok inventory baru
        $newInventory->decrement('stock', $validated['quantity']);

        return redirect()->route('allocates.index', ['location' => $validated['location_id']])
                        ->with('success', "<strong>{$allocateOther->others->name}</strong> has been successfully updated in <strong>{$allocateOther->location->name}</strong>.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroyHardware(AllocateHardware $allocateHardware)
    {
        // Daftar field komponen
        $inventoryFields = [
            'computer_id', 'disk_drive_1_id', 'disk_drive_2_id',
            'processor_id', 'vga_card_id', 'ram_id', 'monitor_id'
        ];

        // Kembalikan stok semua komponen yang terpakai
        foreach ($inventoryFields as $field) {
            if (!empty($allocateHardware->$field)) {
                Inventory::where('id', $allocateHardware->$field)->increment('stock');
            }
        }

        // Hapus file QR code jika ada
        if ($allocateHardware->qr_code && Storage::disk('public')->exists($allocateHardware->qr_code)) {
            Storage::disk('public')->delete($allocateHardware->qr_code);
        }

        // Hapus data alokasi
        $allocateHardware->delete();

        return redirect()->route('allocates.index', [
            'location' => $allocateHardware->location_id
        ])->with('success', "Deleted <strong>Computer Components</strong> from <strong>{$allocateHardware->location->name}</strong>, Desk No. <strong>{$allocateHardware->desk_number}</strong>. Stock has been restored.");
    }

    public function destroyOther(AllocateOther $allocateOther)
    {
        // Ambil data inventory terkait
        $inventory = Inventory::find($allocateOther->others_id);

        // Jika inventory masih ada, kembalikan stok
        if ($inventory) {
            $inventory->increment('stock', $allocateOther->quantity);
        }

        // Hapus data alokasi
        $allocateOther->delete();

        return redirect()->route('allocates.index', ['location' => $allocateOther->location_id])
                        ->with('success', "Deleted <strong>{$allocateOther->others->name}</strong> from <strong>{$allocateOther->location->name}</strong>. Stock has been restored.");
    }

    public function exportPdf(Request $request)
    {
        $locationId = $request->location;

        if (!$locationId || !Location::find($locationId)) {
            return redirect()->route('allocates.index')->with('error', 'Location not found.');
        }

        $location = Location::find($locationId);
        $allocateHardware = AllocateHardware::with([
            'computer', 'processor', 'diskDrive1', 'diskDrive2',
            'vgaCard', 'ram', 'monitor'
        ])
        ->where('location_id', $locationId)
        ->get();

        $allocateOther = AllocateOther::with([
            'others'
        ])
        ->where('location_id', $locationId)
        ->get();

        $pdf = Pdf::loadView('allocates.partials.allocated_report', compact('allocateHardware', 'allocateOther', 'location'))
            ->setPaper('a4', 'portrait');

        return $pdf->download("Allocations_{$location->name}.pdf");
    }
}
