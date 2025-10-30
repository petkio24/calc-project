<?php

use App\Http\Controllers\CalculatorDrillingController;
use App\Http\Controllers\CalculatorMillingController;
use App\Http\Controllers\CalculatorTurningController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

// Защищаем все остальные маршруты аутентификацией
Route::middleware(['auth'])->group(function () {
    // Маршруты калькуляторов
    Route::get('/calculators/drilling', [CalculatorDrillingController::class, 'index'])->name('calculators.drilling');
    Route::post('/calculators/drilling/calculate', [CalculatorDrillingController::class, 'calculate'])->name('calculators.drilling.calculate');

    Route::get('/calculators/turning', [CalculatorTurningController::class, 'index'])->name('calculators.turning');
    Route::post('/calculators/turning/calculate', [CalculatorTurningController::class, 'calculate'])->name('calculators.turning.calculate');

    Route::get('/calculators/milling', [CalculatorMillingController::class, 'index'])->name('calculators.milling');
    Route::post('/calculators/milling/calculate', [CalculatorMillingController::class, 'calculate'])->name('calculators.milling.calculate');
});
