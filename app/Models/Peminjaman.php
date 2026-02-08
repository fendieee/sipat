<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Peminjaman extends Model
{
    protected $table = 'peminjamans';

    protected $fillable = [
        'user_id',
        'foto_peminjam',
        'foto_kondisi',
        'alat_id',
        'tanggal_pinjam',
        'tanggal_jatuh_tempo',
        'tanggal_kembali',
        'hari_telat',
        'status',
        'denda',
        'catatan_petugas',
    ];

    // =====================
    // RELATION
    // =====================
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function alat()
    {
        return $this->belongsTo(Alat::class);
    }

    public function getTanggalPinjamFormatAttribute()
    {
        return $this->tanggal_pinjam
            ? Carbon::parse($this->tanggal_pinjam)->translatedFormat('d M Y')
            : '-';
    }

    public function getTanggalJatuhTempoFormatAttribute()
    {
        return $this->tanggal_jatuh_tempo
            ? Carbon::parse($this->tanggal_jatuh_tempo)->translatedFormat('d M Y')
            : '-';
    }

    public function getTanggalKembaliFormatAttribute()
    {
        return $this->tanggal_kembali
            ? Carbon::parse($this->tanggal_kembali)->translatedFormat('d M Y')
            : '-';
    }
}
