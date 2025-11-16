<?php
// database/seeders/DrillingMaterialsSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DrillingMaterialsSeeder extends Seeder
{
    public function run()
    {
        DB::table('drilling_materials')->insert([
            // Конструкционные стали
            [
                'name' => 'Сталь 20',
                'material_group' => 'carbon_steel',
                'material_group_name' => 'Конструкционные стали',
                'hardness_range' => 'HB 110-150',
                'cutting_speed_min' => 25,
                'cutting_speed_max' => 35,
                'feed_per_rev_min' => 0.08,
                'feed_per_rev_max' => 0.25,
                'power_factor' => 0.8,
                'specific_pressure' => 1800,
                'thermal_conductivity' => 48.0,
                'description' => 'Низкоуглеродистая сталь'
            ],
            [
                'name' => 'Сталь 45',
                'material_group' => 'carbon_steel',
                'material_group_name' => 'Конструкционные стали',
                'hardness_range' => 'HB 180-220',
                'cutting_speed_min' => 20,
                'cutting_speed_max' => 28,
                'feed_per_rev_min' => 0.06,
                'feed_per_rev_max' => 0.20,
                'power_factor' => 1.0,
                'specific_pressure' => 2000,
                'thermal_conductivity' => 45.0,
                'description' => 'Углеродистая конструкционная сталь'
            ],
            [
                'name' => 'Сталь 40Х',
                'material_group' => 'alloy_steel',
                'material_group_name' => 'Легированные стали',
                'hardness_range' => 'HB 220-260',
                'cutting_speed_min' => 15,
                'cutting_speed_max' => 22,
                'feed_per_rev_min' => 0.05,
                'feed_per_rev_max' => 0.15,
                'power_factor' => 1.2,
                'specific_pressure' => 2200,
                'thermal_conductivity' => 42.0,
                'description' => 'Хромистая легированная сталь'
            ],

            // Нержавеющие стали
            [
                'name' => 'Нержавеющая сталь 12Х18Н10Т',
                'material_group' => 'stainless_steel',
                'material_group_name' => 'Нержавеющие стали',
                'hardness_range' => 'HB 150-200',
                'cutting_speed_min' => 10,
                'cutting_speed_max' => 18,
                'feed_per_rev_min' => 0.04,
                'feed_per_rev_max' => 0.12,
                'power_factor' => 1.4,
                'specific_pressure' => 2500,
                'thermal_conductivity' => 15.0,
                'description' => 'Аустенитная нержавеющая сталь'
            ],

            // Чугуны
            [
                'name' => 'Чугун СЧ20',
                'material_group' => 'cast_iron',
                'material_group_name' => 'Чугуны',
                'hardness_range' => 'HB 170-220',
                'cutting_speed_min' => 18,
                'cutting_speed_max' => 25,
                'feed_per_rev_min' => 0.10,
                'feed_per_rev_max' => 0.30,
                'power_factor' => 0.7,
                'specific_pressure' => 1200,
                'thermal_conductivity' => 50.0,
                'description' => 'Серый чугун'
            ],

            // Алюминиевые сплавы
            [
                'name' => 'Алюминий АД31',
                'material_group' => 'aluminum',
                'material_group_name' => 'Алюминиевые сплавы',
                'hardness_range' => 'HB 60-80',
                'cutting_speed_min' => 60,
                'cutting_speed_max' => 100,
                'feed_per_rev_min' => 0.15,
                'feed_per_rev_max' => 0.40,
                'power_factor' => 0.3,
                'specific_pressure' => 400,
                'thermal_conductivity' => 180.0,
                'description' => 'Алюминиевый сплав'
            ],
            [
                'name' => 'Дюралюминий Д16',
                'material_group' => 'aluminum',
                'material_group_name' => 'Алюминиевые сплавы',
                'hardness_range' => 'HB 70-90',
                'cutting_speed_min' => 50,
                'cutting_speed_max' => 80,
                'feed_per_rev_min' => 0.12,
                'feed_per_rev_max' => 0.35,
                'power_factor' => 0.4,
                'specific_pressure' => 500,
                'thermal_conductivity' => 160.0,
                'description' => 'Дюралюминий'
            ],

            // Медные сплавы
            [
                'name' => 'Латунь Л63',
                'material_group' => 'copper_alloy',
                'material_group_name' => 'Медные сплавы',
                'hardness_range' => 'HB 55-65',
                'cutting_speed_min' => 40,
                'cutting_speed_max' => 70,
                'feed_per_rev_min' => 0.10,
                'feed_per_rev_max' => 0.30,
                'power_factor' => 0.5,
                'specific_pressure' => 700,
                'thermal_conductivity' => 120.0,
                'description' => 'Латунь'
            ],
            [
                'name' => 'Бронза БрОЦС5-5-5',
                'material_group' => 'copper_alloy',
                'material_group_name' => 'Медные сплавы',
                'hardness_range' => 'HB 60-70',
                'cutting_speed_min' => 25,
                'cutting_speed_max' => 45,
                'feed_per_rev_min' => 0.08,
                'feed_per_rev_max' => 0.25,
                'power_factor' => 0.6,
                'specific_pressure' => 900,
                'thermal_conductivity' => 80.0,
                'description' => 'Оловянная бронза'
            ]
        ]);
    }
}
