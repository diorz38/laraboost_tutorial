<?php
declare(strict_types=1);
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

uses(RefreshDatabase::class);

it('super-admin can select all permissions for a role', function () {
    $admin = User::factory()->create(['email' => 'admin@admin.com']);
    Role::create(['name' => 'super-admin', 'guard_name' => 'web']);
    $admin->assignRole('super-admin');
    $permissions = Permission::factory()->count(5)->create();
    $role = Role::create(['name' => 'manager', 'guard_name' => 'web']);

    Livewire::actingAs($admin)
        ->test(\App\Livewire\Admin\Roles::class)
        ->set('form.name', 'manager')
        ->set('selectedPermissions', [])
        ->call('toggleSelectAllPermissions')
        ->assertSet('selectedPermissions', $permissions->pluck('id')->toArray());
});

it('super-admin can deselect all permissions for a role', function () {
    $admin = User::factory()->create(['email' => 'admin@admin.com']);
    Role::create(['name' => 'super-admin', 'guard_name' => 'web']);
    $admin->assignRole('super-admin');
    $permissions = Permission::factory()->count(5)->create();
    $role = Role::create(['name' => 'manager', 'guard_name' => 'web']);

    Livewire::actingAs($admin)
        ->test(\App\Livewire\Admin\Roles::class)
        ->set('form.name', 'manager')
        ->set('selectedPermissions', $permissions->pluck('id')->toArray())
        ->call('toggleSelectAllPermissions')
        ->assertSet('selectedPermissions', []);
});
