<?php

use App\Http\Controllers\CalculationHistoryController;
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
    Route::prefix('references')->name('references.')->group(function () {
        Route::get('/', [ReferenceController::class, 'index'])->name('index');

        // Материалы
        Route::get('/turning-materials', [ReferenceController::class, 'turningMaterials'])->name('turning-materials');
        Route::get('/drilling-materials', [ReferenceController::class, 'drillingMaterials'])->name('drilling-materials');
        Route::get('/milling-materials', [ReferenceController::class, 'millingMaterials'])->name('milling-materials');

        // Инструменты
        Route::get('/tool-materials', [ReferenceController::class, 'toolMaterials'])->name('tool-materials');
        Route::get('/tool-geometries', [ReferenceController::class, 'toolGeometries'])->name('tool-geometries');
        Route::get('/drilling-tools', [ReferenceController::class, 'drillingTools'])->name('drilling-tools');
        Route::get('/milling-tools', [ReferenceController::class, 'millingTools'])->name('milling-tools');

        // Станки
        Route::get('/machine-types', [ReferenceController::class, 'machineTypes'])->name('machine-types');

        // Поиск
        Route::get('/search', [ReferenceController::class, 'search'])->name('search');
    });

    Route::prefix('history')->name('history.')->group(function () {
        Route::get('/', [CalculationHistoryController::class, 'index'])->name('index');
        Route::get('/{id}', [CalculationHistoryController::class, 'show'])->name('show');
        Route::put('/{id}', [CalculationHistoryController::class, 'update'])->name('update');
        Route::delete('/{id}', [CalculationHistoryController::class, 'destroy'])->name('destroy');
    });

    // Маршруты калькуляторов
    Route::get('/calculators/drilling', [CalculatorDrillingController::class, 'index'])->name('calculators.drilling');
    Route::post('/calculators/drilling/calculate', [CalculatorDrillingController::class, 'calculate'])->name('calculators.drilling.calculate');

    Route::get('/calculators/turning', [CalculatorTurningController::class, 'index'])->name('calculators.turning');
    Route::post('/calculators/turning/calculate', [CalculatorTurningController::class, 'calculate'])->name('calculators.turning.calculate');

    Route::get('/calculators/milling', [CalculatorMillingController::class, 'index'])->name('calculators.milling');
    Route::post('/calculators/milling/calculate', [CalculatorMillingController::class, 'calculate'])->name('calculators.milling.calculate');
});
