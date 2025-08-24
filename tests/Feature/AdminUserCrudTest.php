<?php

declare(strict_types=1);

use Livewire\Livewire;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

it('super-admin can access user CRUD', function () {
    $admin = User::factory()->create(['email' => 'admin@admin.com']);
    \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);
    $admin->assignRole('super-admin');
    Auth::login($admin);

        $component = Livewire::test(\App\Livewire\Admin\Users::class);
        $component->assertSee('Users');
});

it('non-super-admin cannot see edit/delete buttons', function () {
    $user = User::factory()->create(['email' => 'user@example.com']);
    \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);
    $user->assignRole('user');
    Auth::login($user);

        Auth::login($user);
        $component = Livewire::test(\App\Livewire\Admin\Users::class);
        $html = $component->html();
        $tableStart = strpos($html, '<table');
        $tableEnd = strpos($html, '</table>');
        $tableHtml = substr($html, $tableStart, $tableEnd - $tableStart);
        expect($tableHtml)->not->toContain('Edit');
        expect($tableHtml)->not->toContain('Delete');
});
