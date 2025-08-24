<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('admin/manage', \App\Livewire\Admin\Manage::class)->name('admin.manage');
    Route::get('admin/permissions', \App\Livewire\Admin\Permissions::class)->name('admin.permissions');
    Route::get('admin/roles', \App\Livewire\Admin\Roles::class)->name('admin.roles');
    Route::get('admin/users', \App\Livewire\Admin\Users::class)->name('admin.users');
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
    
    Route::get('documents', \App\Livewire\Documents\Index::class)->name('documents.index');

});

require __DIR__.'/auth.php';
