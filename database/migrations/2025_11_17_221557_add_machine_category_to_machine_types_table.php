<?php
// database/migrations/xxxx_xx_xx_xxxxxx_add_machine_category_to_machine_types_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('machine_types', function (Blueprint $table) {
            $table->string('machine_category')->default('turning')->after('max_power_kw');
        });
    }

    public function down()
    {
        Schema::table('machine_types', function (Blueprint $table) {
            $table->dropColumn('machine_category');
        });
    }
};
