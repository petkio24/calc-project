<?php
// database/seeders/UpdateDrillingMaterialsGroupingSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateDrillingMaterialsGroupingSeeder extends Seeder
{
    public function run()
    {
        // Обновляем группировку существующих материалов
        DB::table('drilling_materials')
            ->where('material_group', 'carbon_steel')
            ->update(['material_group' => 'carbon_steel']);

        DB::table('drilling_materials')
            ->where('material_group', 'alloy_steel')
            ->update(['material_group' => 'alloy_steel']);

        DB::table('drilling_materials')
            ->where('material_group', 'aluminum')
            ->update(['material_group' => 'aluminum']);

        DB::table('drilling_materials')
            ->where('material_group', 'copper_alloy')
            ->update(['material_group' => 'copper_alloy']);

        DB::table('drilling_materials')
            ->where('material_group', 'plastics')
            ->update(['material_group' => 'plastics']);

        // Добавляем материалы в основные группы для полноты
        DB::table('drilling_materials')->insertOrIgnore([
            // Черные металлы
            [
                'name' => 'Сталь 20',
                'material_group' => 'black_metals',
                'material_group_name' => 'Черные металлы',
                'hardness_range' => 'HB 120-150',
                'cutting_speed_min' => 25,
                'cutting_speed_max' => 35,
                'feed_per_rev_min' => 0.1,
                'feed_per_rev_max' => 0.3,
                'power_factor' => 0.9,
                'specific_pressure' => 1800,
                'thermal_conductivity' => 48.0,
                'description' => 'Низкоуглеродистая сталь'
            ],
            // Цветные металлы
            [
                'name' => 'Латунь Л63',
                'material_group' => 'nonferrous_metals',
                'material_group_name' => 'Цветные металлы',
                'hardness_range' => 'HB 70-90',
                'cutting_speed_min' => 50,
                'cutting_speed_max' => 80,
                'feed_per_rev_min' => 0.12,
                'feed_per_rev_max' => 0.35,
                'power_factor' => 0.4,
                'specific_pressure' => 500,
                'thermal_conductivity' => 120.0,
                'description' => 'Латунь'
            ],
            // Неметаллы
            [
                'name' => 'Текстолит',
                'material_group' => 'non_metals',
                'material_group_name' => 'Неметаллы',
                'hardness_range' => 'HB 20-30',
                'cutting_speed_min' => 60,
                'cutting_speed_max' => 100,
                'feed_per_rev_min' => 0.08,
                'feed_per_rev_max' => 0.2,
                'power_factor' => 0.18,
                'specific_pressure' => 180,
                'thermal_conductivity' => 0.3,
                'description' => 'Слоистый пластик'
            ]
        ]);
    }
}
