<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class AssignUserRoleSeeder extends Seeder
{
    public function run(): void
    {
        User::where('name',  'Admin')->get()->each(function ($user) {
            $user->assignRole('super-admin');
        });
        User::where('name', '!=', 'Admin')->get()->each(function ($user) {
            $user->assignRole('user');
        });
    }
}
