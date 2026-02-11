<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('login'));

// Route bawaan Breeze
require __DIR__.'/auth.php';

/**
 * Dashboard default Breeze.
 * Karena redirect role sudah ditangani di AuthenticatedSessionController@store,
 * route ini cukup jadi fallback bila user akses /dashboard manual.
 */
Route::get('/dashboard', fn () => redirect()->route('home'))
    ->middleware(['auth'])
    ->name('dashboard');

/**
 * Siswa (Home)
 */
Route::get('/home', fn () => view('dashboard.home'))
    ->middleware(['auth'])
    ->name('home');

/**
 * Admin
 */
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {

    Route::get('/dashboard', fn () => view('admin.dashboard'))->name('dashboard');

    Route::get('/guru-bk', fn () => 'index guru bk')->name('gurubk.index');
    Route::get('/siswa', fn () => 'index siswa')->name('siswa.index');

    Route::get('/status', fn () => 'status index')->name('status.index');

    Route::get('/jurusan', fn () => 'jurusan index')->name('jurusan.index');

    Route::get('/artikel', fn () => 'artikel index')->name('artikel.index');

    Route::get('/informasi-jurusan', fn () => 'info jurusan index')->name('infojurusan.index');

    Route::get('/monitoring', fn () => 'monitoring index')->name('monitoring.index');
});


/**
 * Guru BK (dummy dulu)
 */
Route::prefix('bk')->name('bk.')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', fn () => view('bk.dashboard'))->name('dashboard');
});
