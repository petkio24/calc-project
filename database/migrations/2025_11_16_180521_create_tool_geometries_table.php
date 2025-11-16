<?php
// database/migrations/2024_01_01_000003_create_tool_geometries_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateToolGeometriesTable extends Migration
{
    public function up()
    {
        Schema::create('tool_geometries', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Маркировка (CNMG, SNMM, etc.)
            $table->string('shape'); // diamond, square, triangle, round
            $table->string('shape_name'); // Ромб, Квадрат, Треугольник, Круг
            $table->decimal('clearance_angle', 5, 2); // Задний угол °
            $table->string('tolerance_class'); // Класс точности
            $table->string('tolerance_class_name'); // Название класса точности
            $table->string('chipbreaker_type')->nullable(); // Тип стружколома
            $table->decimal('cutting_edge_length', 8, 2); // Длина режущей кромки мм
            $table->decimal('insert_thickness', 8, 2); // Толщина пластины мм
            $table->decimal('corner_radius', 8, 2); // Радиус при вершине мм
            $table->decimal('feed_factor', 8, 4); // Коэффициент для подачи
            $table->decimal('speed_factor', 8, 4); // Коэффициент для скорости
            $table->decimal('power_factor', 8, 4); // Коэффициент для мощности
            $table->decimal('max_depth_of_cut', 8, 2); // Макс. глубина резания мм
            $table->text('recommended_use')->nullable(); // Рекомендуемое применение
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tool_geometries');
    }
}
