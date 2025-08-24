<?php

use Illuminate\Support\Facades\Route;

// Dusk-only login route for browser tests
Route::get('/dusk/login/{id}', function ($id) {
    \Auth::loginUsingId($id);
    return redirect('/');
})->name('dusk.login');
