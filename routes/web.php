<?php

use App\Http\Controllers\MergeController;
use Illuminate\Support\Facades\Route;

// Dashboard Merge BHL - GEKO
Route::get('/', [MergeController::class, 'index'])->name('merge.index');

// API endpoints
Route::prefix('api')->group(function () {
    Route::get('/statistics', [MergeController::class, 'statistics']);
    Route::get('/lahan', [MergeController::class, 'apiLahan']);
});
