<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Struktur lengkap mengikuti CSV GEKO
     */
    public function up(): void
    {
        Schema::dropIfExists('geko_lahan');

        Schema::create('geko_lahan', function (Blueprint $table) {
            $table->id();

            // Identifikasi Lahan
            $table->string('lahan_no', 100)->nullable()->index();
            $table->string('document_no', 100)->nullable();
            $table->string('internal_code', 100)->nullable();

            // Area
            $table->string('land_area', 50)->nullable();
            $table->string('planting_area', 50)->nullable();

            // Koordinat
            $table->string('longitude', 50)->nullable();
            $table->string('latitude', 50)->nullable();
            $table->text('coordinate')->nullable();
            $table->text('polygon')->nullable();

            // Lokasi - PENTING untuk filtering
            $table->string('nearby_village', 100)->nullable();
            $table->string('nearby_village_distance', 50)->nullable();
            $table->string('village', 50)->nullable()->index();      // Desa
            $table->string('kecamatan', 50)->nullable()->index();    // Kecamatan
            $table->string('city', 50)->nullable()->index();         // Kota/Kabupaten
            $table->string('province', 50)->nullable()->index();     // Provinsi

            // Deskripsi
            $table->text('description')->nullable();
            $table->string('elevation', 50)->nullable();
            $table->string('soil_type', 100)->nullable();
            $table->string('soil_photo', 255)->nullable();
            $table->text('current_crops')->nullable();

            // Status
            $table->boolean('active')->default(true);

            // Petani
            $table->string('farmer_no', 100)->nullable()->index();
            $table->string('farmer_temp', 100)->nullable();

            // Management Unit & Target Area
            $table->string('mu_no', 50)->nullable()->index();
            $table->string('target_area', 50)->nullable();

            // User tracking
            $table->string('user_id', 50)->nullable();
            $table->string('created_time', 50)->nullable();

            // Photos
            $table->string('sppt', 255)->nullable();
            $table->string('tutupan_lahan', 50)->nullable();
            $table->string('tutupan_pohon_percentage', 50)->nullable();
            $table->string('tutupan_pohon_photo', 255)->nullable();
            $table->string('tutupan_tanaman_bawah_percentage', 50)->nullable();
            $table->string('tutupan_tanaman_bawah_photo', 255)->nullable();
            $table->string('tutupan_lain_bangunan_percentage', 50)->nullable();
            $table->string('tutupan_lain_bangunan_photo', 255)->nullable();
            $table->string('photo1', 255)->nullable();
            $table->string('photo2', 255)->nullable();
            $table->string('photo3', 255)->nullable();
            $table->string('photo4', 255)->nullable();

            // Karakteristik Lahan
            $table->string('group_no', 50)->nullable();
            $table->string('kelerengan_lahan', 50)->nullable();
            $table->string('fertilizer', 50)->nullable();
            $table->string('pesticide', 50)->nullable();
            $table->string('access_to_water_sources', 50)->nullable();
            $table->string('water_availability', 100)->nullable();
            $table->string('water_availability_level', 50)->nullable();
            $table->string('access_to_lahan', 50)->nullable();
            $table->string('potency', 50)->nullable();
            $table->string('barcode', 100)->nullable();
            $table->string('lahan_type', 100)->nullable();
            $table->string('jarak_lahan', 50)->nullable();
            $table->string('exposure', 50)->nullable();
            $table->string('opsi_pola_tanam', 100)->nullable();
            $table->string('pohon_kayu', 50)->nullable();
            $table->string('pohon_mpts', 50)->nullable();
            $table->string('tanaman_bawah', 50)->nullable();
            $table->string('type_sppt', 50)->nullable();

            // Status flags
            $table->boolean('is_dell')->default(false);
            $table->boolean('complete_data')->default(false);
            $table->boolean('fc_complete_data')->default(false);
            $table->string('fc_complete_data_at', 50)->nullable();
            $table->string('fc_complete_data_by', 100)->nullable();
            $table->boolean('approve')->default(false);
            $table->string('approved_by', 100)->nullable();
            $table->string('approved_at', 50)->nullable();

            // Eligibility
            $table->string('eligible_status', 50)->nullable();
            $table->string('update_eligible_at', 50)->nullable();
            $table->string('update_eligible_by', 100)->nullable();

            // Force Majeure
            $table->text('force_majeure_description')->nullable();
            $table->boolean('floods')->default(false);
            $table->boolean('wildfire')->default(false);
            $table->boolean('landslide')->default(false);
            $table->boolean('drought')->default(false);
            $table->boolean('animal_protected_habitat')->default(false);
            $table->string('animal_protected_habitat_distance', 50)->nullable();

            // Kondisi terbaru
            $table->string('latest_condition', 50)->nullable();

            // GIS Data
            $table->text('polygon_from_ff')->nullable();
            $table->text('polygon_from_gis')->nullable();
            $table->string('gis_polygon_area', 50)->nullable();
            $table->string('gis_planting_area', 50)->nullable();
            $table->string('gis_planting_enhancement_area', 50)->nullable();
            $table->text('gis_planting_enhancement_polygon')->nullable();
            $table->string('gis_arr_area', 50)->nullable();
            $table->text('gis_arr_polygon')->nullable();
            $table->string('updated_gis', 50)->nullable();
            $table->string('gis_updated_at', 50)->nullable();
            $table->string('gis_officer', 100)->nullable();
            $table->string('gis_not_eligible_status', 50)->nullable();
            $table->string('gps_accuration', 50)->nullable();

            // Status tambahan
            $table->string('status', 50)->nullable();
            $table->string('status_perubahan_tutupan', 50)->nullable();
            $table->string('plant_status', 50)->nullable();
            $table->boolean('seed_is_modified')->default(false);
            $table->boolean('seed_verify_status')->default(false);
            $table->string('seed_verify_at', 50)->nullable();
            $table->string('seed_verify_by', 100)->nullable();

            // MOU
            $table->boolean('mou_complete_status')->default(false);
            $table->text('land_mou_agreement')->nullable();
            $table->string('land_mou_attachment', 255)->nullable();

            // Polygon tutupan
            $table->string('polygon_tutupan_area', 50)->nullable();
            $table->string('polygon_tutupan_photo', 255)->nullable();
            $table->boolean('polygon_tutupan_status')->default(false);
            $table->string('polygon_tutupan_by', 100)->nullable();
            $table->string('polygon_tutupan_at', 50)->nullable();

            // GIS Eligibility
            $table->string('gis_eligibility_status', 50)->nullable();
            $table->string('gis_eligibility_type', 50)->nullable();
            $table->string('gis_eligibility_srnppi', 50)->nullable();
            $table->string('gis_update_eligibility_at', 50)->nullable();

            // Legal Eligibility
            $table->string('legal_eligibility_status', 50)->nullable();
            $table->string('legal_eligibility_by', 100)->nullable();
            $table->string('legal_eligibility_at', 50)->nullable();

            // Info tambahan
            $table->string('penebangan_kayu', 50)->nullable();
            $table->string('kawasan_hutan', 50)->nullable();
            $table->string('satwa_sekitar', 50)->nullable();
            $table->string('pohon_endemic', 50)->nullable();

            // Sumber data - untuk identifikasi merge
            $table->enum('data_source', ['geko', 'bhl', 'merged'])->default('geko')->index();
            $table->boolean('exists_in_geko')->default(false);
            $table->boolean('exists_in_bhl')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('geko_lahan');
    }
};
