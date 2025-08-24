<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skp extends Model
{
    use HasFactory;

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

    /**
     * Get the user that owns the SKP.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
