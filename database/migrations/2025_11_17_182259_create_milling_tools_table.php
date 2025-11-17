<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('milling_tools', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('tool_type');
            $table->string('tool_type_name');
            $table->string('material_type');
            $table->string('material_type_name');
            $table->integer('number_of_teeth_min');
            $table->integer('number_of_teeth_max');
            $table->decimal('helix_angle', 5, 2)->nullable();
            $table->decimal('max_cutting_speed', 8, 2);
            $table->decimal('wear_resistance_factor', 5, 3);
            $table->decimal('toughness_factor', 5, 3);
            $table->text('application');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('milling_tools');
    }
};
