<?php
// database/seeders/OperationFactorsSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OperationFactorsSeeder extends Seeder
{
    public function run()
    {
        DB::table('operation_factors')->insert([
            // ЧЕРНОВАЯ ОБРАБОТКА
            [
                'operation_type' => 'roughing',
                'operation_type_name' => 'Черновая',
                'surface_quality' => 'normal',
                'surface_quality_name' => 'Нормальное',
                'speed_factor' => 1.0,
                'feed_factor' => 1.0,
                'power_factor' => 1.0
            ],
            [
                'operation_type' => 'roughing',
                'operation_type_name' => 'Черновая',
                'surface_quality' => 'good',
                'surface_quality_name' => 'Хорошее',
                'speed_factor' => 0.9,
                'feed_factor' => 0.8,
                'power_factor' => 0.9
            ],
            [
                'operation_type' => 'roughing',
                'operation_type_name' => 'Черновая',
                'surface_quality' => 'excellent',
                'surface_quality_name' => 'Отличное',
                'speed_factor' => 0.8,
                'feed_factor' => 0.6,
                'power_factor' => 0.8
            ],

            // ЧИСТОВАЯ ОБРАБОТКА
            [
                'operation_type' => 'finishing',
                'operation_type_name' => 'Чистовая',
                'surface_quality' => 'normal',
                'surface_quality_name' => 'Нормальное',
                'speed_factor' => 1.2,
                'feed_factor' => 0.7,
                'power_factor' => 0.8
            ],
            [
                'operation_type' => 'finishing',
                'operation_type_name' => 'Чистовая',
                'surface_quality' => 'good',
                'surface_quality_name' => 'Хорошее',
                'speed_factor' => 1.3,
                'feed_factor' => 0.5,
                'power_factor' => 0.7
            ],
            [
                'operation_type' => 'finishing',
                'operation_type_name' => 'Чистовая',
                'surface_quality' => 'excellent',
                'surface_quality_name' => 'Отличное',
                'speed_factor' => 1.4,
                'feed_factor' => 0.3,
                'power_factor' => 0.6
            ]
        ]);
    }
}
