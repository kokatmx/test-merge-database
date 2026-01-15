# Merge Database Platform (BHL to GEKO)

Platform berbasis web untuk mengelola migrasi dan integrasi data lahan dari database lama (BHL) ke database baru (GEKO). Aplikasi ini dibuat untuk mempermudah identifikasi data, merge otomatis, dan visualisasi status integrasi data.

## Fitur Utama

-   **Dashboard Statistik**: Memantau total lahan, data terintegrasi (merged), data baru, dan data konflik secara real-time.
-   **Filtering Data**: Pencarian data berdasarkan lokasi (Desa, Kecamatan, Kota, Provinsi) dan sumber data.
-   **Smart Merge Logic**:
    -   Otomatis mencocokkan data berdasarkan ID Lahan (`kd_lahan` dan `no_lahan`).
    -   Mendeteksi data duplikat secara cerdas.
    -   Memisahkan data yang sudah ada di sistem GEKO vs data baru dari BHL.
-   **Visualisasi Modern**: Antarmuka pengguna yang bersih menggunakan Tailwind CSS v4.

## Persyaratan Sistem

-   PHP 8.1 atau lebih baru
-   Composer
-   MySQL Database
-   Web Browser modern (Chrome/Edge/Firefox)

## Cara Instalasi

1. **Clone Repository**

    ```bash
    git clone https://github.com/kokatmx/test-merge-database.git
    cd migrasi-data
    ```

2. **Install Dependencies**

    ```bash
    composer install
    ```

3. **Konfigurasi Environment**
   Salin file `.env.example` menjadi `.env`:

    ```bash
    cp .env.example .env
    ```

    Atur koneksi database di `.env`:

    ```ini
    # Database Utama (GEKO / Target)
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=new_database
    DB_USERNAME=root
    DB_PASSWORD=

    # Database Lama (BHL / Source)
    OLD_DB_CONNECTION=mysql
    OLD_DB_HOST=127.0.0.1
    OLD_DB_PORT=3306
    OLD_DB_DATABASE=old_database
    OLD_DB_USERNAME=root
    OLD_DB_PASSWORD=
    ```

4. **Generate Key & Migrasi Database**
    ```bash
    php artisan key:generate
    php artisan migrate:fresh
    ```

## Cara Penggunaan

### 1. Import Data (Rekomendasi)

Untuk performa terbaik (menangani 30.000+ data), disarankan menggunakan script SQL yang telah disediakan via MySQL Workbench atau tool sejenis.

Jalankan script `database/scripts/import_all_data.sql`. Script ini akan melakukan:

1. Import data CSV GEKO.
2. Melakukan merge data BHL yang cocok dengan GEKO.
3. Memasukkan data BHL baru yang belum ada di GEKO.

### 2. Import Data (Alternatif via CLI)

Jika ingin menggunakan command line (lebih lambat untuk data besar):

```bash
# Import data GEKO dari CSV
php artisan import:geko

# Import dan Merge data BHL
php artisan import:bhl --force
```

### 3. Akses Dashboard

Jalankan server lokal:

```bash
php artisan serve
```

Buka browser dan akses: `http://127.0.0.1:8000`

## Struktur Project

-   **Models**: `App\Models\GekoLahan` (Target) & `App\Models\Old\BhlLahanStaging` (Source).
-   **Controllers**: `App\Http\Controllers\MergeController` mengatur logika dashboard.
-   **Commands**: `App\Console\Commands` berisi script import CLI.
-   **Database Scripts**: Folder `database/scripts/` berisi query SQL optimasi untuk migrasi bulk.
-   **Views**: UI menggunakan Blade template + Tailwind CSS v4.

---

&copy; 2026 Trees4Trees - Technical Test Submission
