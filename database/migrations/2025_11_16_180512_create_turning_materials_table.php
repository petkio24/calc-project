<?php
// database/migrations/2024_01_01_000001_create_turning_materials_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTurningMaterialsTable extends Migration
{
    public function up()
    {
        Schema::create('turning_materials', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('material_group'); // black_metals, nonferrous_metals, non_metals
            $table->string('material_group_name'); // Черные металлы, Цветные металлы, Неметаллы
            $table->string('hardness_range'); // Твердость по Бринеллю
            $table->decimal('cutting_speed_min', 8, 2); // Минимальная скорость резания м/мин
            $table->decimal('cutting_speed_max', 8, 2); // Максимальная скорость резания м/мин
            $table->decimal('feed_min', 8, 4); // Минимальная подача мм/об
            $table->decimal('feed_max', 8, 4); // Максимальная подача мм/об
            $table->decimal('specific_pressure', 10, 4); // Удельное давление резания Н/мм²
            $table->decimal('power_factor', 10, 4); // Коэффициент мощности
            $table->decimal('thermal_conductivity', 10, 4); // Теплопроводность
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('turning_materials');
    }
}
