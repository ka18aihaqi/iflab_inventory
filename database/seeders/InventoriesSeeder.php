<?php

namespace Database\Seeders;

use App\Models\Inventory;
use Illuminate\Database\Seeder;

class InventoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            ['category' => 'Computer', 'name' => 'Asus', 'description' => 'Vivobook 14'],
            ['category' => 'Disk Drive', 'name' => 'WDC', 'description' => '512 GB - HDD'],
            ['category' => 'Disk Drive', 'name' => 'Samsung', 'description' => '256 GB - SSD'],
            ['category' => 'Processor', 'name' => 'Intel', 'description' => 'Core i5 Gen 10 - 3.9 GHz'],
            ['category' => 'Processor', 'name' => 'AMD', 'description' => 'Ryzen 7 3700X - 3.9 GHz'],
            ['category' => 'VGA', 'name' => 'NVIDIA', 'description' => '8 GB'],
            ['category' => 'RAM', 'name' => 'Kingston', 'description' => '8 GB - DDR4'],
            ['category' => 'RAM', 'name' => 'Corsair', 'description' => '16 GB - DDR4'],
            ['category' => 'Monitor', 'name' => 'LG', 'description' => '1920x1080 - 24 inch'],
            ['category' => 'Monitor', 'name' => 'Samsung', 'description' => '1920x1080 - 27 inch'],
            ['category' => 'Other', 'name' => 'Whiteboard', 'description' => null],
        ];

        foreach ($items as $item) {
            Inventory::create([
                'category' => $item['category'],
                'name' => $item['name'],
                'description' => $item['description'],
                'total_quantity' => 0 // default quantity
            ]);
        }
    }
}
