<?php

use App\Events\EnemyMove;
use App\Events\PlayerMove;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Jobs\PreformService;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/game', function () {
    return Inertia::render('Game');
})->middleware(['auth', 'verified'])->name('game');

Route::post('/start', function (Request $request) {
     Log::info($request->all());
    $playerPosition = $request->all();
    // $playerPosition = [
    //     'x' => $player[0],
    //     'y' => $player[1],
    // ];
    PlayerMove::dispatch($playerPosition);
    return to_route('game'); 
    
})->name('start');

Route::get('/lists', function () {
    return Inertia::render('Lists');
})->middleware(['auth', 'verified'])->name('lists');
Route::get('/service', function () {
    $service = Auth::user()->service;
    return Inertia::render('Service',
[
    'service' => $service,
]);
})->middleware(['auth', 'verified'])->name('service');

Route::post('/service', function () {
    Auth::user()->service()->create([
        'content' => request('content'),
    ]);
    return redirect()->route('service');
})->middleware(['auth', 'verified'])->name('service.store');

Route::post('/service/preform', function () {
    $user = Auth::user();
    $content = $user->service->content;
    PreformService::dispatch($user);
    return redirect()->route('service');
})->middleware(['auth', 'verified'])->name('service.preform');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
