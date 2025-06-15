<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\InternshipController;
use App\Http\Controllers\Web\ApplicationController;

Route::get('/', function () {
    return view('landing');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/profile/edit', [App\Http\Controllers\Web\ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [App\Http\Controllers\Web\ProfileController::class, 'update'])->name('profile.update');
});

Route::middleware(['auth', 'ensure.profile.complete'])->group(function () {
    // Route untuk dashboard utama, kita arahkan ke InternshipController@index
    Route::get('/dashboard', [InternshipController::class, 'index'])->name('dashboard');

    // Menggunakan Resource Controller untuk CRUD Lowongan Magang
    // Ini akan otomatis membuat route untuk index, create, store, show, edit, update, destroy
    Route::resource('internships', InternshipController::class);

    // Route untuk melihat pelamar di sebuah lowongan
    Route::get('/internships/{internship}/applications', [ApplicationController::class, 'index'])->name('internships.applications');

    // Route untuk mengupdate status pelamar
    Route::patch('/applications/{application}/status', [ApplicationController::class, 'updateStatus'])->name('applications.updateStatus');

    // unutk melihat profile perusahaan
    Route::get('/profile', [App\Http\Controllers\Web\ProfileController::class, 'show'])->name('profile.show');

    // Route untuk menampilkan detail satu lamaran
    Route::get('/applications/{application}/details', [ApplicationController::class, 'showDetail'])->name('applications.showDetail');

    // routes/web.php
    Route::resource('divisions', App\Http\Controllers\Web\DivisionController::class)->except(['show']);

    Route::get('/profile/create', [App\Http\Controllers\Web\ProfileController::class, 'create'])->name('profile.create');
    Route::post('/profile/store', [App\Http\Controllers\Web\ProfileController::class, 'store'])->name('profile.store');
});

require __DIR__.'/auth.php';
