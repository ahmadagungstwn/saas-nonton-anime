<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AnimeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CoinTopUpController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/anime/{slug}', [AnimeController::class, 'show'])->name('anime.show');

Route::middleware('auth')->group(function () {
    Route::get('profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile/update-picture', [ProfileController::class, 'updatePicture'])
        ->name('profile.updatePicture');
    Route::post('profile', [AuthController::class, 'logout'])->name('logout');

    Route::get('/anime/{animeSlug}/episode/{episodeSlug}', [AnimeController::class, 'play'])->name('anime.play');
    Route::post('/anime/{animeSlug}/unlock/{episodeId}', [AnimeController::class, 'unlockEpisode'])->name('episode.unlock');

    Route::post('/topup/coin/{packageId}', [CoinTopUpController::class, 'topup'])
        ->middleware('auth');


    Route::post('/midtrans/callback', [CoinTopUpController::class, 'callback']);
});

Route::get('login', [AuthController::class, 'loginForm'])->name('login');
Route::post('login', [AuthController::class, 'storeLogin'])->name('login.store');

Route::get('register', [AuthController::class, 'registerForm'])->name('register');
Route::post('register', [AuthController::class, 'storeRegister'])->name('register.store');
