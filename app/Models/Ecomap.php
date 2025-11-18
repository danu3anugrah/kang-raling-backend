<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ecomap extends Model
{
    use HasFactory;

    protected $fillable = [
        'desa_id',
        'tanggal',
        'jumlah_organik',
        'jumlah_anorganik',
        'jumlah_residu',
        'pengelolaan_organik',
        'pengelolaan_anorganik',
        'pengelolaan_residu'
    ];

    public function desa()
    {
        return $this->belongsTo(Desa::class);
    }
}
