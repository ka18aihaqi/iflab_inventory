<?php

namespace Database\Seeders;

use App\Models\Inventory;
use App\Models\ItemType;
use Illuminate\Database\Seeder;

class InventoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            'Computer' => [
                ['name' => 'Asus', 'description' => 'Vivobook 14', 'stock' => 10],
            ],
            'Disk Drive' => [
                ['name' => 'WDC', 'description' => '512 GB - HDD', 'stock' => 10],
                ['name' => 'Samsung', 'description' => '256 GB - SSD', 'stock' => 10],
            ],
            'Processor' => [
                ['name' => 'Intel', 'description' => 'Core i5 Gen 10 - 3.9 GHz', 'stock' => 10],
                ['name' => 'AMD', 'description' => 'Ryzen 7 3700X - 3.9 GHz', 'stock' => 10],
            ],
            'VGA' => [
                ['name' => 'NVIDIA', 'description' => '8 GB', 'stock' => 10],
            ],
            'RAM' => [
                ['name' => 'Kingston', 'description' => '8 GB - DDR4', 'stock' => 10],
                ['name' => 'Corsair', 'description' => '16 GB - DDR4', 'stock' => 10],
            ],
            'Monitor' => [
                ['name' => 'LG', 'description' => '1920x1080 - 24 inch', 'stock' => 10],
                ['name' => 'Samsung', 'description' => '1920x1080 - 27 inch', 'stock' => 10],
            ],
            'Other Items' => [
                ['name' => 'Whiteboard', 'description' => '', 'stock' => 10],
            ],
        ];

        foreach ($items as $typeName => $inventoryList) {
            $type = ItemType::where('name', $typeName)->first();

            if ($type) {
                foreach ($inventoryList as $item) {
                    Inventory::create([
                        'item_type_id' => $type->id,
                        'name' => $item['name'],
                        'description' => $item['description'],
                        'stock' => $item['stock'],
                    ]);
                }
            }
        }
    }
}
