<?php

namespace App\Http\Controllers;

use Illuminate\Validation\Rule;
use App\Models\Inventory;
use App\Models\InventoryItem;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Inventory::query();

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%")
                ->orWhere('category', 'like', "%{$search}%")
                ->orWhere('total_quantity', 'like', "%{$search}%");
            });
        }

        $inventories = $query->orderBy('category')->paginate(10);
        $categories = Inventory::CATEGORIES;

        // Kalau request AJAX → kirim HTML tbody saja
        if ($request->ajax()) {
            return view('inventories.partials.table-body', compact('inventories'))->render();
        }

        return view('inventories.index', compact('inventories', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('inventories.create', [
            'categories' => Inventory::CATEGORIES
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());

        if (!in_array($request->category, \App\Models\Inventory::CATEGORIES)) {
            return back()->withErrors(['category' => 'Invalid category selected.']);
        }

        if ($request->category === 'Computer') {
            // Validasi input
            $validated = $request->validate([
                'category' => ['required', Rule::in(Inventory::CATEGORIES)],
                'brand'    => 'required|string',
                'model'    => 'nullable|string',
            ]);

            // Cek apakah sudah ada inventory dengan kategori + brand + model yang sama
            $exists = Inventory::where('category', $validated['category'])
                ->where('name', $validated['brand'])
                ->where('description', $validated['model'])
                ->exists();

            if ($exists) {
                return back()
                    ->withErrors(['brand' => 'Barang dengan kategori dan merek/model ini sudah ada.'])
                    ->withInput();
            }

            // Simpan data baru
            $inventory = Inventory::create([
                'category'       => $validated['category'],
                'name'           => $validated['brand'],
                'description'    => $validated['model'],
                'total_quantity' => 0 // awalnya 0, nanti bertambah kalau inventory_items dibuat
            ]);

            return redirect()->route('inventories.index')
                ->with('success', "<strong>{$inventory->name} - {$inventory->description}</strong> has been added to the inventory.");
        }

        if ($request->category === 'Disk Drive') {
            // Validasi input
            $validated = $request->validate([
                'category' => ['required', Rule::in(Inventory::CATEGORIES)],
                'brand'    => 'required|string',
                'type'     => 'required|string|in:HDD,SSD',
                'size'     => 'required|integer|min:1', // kapasitas GB
            ]);

            // Gabungkan spesifikasi ke description
            $description = "{$validated['size']} GB - {$validated['type']}";

            // Cek apakah sudah ada barang yang sama
            $exists = Inventory::where('category', $validated['category'])
                ->where('name', $validated['brand'])
                ->where('description', $description)
                ->exists();

            if ($exists) {
                return back()
                    ->withErrors(['brand' => 'Disk Drive dengan spesifikasi ini sudah ada.'])
                    ->withInput();
            }

            // Simpan data baru
            $inventory = Inventory::create([
                'category'       => $validated['category'],
                'name'           => $validated['brand'],
                'description'    => $description,
                'total_quantity' => 0 // awalnya 0
            ]);

            return redirect()->route('inventories.index')
                ->with('success', "<strong>{$inventory->name} - {$inventory->description}</strong> has been added to the inventory.");
        }

        if ($request->category === 'Processor') {
            // Validasi input
            $validated = $request->validate([
                'category'   => ['required', Rule::in(Inventory::CATEGORIES)],
                'type'       => 'required|string',      // e.g., Intel, AMD
                'model'      => 'nullable|string',      // e.g., i5-11400F
                'frequency'  => 'nullable|numeric|min:0.1', // e.g., 2.6
            ]);

            // Gabungkan spesifikasi jadi description
            $descriptionParts = [];
            if (!empty($validated['model'])) {
                $descriptionParts[] = $validated['model'];
            }
            if (!empty($validated['frequency'])) {
                $descriptionParts[] = $validated['frequency'] . ' GHz';
            }
            $description = implode(' - ', $descriptionParts);

            // Cek apakah sudah ada processor yang sama
            $exists = Inventory::where('category', $validated['category'])
                ->where('name', $validated['type'])
                ->where('description', $description)
                ->exists();

            if ($exists) {
                return back()
                    ->withErrors(['type' => 'Processor dengan spesifikasi ini sudah ada.'])
                    ->withInput();
            }

            // Simpan data baru
            $inventory = Inventory::create([
                'category'       => $validated['category'],
                'name'           => $validated['type'],
                'description'    => $description,
                'total_quantity' => 0 // awalnya 0
            ]);

            return redirect()->route('inventories.index')
                ->with('success', "<strong>{$inventory->name} - {$inventory->description}</strong> has been added to the inventory.");
        }

        if ($request->category === 'VGA') {
            // Validasi input
            $validated = $request->validate([
                'category' => ['required', Rule::in(Inventory::CATEGORIES)],
                'brand'    => 'required|string',
                'size'     => 'required|integer|min:1', // GB
            ]);

            // Buat description
            $description = "{$validated['size']} GB";

            // Cek apakah VGA dengan brand + size yang sama sudah ada
            $exists = Inventory::where('category', $validated['category'])
                ->where('name', $validated['brand'])
                ->where('description', $description)
                ->exists();

            if ($exists) {
                return back()
                    ->withErrors(['brand' => 'VGA dengan merk dan kapasitas ini sudah ada.'])
                    ->withInput();
            }

            // Simpan data baru
            $inventory = Inventory::create([
                'category'       => $validated['category'],
                'name'           => $validated['brand'],
                'description'    => $description,
                'total_quantity' => 0
            ]);

            return redirect()->route('inventories.index')
                ->with('success', "<strong>{$inventory->name} - {$inventory->description}</strong> has been added to the inventory.");
        }

        if ($request->category === 'RAM') {
            // Validasi input
            $validated = $request->validate([
                'category' => ['required', Rule::in(Inventory::CATEGORIES)],
                'brand'    => 'required|string',
                'size'     => 'nullable|integer|min:1', // GB
                'type'     => 'nullable|string',        // DDR3, DDR4, DDR5, dll.
            ]);

            // Gabungkan size dan type untuk description
            $description = trim(
                ($validated['size'] ? "{$validated['size']} GB" : '') .
                ($validated['type'] ? " - {$validated['type']}" : '')
            );

            // Cek apakah RAM dengan brand + description sama sudah ada
            $exists = Inventory::where('category', $validated['category'])
                ->where('name', $validated['brand'])
                ->where('description', $description)
                ->exists();

            if ($exists) {
                return back()
                    ->withErrors(['brand' => 'RAM dengan merk, ukuran, dan tipe ini sudah ada.'])
                    ->withInput();
            }

            // Simpan data baru
            $inventory = Inventory::create([
                'category'       => $validated['category'],
                'name'           => $validated['brand'],
                'description'    => $description,
                'total_quantity' => 0
            ]);

            return redirect()->route('inventories.index')
                ->with('success', "<strong>{$inventory->name} - {$inventory->description}</strong> has been added to the inventory.");
        }

        if ($request->category === 'Monitor') {
            // Validasi input
            $validated = $request->validate([
                'category'   => ['required', Rule::in(Inventory::CATEGORIES)],
                'brand'      => 'required|string',
                'resolution' => 'nullable|string',  // misal: 1920x1080
                'inch'       => 'nullable|integer|min:1', // ukuran layar
            ]);

            // Gabungkan resolusi & ukuran ke description
            $description = trim(
                ($validated['resolution'] ? "{$validated['resolution']}" : '') .
                ($validated['inch'] ? " - {$validated['inch']} inch" : '')
            );

            // Cek apakah sudah ada monitor yang sama
            $exists = Inventory::where('category', $validated['category'])
                ->where('name', $validated['brand'])
                ->where('description', $description)
                ->exists();

            if ($exists) {
                return back()
                    ->withErrors(['brand' => 'Monitor dengan merk, resolusi, dan ukuran ini sudah ada.'])
                    ->withInput();
            }

            // Simpan data baru
            $inventory = Inventory::create([
                'category'       => $validated['category'],
                'name'           => $validated['brand'],
                'description'    => $description,
                'total_quantity' => 0 // awalnya 0, nanti bertambah kalau inventory_items dibuat
            ]);

            return redirect()->route('inventories.index')
                ->with('success', "<strong>{$inventory->name} - {$inventory->description}</strong> has been added to the inventory.");
        }

        if ($request->category === 'Other') {
            // Validasi input
            $validated = $request->validate([
                'category'    => ['required', Rule::in(Inventory::CATEGORIES)],
                'name'        => 'required|string',
                'description' => 'nullable|string',
            ]);

            $description = $validated['description'] ?? '';

            // Cek apakah barang sudah ada di kategori "Other"
            $exists = Inventory::where('category', $validated['category'])
                ->where('name', $validated['name'])
                ->where('description', $description)
                ->exists();

            if ($exists) {
                return back()
                    ->withErrors(['name' => 'Barang dengan nama dan deskripsi ini sudah ada di kategori Other.'])
                    ->withInput();
            }

            // Simpan data baru
            $inventory = Inventory::create([
                'category'       => $validated['category'],
                'name'           => $validated['name'],
                'description'    => $description,
                'total_quantity' => 0 // awalnya 0, bertambah kalau ada inventory_items
            ]);

            return redirect()->route('inventories.index')
                ->with('success', "<strong>{$inventory->name} - {$inventory->description}</strong> has been added to the inventory.");
        }

        return back()->withErrors(['item_type_id' => 'Sorry, this item type is not supported yet.']);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Inventory $inventory)
    {
        if ($inventory->category === 'Disk Drive') {
            [$size, $type] = explode(' - ', str_replace(' GB', '', $inventory->description));
            $inventory->size = $size;
            $inventory->type = $type;
        }

        if ($inventory->category === 'Processor') {
            [$model, $frequency] = explode(' - ', str_replace(' GHz', '', $inventory->description));
            $inventory->model = $model;
            $inventory->frequency = $frequency;
        }

        if ($inventory->category === 'VGA') {
            $inventory->size = (int) str_replace(' GB', '', $inventory->description);
        }

        if ($inventory->category === 'RAM') {
            $parts = explode(' - ', $inventory->description);
            $inventory->size = isset($parts[0]) ? (int) str_replace(' GB', '', $parts[0]) : null;
            $inventory->type = $parts[1] ?? null;
        }

        if ($inventory->category === 'Monitor') {
            $parts = explode(' - ', $inventory->description);
            $inventory->resolution = $parts[0] ?? null;
            $inventory->inch = isset($parts[1]) ? (float) str_replace(' inch', '', $parts[1]) : null;
        }

        return view('inventories.edit', compact('inventory'));
    }
    
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Inventory $inventory)
    {
        if ($inventory->category === 'Computer') {
            $validated = $request->validate([
                'brand' => 'required|string',
                'model' => 'nullable|string',
            ]);

            $inventory->name = $validated['brand'];
            $inventory->description = $validated['model'];
        }

        elseif ($inventory->category === 'Disk Drive') {
            $validated = $request->validate([
                'brand' => 'required|string',
                'type'  => 'nullable|string|in:HDD,SSD',
                'size'  => 'nullable|integer|min:1',
            ]);

            $inventory->name = $validated['brand'];
            $inventory->description = "{$validated['size']} GB - {$validated['type']}";
        }

        elseif ($inventory->category === 'Processor') {
            $validated = $request->validate([
                'type'      => 'required|string',
                'model'     => 'nullable|string',
                'frequency' => 'nullable|numeric|min:0.1',
            ]);

            $inventory->name = $validated['type'];
            $inventory->description = "{$validated['model']} - {$validated['frequency']} GHz";
        }

        elseif ($inventory->category === 'VGA') {
            $validated = $request->validate([
                'brand' => 'required|string',
                'size'  => 'required|integer|min:1',
            ]);

            $inventory->name = $validated['brand'];
            $inventory->description = "{$validated['size']} GB";
        }

        elseif ($inventory->category === 'RAM') {
            $validated = $request->validate([
                'brand' => 'required|string',
                'size'  => 'nullable|integer|min:1',
                'type'  => 'nullable|string',
            ]);

            $inventory->name = $validated['brand'];
            $inventory->description = "{$validated['size']} GB - {$validated['type']}";
        }

        elseif ($inventory->category === 'Monitor') {
            $validated = $request->validate([
                'brand'      => 'required|string',
                'resolution' => 'nullable|string',
                'inch'       => 'nullable|integer|min:1',
            ]);

            $inventory->name = $validated['brand'];
            $inventory->description = "{$validated['resolution']} - {$validated['inch']} inch";
        }

        elseif ($inventory->category === 'Other') {
            $validated = $request->validate([
                'name'        => 'required|string',
                'description' => 'nullable|string',
            ]);

            $inventory->name = $validated['name'];
            $inventory->description = $validated['description'];
        }

        $inventory->save();

        return redirect()->route('inventories.index')
            ->with('success', "<strong>{$inventory->name} - {$inventory->description}</strong> has been updated successfully.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Inventory $inventory)
    {
        $inventory->delete();

        return redirect()->route('inventories.index')
        ->with('success', "<strong>{$inventory->name} - {$inventory->description}</strong> has been removed from the inventory.");
    }


    /**
     * Tampilkan detail inventory beserta unit barangnya
     */
    public function show(Inventory $inventory, Request $request)
    {
        $search = $request->input('search');

        $itemsQuery = $inventory->items()->orderBy('id');

        if ($search) {
            $itemsQuery->where(function ($q) use ($search) {
                $q->where('serial_number', 'like', "%{$search}%")
                ->orWhere('condition_status', 'like', "%{$search}%")
                ->orWhere('last_checked_at', 'like', "%{$search}%")
                ->orWhere('status_allocate', 'like', "%{$search}%")
                ->orWhereHas('inventory', function($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%");
                });
            });
        }

        $items = $itemsQuery->paginate(10)->appends(['search' => $search]);

        return view('inventories.show', [
            'inventory' => $inventory,
            'items' => $items,
        ]);
    }

    public function createItem(Inventory $inventory)
    {
        return view('inventories.items.create', [
            'inventory' => $inventory
        ]);
    }

    public function storeItem(Request $request, Inventory $inventory)
    {
        $validated = $request->validate([
            'serial_number' => 'nullable|string|max:255',
            'condition_status' => 'required|in:Baik,Perlu Perbaikan,Rusak',
            'received_date' => 'required|date',
        ]);

        $validated['inventory_id'] = $inventory->id;
        $validated['last_checked_at'] = now();
        $validated['last_checked_by'] = auth()->id();

        InventoryItem::create($validated);

        // Update total_quantity
        $inventory->increment('total_quantity');

        return redirect()->route('inventories.show', $inventory->id)
            ->with(
                'success',
                '<strong>' . e($inventory->name) . ' ' . e($inventory->description) . '</strong> - <strong>' . e($validated['serial_number']) . '</strong> berhasil ditambahkan.'
            );
    }

    public function editItem(Inventory $inventory, InventoryItem $item)
    {
        return view('inventories.items.edit', [
            'inventory' => $inventory,
            'item' => $item
        ]);
    }

    public function updateItem(Request $request, Inventory $inventory, InventoryItem $item)
    {
        $validated = $request->validate([
            'serial_number' => 'nullable|string|max:255',
            'condition_status' => 'required|in:Baik,Perlu Perbaikan,Rusak',
            'qr_code' => 'nullable|string|max:255',
            'received_date' => 'required|date',
        ]);

        $validated['last_checked_at'] = now();
        $validated['last_checked_by'] = auth()->id();
        $item->update($validated);

        return redirect()->route('inventories.show', $inventory->id)
            ->with(
                'success',
                '<strong>' . e($inventory->name) . ' ' . e($inventory->description) . '</strong> - <strong>' . e($validated['serial_number']) . '</strong> berhasil diperbarui.'
            );
    }

    public function destroyItem(Inventory $inventory, InventoryItem $item)
    {
        // Pastikan item memang milik inventory ini
        if ($item->inventory_id !== $inventory->id) {
            abort(404);
        }

        // Hapus item
        $item->delete();

        // Kurangi total_quantity
        $inventory->decrement('total_quantity');

        return redirect()->route('inventories.show', $inventory->id)
            ->with(
                'success',
                '<strong>' . e($inventory->name) . ' ' . e($inventory->description) . '</strong> - <strong>' . e($item->serial_number) . '</strong> berhasil dihapus.'
            );
    }
}
