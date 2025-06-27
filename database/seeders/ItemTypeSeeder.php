<?php

namespace Database\Seeders;

use App\Models\ItemType;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ItemTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            'Computer',
            'Disk Drive',
            'Processor',
            'VGA',
            'RAM',
            'Monitor',
            'Other Items',
        ];

        foreach ($types as $type) {
            ItemType::create(['name' => $type]);
        }
    }
}
