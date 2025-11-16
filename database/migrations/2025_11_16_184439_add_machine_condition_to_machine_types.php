<?php
// database/migrations/2024_01_01_000007_add_machine_condition_to_machine_types.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMachineConditionToMachineTypes extends Migration
{
    public function up()
    {
        Schema::table('machine_types', function (Blueprint $table) {
            $table->integer('years_in_service')->default(0);
            $table->decimal('condition_factor', 8, 4)->default(1.0);
            $table->string('machine_condition')->default('new'); // new, normal, worn
        });
    }

    public function down()
    {
        Schema::table('machine_types', function (Blueprint $table) {
            $table->dropColumn(['years_in_service', 'condition_factor', 'machine_condition']);
        });
    }
}
