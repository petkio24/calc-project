<?php
// database/migrations/2024_01_01_000005_create_operation_factors_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOperationFactorsTable extends Migration
{
    public function up()
    {
        Schema::create('operation_factors', function (Blueprint $table) {
            $table->id();
            $table->string('operation_type'); // roughing, finishing
            $table->string('operation_type_name'); // Черновая, Чистовая
            $table->string('surface_quality'); // normal, good, excellent
            $table->string('surface_quality_name'); // Нормальное, Хорошее, Отличное
            $table->decimal('speed_factor', 8, 4); // Коэффициент для скорости
            $table->decimal('feed_factor', 8, 4); // Коэффициент для подачи
            $table->decimal('power_factor', 8, 4); // Коэффициент для мощности
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('operation_factors');
    }
}
