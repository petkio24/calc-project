<?php
// database/migrations/2024_01_01_000008_add_operation_subtype_to_calculations.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOperationSubtypeToCalculations extends Migration
{
    public function up()
    {
        Schema::table('operation_factors', function (Blueprint $table) {
            $table->string('operation_subtype')->default('external_turning'); // external_turning, internal_turning
            $table->string('operation_subtype_name')->default('Наружное точение');
        });
    }

    public function down()
    {
        Schema::table('operation_factors', function (Blueprint $table) {
            $table->dropColumn(['operation_subtype', 'operation_subtype_name']);
        });
    }
}
