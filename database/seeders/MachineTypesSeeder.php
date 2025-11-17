<?php
// database/seeders/MachineTypesSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MachineTypesSeeder extends Seeder
{
    public function run()
    {
        // Очищаем таблицу перед добавлением новых данных
        DB::table('machine_types')->delete();

        DB::table('machine_types')->insert([
            // ТОКАРНЫЕ СТАНКИ
            [
                'name' => 'Токарный станок 16К20',
                'power_range' => '7.5-11 кВт',
                'max_rpm' => 1600,
                'rigidity_factor' => 0.9,
                'efficiency' => 0.85,
                'max_power_kw' => 11.0,
                'machine_category' => 'turning',
                'description' => 'Универсальный токарно-винторезный станок',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Токарный станок с ЧПУ',
                'power_range' => '15-22 кВт',
                'max_rpm' => 4500,
                'rigidity_factor' => 1.0,
                'efficiency' => 0.9,
                'max_power_kw' => 22.0,
                'machine_category' => 'turning',
                'description' => 'Современный токарный станок с ЧПУ',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Токарный станок 1К62',
                'power_range' => '5.5-7.5 кВт',
                'max_rpm' => 2000,
                'rigidity_factor' => 0.8,
                'efficiency' => 0.8,
                'max_power_kw' => 7.5,
                'machine_category' => 'turning',
                'description' => 'Классический токарный станок',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // ФРЕЗЕРНЫЕ СТАНКИ
            [
                'name' => 'Вертикально-фрезерный 6Р13',
                'power_range' => '7.5-11 кВт',
                'max_rpm' => 1600,
                'rigidity_factor' => 1.0,
                'efficiency' => 0.85,
                'max_power_kw' => 11.0,
                'machine_category' => 'milling',
                'description' => 'Универсальный вертикально-фрезерный станок',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Фрезерный станок с ЧПУ VMC-850',
                'power_range' => '15-22 кВт',
                'max_rpm' => 8000,
                'rigidity_factor' => 1.2,
                'efficiency' => 0.9,
                'max_power_kw' => 22.0,
                'machine_category' => 'milling',
                'description' => 'Вертикальный обрабатывающий центр',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Горизонтально-фрезерный 6Р82',
                'power_range' => '7.5-11 кВт',
                'max_rpm' => 1500,
                'rigidity_factor' => 0.9,
                'efficiency' => 0.82,
                'max_power_kw' => 11.0,
                'machine_category' => 'milling',
                'description' => 'Горизонтально-фрезерный станок',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Универсально-фрезерный 6Р82Ш',
                'power_range' => '7.5-11 кВт',
                'max_rpm' => 1500,
                'rigidity_factor' => 0.9,
                'efficiency' => 0.8,
                'max_power_kw' => 11.0,
                'machine_category' => 'milling',
                'description' => 'Универсально-фрезерный станок',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // СВЕРЛИЛЬНЫЕ СТАНКИ
            [
                'name' => 'Сверлильный станок 2Н135',
                'power_range' => '4-5.5 кВт',
                'max_rpm' => 2000,
                'rigidity_factor' => 0.8,
                'efficiency' => 0.8,
                'max_power_kw' => 5.5,
                'machine_category' => 'drilling',
                'description' => 'Вертикально-сверлильный станок',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Радиально-сверлильный станок 2Н55',
                'power_range' => '7.5-11 кВт',
                'max_rpm' => 1600,
                'rigidity_factor' => 0.9,
                'efficiency' => 0.85,
                'max_power_kw' => 11.0,
                'machine_category' => 'drilling',
                'description' => 'Радиально-сверлильный станок',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Координатно-сверлильный станок',
                'power_range' => '5.5-7.5 кВт',
                'max_rpm' => 3000,
                'rigidity_factor' => 1.0,
                'efficiency' => 0.88,
                'max_power_kw' => 7.5,
                'machine_category' => 'drilling',
                'description' => 'Координатно-сверлильный станок с ЧПУ',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
