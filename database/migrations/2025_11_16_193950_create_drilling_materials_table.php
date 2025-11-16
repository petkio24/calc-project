<?php
// database/migrations/2024_01_01_000001_create_drilling_materials_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDrillingMaterialsTable extends Migration
{
    public function up()
    {
        Schema::create('drilling_materials', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('material_group');
            $table->string('material_group_name');
            $table->string('hardness_range');
            $table->decimal('cutting_speed_min', 8, 2);
            $table->decimal('cutting_speed_max', 8, 2);
            $table->decimal('feed_per_rev_min', 8, 4);
            $table->decimal('feed_per_rev_max', 8, 4);
            $table->decimal('power_factor', 8, 3);
            $table->decimal('specific_pressure', 8, 2);
            $table->decimal('thermal_conductivity', 8, 2);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('drilling_materials');
    }
}
