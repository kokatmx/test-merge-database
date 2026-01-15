<?php

namespace App\Models\Old;

use Illuminate\Database\Eloquent\Model;

/**
 * Model untuk tabel bhl_lahan_staging di database lama
 * Digunakan untuk membaca data lahan dari staging
 */
class BhlLahanStaging extends Model
{
    protected $connection = 'old_database';
    protected $table = 'bhl_lahan_staging';
    public $timestamps = false;
    protected $primaryKey = 'no';

    protected $fillable = [
        'no',
        'kd_fc',
        'kd_mu',
        'kd_ta',
        'id_desa',
        'no_lahan',
        'status_lahan',
        'kd_petani',
        'noGPS',
        'thn_tanam',
        'id_lahan',
        'id_pohon',
        'luas_lahan',
        'luas_tanam',
        'tutup_lahan',
        'jml_usulan',
        'kd_rule',
        'acc',
        'jml_realisasi',
        'id_pohon2',
        'wkt_tanam',
        'acc2',
        'endmon1',
        'endmon2',
        'endmon3',
        'endmon4',
        'endmon5',
        'endmon6',
        'endmon7',
        'accmon1',
        'accmon2',
        'accmon3',
        'accmon4',
        'accmon5',
        'accmon6',
        'accmon7',
        'stok',
        'kd_lahan',
        'telat1',
        'telat2',
        'telat3',
        'telat4',
        'telat5',
        'telat6',
        'telat7',
        'no_dok',
        'elevasi',
        'koordinat',
        'stok_order',
        'stok_sisa',
        'stok_cek',
        'lat',
        'lng',
        'jml_persetujuan',
        'mon_fc',
        'lahan_no_geko',
        'deleted_at'
    ];

    protected $casts = [
        'luas_lahan' => 'decimal:2',
        'luas_tanam' => 'decimal:2',
        'lat' => 'decimal:8',
        'lng' => 'decimal:8',
        'deleted_at' => 'datetime',
    ];

    /**
     * Relasi ke petani staging
     */
    public function petani()
    {
        return $this->belongsTo(BhlPetaniStaging::class, 'kd_petani', 'kd_petani');
    }

    /**
     * Scope: hanya data yang tidak dihapus (termasuk '0000-00-00 00:00:00' dari CSV)
     */
    public function scopeActive($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('deleted_at')
                ->orWhere('deleted_at', '0000-00-00 00:00:00')
                ->orWhere('deleted_at', '');
        });
    }
}
