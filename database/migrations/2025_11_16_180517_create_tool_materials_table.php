<?php
// database/migrations/2024_01_01_000002_create_tool_materials_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateToolMaterialsTable extends Migration
{
    public function up()
    {
        Schema::create('tool_materials', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('material_type'); // hard_alloy, high_speed_steel, ceramic
            $table->string('material_type_name'); // Твердые сплавы, Быстрорежущие стали, Керамика
            $table->string('grade'); // Марка (P10, M2, etc.)
            $table->decimal('max_cutting_speed', 8, 2); // Макс. скорость резания м/мин
            $table->decimal('wear_resistance_factor', 8, 4); // Коэффициент износостойкости
            $table->decimal('thermal_resistance', 10, 4); // Термостойкость °C
            $table->decimal('toughness_factor', 8, 4); // Коэффициент вязкости
            $table->decimal('speed_factor', 8, 4); // Коэффициент для скорости
            $table->text('application')->nullable(); // Область применения
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tool_materials');
    }
}
