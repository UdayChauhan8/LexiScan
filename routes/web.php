<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [\App\Http\Controllers\AnalysisController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('analyses', \App\Http\Controllers\AnalysisController::class);

    // Report Management (Generate/Revoke)
    Route::post('/analyses/{analysis}/report', [\App\Http\Controllers\ReportController::class, 'store'])->name('reports.store');
    Route::delete('/analyses/{analysis}/report', [\App\Http\Controllers\ReportController::class, 'destroy'])->name('reports.destroy');
});

// Public Report Route
Route::get('/r/{token}', [\App\Http\Controllers\ReportController::class, 'show'])->name('reports.show');

require __DIR__ . '/auth.php';
