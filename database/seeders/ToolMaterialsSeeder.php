<?php
// database/seeders/ToolMaterialsSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ToolMaterialsSeeder extends Seeder
{
    public function run()
    {
        DB::table('tool_materials')->insert([
            // ТВЕРДЫЕ СПЛАВЫ
            [
                'name' => 'Твердый сплав P10',
                'material_type' => 'hard_alloy',
                'material_type_name' => 'Твердые сплавы',
                'grade' => 'P10',
                'max_cutting_speed' => 350,
                'wear_resistance_factor' => 1.0,
                'thermal_resistance' => 900,
                'toughness_factor' => 0.8,
                'speed_factor' => 1.0,
                'application' => 'Для сталей и чугунов'
            ],
            [
                'name' => 'Твердый сплав P20',
                'material_type' => 'hard_alloy',
                'material_type_name' => 'Твердые сплавы',
                'grade' => 'P20',
                'max_cutting_speed' => 300,
                'wear_resistance_factor' => 0.9,
                'thermal_resistance' => 850,
                'toughness_factor' => 1.0,
                'speed_factor' => 0.9,
                'application' => 'Универсальный для сталей'
            ],
            [
                'name' => 'Твердый сплав K10',
                'material_type' => 'hard_alloy',
                'material_type_name' => 'Твердые сплавы',
                'grade' => 'K10',
                'max_cutting_speed' => 250,
                'wear_resistance_factor' => 0.8,
                'thermal_resistance' => 800,
                'toughness_factor' => 1.2,
                'speed_factor' => 0.8,
                'application' => 'Для чугунов и цветных металлов'
            ],
            [
                'name' => 'Твердый сплав M20',
                'material_type' => 'hard_alloy',
                'material_type_name' => 'Твердые сплавы',
                'grade' => 'M20',
                'max_cutting_speed' => 280,
                'wear_resistance_factor' => 0.85,
                'thermal_resistance' => 820,
                'toughness_factor' => 1.1,
                'speed_factor' => 0.85,
                'application' => 'Для нержавеющих сталей'
            ],

            // БЫСТРОРЕЖУЩИЕ СТАЛИ
            [
                'name' => 'Быстрорежущая сталь Р6М5',
                'material_type' => 'high_speed_steel',
                'material_type_name' => 'Быстрорежущие стали',
                'grade' => 'Р6М5',
                'max_cutting_speed' => 60,
                'wear_resistance_factor' => 0.4,
                'thermal_resistance' => 600,
                'toughness_factor' => 1.5,
                'speed_factor' => 0.4,
                'application' => 'Универсальная быстрорез'
            ],
            [
                'name' => 'Быстрорежущая сталь Р18',
                'material_type' => 'high_speed_steel',
                'material_type_name' => 'Быстрорежущие стали',
                'grade' => 'Р18',
                'max_cutting_speed' => 55,
                'wear_resistance_factor' => 0.35,
                'thermal_resistance' => 620,
                'toughness_factor' => 1.4,
                'speed_factor' => 0.35,
                'application' => 'Для труднообрабатываемых материалов'
            ]
        ]);
    }
}
