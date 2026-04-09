<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.home');
})->name('home');

Route::get('/import', [\App\Http\Controllers\RegionWebController::class, 'create'])->name('import.create');
Route::post('/import', [\App\Http\Controllers\RegionWebController::class, 'store'])->name('import.store');
