<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locations = [
            'LN2',
            'Fridge -70C',
            'Fridge -20C',
            'Fridge +4C',
            'Desiccator',
            'Shelf',
            'Drawer',
            'Box'
        ];


        foreach ($locations as $location) {
            Location::insert(['location' => $location]);
        }
    }
}
