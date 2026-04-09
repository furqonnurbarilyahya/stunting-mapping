<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \Illuminate\Support\Facades\DB::table('regions')->insert([
            [
                'name' => 'Kecamatan A',
                'cluster' => 'Tinggi',
                'latitude' => -6.200000,
                'longitude' => 106.816666,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kecamatan B',
                'cluster' => 'Rendah',
                'latitude' => -6.210000,
                'longitude' => 106.820000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kecamatan C',
                'cluster' => 'Sedang',
                'latitude' => -6.220000,
                'longitude' => 106.830000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
