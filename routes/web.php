<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Http\Request;

Route::get('/', function () {
    return Inertia::render('Welcome');
});
Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/game', function () {
    return Inertia::render('Game');
})->middleware(['auth', 'verified'])->name('game');

Route::get('/phasergame', function () {
    return Inertia::render('PhaserGame');
})->middleware(['auth', 'verified'])->name('phasergame');

Route::get('/phasertemplate', function () {
    return Inertia::render('PhaserTemplate');
})->middleware(['auth', 'verified'])->name('phasertemplate');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
