<?php

namespace App\Models\Old;

use Illuminate\Database\Eloquent\Model;

/**
 * Model untuk tabel bhl_petani_staging di database lama
 * Digunakan untuk membaca data petani dari staging
 */
class BhlPetaniStaging extends Model
{
    protected $connection = 'old_database';
    protected $table = 'bhl_petani_staging';
    public $timestamps = false;
    protected $primaryKey = 'no';

    protected $fillable = [
        'no',
        'kd_petani',
        'nm_petani',
        'alamat',
        'profesi',
        'pdpTani',
        'pdpDagang',
        'pdpPegawai',
        'pdpKebun',
        'pdpLain',
        'motif',
        'persepsi',
        'budaya',
        'id_desa',
        'digawe',
        'no_ktp',
        'photo',
        'deleted_at',
        'active',
        'kd_fc',
        'kd_ta',
        'kd_mu',
        'thn_program',
        'farmer_no_alt',
        'id_petani_comp'
    ];

    protected $casts = [
        'deleted_at' => 'datetime',
    ];

    /**
     * Relasi ke lahan staging
     */
    public function lahan()
    {
        return $this->hasMany(BhlLahanStaging::class, 'kd_petani', 'kd_petani');
    }

    /**
     * Scope: hanya data yang aktif
     */
    public function scopeActive($query)
    {
        return $query->where('active', 1)->whereNull('deleted_at');
    }
}
