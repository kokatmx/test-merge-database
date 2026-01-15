<?php

namespace App\Console\Commands;

use App\Models\GekoLahan;
use App\Models\Old\BhlLahanStaging;
use App\Models\Old\BhlPetaniStaging;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MigrateLahanData extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'migrate:lahan
                            {--chunk=500 : Jumlah data per batch}
                            {--dry-run : Jalankan tanpa insert ke database}
                            {--force : Skip konfirmasi}';

    /**
     * The console command description.
     */
    protected $description = 'Migrasi data lahan dari old_database (staging) ke new_database (geko_lahan)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $chunkSize = (int) $this->option('chunk');
        $dryRun = $this->option('dry-run');
        $force = $this->option('force');

        // Hitung total data
        $totalData = BhlLahanStaging::active()->count();

        $this->info("===========================================");
        $this->info("  MIGRASI DATA LAHAN - BHL to GEKO");
        $this->info("===========================================");
        $this->info("Total data staging: {$totalData}");
        $this->info("Chunk size: {$chunkSize}");
        $this->info("Dry run: " . ($dryRun ? 'YES' : 'NO'));
        $this->info("===========================================");

        if (!$force && !$this->confirm('Lanjutkan migrasi?')) {
            $this->warn('Migrasi dibatalkan.');
            return 1;
        }

        $progressBar = $this->output->createProgressBar($totalData);
        $progressBar->start();

        $migrated = 0;
        $failed = 0;
        $skipped = 0;

        // Ambil data dengan chunk untuk hemat memory
        BhlLahanStaging::active()
            ->chunk($chunkSize, function ($lahanList) use (&$migrated, &$failed, &$skipped, $dryRun, $progressBar) {
                foreach ($lahanList as $oldLahan) {
                    try {
                        // Transform data
                        $newData = $this->transformData($oldLahan);

                        if (!$dryRun) {
                            // Upsert: insert atau update berdasarkan lahan_no
                            GekoLahan::updateOrCreate(
                                ['lahan_no' => $newData['lahan_no']],
                                $newData
                            );
                        }

                        $migrated++;
                    } catch (\Exception $e) {
                        $failed++;
                        Log::error("Gagal migrasi lahan: {$oldLahan->no_lahan}", [
                            'error' => $e->getMessage(),
                            'data' => $oldLahan->toArray()
                        ]);
                    }

                    $progressBar->advance();
                }
            });

        $progressBar->finish();
        $this->newLine(2);

        // Summary
        $this->info("===========================================");
        $this->info("  HASIL MIGRASI");
        $this->info("===========================================");
        $this->info("Berhasil: {$migrated}");
        $this->error("Gagal: {$failed}");
        $this->warn("Dilewati: {$skipped}");

        if ($dryRun) {
            $this->warn("Ini adalah DRY RUN - tidak ada data yang di-insert");
        }

        return $failed > 0 ? 1 : 0;
    }

    /**
     * Transform data dari format lama ke format baru
     */
    private function transformData(BhlLahanStaging $old): array
    {
        // Cari data petani jika ada
        $petani = BhlPetaniStaging::where('kd_petani', $old->kd_petani)
            ->where('id_desa', $old->id_desa) // Match desa juga untuk akurasi
            ->first();

        return [
            // Direct mapping
            'lahan_no'       => $old->no_lahan,
            'document_no'    => $old->no_dok,
            'land_area'      => $old->luas_lahan,
            'planting_area'  => $old->luas_tanam,
            'longitude'      => $old->lng,
            'latitude'       => $old->lat,
            'coordinate'     => $old->koordinat,
            'elevation'      => $old->elevasi,
            'tutupan_lahan'  => $old->tutup_lahan,

            // Mapping dengan referensi ke petani
            'farmer_no'      => $petani?->farmer_no_alt ?? $old->kd_petani,

            // Mapping kode
            'mu_no'          => $old->kd_mu,
            'target_area'    => $old->kd_ta,

            // Lookup desa - bisa di-enhance dengan tabel referensi
            'village'        => $this->getVillageName($old->id_desa),

            // Default values
            'internal_code'  => $old->kd_lahan ?? $old->id_lahan,
            'active'         => $old->deleted_at === null,

            // Timestamp
            'created_at'     => now(),
            'updated_at'     => now(),
        ];
    }

    /**
     * Lookup nama desa dari id_desa
     * TODO: Enhance dengan tabel referensi desa
     */
    private function getVillageName(?int $idDesa): ?string
    {
        // Placeholder - bisa di-enhance dengan tabel referensi desa
        // Contoh: return Desa::find($idDesa)?->nama_desa;
        return $idDesa ? "Desa ID: {$idDesa}" : null;
    }
}
