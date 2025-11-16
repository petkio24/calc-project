<?php
// database/seeders/AdditionalMaterialsSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdditionalMaterialsSeeder extends Seeder
{
    public function run()
    {
        // Добавляем недостающие материалы из документа
        DB::table('turning_materials')->insert([
            // Черные металлы из документа
            [
                'name' => 'Сталь 20',
                'material_group' => 'black_metals',
                'material_group_name' => 'Конструкционные стали',
                'hardness_range' => 'HB 110-150',
                'cutting_speed_min' => 120,
                'cutting_speed_max' => 180,
                'feed_min' => 0.5,
                'feed_max' => 1.2,
                'specific_pressure' => 1800,
                'power_factor' => 0.9,
                'thermal_conductivity' => 48.0,
                'description' => 'Низкоуглеродистая сталь'
            ],
            [
                'name' => 'Сталь 35',
                'material_group' => 'black_metals',
                'material_group_name' => 'Конструкционные стали',
                'hardness_range' => 'HB 150-180',
                'cutting_speed_min' => 120,
                'cutting_speed_max' => 160,
                'feed_min' => 0.3,
                'feed_max' => 0.8,
                'specific_pressure' => 1900,
                'power_factor' => 0.95,
                'thermal_conductivity' => 46.0,
                'description' => 'Углеродистая конструкционная сталь'
            ],
            [
                'name' => 'Сталь 30ХГСА',
                'material_group' => 'black_metals',
                'material_group_name' => 'Легированные стали',
                'hardness_range' => 'HB 200-240',
                'cutting_speed_min' => 80,
                'cutting_speed_max' => 120,
                'feed_min' => 0.2,
                'feed_max' => 0.6,
                'specific_pressure' => 2300,
                'power_factor' => 1.2,
                'thermal_conductivity' => 38.0,
                'description' => 'Хромансилевая конструкционная сталь'
            ],

            // Цветные металлы из документа
            [
                'name' => 'Алюминий АД1',
                'material_group' => 'nonferrous_metals',
                'material_group_name' => 'Алюминиевые сплавы',
                'hardness_range' => 'HB 25-35',
                'cutting_speed_min' => 400,
                'cutting_speed_max' => 800,
                'feed_min' => 0.3,
                'feed_max' => 1.5,
                'specific_pressure' => 300,
                'power_factor' => 0.25,
                'thermal_conductivity' => 220.0,
                'description' => 'Технический алюминий'
            ],
            [
                'name' => 'Дюралюминий Д16',
                'material_group' => 'nonferrous_metals',
                'material_group_name' => 'Алюминиевые сплавы',
                'hardness_range' => 'HB 70-90',
                'cutting_speed_min' => 300,
                'cutting_speed_max' => 600,
                'feed_min' => 0.2,
                'feed_max' => 1.0,
                'specific_pressure' => 500,
                'power_factor' => 0.35,
                'thermal_conductivity' => 160.0,
                'description' => 'Дюралюминий'
            ],
            [
                'name' => 'Медь М1',
                'material_group' => 'nonferrous_metals',
                'material_group_name' => 'Медные сплавы',
                'hardness_range' => 'HB 35-45',
                'cutting_speed_min' => 200,
                'cutting_speed_max' => 400,
                'feed_min' => 0.2,
                'feed_max' => 0.8,
                'specific_pressure' => 600,
                'power_factor' => 0.4,
                'thermal_conductivity' => 390.0,
                'description' => 'Техническая медь'
            ],
            [
                'name' => 'Латунь Л63',
                'material_group' => 'nonferrous_metals',
                'material_group_name' => 'Медные сплавы',
                'hardness_range' => 'HB 55-65',
                'cutting_speed_min' => 250,
                'cutting_speed_max' => 450,
                'feed_min' => 0.3,
                'feed_max' => 1.2,
                'specific_pressure' => 700,
                'power_factor' => 0.45,
                'thermal_conductivity' => 120.0,
                'description' => 'Латунь'
            ],
            [
                'name' => 'Бронза БрОЦС5-5-5',
                'material_group' => 'nonferrous_metals',
                'material_group_name' => 'Медные сплавы',
                'hardness_range' => 'HB 60-70',
                'cutting_speed_min' => 200,
                'cutting_speed_max' => 400,
                'feed_min' => 0.2,
                'feed_max' => 0.7,
                'specific_pressure' => 900,
                'power_factor' => 0.5,
                'thermal_conductivity' => 80.0,
                'description' => 'Оловянная бронза'
            ]
        ]);
    }
}
