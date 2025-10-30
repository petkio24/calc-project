<?php

use App\Http\Controllers\CalculatorDrillingController;
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
});
