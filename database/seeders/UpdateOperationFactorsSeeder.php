<?php
// database/seeders/UpdateOperationFactorsSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateOperationFactorsSeeder extends Seeder
{
    public function run()
    {
        // Удаляем старые данные
        DB::table('operation_factors')->delete();

        // Добавляем новые данные с учетом типа обработки (наружное/внутреннее)
        $factors = [
            // НАРУЖНОЕ ТОЧЕНИЕ - Черновая обработка
            ['operation_type' => 'roughing', 'operation_type_name' => 'Черновая',
                'operation_subtype' => 'external_turning', 'operation_subtype_name' => 'Наружное точение',
                'surface_quality' => 'normal', 'surface_quality_name' => 'Нормальное',
                'speed_factor' => 1.0, 'feed_factor' => 1.0, 'power_factor' => 1.0],
            ['operation_type' => 'roughing', 'operation_type_name' => 'Черновая',
                'operation_subtype' => 'external_turning', 'operation_subtype_name' => 'Наружное точение',
                'surface_quality' => 'good', 'surface_quality_name' => 'Хорошее',
                'speed_factor' => 0.9, 'feed_factor' => 0.8, 'power_factor' => 0.9],
            ['operation_type' => 'roughing', 'operation_type_name' => 'Черновая',
                'operation_subtype' => 'external_turning', 'operation_subtype_name' => 'Наружное точение',
                'surface_quality' => 'excellent', 'surface_quality_name' => 'Отличное',
                'speed_factor' => 0.8, 'feed_factor' => 0.6, 'power_factor' => 0.8],

            // НАРУЖНОЕ ТОЧЕНИЕ - Чистовая обработка
            ['operation_type' => 'finishing', 'operation_type_name' => 'Чистовая',
                'operation_subtype' => 'external_turning', 'operation_subtype_name' => 'Наружное точение',
                'surface_quality' => 'normal', 'surface_quality_name' => 'Нормальное',
                'speed_factor' => 1.2, 'feed_factor' => 0.7, 'power_factor' => 0.8],
            ['operation_type' => 'finishing', 'operation_type_name' => 'Чистовая',
                'operation_subtype' => 'external_turning', 'operation_subtype_name' => 'Наружное точение',
                'surface_quality' => 'good', 'surface_quality_name' => 'Хорошее',
                'speed_factor' => 1.3, 'feed_factor' => 0.5, 'power_factor' => 0.7],
            ['operation_type' => 'finishing', 'operation_type_name' => 'Чистовая',
                'operation_subtype' => 'external_turning', 'operation_subtype_name' => 'Наружное точение',
                'surface_quality' => 'excellent', 'surface_quality_name' => 'Отличное',
                'speed_factor' => 1.4, 'feed_factor' => 0.3, 'power_factor' => 0.6],

            // ВНУТРЕННЕЕ ТОЧЕНИЕ (РАСТАЧИВАНИЕ) - Черновая обработка
            ['operation_type' => 'roughing', 'operation_type_name' => 'Черновая',
                'operation_subtype' => 'internal_turning', 'operation_subtype_name' => 'Растачивание',
                'surface_quality' => 'normal', 'surface_quality_name' => 'Нормальное',
                'speed_factor' => 0.9, 'feed_factor' => 0.6, 'power_factor' => 0.9],
            ['operation_type' => 'roughing', 'operation_type_name' => 'Черновая',
                'operation_subtype' => 'internal_turning', 'operation_subtype_name' => 'Растачивание',
                'surface_quality' => 'good', 'surface_quality_name' => 'Хорошее',
                'speed_factor' => 0.8, 'feed_factor' => 0.5, 'power_factor' => 0.8],
            ['operation_type' => 'roughing', 'operation_type_name' => 'Черновая',
                'operation_subtype' => 'internal_turning', 'operation_subtype_name' => 'Растачивание',
                'surface_quality' => 'excellent', 'surface_quality_name' => 'Отличное',
                'speed_factor' => 0.7, 'feed_factor' => 0.4, 'power_factor' => 0.7],

            // ВНУТРЕННЕЕ ТОЧЕНИЕ (РАСТАЧИВАНИЕ) - Чистовая обработка
            ['operation_type' => 'finishing', 'operation_type_name' => 'Чистовая',
                'operation_subtype' => 'internal_turning', 'operation_subtype_name' => 'Растачивание',
                'surface_quality' => 'normal', 'surface_quality_name' => 'Нормальное',
                'speed_factor' => 1.1, 'feed_factor' => 0.5, 'power_factor' => 0.7],
            ['operation_type' => 'finishing', 'operation_type_name' => 'Чистовая',
                'operation_subtype' => 'internal_turning', 'operation_subtype_name' => 'Растачивание',
                'surface_quality' => 'good', 'surface_quality_name' => 'Хорошее',
                'speed_factor' => 1.2, 'feed_factor' => 0.4, 'power_factor' => 0.6],
            ['operation_type' => 'finishing', 'operation_type_name' => 'Чистовая',
                'operation_subtype' => 'internal_turning', 'operation_subtype_name' => 'Растачивание',
                'surface_quality' => 'excellent', 'surface_quality_name' => 'Отличное',
                'speed_factor' => 1.3, 'feed_factor' => 0.3, 'power_factor' => 0.5],
        ];

        DB::table('operation_factors')->insert($factors);
    }
}
