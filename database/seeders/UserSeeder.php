<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $csvPath = public_path('sources/users.csv');
        if (!file_exists($csvPath)) {
            return;
        }

        $rows = array_map(function($line) {
            return str_getcsv($line, ';');
        }, file($csvPath));
        $header = array_map('trim', array_shift($rows));

        foreach ($rows as $row) {
            $data = array_combine($header, $row);
            // Convert \N to null
            foreach ($data as $key => $value) {
                if ($value === '\\N') {
                    $data[$key] = null;
                }
            }
            // Only proceed if email exists
            if (empty($data['email'])) {
                continue;
            }
            // Passwords are already hashed in CSV, do not hash again
            User::updateOrCreate([
                'email' => $data['email']
            ], $data);
        }
    }
}
