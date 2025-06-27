<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $names = [
            'TULT-06-01',
            'TULT-06-02',
            'TULT-06-03',
            'TULT-06-04',
            'TULT-07-01',
            'TULT-07-02',
            'TULT-07-03',
            'TULT-07-04',
        ];

        foreach ($names as $name) {
            Location::create(['name' => $name]);
        }
    }
}
