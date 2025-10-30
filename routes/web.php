<?php

use App\Http\Controllers\CalculatorDrillingController;
use App\Http\Controllers\CalculatorMillingController;
use App\Http\Controllers\CalculatorTurningController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReferenceController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

// Защищаем все остальные маршруты аутентификацией
Route::middleware(['auth'])->group(function () {
    // Маршруты справочников
    Route::prefix('references')->group(function () {
        Route::get('/', [ReferenceController::class, 'index'])->name('references.index');
        Route::get('/drilling-materials', [ReferenceController::class, 'drillingMaterials'])->name('references.drilling-materials');
        Route::get('/turning-materials', [ReferenceController::class, 'turningMaterials'])->name('references.turning-materials');
        Route::get('/milling-materials', [ReferenceController::class, 'millingMaterials'])->name('references.milling-materials');
        Route::get('/tool-materials', [ReferenceController::class, 'toolMaterials'])->name('references.tool-materials');
        Route::get('/tool-geometries', [ReferenceController::class, 'toolGeometries'])->name('references.tool-geometries');
        Route::get('/machine-types', [ReferenceController::class, 'machineTypes'])->name('references.machine-types');
    });
    // Маршруты калькуляторов
    Route::get('/calculators/drilling', [CalculatorDrillingController::class, 'index'])->name('calculators.drilling');
    Route::post('/calculators/drilling/calculate', [CalculatorDrillingController::class, 'calculate'])->name('calculators.drilling.calculate');

    Route::get('/calculators/turning', [CalculatorTurningController::class, 'index'])->name('calculators.turning');
    Route::post('/calculators/turning/calculate', [CalculatorTurningController::class, 'calculate'])->name('calculators.turning.calculate');

    Route::get('/calculators/milling', [CalculatorMillingController::class, 'index'])->name('calculators.milling');
    Route::post('/calculators/milling/calculate', [CalculatorMillingController::class, 'calculate'])->name('calculators.milling.calculate');
});
