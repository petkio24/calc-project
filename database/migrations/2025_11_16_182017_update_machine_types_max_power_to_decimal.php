<?php
// database/migrations/2024_01_01_000006_update_machine_types_max_power_to_decimal.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateMachineTypesMaxPowerToDecimal extends Migration
{
    public function up()
    {
        Schema::table('machine_types', function (Blueprint $table) {
            $table->decimal('max_power_kw', 8, 2)->change();
        });
    }

    public function down()
    {
        Schema::table('machine_types', function (Blueprint $table) {
            $table->integer('max_power_kw')->change();
        });
    }
}
