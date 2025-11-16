<?php
// database/seeders/UpdateMachineTypesSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateMachineTypesSeeder extends Seeder
{
    public function run()
    {
        // Обновляем существующие станки с учетом состояния
        DB::table('machine_types')->where('name', 'Токарный станок 16К20')->update([
            'years_in_service' => 5,
            'condition_factor' => 0.9,
            'machine_condition' => 'normal'
        ]);

        DB::table('machine_types')->where('name', 'Токарный станок с ЧПУ')->update([
            'years_in_service' => 2,
            'condition_factor' => 1.0,
            'machine_condition' => 'new'
        ]);

        DB::table('machine_types')->where('name', 'Токарный станок 1К62')->update([
            'years_in_service' => 12,
            'condition_factor' => 0.75,
            'machine_condition' => 'worn'
        ]);

        DB::table('machine_types')->where('name', 'Токарный обрабатывающий центр')->update([
            'years_in_service' => 1,
            'condition_factor' => 1.0,
            'machine_condition' => 'new'
        ]);
    }
}
