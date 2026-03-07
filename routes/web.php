<?php

use App\Livewire\Login;
use App\Livewire\Register;
use Illuminate\Support\Facades\Route;

Route::prefix('{locale?}')->group(function (): void {
    Route::get('/', function () {
        return view('pages.home');
    })->name('home')->middleware('auth');

    Route::get('/login', Login::class)->name('login')->middleware('guest');
    Route::get('/register', Register::class)->name('register')->middleware('guest');
});
