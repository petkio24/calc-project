<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MillingMaterialsSeeder extends Seeder
{
    public function run()
    {
        DB::table('milling_materials')->insert([
            // Конструкционные стали
            [
                'name' => 'Сталь 20',
                'material_group' => 'carbon_steel',
                'material_group_name' => 'Конструкционные стали',
                'hardness_range' => 'HB 110-150',
                'cutting_speed_min' => 80,
                'cutting_speed_max' => 120,
                'feed_per_tooth_min' => 0.05,
                'feed_per_tooth_max' => 0.15,
                'power_factor' => 0.9,
                'specific_pressure' => 1800,
                'thermal_conductivity' => 48.0,
                'description' => 'Низкоуглеродистая сталь'
            ],
            [
                'name' => 'Сталь 45',
                'material_group' => 'carbon_steel',
                'material_group_name' => 'Конструкционные стали',
                'hardness_range' => 'HB 180-220',
                'cutting_speed_min' => 60,
                'cutting_speed_max' => 100,
                'feed_per_tooth_min' => 0.04,
                'feed_per_tooth_max' => 0.12,
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
                'cutting_speed_min' => 40,
                'cutting_speed_max' => 70,
                'feed_per_tooth_min' => 0.03,
                'feed_per_tooth_max' => 0.10,
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
                'cutting_speed_min' => 30,
                'cutting_speed_max' => 50,
                'feed_per_tooth_min' => 0.02,
                'feed_per_tooth_max' => 0.08,
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
                'cutting_speed_min' => 50,
                'cutting_speed_max' => 80,
                'feed_per_tooth_min' => 0.08,
                'feed_per_tooth_max' => 0.20,
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
                'cutting_speed_min' => 200,
                'cutting_speed_max' => 400,
                'feed_per_tooth_min' => 0.10,
                'feed_per_tooth_max' => 0.25,
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
                'cutting_speed_min' => 150,
                'cutting_speed_max' => 300,
                'feed_per_tooth_min' => 0.08,
                'feed_per_tooth_max' => 0.20,
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
                'cutting_speed_min' => 100,
                'cutting_speed_max' => 200,
                'feed_per_tooth_min' => 0.06,
                'feed_per_tooth_max' => 0.18,
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
                'cutting_speed_min' => 80,
                'cutting_speed_max' => 150,
                'feed_per_tooth_min' => 0.05,
                'feed_per_tooth_max' => 0.15,
                'power_factor' => 0.6,
                'specific_pressure' => 900,
                'thermal_conductivity' => 80.0,
                'description' => 'Оловянная бронза'
            ],

            // Пластмассы
            [
                'name' => 'Пластик ПВХ',
                'material_group' => 'plastics',
                'material_group_name' => 'Пластмассы',
                'hardness_range' => 'HRC 70-90',
                'cutting_speed_min' => 150,
                'cutting_speed_max' => 250,
                'feed_per_tooth_min' => 0.03,
                'feed_per_tooth_max' => 0.10,
                'power_factor' => 0.15,
                'specific_pressure' => 150,
                'thermal_conductivity' => 0.2,
                'description' => 'Поливинилхлорид'
            ],
            [
                'name' => 'Оргстекло',
                'material_group' => 'plastics',
                'material_group_name' => 'Пластмассы',
                'hardness_range' => 'HRC 85-105',
                'cutting_speed_min' => 100,
                'cutting_speed_max' => 180,
                'feed_per_tooth_min' => 0.02,
                'feed_per_tooth_max' => 0.08,
                'power_factor' => 0.12,
                'specific_pressure' => 120,
                'thermal_conductivity' => 0.2,
                'description' => 'Полиметилметакрилат'
            ]
        ]);
    }
}
