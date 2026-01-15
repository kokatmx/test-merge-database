<?php

namespace App\Console\Commands;

use App\Models\GekoLahan;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportGekoData extends Command
{
    protected $signature = 'import:geko
                            {--file= : Path to CSV file}
                            {--chunk=500 : Records per batch}
                            {--dry-run : Test without inserting}';

    protected $description = 'Import data GEKO dari CSV file ke database';

    // Mapping header CSV ke kolom database
    private array $columnMapping = [
        'id' => 'id',
        'lahan_no' => 'lahan_no',
        'document_no' => 'document_no',
        'internal_code' => 'internal_code',
        'land_area' => 'land_area',
        'planting_area' => 'planting_area',
        'longitude' => 'longitude',
        'latitude' => 'latitude',
        'coordinate' => 'coordinate',
        'polygon' => 'polygon',
        'nearby_village' => 'nearby_village',
        'nearby_village_distance' => 'nearby_village_distance',
        'village' => 'village',
        'kecamatan' => 'kecamatan',
        'city' => 'city',
        'province' => 'province',
        'description' => 'description',
        'elevation' => 'elevation',
        'soil_type' => 'soil_type',
        'soil_photo' => 'soil_photo',
        'current_crops' => 'current_crops',
        'active' => 'active',
        'farmer_no' => 'farmer_no',
        'farmer_temp' => 'farmer_temp',
        'mu_no' => 'mu_no',
        'target_area' => 'target_area',
        'user_id' => 'user_id',
        'created_time' => 'created_time',
        'sppt' => 'sppt',
        'tutupan_lahan' => 'tutupan_lahan',
        'tutupan_pohon_percentage' => 'tutupan_pohon_percentage',
        'tutupan_pohon_photo' => 'tutupan_pohon_photo',
        'tutupan_tanaman_bawah_percentage' => 'tutupan_tanaman_bawah_percentage',
        'tutupan_tanaman_bawah_photo' => 'tutupan_tanaman_bawah_photo',
        'tutupan_lain_bangunan_percentage' => 'tutupan_lain_bangunan_percentage',
        'tutupan_lain_bangunan_photo' => 'tutupan_lain_bangunan_photo',
        'photo1' => 'photo1',
        'photo2' => 'photo2',
        'photo3' => 'photo3',
        'photo4' => 'photo4',
        'group_no' => 'group_no',
        'kelerengan_lahan' => 'kelerengan_lahan',
        'fertilizer' => 'fertilizer',
        'pesticide' => 'pesticide',
        'access_to_water_sources' => 'access_to_water_sources',
        'water_availability' => 'water_availability',
        'water_availability_level' => 'water_availability_level',
        'access_to_lahan' => 'access_to_lahan',
        'potency' => 'potency',
        'barcode' => 'barcode',
        'lahan_type' => 'lahan_type',
        'jarak_lahan' => 'jarak_lahan',
        'exposure' => 'exposure',
        'opsi_pola_tanam' => 'opsi_pola_tanam',
        'pohon_kayu' => 'pohon_kayu',
        'pohon_mpts' => 'pohon_mpts',
        'tanaman_bawah' => 'tanaman_bawah',
        'type_sppt' => 'type_sppt',
        'is_dell' => 'is_dell',
        'complete_data' => 'complete_data',
        'fc_complete_data' => 'fc_complete_data',
        'fc_complete_data_at' => 'fc_complete_data_at',
        'fc_complete_data_by' => 'fc_complete_data_by',
        'approve' => 'approve',
        'approved_by' => 'approved_by',
        'approved_at' => 'approved_at',
        'eligible_status' => 'eligible_status',
        'update_eligible_at' => 'update_eligible_at',
        'update_eligible_by' => 'update_eligible_by',
        'force_majeure_description' => 'force_majeure_description',
        'floods' => 'floods',
        'wildfire' => 'wildfire',
        'landslide' => 'landslide',
        'drought' => 'drought',
        'animal_protected_habitat' => 'animal_protected_habitat',
        'animal_protected_habitat_distance' => 'animal_protected_habitat_distance',
        'latest_condition' => 'latest_condition',
        'polygon_from_ff' => 'polygon_from_ff',
        'polygon_from_gis' => 'polygon_from_gis',
        'gis_polygon_area' => 'gis_polygon_area',
        'gis_planting_area' => 'gis_planting_area',
        'gis_planting_enhancement_area' => 'gis_planting_enhancement_area',
        'gis_planting_enhancement_polygon' => 'gis_planting_enhancement_polygon',
        'gis_arr_area' => 'gis_arr_area',
        'gis_arr_polygon' => 'gis_arr_polygon',
        'updated_gis' => 'updated_gis',
        'gis_updated_at' => 'gis_updated_at',
        'gis_officer' => 'gis_officer',
        'gis_not_eligible_status' => 'gis_not_eligible_status',
        'gps_accuration' => 'gps_accuration',
        'status' => 'status',
        'status_perubahan_tutupan' => 'status_perubahan_tutupan',
        'plant_status' => 'plant_status',
        'seed_is_modified' => 'seed_is_modified',
        'seed_verify_status' => 'seed_verify_status',
        'seed_verify_at' => 'seed_verify_at',
        'seed_verify_by' => 'seed_verify_by',
        'mou_complete_status' => 'mou_complete_status',
        'land_mou_agreement' => 'land_mou_agreement',
        'land_mou_attachment' => 'land_mou_attachment',
        'polygon_tutupan_area' => 'polygon_tutupan_area',
        'polygon_tutupan_photo' => 'polygon_tutupan_photo',
        'polygon_tutupan_status' => 'polygon_tutupan_status',
        'polygon_tutupan_by' => 'polygon_tutupan_by',
        'polygon_tutupan_at' => 'polygon_tutupan_at',
        'gis_eligibility_status' => 'gis_eligibility_status',
        'gis_eligibility_type' => 'gis_eligibility_type',
        'gis_eligibility_srnppi' => 'gis_eligibility_srnppi',
        'gis_update_eligibility_at' => 'gis_update_eligibility_at',
        'legal_eligibility_status' => 'legal_eligibility_status',
        'legal_eligibility_by' => 'legal_eligibility_by',
        'legal_eligibility_at' => 'legal_eligibility_at',
        'penebangan_kayu' => 'penebangan_kayu',
        'kawasan_hutan' => 'kawasan_hutan',
        'satwa_sekitar' => 'satwa_sekitar',
        'pohon_endemic' => 'pohon_endemic',
    ];

    public function handle(): int
    {
        $filePath = $this->option('file') ?? database_path('data/LahanGEKO_2023 3(in).csv');
        $chunkSize = (int) $this->option('chunk');
        $dryRun = $this->option('dry-run');

        if (!file_exists($filePath)) {
            $this->error("File tidak ditemukan: {$filePath}");
            return 1;
        }

        $this->info("===========================================");
        $this->info("  IMPORT DATA GEKO");
        $this->info("===========================================");
        $this->info("File: {$filePath}");
        $this->info("Chunk: {$chunkSize}");
        $this->info("Dry Run: " . ($dryRun ? 'YES' : 'NO'));
        $this->info("===========================================");

        // Open file
        $file = fopen($filePath, 'r');

        // Parse header
        $headerLine = fgets($file);
        $headers = str_getcsv($headerLine, ';');
        $headers = array_map('trim', $headers);

        $this->info("Kolom ditemukan: " . count($headers));

        $imported = 0;
        $failed = 0;
        $batch = [];

        $progressBar = $this->output->createProgressBar();
        $progressBar->start();

        while (($line = fgets($file)) !== false) {
            $row = str_getcsv($line, ';');

            if (count($row) < 5) continue; // Skip invalid rows

            try {
                $data = $this->mapRowToData($headers, $row);
                $data['data_source'] = 'geko';
                $data['exists_in_geko'] = true;
                $data['exists_in_bhl'] = false;

                if (!$dryRun) {
                    $batch[] = $data;

                    if (count($batch) >= $chunkSize) {
                        $this->insertBatch($batch);
                        $batch = [];
                    }
                }

                $imported++;
            } catch (\Exception $e) {
                $failed++;
            }

            $progressBar->advance();
        }

        // Insert remaining batch
        if (!$dryRun && count($batch) > 0) {
            $this->insertBatch($batch);
        }

        fclose($file);

        $progressBar->finish();
        $this->newLine(2);

        $this->info("===========================================");
        $this->info("  HASIL IMPORT GEKO");
        $this->info("===========================================");
        $this->info("Berhasil: {$imported}");
        $this->error("Gagal: {$failed}");

        if ($dryRun) {
            $this->warn("Ini adalah DRY RUN - tidak ada data yang di-insert");
        }

        return 0;
    }

    private function mapRowToData(array $headers, array $row): array
    {
        $data = [];

        foreach ($headers as $index => $header) {
            $header = strtolower(trim($header));

            if (isset($this->columnMapping[$header]) && isset($row[$index])) {
                $value = trim($row[$index]);

                // Handle NULL values
                if ($value === '\\N' || $value === 'NULL' || $value === '') {
                    $value = null;
                }

                // Handle boolean fields
                $booleanFields = [
                    'active',
                    'is_dell',
                    'complete_data',
                    'fc_complete_data',
                    'approve',
                    'floods',
                    'wildfire',
                    'landslide',
                    'drought',
                    'animal_protected_habitat',
                    'seed_is_modified',
                    'seed_verify_status',
                    'mou_complete_status',
                    'polygon_tutupan_status'
                ];

                if (in_array($this->columnMapping[$header], $booleanFields)) {
                    $value = $value === '1' || $value === 'true' || $value === 1;
                }

                $data[$this->columnMapping[$header]] = $value;
            }
        }

        // Remove 'id' to let auto-increment handle it
        unset($data['id']);

        return $data;
    }

    private function insertBatch(array $batch): void
    {
        DB::table('geko_lahan')->insert($batch);
    }
}
