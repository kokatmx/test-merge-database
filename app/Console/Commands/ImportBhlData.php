<?php

namespace App\Console\Commands;

use App\Models\GekoLahan;
use App\Models\Old\BhlLahanStaging;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportBhlData extends Command
{
    protected $signature = 'import:bhl
                            {--chunk=500 : Records per batch}
                            {--dry-run : Test without merging}
                            {--force : Skip confirmation}';

    protected $description = 'Import dan merge data BHL ke struktur GEKO. Mengidentifikasi data yang sudah ada vs belum ada.';

    public function handle(): int
    {
        $chunkSize = (int) $this->option('chunk');
        $dryRun = $this->option('dry-run');
        $force = $this->option('force');

        $totalBhl = BhlLahanStaging::active()->count();

        $this->info("===========================================");
        $this->info("  IMPORT & MERGE DATA BHL ke GEKO");
        $this->info("===========================================");
        $this->info("Total data BHL staging: {$totalBhl}");
        $this->info("Chunk size: {$chunkSize}");
        $this->info("Dry run: " . ($dryRun ? 'YES' : 'NO'));
        $this->info("===========================================");

        if (!$force && !$this->confirm('Lanjutkan import?')) {
            $this->warn('Import dibatalkan.');
            return 1;
        }

        $imported = 0;
        $updated = 0;
        $failed = 0;

        $progressBar = $this->output->createProgressBar($totalBhl);
        $progressBar->start();

        // Process BHL data in chunks
        BhlLahanStaging::active()
            ->chunk($chunkSize, function ($bhlList) use (&$imported, &$updated, &$failed, $dryRun, $progressBar) {
                foreach ($bhlList as $bhl) {
                    try {
                        // Transform BHL data ke struktur GEKO
                        $gekoData = $this->transformBhlToGeko($bhl);

                        if (!$dryRun) {
                            // Cek apakah lahan_no sudah ada di GEKO
                            $existing = GekoLahan::where('lahan_no', $gekoData['lahan_no'])->first();

                            if ($existing) {
                                // Update: tandai sebagai sudah ada di BHL juga
                                $existing->update([
                                    'exists_in_bhl' => true,
                                    'data_source' => 'merged',
                                ]);
                                $updated++;
                            } else {
                                // Insert: data baru dari BHL yang belum ada di GEKO
                                GekoLahan::create($gekoData);
                                $imported++;
                            }
                        } else {
                            // Dry run - just count
                            $existing = GekoLahan::where('lahan_no', $gekoData['lahan_no'])->exists();
                            if ($existing) {
                                $updated++;
                            } else {
                                $imported++;
                            }
                        }
                    } catch (\Exception $e) {
                        $failed++;
                        \Log::error("Failed to import BHL: {$bhl->no_lahan}", [
                            'error' => $e->getMessage()
                        ]);
                    }

                    $progressBar->advance();
                }
            });

        $progressBar->finish();
        $this->newLine(2);

        // Summary
        $this->info("===========================================");
        $this->info("  HASIL IMPORT BHL");
        $this->info("===========================================");
        $this->info("Data Baru (belum di GEKO): {$imported}");
        $this->info("Sudah Ada (di-merge): {$updated}");
        $this->error("Gagal: {$failed}");

        if ($dryRun) {
            $this->warn("Ini adalah DRY RUN - tidak ada data yang di-insert");
        }

        return $failed > 0 ? 1 : 0;
    }

    /**
     * Transform data BHL ke struktur GEKO
     */
    private function transformBhlToGeko(BhlLahanStaging $bhl): array
    {
        return [
            // Mapping langsung
            'lahan_no' => $bhl->no_lahan,
            'document_no' => $bhl->no_dok,
            'internal_code' => $bhl->kd_lahan ?? $bhl->id_lahan,

            // Area
            'land_area' => $bhl->luas_lahan,
            'planting_area' => $bhl->luas_tanam,

            // Koordinat
            'longitude' => $bhl->lng,
            'latitude' => $bhl->lat,
            'coordinate' => $bhl->koordinat,
            'elevation' => $bhl->elevasi,

            // Lokasi - gunakan id_desa untuk lookup (perlu tabel referensi)
            'village' => $bhl->id_desa ? "ID:{$bhl->id_desa}" : null,

            // Petani
            'farmer_no' => $bhl->kd_petani,

            // Management Unit
            'mu_no' => $bhl->kd_mu,
            'target_area' => $bhl->kd_ta,

            // Tutupan
            'tutupan_lahan' => $bhl->tutup_lahan,

            // Status
            'active' => $bhl->deleted_at === null || $bhl->deleted_at === '0000-00-00 00:00:00',

            // Metadata untuk tracking
            'data_source' => 'bhl',
            'exists_in_geko' => false,
            'exists_in_bhl' => true,

            // Timestamps
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
