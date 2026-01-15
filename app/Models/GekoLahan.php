<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model untuk tabel geko_lahan (full structure)
 * Mengikuti struktur CSV GEKO dengan tracking merge BHL
 */
class GekoLahan extends Model
{
    protected $table = 'geko_lahan';

    protected $fillable = [
        'lahan_no',
        'document_no',
        'internal_code',
        'land_area',
        'planting_area',
        'longitude',
        'latitude',
        'coordinate',
        'polygon',
        'nearby_village',
        'nearby_village_distance',
        'village',
        'kecamatan',
        'city',
        'province',
        'description',
        'elevation',
        'soil_type',
        'soil_photo',
        'current_crops',
        'active',
        'farmer_no',
        'farmer_temp',
        'mu_no',
        'target_area',
        'user_id',
        'created_time',
        'sppt',
        'tutupan_lahan',
        'tutupan_pohon_percentage',
        'tutupan_pohon_photo',
        'tutupan_tanaman_bawah_percentage',
        'tutupan_tanaman_bawah_photo',
        'tutupan_lain_bangunan_percentage',
        'tutupan_lain_bangunan_photo',
        'photo1',
        'photo2',
        'photo3',
        'photo4',
        'group_no',
        'kelerengan_lahan',
        'fertilizer',
        'pesticide',
        'access_to_water_sources',
        'water_availability',
        'water_availability_level',
        'access_to_lahan',
        'potency',
        'barcode',
        'lahan_type',
        'jarak_lahan',
        'exposure',
        'opsi_pola_tanam',
        'pohon_kayu',
        'pohon_mpts',
        'tanaman_bawah',
        'type_sppt',
        'is_dell',
        'complete_data',
        'fc_complete_data',
        'fc_complete_data_at',
        'fc_complete_data_by',
        'approve',
        'approved_by',
        'approved_at',
        'eligible_status',
        'update_eligible_at',
        'update_eligible_by',
        'force_majeure_description',
        'floods',
        'wildfire',
        'landslide',
        'drought',
        'animal_protected_habitat',
        'animal_protected_habitat_distance',
        'latest_condition',
        'polygon_from_ff',
        'polygon_from_gis',
        'gis_polygon_area',
        'gis_planting_area',
        'gis_planting_enhancement_area',
        'gis_planting_enhancement_polygon',
        'gis_arr_area',
        'gis_arr_polygon',
        'updated_gis',
        'gis_updated_at',
        'gis_officer',
        'gis_not_eligible_status',
        'gps_accuration',
        'status',
        'status_perubahan_tutupan',
        'plant_status',
        'seed_is_modified',
        'seed_verify_status',
        'seed_verify_at',
        'seed_verify_by',
        'mou_complete_status',
        'land_mou_agreement',
        'land_mou_attachment',
        'polygon_tutupan_area',
        'polygon_tutupan_photo',
        'polygon_tutupan_status',
        'polygon_tutupan_by',
        'polygon_tutupan_at',
        'gis_eligibility_status',
        'gis_eligibility_type',
        'gis_eligibility_srnppi',
        'gis_update_eligibility_at',
        'legal_eligibility_status',
        'legal_eligibility_by',
        'legal_eligibility_at',
        'penebangan_kayu',
        'kawasan_hutan',
        'satwa_sekitar',
        'pohon_endemic',
        'data_source',
        'exists_in_geko',
        'exists_in_bhl',
    ];

    protected $casts = [
        'active' => 'boolean',
        'is_dell' => 'boolean',
        'complete_data' => 'boolean',
        'fc_complete_data' => 'boolean',
        'approve' => 'boolean',
        'floods' => 'boolean',
        'wildfire' => 'boolean',
        'landslide' => 'boolean',
        'drought' => 'boolean',
        'animal_protected_habitat' => 'boolean',
        'seed_is_modified' => 'boolean',
        'seed_verify_status' => 'boolean',
        'mou_complete_status' => 'boolean',
        'polygon_tutupan_status' => 'boolean',
        'exists_in_geko' => 'boolean',
        'exists_in_bhl' => 'boolean',
    ];

    // ==================== SCOPES ====================

    /**
     * Scope: Data dari GEKO
     */
    public function scopeFromGeko($query)
    {
        return $query->where('exists_in_geko', true);
    }

    /**
     * Scope: Data dari BHL
     */
    public function scopeFromBhl($query)
    {
        return $query->where('exists_in_bhl', true);
    }

    /**
     * Scope: Data yang sudah di-merge (ada di keduanya)
     */
    public function scopeMerged($query)
    {
        return $query->where('exists_in_geko', true)->where('exists_in_bhl', true);
    }

    /**
     * Scope: Data BHL yang belum ada di GEKO
     */
    public function scopeBhlOnly($query)
    {
        return $query->where('exists_in_geko', false)->where('exists_in_bhl', true);
    }

    /**
     * Scope: Data GEKO yang belum ada di BHL
     */
    public function scopeGekoOnly($query)
    {
        return $query->where('exists_in_geko', true)->where('exists_in_bhl', false);
    }

    /**
     * Scope: Filter by village (desa)
     */
    public function scopeByVillage($query, $village)
    {
        if ($village) {
            return $query->where('village', 'like', "%{$village}%");
        }
        return $query;
    }

    /**
     * Scope: Filter by kecamatan
     */
    public function scopeByKecamatan($query, $kecamatan)
    {
        if ($kecamatan) {
            return $query->where('kecamatan', 'like', "%{$kecamatan}%");
        }
        return $query;
    }

    /**
     * Scope: Filter by city
     */
    public function scopeByCity($query, $city)
    {
        if ($city) {
            return $query->where('city', 'like', "%{$city}%");
        }
        return $query;
    }

    /**
     * Scope: Filter by province
     */
    public function scopeByProvince($query, $province)
    {
        if ($province) {
            return $query->where('province', 'like', "%{$province}%");
        }
        return $query;
    }

    // ==================== STATISTICS ====================

    /**
     * Get statistics untuk dashboard
     */
    public static function getStatistics()
    {
        return [
            'total_lahan' => static::count(),
            'total_geko' => static::fromGeko()->count(),
            'total_bhl' => static::fromBhl()->count(),
            'total_merged' => static::merged()->count(),
            'bhl_only' => static::bhlOnly()->count(),
            'geko_only' => static::gekoOnly()->count(),
            'total_petani' => static::distinct('farmer_no')->count('farmer_no'),
        ];
    }

    /**
     * Get unique locations untuk filter dropdown
     */
    public static function getUniqueLocations()
    {
        return [
            'villages' => static::whereNotNull('village')
                ->where('village', '!=', '\\N')
                ->distinct()
                ->pluck('village')
                ->sort()
                ->values(),
            'kecamatan' => static::whereNotNull('kecamatan')
                ->where('kecamatan', '!=', '\\N')
                ->distinct()
                ->pluck('kecamatan')
                ->sort()
                ->values(),
            'cities' => static::whereNotNull('city')
                ->where('city', '!=', '\\N')
                ->distinct()
                ->pluck('city')
                ->sort()
                ->values(),
            'provinces' => static::whereNotNull('province')
                ->where('province', '!=', '\\N')
                ->distinct()
                ->pluck('province')
                ->sort()
                ->values(),
        ];
    }
}
