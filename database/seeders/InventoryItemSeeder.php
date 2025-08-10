<?php

namespace Database\Seeders;

use App\Models\Inventory;
use App\Models\InventoryItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class InventoryItemSeeder extends Seeder
{
    public function run(): void
    {
        $inventories = Inventory::all();

        foreach ($inventories as $inventory) {
            for ($i = 1; $i <= 3; $i++) {
                InventoryItem::create([
                    'inventory_id'      => $inventory->id,
                    'serial_number'     => strtoupper(Str::random(10)),
                    'condition_status'  => 'Baik',
                    'last_checked_at'   => now(),
                    'last_checked_by'   => null, // awalnya kosong
                ]);
            }

            // Update total_quantity setelah membuat item
            $inventory->update([
                'total_quantity' => InventoryItem::where('inventory_id', $inventory->id)->count()
            ]);
        }
    }
}
