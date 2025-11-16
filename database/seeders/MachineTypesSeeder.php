<?php
// database/seeders/MachineTypesSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MachineTypesSeeder extends Seeder
{
    public function run()
    {
        DB::table('machine_types')->insert([
            [
                'name' => 'Токарный станок 16К20',
                'power_range' => '7.5-11 кВт',
                'max_rpm' => 1600,
                'rigidity_factor' => 0.9,
                'efficiency' => 0.85,
                'max_power_kw' => 11.0, // Используем float вместо integer
                'description' => 'Универсальный токарно-винторезный станок'
            ],
            [
                'name' => 'Токарный станок с ЧПУ',
                'power_range' => '15-22 кВт',
                'max_rpm' => 4500,
                'rigidity_factor' => 1.0,
                'efficiency' => 0.9,
                'max_power_kw' => 22.0,
                'description' => 'Современный токарный станок с ЧПУ'
            ],
            [
                'name' => 'Токарный станок 1К62',
                'power_range' => '5.5-7.5 кВт',
                'max_rpm' => 2000,
                'rigidity_factor' => 0.8,
                'efficiency' => 0.8,
                'max_power_kw' => 7.5,
                'description' => 'Классический токарный станок'
            ],
            [
                'name' => 'Токарный обрабатывающий центр',
                'power_range' => '25-40 кВт',
                'max_rpm' => 6000,
                'rigidity_factor' => 1.2,
                'efficiency' => 0.92,
                'max_power_kw' => 40.0,
                'description' => 'Высокопроизводительный обрабатывающий центр'
            ]
        ]);
    }
}
