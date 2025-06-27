<?php

namespace App\Http\Controllers;

use App\Models\ItemType;
use App\Models\Inventory;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->search;

        $computer = Inventory::with('itemType')
            ->whereHas('itemType', function ($q) {
                $q->where('name', 'Computer');
            })
            ->when($request->has('search'), function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%");
            })
            ->paginate(5, ['*'], 'computer_page'); // custom page name

        $diskDrives = Inventory::with('itemType')
            ->whereHas('itemType', function ($q) {
                $q->where('name', 'Disk Drive');
            })
            ->when($request->has('search'), function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%");
            })
            ->paginate(5, ['*'], 'diskdrives_page');

        $processors = Inventory::with('itemType')
            ->whereHas('itemType', function ($q) {
                $q->where('name', 'Processor');
            })
            ->when($request->has('search'), function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%");
            })
            ->paginate(5, ['*'], 'processors_page');

        $vga = Inventory::with('itemType')
            ->whereHas('itemType', function ($q) {
                $q->where('name', 'VGA');
            })
            ->when($request->has('search'), function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%");
            })
            ->paginate(5, ['*'], 'vga_page');

        $ram = Inventory::with('itemType')
            ->whereHas('itemType', function ($q) {
                $q->where('name', 'RAM');
            })
            ->when($request->has('search'), function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%");
            })
            ->paginate(5, ['*'], 'ram_page');

        $monitors = Inventory::with('itemType')
            ->whereHas('itemType', function ($q) {
                $q->where('name', 'Monitor');
            })
            ->when($request->has('search'), function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%");
            })
            ->paginate(5, ['*'], 'monitors_page');

        $others = Inventory::with('itemType')
            ->whereHas('itemType', function ($q) {
                $q->where('name', 'Other Items');
            })
            ->when($request->has('search'), function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%");
            })
            ->paginate(5, ['*'], 'others_page');

        return view('inventories.index', compact('computer', 'search', 'diskDrives', 'processors', 'vga', 'ram', 'monitors', 'others'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $itemTypes = ItemType::all();
        return view('inventories.create', compact('itemTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $itemType = \App\Models\ItemType::find($request->item_type_id);

        if (!$itemType) {
            return back()->withErrors(['item_type_id' => 'Item type not found.']);
        }

        if ($itemType->name === 'Computer') {
            $validated = $request->validate([
                'item_type_id' => 'required|exists:item_types,id',
                'brand'        => 'required|string',
                'model'        => 'nullable|string',
                'stock'        => 'nullable|integer|min:0',
            ]);

            // Cari inventory dengan brand + model yang sama
            $existing = \App\Models\Inventory::where('item_type_id', $validated['item_type_id'])
                ->where('name', $validated['brand'])
                ->where('description', $validated['model'])
                ->first();

            if ($existing) {
                // Tambahkan stok jika sudah ada
                $existing->increment('stock', $validated['stock'] ?? 0);

                return redirect()->route('inventories.index')
                    ->with('success', "<strong>{$existing->name} - {$existing->description}</strong> stock has been increased.");
            }

            // Jika belum ada, buat baru
            $inventory = new \App\Models\Inventory();
            $inventory->item_type_id = $validated['item_type_id'];
            $inventory->name = $validated['brand'];
            $inventory->description = $validated['model'];
            $inventory->stock = $validated['stock'];
            $inventory->save();

            return redirect()->route('inventories.index')
                ->with('success', "<strong>{$inventory->name} - {$inventory->description}</strong> has been added to the inventory.");
        }

        if ($itemType->name === 'Disk Drive') {
            $validated = $request->validate([
                'item_type_id' => 'required|exists:item_types,id',
                'brand'        => 'required|string',
                'type'         => 'nullable|string|in:HDD,SSD',
                'size'         => 'nullable|integer|min:1',
                'stock'        => 'nullable|integer|min:0',
            ]);

            $description = "{$validated['size']} GB - {$validated['type']}";

            // Cari apakah data sudah ada
            $existing = \App\Models\Inventory::where('item_type_id', $validated['item_type_id'])
                ->where('name', $validated['brand'])
                ->where('description', $description)
                ->first();

            if ($existing) {
                $existing->increment('stock', $validated['stock'] ?? 0);

                return redirect()->route('inventories.index')
                    ->with('success', "<strong>{$existing->name} - {$existing->description}</strong> stock has been increased.");
            }

            // Kalau belum ada → buat baru
            $inventory = new \App\Models\Inventory();
            $inventory->item_type_id = $validated['item_type_id'];
            $inventory->name = $validated['brand'];
            $inventory->description = $description;
            $inventory->stock = $validated['stock'];
            $inventory->save();

            return redirect()->route('inventories.index')
                ->with('success', "<strong>{$inventory->name} - {$inventory->description}</strong> has been added to the inventory.");
        }

        if ($itemType->name === 'Processor') {
            $validated = $request->validate([
                'item_type_id' => 'required|exists:item_types,id',
                'type'         => 'required|string',      // e.g., Intel, AMD
                'model'        => 'nullable|string',      // e.g., i5-11400F
                'frequency'    => 'nullable|numeric|min:0.1', // e.g., 2.6
                'stock'        => 'nullable|integer|min:0',
            ]);

            $description = "{$validated['model']} - {$validated['frequency']} GHz";

            // Cari apakah sudah ada processor yang sama
            $existing = \App\Models\Inventory::where('item_type_id', $validated['item_type_id'])
                ->where('name', $validated['type'])
                ->where('description', $description)
                ->first();

            if ($existing) {
                $existing->increment('stock', $validated['stock'] ?? 0);

                return redirect()->route('inventories.index')
                    ->with('success', "<strong>{$existing->name} - {$existing->description}</strong> stock has been increased.");
            }

            // Kalau belum ada → buat baru
            $inventory = new \App\Models\Inventory();
            $inventory->item_type_id = $validated['item_type_id'];
            $inventory->name = $validated['type'];
            $inventory->description = $description;
            $inventory->stock = $validated['stock'];
            $inventory->save();

            return redirect()->route('inventories.index')
                ->with('success', "<strong>{$inventory->name} - {$inventory->description}</strong> has been added to the inventory.");
        }

        if ($itemType->name === 'VGA') {
            $validated = $request->validate([
                'item_type_id' => 'required|exists:item_types,id',
                'brand'        => 'required|string',
                'size'         => 'required|integer|min:1',
                'stock'        => 'nullable|integer|min:0',
            ]);

            $description = "{$validated['size']} GB";

            // Cek apakah item VGA dengan brand dan size yang sama sudah ada
            $existing = \App\Models\Inventory::where('item_type_id', $validated['item_type_id'])
                ->where('name', $validated['brand'])
                ->where('description', $description)
                ->first();

            if ($existing) {
                $existing->increment('stock', $validated['stock'] ?? 0);

                return redirect()->route('inventories.index')
                    ->with('success', "<strong>{$existing->name} - {$existing->description}</strong> stock has been increased.");
            }

            // Kalau belum ada → buat baru
            $inventory = new \App\Models\Inventory();
            $inventory->item_type_id = $validated['item_type_id'];
            $inventory->name = $validated['brand'];
            $inventory->description = $description;
            $inventory->stock = $validated['stock'];
            $inventory->save();

            return redirect()->route('inventories.index')
                ->with('success', "<strong>{$inventory->name} - {$inventory->description}</strong> has been added to the inventory.");
        }

        if ($itemType->name === 'RAM') {
            $validated = $request->validate([
                'item_type_id' => 'required|exists:item_types,id',
                'brand'        => 'required|string',
                'size'         => 'nullable|integer|min:1',
                'type'         => 'nullable|string',
                'stock'        => 'nullable|integer|min:0',
            ]);

            $description = trim("{$validated['size']} GB - {$validated['type']}");

            $existing = \App\Models\Inventory::where('item_type_id', $validated['item_type_id'])
                ->where('name', $validated['brand'])
                ->where('description', $description)
                ->first();

            if ($existing) {
                $existing->increment('stock', $validated['stock'] ?? 0);

                return redirect()->route('inventories.index')
                    ->with('success', "<strong>{$existing->name} - {$existing->description}</strong> stock has been increased.");
            }

            $inventory = new \App\Models\Inventory();
            $inventory->item_type_id = $validated['item_type_id'];
            $inventory->name = $validated['brand'];
            $inventory->description = $description;
            $inventory->stock = $validated['stock'];
            $inventory->save();

            return redirect()->route('inventories.index')
                ->with('success', "<strong>{$inventory->name} - {$inventory->description}</strong> has been added to the inventory.");
        }

        if ($itemType->name === 'Monitor') {
            $validated = $request->validate([
                'item_type_id' => 'required|exists:item_types,id',
                'brand'        => 'required|string',
                'resolution'   => 'nullable|string',
                'inch'         => 'nullable|integer|min:1',
                'stock'        => 'nullable|integer|min:0',
            ]);

            // Gabungkan resolusi dan ukuran ke deskripsi
            $description = trim("{$validated['resolution']} - {$validated['inch']} inch");

            // Cari apakah monitor dengan detail yang sama sudah ada
            $existing = \App\Models\Inventory::where('item_type_id', $validated['item_type_id'])
                ->where('name', $validated['brand'])
                ->where('description', $description)
                ->first();

            if ($existing) {
                $existing->increment('stock', $validated['stock'] ?? 0);

                return redirect()->route('inventories.index')
                    ->with('success', "<strong>{$existing->name} - {$existing->description}</strong> stock has been increased.");
            }

            // Jika belum ada, buat baru
            $inventory = new \App\Models\Inventory();
            $inventory->item_type_id = $validated['item_type_id'];
            $inventory->name = $validated['brand'];
            $inventory->description = $description;
            $inventory->stock = $validated['stock'];
            $inventory->save();

            return redirect()->route('inventories.index')
                ->with('success', "<strong>{$inventory->name} - {$inventory->description}</strong> has been added to the inventory.");
        }

        if ($itemType->name === 'Other Items') {
            $validated = $request->validate([
                'item_type_id' => 'required|exists:item_types,id',
                'name'         => 'required|string',
                'description'  => 'nullable|string',
                'stock'        => 'nullable|integer|min:0',
            ]);

            $description = $validated['description'] ?? '';

            // Cek apakah item sudah ada
            $existing = \App\Models\Inventory::where('item_type_id', $validated['item_type_id'])
                ->where('name', $validated['name'])
                ->where('description', $description)
                ->first();

            if ($existing) {
                $existing->increment('stock', $validated['stock'] ?? 0);

                return redirect()->route('inventories.index')
                    ->with('success', "<strong>{$existing->name} - {$existing->description}</strong> stock has been increased.");
            }

            // Jika belum ada, buat baru
            $inventory = new \App\Models\Inventory();
            $inventory->item_type_id = $validated['item_type_id'];
            $inventory->name = $validated['name'];
            $inventory->description = $description;
            $inventory->stock = $validated['stock'];
            $inventory->save();

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
        if ($inventory->itemType->name === 'Disk Drive') {
            [$size, $type] = explode(' - ', str_replace(' GB', '', $inventory->description));
            $inventory->size = $size;
            $inventory->type = $type;
        }

        if ($inventory->itemType->name === 'Processor') {
            [$model, $frequency] = explode(' - ', str_replace(' GHz', '', $inventory->description));
            $inventory->model = $model;
            $inventory->frequency = $frequency;
        }

        if ($inventory->itemType->name === 'VGA') {
            $inventory->size = (int) str_replace(' GB', '', $inventory->description);
        }

        if ($inventory->itemType->name === 'RAM') {
            $parts = explode(' - ', $inventory->description);
            $inventory->size = isset($parts[0]) ? (int) str_replace(' GB', '', $parts[0]) : null;
            $inventory->type = $parts[1] ?? null;
        }

        if ($inventory->itemType->name === 'Monitor') {
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
        $itemType = $inventory->itemType;

        if (!$itemType) {
            return back()->withErrors(['item_type_id' => 'Item type tidak ditemukan.']);
        }

        if ($itemType->name === 'Computer') {
            $validated = $request->validate([
                'brand' => 'required|string',
                'model' => 'nullable|string',
                'stock' => 'nullable|integer|min:0',
            ]);

            $inventory->name = $validated['brand'];
            $inventory->description = $validated['model'];
        }

        elseif ($itemType->name === 'Disk Drive') {
            $validated = $request->validate([
                'brand' => 'required|string',
                'type'  => 'nullable|string|in:HDD,SSD',
                'size'  => 'nullable|integer|min:1',
                'stock' => 'nullable|integer|min:0',
            ]);

            $inventory->name = $validated['brand'];
            $inventory->description = "{$validated['size']} GB - {$validated['type']}";
        }

        elseif ($itemType->name === 'Processor') {
            $validated = $request->validate([
                'type'      => 'required|string',
                'model'     => 'nullable|string',
                'frequency' => 'nullable|numeric|min:0.1',
                'stock'     => 'nullable|integer|min:0',
            ]);

            $inventory->name = $validated['type'];
            $inventory->description = "{$validated['model']} - {$validated['frequency']} GHz";
        }

        elseif ($itemType->name === 'VGA') {
            $validated = $request->validate([
                'brand' => 'required|string',
                'size'  => 'required|integer|min:1',
                'stock' => 'nullable|integer|min:0',
            ]);

            $inventory->name = $validated['brand'];
            $inventory->description = "{$validated['size']} GB";
        }

        elseif ($itemType->name === 'RAM') {
            $validated = $request->validate([
                'brand' => 'required|string',
                'size'  => 'nullable|integer|min:1',
                'type'  => 'nullable|string',
                'stock' => 'nullable|integer|min:0',
            ]);

            $inventory->name = $validated['brand'];
            $inventory->description = "{$validated['size']} GB - {$validated['type']}";
        }

        elseif ($itemType->name === 'Monitor') {
            $validated = $request->validate([
                'brand'      => 'required|string',
                'resolution' => 'nullable|string',
                'inch'       => 'nullable|integer|min:1',
                'stock'      => 'nullable|integer|min:0',
            ]);

            $inventory->name = $validated['brand'];
            $inventory->description = "{$validated['resolution']} - {$validated['inch']} inch";
        }

        elseif ($itemType->name === 'Other Items') {
            $validated = $request->validate([
                'name'        => 'required|string',
                'description' => 'nullable|string',
                'stock'       => 'nullable|integer|min:0',
            ]);

            $inventory->name = $validated['name'];
            $inventory->description = $validated['description'];
        }

        // Update stock for all cases
        $inventory->stock = $validated['stock'] ?? 0;
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
}
