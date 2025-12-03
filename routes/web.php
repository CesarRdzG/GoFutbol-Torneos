<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TournamentController;
use Illuminate\Support\Facades\Route;
use App\Models\Tournament;

Route::get('/', function () {
    return redirect()->route('register');
});

Route::get('/dashboard', function () {
    $tournaments = Tournament::all(); 
    return view('dashboard', compact('tournaments'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/tournaments/{tournament}/pdf', [TournamentController::class, 'downloadPdf'])
    ->name('tournaments.pdf');

    Route::post('/tournaments/{tournament}/add-team', [TournamentController::class, 'addTeam'])
        ->name('tournaments.add-team');

    Route::resource('tournaments', TournamentController::class);
});

require __DIR__.'/auth.php';