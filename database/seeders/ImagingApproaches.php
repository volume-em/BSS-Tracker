<?php

namespace Database\Seeders;

use App\Models\ImagingApproach;
use App\Models\SubstrateType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ImagingApproaches extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $imagingApproaches = [
            'FIB-SEM',
            'mbAT',
            'sbAT',
            'ET',
            'gtTEM',
            'TEM/STEM',
            'SBF-SEM'
        ];

        foreach ($imagingApproaches as $imagingApproach) {
            ImagingApproach::insert(['imaging_approach' => $imagingApproach]);
        }
    }
}
