<?php
// database/seeders/TurningMaterialsSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TurningMaterialsSeeder extends Seeder
{
    public function run()
    {
        DB::table('turning_materials')->insert([
            // ЧЕРНЫЕ МЕТАЛЛЫ
            [
                'name' => 'Сталь 45',
                'material_group' => 'black_metals',
                'material_group_name' => 'Конструкционные стали',
                'hardness_range' => 'HB 180-220',
                'cutting_speed_min' => 120,
                'cutting_speed_max' => 180,
                'feed_min' => 0.1,
                'feed_max' => 0.4,
                'specific_pressure' => 2000,
                'power_factor' => 1.0,
                'thermal_conductivity' => 45.0,
                'description' => 'Углеродистая конструкционная сталь'
            ],
            [
                'name' => 'Сталь 40Х',
                'material_group' => 'black_metals',
                'material_group_name' => 'Легированные стали',
                'hardness_range' => 'HB 220-260',
                'cutting_speed_min' => 100,
                'cutting_speed_max' => 150,
                'feed_min' => 0.08,
                'feed_max' => 0.35,
                'specific_pressure' => 2200,
                'power_factor' => 1.1,
                'thermal_conductivity' => 42.0,
                'description' => 'Хромистая легированная сталь'
            ],
            [
                'name' => 'Нержавеющая сталь 12Х18Н10Т',
                'material_group' => 'black_metals',
                'material_group_name' => 'Нержавеющие стали',
                'hardness_range' => 'HB 150-200',
                'cutting_speed_min' => 60,
                'cutting_speed_max' => 100,
                'feed_min' => 0.05,
                'feed_max' => 0.25,
                'specific_pressure' => 2500,
                'power_factor' => 1.3,
                'thermal_conductivity' => 15.0,
                'description' => 'Аустенитная нержавеющая сталь'
            ],
            [
                'name' => 'Чугун СЧ20',
                'material_group' => 'black_metals',
                'material_group_name' => 'Чугуны',
                'hardness_range' => 'HB 170-220',
                'cutting_speed_min' => 80,
                'cutting_speed_max' => 120,
                'feed_min' => 0.15,
                'feed_max' => 0.5,
                'specific_pressure' => 1200,
                'power_factor' => 0.8,
                'thermal_conductivity' => 50.0,
                'description' => 'Серый чугун'
            ],

            // ЦВЕТНЫЕ МЕТАЛЛЫ
            [
                'name' => 'Алюминий АД31',
                'material_group' => 'nonferrous_metals',
                'material_group_name' => 'Алюминиевые сплавы',
                'hardness_range' => 'HB 60-80',
                'cutting_speed_min' => 300,
                'cutting_speed_max' => 500,
                'feed_min' => 0.1,
                'feed_max' => 0.6,
                'specific_pressure' => 400,
                'power_factor' => 0.3,
                'thermal_conductivity' => 180.0,
                'description' => 'Алюминиевый сплав'
            ],
            [
                'name' => 'Латунь ЛС59',
                'material_group' => 'nonferrous_metals',
                'material_group_name' => 'Медные сплавы',
                'hardness_range' => 'HB 80-100',
                'cutting_speed_min' => 150,
                'cutting_speed_max' => 250,
                'feed_min' => 0.1,
                'feed_max' => 0.4,
                'specific_pressure' => 800,
                'power_factor' => 0.5,
                'thermal_conductivity' => 110.0,
                'description' => 'Латунь'
            ],
            [
                'name' => 'Бронза БрАЖ9-4',
                'material_group' => 'nonferrous_metals',
                'material_group_name' => 'Медные сплавы',
                'hardness_range' => 'HB 110-130',
                'cutting_speed_min' => 100,
                'cutting_speed_max' => 180,
                'feed_min' => 0.08,
                'feed_max' => 0.3,
                'specific_pressure' => 1000,
                'power_factor' => 0.7,
                'thermal_conductivity' => 70.0,
                'description' => 'Алюминиево-железистая бронза'
            ],

            // НЕМЕТАЛЛЫ
            [
                'name' => 'Пластик ПВХ',
                'material_group' => 'non_metals',
                'material_group_name' => 'Пластмассы',
                'hardness_range' => 'HRC 70-90',
                'cutting_speed_min' => 200,
                'cutting_speed_max' => 300,
                'feed_min' => 0.05,
                'feed_max' => 0.2,
                'specific_pressure' => 200,
                'power_factor' => 0.2,
                'thermal_conductivity' => 0.2,
                'description' => 'Поливинилхлорид'
            ],
            [
                'name' => 'Оргстекло',
                'material_group' => 'non_metals',
                'material_group_name' => 'Пластмассы',
                'hardness_range' => 'HRC 85-105',
                'cutting_speed_min' => 150,
                'cutting_speed_max' => 250,
                'feed_min' => 0.03,
                'feed_max' => 0.15,
                'specific_pressure' => 150,
                'power_factor' => 0.15,
                'thermal_conductivity' => 0.2,
                'description' => 'Полиметилметакрилат'
            ]
        ]);
    }
}
