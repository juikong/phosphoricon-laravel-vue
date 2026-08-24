<?php

use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Categories')->name('categories');

Route::inertia('/welcome', 'Welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';
