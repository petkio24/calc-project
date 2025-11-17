<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('milling_materials', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('material_group');
            $table->string('material_group_name');
            $table->string('hardness_range');
            $table->decimal('cutting_speed_min', 8, 2);
            $table->decimal('cutting_speed_max', 8, 2);
            $table->decimal('feed_per_tooth_min', 8, 4);
            $table->decimal('feed_per_tooth_max', 8, 4);
            $table->decimal('power_factor', 8, 4);
            $table->decimal('specific_pressure', 8, 2);
            $table->decimal('thermal_conductivity', 8, 2);
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index('material_group');
        });
    }

    public function down()
    {
        Schema::dropIfExists('milling_materials');
    }
};
