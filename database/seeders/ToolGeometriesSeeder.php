<?php
// database/seeders/ToolGeometriesSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ToolGeometriesSeeder extends Seeder
{
    public function run()
    {
        DB::table('tool_geometries')->insert([
            // РОМБИЧЕСКИЕ ПЛАСТИНЫ
            [
                'name' => 'CNMG 120408',
                'shape' => 'diamond',
                'shape_name' => 'Ромб 80°',
                'clearance_angle' => 7.0,
                'tolerance_class' => 'm',
                'tolerance_class_name' => 'Средний (±0.05-0.08 мм)',
                'chipbreaker_type' => 'MF',
                'cutting_edge_length' => 12.7,
                'insert_thickness' => 4.76,
                'corner_radius' => 0.8,
                'feed_factor' => 1.0,
                'speed_factor' => 1.0,
                'power_factor' => 1.0,
                'max_depth_of_cut' => 4.0,
                'recommended_use' => 'Черновая и чистовая обработка'
            ],
            [
                'name' => 'DNMG 150408',
                'shape' => 'diamond',
                'shape_name' => 'Ромб 55°',
                'clearance_angle' => 7.0,
                'tolerance_class' => 'g',
                'tolerance_class_name' => 'Высокий (±0.025-0.05 мм)',
                'chipbreaker_type' => 'F',
                'cutting_edge_length' => 15.875,
                'insert_thickness' => 4.76,
                'corner_radius' => 0.8,
                'feed_factor' => 0.9,
                'speed_factor' => 1.1,
                'power_factor' => 0.9,
                'max_depth_of_cut' => 3.0,
                'recommended_use' => 'Чистовая обработка'
            ],

            // ТРЕУГОЛЬНЫЕ ПЛАСТИНЫ
            [
                'name' => 'TNMG 160408',
                'shape' => 'triangle',
                'shape_name' => 'Треугольник 60°',
                'clearance_angle' => 7.0,
                'tolerance_class' => 'm',
                'tolerance_class_name' => 'Средний (±0.05-0.08 мм)',
                'chipbreaker_type' => 'M',
                'cutting_edge_length' => 16.5,
                'insert_thickness' => 4.76,
                'corner_radius' => 0.8,
                'feed_factor' => 1.1,
                'speed_factor' => 0.9,
                'power_factor' => 1.1,
                'max_depth_of_cut' => 3.5,
                'recommended_use' => 'Универсальная обработка'
            ],

            // КВАДРАТНЫЕ ПЛАСТИНЫ
            [
                'name' => 'SNMG 120408',
                'shape' => 'square',
                'shape_name' => 'Квадрат 90°',
                'clearance_angle' => 7.0,
                'tolerance_class' => 'm',
                'tolerance_class_name' => 'Средний (±0.05-0.08 мм)',
                'chipbreaker_type' => 'H',
                'cutting_edge_length' => 12.7,
                'insert_thickness' => 4.76,
                'corner_radius' => 0.8,
                'feed_factor' => 1.2,
                'speed_factor' => 0.8,
                'power_factor' => 1.2,
                'max_depth_of_cut' => 5.0,
                'recommended_use' => 'Черновая обработка'
            ],

            // КРУГЛЫЕ ПЛАСТИНЫ
            [
                'name' => 'RCMT 120400',
                'shape' => 'circle',
                'shape_name' => 'Круг',
                'clearance_angle' => 7.0,
                'tolerance_class' => 'g',
                'tolerance_class_name' => 'Высокий (±0.025-0.05 мм)',
                'chipbreaker_type' => 'F',
                'cutting_edge_length' => 12.0,
                'insert_thickness' => 4.0,
                'corner_radius' => 12.0,
                'feed_factor' => 0.8,
                'speed_factor' => 1.2,
                'power_factor' => 0.8,
                'max_depth_of_cut' => 2.0,
                'recommended_use' => 'Чистовая обработка и копирование'
            ]
        ]);
    }
}
