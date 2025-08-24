<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SkpSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $csvPath = base_path('public/sources/skps.csv');
        if (!file_exists($csvPath)) return;

        $handle = fopen($csvPath, 'r');
        $header = null;
        while (($row = fgetcsv($handle, 0, ';')) !== false) {
            if (!$header) {
                $header = $row;
                continue;
            }
            if (count($row) !== count($header)) {
                // Skip rows with mismatched column count
                continue;
            }
            $data = array_combine($header, $row);
            // Convert '\N' to null
            foreach ($data as $key => $value) {
                if ($value === '\\N' || $value === '\N') {
                    $data[$key] = null;
                }
            }
            // Insert into DB
            \App\Models\Skp::create([
                'id' => $data['id'],
                'user_id' => $data['user_id'],
                'jenis' => $data['jenis'],
                'kode' => $data['kode'],
                'nama' => $data['nama'],
                'bulan' => $data['bulan'],
                'tahun' => $data['tahun'],
                'link' => $data['link'],
                'konten' => $data['konten'],
                'created_at' => $data['created_at'],
                'updated_at' => $data['updated_at'],
            ]);
        }
        fclose($handle);
    }
}
