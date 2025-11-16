<?php
// database/seeders/DrillingToolsSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DrillingToolsSeeder extends Seeder
{
    public function run()
    {
        DB::table('drilling_tools')->insert([
            // Быстрорежущие сверла (HSS)
            [
                'name' => 'Сверло спиральное HSS',
                'tool_type' => 'twist_drill',
                'tool_type_name' => 'Спиральное сверло',
                'material_type' => 'hss',
                'material_type_name' => 'Быстрорежущая сталь',
                'point_angle' => 118.0,
                'helix_angle' => 30.0,
                'max_cutting_speed' => 35,
                'wear_resistance_factor' => 1.0,
                'toughness_factor' => 1.2,
                'application' => 'Универсальное сверло для сталей и чугунов'
            ],
            [
                'name' => 'Сверло HSS с покрытием TiN',
                'tool_type' => 'twist_drill',
                'tool_type_name' => 'Спиральное сверло',
                'material_type' => 'hss_coated',
                'material_type_name' => 'Быстрорежущая сталь с покрытием',
                'point_angle' => 118.0,
                'helix_angle' => 30.0,
                'max_cutting_speed' => 45,
                'wear_resistance_factor' => 1.3,
                'toughness_factor' => 1.1,
                'application' => 'Для повышенной стойкости и производительности'
            ],

            // Твердосплавные сверла
            [
                'name' => 'Сверло твердосплавное P10',
                'tool_type' => 'carbide_drill',
                'tool_type_name' => 'Твердосплавное сверло',
                'material_type' => 'carbide',
                'material_type_name' => 'Твердый сплав',
                'point_angle' => 140.0,
                'helix_angle' => 35.0,
                'max_cutting_speed' => 120,
                'wear_resistance_factor' => 1.8,
                'toughness_factor' => 0.8,
                'application' => 'Для сталей и чугунов'
            ],
            [
                'name' => 'Сверло твердосплавное K10',
                'tool_type' => 'carbide_drill',
                'tool_type_name' => 'Твердосплавное сверло',
                'material_type' => 'carbide',
                'material_type_name' => 'Твердый сплав',
                'point_angle' => 130.0,
                'helix_angle' => 40.0,
                'max_cutting_speed' => 150,
                'wear_resistance_factor' => 1.6,
                'toughness_factor' => 1.0,
                'application' => 'Для цветных металлов и нержавеющих сталей'
            ],

            // Центровочные сверла
            [
                'name' => 'Сверло центровочное',
                'tool_type' => 'center_drill',
                'tool_type_name' => 'Центровочное сверло',
                'material_type' => 'hss',
                'material_type_name' => 'Быстрорежущая сталь',
                'point_angle' => 60.0,
                'helix_angle' => 0.0,
                'max_cutting_speed' => 25,
                'wear_resistance_factor' => 0.9,
                'toughness_factor' => 1.0,
                'application' => 'Для центровки и начального засверливания'
            ]
        ]);
    }
}
