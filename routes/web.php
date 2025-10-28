<?php

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
    // Здесь будут все защищенные маршруты
});
