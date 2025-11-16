<?php
// database/migrations/2024_01_01_000002_create_drilling_tools_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDrillingToolsTable extends Migration
{
    public function up()
    {
        Schema::create('drilling_tools', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('tool_type'); // twist_drill, center_drill, etc.
            $table->string('tool_type_name');
            $table->string('material_type');
            $table->string('material_type_name');
            $table->decimal('point_angle', 5, 1); // Угол при вершине
            $table->decimal('helix_angle', 5, 1); // Угол наклона винтовой канавки
            $table->decimal('max_cutting_speed', 8, 2);
            $table->decimal('wear_resistance_factor', 5, 2);
            $table->decimal('toughness_factor', 5, 2);
            $table->text('application');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('drilling_tools');
    }
}
