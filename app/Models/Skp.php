<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Skp extends Model
{
    protected $fillable = [
        'user_id',
        'jenis',
        'kode',
        'nama',
        'bulan',
        'tahun',
        'link',
        'konten',
    ];
}
