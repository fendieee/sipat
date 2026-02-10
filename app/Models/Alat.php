<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alat extends Model
{
    use HasFactory;

    protected $table = 'alats';

    protected $fillable = [
        'nama_alat',
        'deskripsi',
        'gambar_alat',
        'stok',
        'harga',
        'kategori_id',
    ];

    protected $casts = [
        'stok' => 'integer',
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

        public function kategoris()
    {
        return $this->belongsToMany(Kategori::class, 'alat_kategori');
    }

    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class);
    }
}
