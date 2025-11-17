<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MillingToolsSeeder extends Seeder
{
    public function run()
    {
        DB::table('milling_tools')->insert([
            // Концевые фрезы HSS
            [
                'name' => 'Фреза концевая HSS 2-зубая',
                'tool_type' => 'end_mill',
                'tool_type_name' => 'Фреза концевая',
                'material_type' => 'hss',
                'material_type_name' => 'Быстрорежущая сталь',
                'number_of_teeth_min' => 2,
                'number_of_teeth_max' => 2,
                'helix_angle' => 30.0,
                'max_cutting_speed' => 40,
                'wear_resistance_factor' => 1.0,
                'toughness_factor' => 1.2,
                'application' => 'Для алюминия, пластиков и мягких материалов'
            ],
            [
                'name' => 'Фреза концевая HSS 4-зубая',
                'tool_type' => 'end_mill',
                'tool_type_name' => 'Фреза концевая',
                'material_type' => 'hss',
                'material_type_name' => 'Быстрорежущая сталь',
                'number_of_teeth_min' => 4,
                'number_of_teeth_max' => 4,
                'helix_angle' => 35.0,
                'max_cutting_speed' => 35,
                'wear_resistance_factor' => 1.1,
                'toughness_factor' => 1.0,
                'application' => 'Универсальная для сталей средней твердости'
            ],

            // Твердосплавные концевые фрезы
            [
                'name' => 'Фреза концевая твердосплавная 3-зубая',
                'tool_type' => 'end_mill',
                'tool_type_name' => 'Фреза концевая',
                'material_type' => 'carbide',
                'material_type_name' => 'Твердый сплав',
                'number_of_teeth_min' => 3,
                'number_of_teeth_max' => 3,
                'helix_angle' => 45.0,
                'max_cutting_speed' => 150,
                'wear_resistance_factor' => 1.8,
                'toughness_factor' => 0.8,
                'application' => 'Для высокоскоростного фрезерования алюминия'
            ],
            [
                'name' => 'Фреза концевая твердосплавная 6-зубая',
                'tool_type' => 'end_mill',
                'tool_type_name' => 'Фреза концевая',
                'material_type' => 'carbide',
                'material_type_name' => 'Твердый сплав',
                'number_of_teeth_min' => 6,
                'number_of_teeth_max' => 6,
                'helix_angle' => 40.0,
                'max_cutting_speed' => 120,
                'wear_resistance_factor' => 1.6,
                'toughness_factor' => 1.0,
                'application' => 'Для твердых сталей и чугуна'
            ],

            // Торцевые фрезы
            [
                'name' => 'Фреза торцевая HSS',
                'tool_type' => 'face_mill',
                'tool_type_name' => 'Фреза торцевая',
                'material_type' => 'hss',
                'material_type_name' => 'Быстрорежущая сталь',
                'number_of_teeth_min' => 6,
                'number_of_teeth_max' => 8,
                'helix_angle' => 15.0,
                'max_cutting_speed' => 30,
                'wear_resistance_factor' => 1.0,
                'toughness_factor' => 1.1,
                'application' => 'Для черновой обработки плоскостей'
            ],
            [
                'name' => 'Фреза торцевая твердосплавная',
                'tool_type' => 'face_mill',
                'tool_type_name' => 'Фреза торцевая',
                'material_type' => 'carbide',
                'material_type_name' => 'Твердый сплав',
                'number_of_teeth_min' => 8,
                'number_of_teeth_max' => 12,
                'helix_angle' => 20.0,
                'max_cutting_speed' => 200,
                'wear_resistance_factor' => 1.7,
                'toughness_factor' => 0.9,
                'application' => 'Для высокопроизводительного фрезерования'
            ],

            // Фрезы для пазов
            [
                'name' => 'Фреза пазовая HSS',
                'tool_type' => 'slot_mill',
                'tool_type_name' => 'Фреза пазовая',
                'material_type' => 'hss',
                'material_type_name' => 'Быстрорежущая сталь',
                'number_of_teeth_min' => 3,
                'number_of_teeth_max' => 4,
                'helix_angle' => 25.0,
                'max_cutting_speed' => 25,
                'wear_resistance_factor' => 0.9,
                'toughness_factor' => 1.0,
                'application' => 'Для фрезерования пазов и канавок'
            ]
        ]);
    }
}
