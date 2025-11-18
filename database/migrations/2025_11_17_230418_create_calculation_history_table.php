<?php
// database/migrations/2024_01_01_000000_create_calculation_history_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCalculationHistoryTable extends Migration
{
    public function up()
    {
        Schema::create('calculation_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('operation_type', ['turning', 'milling', 'drilling']);
            $table->string('title');
            $table->json('input_parameters');
            $table->json('calculation_results');
            $table->boolean('is_favorite')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'operation_type']);
            $table->index('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('calculation_history');
    }
}
