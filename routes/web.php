<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TrackController;
use App\Http\Controllers\PlaylistController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Redirect accueil → tracks
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect('/tracks');
});

/*
|--------------------------------------------------------------------------
| Routes protégées (auth obligatoire)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // 🎵 TRACKS
    Route::get('/tracks', [TrackController::class, 'index'])->name('tracks.index');
    Route::get('/tracks/create', [TrackController::class, 'create'])->name('tracks.create');
    Route::post('/tracks/store', [TrackController::class, 'store'])->name('tracks.store');

    // 🌍 API musique
    Route::get('/tracks/online', [TrackController::class, 'searchOnline'])->name('tracks.online');

    // 🔥 YouTube
    Route::get('/tracks/youtube', [TrackController::class, 'youtube'])->name('tracks.youtube');

    // 🎶 PLAYLISTS
    Route::get('/playlists', [PlaylistController::class, 'index'])->name('playlists.index');
    Route::get('/playlists/create', [PlaylistController::class, 'create'])->name('playlists.create');
    Route::post('/playlist/add-track', [PlaylistController::class, 'addTrack'])->name('playlist.add');
    Route::post('/playlist/remove-track', [PlaylistController::class, 'removeTrack'])->name('playlist.remove');

    // 👤 PROFILE
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

/*
|--------------------------------------------------------------------------
| Auth routes (login/register)
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';
