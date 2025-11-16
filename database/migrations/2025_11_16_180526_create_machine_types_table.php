<?php
// database/migrations/2024_01_01_000004_create_machine_types_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMachineTypesTable extends Migration
{
    public function up()
    {
        Schema::create('machine_types', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Название станка
            $table->string('power_range'); // Диапазон мощности (5-10 кВт)
            $table->integer('max_rpm'); // Максимальные обороты
            $table->decimal('rigidity_factor', 8, 4); // Коэффициент жесткости
            $table->decimal('efficiency', 5, 4); // КПД станка
            $table->integer('max_power_kw'); // Макс. мощность в кВт
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('machine_types');
    }
}
