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

Route::middleware(['auth'])->prefix('executive')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\ExecutiveController::class, 'index'])
        ->name('executive.dashboard');

    Route::post('/approval/{tconst}', [App\Http\Controllers\ExecutiveController::class, 'updateStatus'])
        ->name('executive.approval');

    Route::get('/review/{tconst}', [App\Http\Controllers\ExecutiveController::class, 'show'])
        ->name('executive.show');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

use App\Http\Controllers\CampaignController;

Route::prefix('marketing')->group(function () {
    
    Route::get('/dashboard', [App\Http\Controllers\MarketingController::class, 'index'])->name('marketing.dashboard');

    Route::resource('campaigns', CampaignController::class);
});

use App\Http\Controllers\NativeController;

Route::get('/title/{tconst}', [NativeController::class, 'show'])->name('native.show');

require __DIR__.'/auth.php';