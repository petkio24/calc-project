<?php
// database/seeders/CompleteDrillingMaterialsSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompleteDrillingMaterialsSeeder extends Seeder
{
    public function run()
    {
        // Добавляем недостающие материалы из точения
        DB::table('drilling_materials')->insert([
            // Конструкционные стали
            [
                'name' => 'Сталь 35',
                'material_group' => 'carbon_steel',
                'material_group_name' => 'Конструкционные стали',
                'hardness_range' => 'HB 150-180',
                'cutting_speed_min' => 22,
                'cutting_speed_max' => 30,
                'feed_per_rev_min' => 0.08,
                'feed_per_rev_max' => 0.25,
                'power_factor' => 0.95,
                'specific_pressure' => 1900,
                'thermal_conductivity' => 46.0,
                'description' => 'Углеродистая конструкционная сталь'
            ],

            // Легированные стали
            [
                'name' => 'Сталь 30ХГСА',
                'material_group' => 'alloy_steel',
                'material_group_name' => 'Легированные стали',
                'hardness_range' => 'HB 200-240',
                'cutting_speed_min' => 12,
                'cutting_speed_max' => 18,
                'feed_per_rev_min' => 0.04,
                'feed_per_rev_max' => 0.12,
                'power_factor' => 1.3,
                'specific_pressure' => 2400,
                'thermal_conductivity' => 38.0,
                'description' => 'Хромансилевая конструкционная сталь'
            ],

            // Алюминиевые сплавы
            [
                'name' => 'Алюминий АД1',
                'material_group' => 'aluminum',
                'material_group_name' => 'Алюминиевые сплавы',
                'hardness_range' => 'HB 25-35',
                'cutting_speed_min' => 80,
                'cutting_speed_max' => 120,
                'feed_per_rev_min' => 0.15,
                'feed_per_rev_max' => 0.50,
                'power_factor' => 0.25,
                'specific_pressure' => 300,
                'thermal_conductivity' => 220.0,
                'description' => 'Технический алюминий'
            ],

            // Медные сплавы
            [
                'name' => 'Медь М1',
                'material_group' => 'copper_alloy',
                'material_group_name' => 'Медные сплавы',
                'hardness_range' => 'HB 35-45',
                'cutting_speed_min' => 40,
                'cutting_speed_max' => 70,
                'feed_per_rev_min' => 0.10,
                'feed_per_rev_max' => 0.30,
                'power_factor' => 0.4,
                'specific_pressure' => 600,
                'thermal_conductivity' => 390.0,
                'description' => 'Техническая медь'
            ],

            // Сложные для сверления материалы (с особыми параметрами)
            [
                'name' => 'Пластик ПВХ',
                'material_group' => 'plastics',
                'material_group_name' => 'Пластмассы',
                'hardness_range' => 'HRC 70-90',
                'cutting_speed_min' => 50,
                'cutting_speed_max' => 80,
                'feed_per_rev_min' => 0.05,
                'feed_per_rev_max' => 0.15,
                'power_factor' => 0.15,
                'specific_pressure' => 150,
                'thermal_conductivity' => 0.2,
                'description' => 'Поливинилхлорид (острый инструмент)'
            ],

            [
                'name' => 'Оргстекло',
                'material_group' => 'plastics',
                'material_group_name' => 'Пластмассы',
                'hardness_range' => 'HRC 85-105',
                'cutting_speed_min' => 40,
                'cutting_speed_max' => 60,
                'feed_per_rev_min' => 0.03,
                'feed_per_rev_max' => 0.10,
                'power_factor' => 0.12,
                'specific_pressure' => 120,
                'thermal_conductivity' => 0.2,
                'description' => 'Полиметилметакрилат (малые подачи)'
            ],

            // Специальные материалы
            [
                'name' => 'Бронза БрАЖ9-4',
                'material_group' => 'copper_alloy',
                'material_group_name' => 'Медные сплавы',
                'hardness_range' => 'HB 110-130',
                'cutting_speed_min' => 20,
                'cutting_speed_max' => 35,
                'feed_per_rev_min' => 0.06,
                'feed_per_rev_max' => 0.18,
                'power_factor' => 0.7,
                'specific_pressure' => 1000,
                'thermal_conductivity' => 70.0,
                'description' => 'Алюминиево-железистая бронза'
            ]
        ]);
    }
}
