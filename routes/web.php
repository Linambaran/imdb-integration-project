<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\MarketingController;
use App\Http\Controllers\ExecutiveController;
use Illuminate\Support\Facades\Auth;

Route::get('/', [PublicController::class, 'welcome'])->name('welcome');

Route::get('/search-preview', [PublicController::class, 'searchPreview'])->name('search.preview');

Route::get('/dashboard', function () {
    $user = Auth::user();

    if ($user->role == 'Marketing') {
        return redirect()->route('marketing.dashboard');
    } 
    elseif ($user->role == 'Executive') {
        return redirect()->route('executive.dashboard');
    }

    return redirect()->route('welcome');

})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'role:Marketing'])->group(function () {
    Route::get('/marketing/dashboard', [MarketingController::class, 'index'])
        ->name('marketing.dashboard');
});

Route::middleware(['auth', 'role:Executive'])->group(function () {
    Route::get('/executive/dashboard', [ExecutiveController::class, 'index'])
        ->name('executive.dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';