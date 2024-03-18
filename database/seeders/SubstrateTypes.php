<?php

namespace Database\Seeders;

use App\Models\SubstrateType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubstrateTypes extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $substrateTypes = [
            'Stub',
            'Pin Stub',
            'Wafer',
            'ITO'
        ];

        foreach ($substrateTypes as $substrateType) {
            SubstrateType::insert(['type' => $substrateType]);
        }
    }
}
