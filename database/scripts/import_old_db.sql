DROP DATABASE IF EXISTS old_database;

CREATE DATABASE old_database
    DEFAULT CHARACTER SET = 'utf8mb4';

USE old_database;

DROP TABLE IF EXISTS bhl_lahan_staging;

CREATE TABLE bhl_lahan_staging (
    no INT,
    kd_fc VARCHAR(50),
    kd_mu VARCHAR(50),
    kd_ta VARCHAR(50),
    id_desa INT,
    no_lahan VARCHAR(100),
    status_lahan VARCHAR(50),
    kd_petani VARCHAR(50),
    noGPS VARCHAR(100),
    thn_tanam INT,
    id_lahan VARCHAR(50),
    id_pohon VARCHAR(50),
    luas_lahan DECIMAL(10,2),
    luas_tanam DECIMAL(10,2),
    tutup_lahan VARCHAR(100),
    jml_usulan INT,
    kd_rule VARCHAR(50),
    acc INT,
    jml_realisasi INT,
    id_pohon2 VARCHAR(50),
    wkt_tanam VARCHAR(100),
    acc2 INT,
    endmon1 INT,
    endmon2 INT,
    endmon3 INT,
    endmon4 INT,
    endmon5 INT,
    endmon6 INT,
    endmon7 INT,
    accmon1 INT,
    accmon2 INT,
    accmon3 INT,
    accmon4 INT,
    accmon5 INT,
    accmon6 INT,
    accmon7 INT,
    stok INT,
    kd_lahan VARCHAR(50),
    telat1 INT,
    telat2 INT,
    telat3 INT,
    telat4 INT,
    telat5 INT,
    telat6 INT,
    telat7 INT,
    no_dok VARCHAR(100),
    elevasi INT,
    koordinat TEXT,
    stok_order INT,
    stok_sisa INT,
    stok_cek INT,
    lat DECIMAL(10,8),
    lng DECIMAL(11,8),
    jml_persetujuan INT,
    mon_fc VARCHAR(50),
    lahan_no_geko VARCHAR(100),
    deleted_at DATETIME
);

SHOW GLOBAL VARIABLES LIKE 'local_infile';
SET GLOBAL local_infile = 1;

-- import csv
LOAD DATA LOCAL INFILE 'E:/Test Kerja/trees4tress/migrasi-data/database/data/Lahan BHL 2023 1(in).csv'
INTO TABLE bhl_lahan_staging
FIELDS TERMINATED BY ';'
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 LINES
(
    no, kd_fc, kd_mu, kd_ta, id_desa, no_lahan, status_lahan, kd_petani,
    noGPS, thn_tanam, id_lahan, id_pohon, luas_lahan, luas_tanam, tutup_lahan,
    jml_usulan, kd_rule, acc, jml_realisasi, id_pohon2, wkt_tanam, acc2,
    endmon1, endmon2, endmon3, endmon4, endmon5, endmon6, endmon7,
    accmon1, accmon2, accmon3, accmon4, accmon5, accmon6, accmon7,
    stok, kd_lahan, telat1, telat2, telat3, telat4, telat5, telat6, telat7,
    no_dok, elevasi, koordinat, stok_order, stok_sisa, stok_cek, lat, lng,
    jml_persetujuan, mon_fc, lahan_no_geko, @deleted_at
)
SET deleted_at = NULLIF(@deleted_at, '');

DROP TABLE IF EXISTS bhl_petani_staging;

CREATE TABLE bhl_petani_staging (
    no INT,
    kd_petani VARCHAR(50),
    nm_petani VARCHAR(100),
    alamat VARCHAR(255),
    profesi VARCHAR(100),
    pdpTani VARCHAR(50),
    pdpDagang VARCHAR(50),
    pdpPegawai VARCHAR(50),
    pdpKebun VARCHAR(50),
    pdpLain VARCHAR(50),
    motif VARCHAR(100),
    persepsi TEXT,
    budaya TEXT,
    id_desa INT,
    digawe VARCHAR(50),
    no_ktp VARCHAR(50),
    photo VARCHAR(255),
    deleted_at DATETIME NULL,
    active INT,
    kd_fc VARCHAR(50),
    kd_ta VARCHAR(50),
    kd_mu VARCHAR(50),
    thn_program VARCHAR(100),
    farmer_no_alt VARCHAR(100),
    id_petani_comp INT NULL
);

LOAD DATA LOCAL INFILE 'E:/Test Kerja/trees4tress/migrasi-data/database/data/Petani BHL 2023 2(in).csv'
INTO TABLE bhl_petani_staging
FIELDS TERMINATED BY ';'
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 LINES
(
    no, kd_petani, nm_petani, alamat, profesi,
    pdpTani, pdpDagang, pdpPegawai, pdpKebun, pdpLain,
    motif, persepsi, budaya, id_desa, digawe,
    no_ktp, photo, @deleted_at_var, @active_var, kd_fc,
    kd_ta, kd_mu, thn_program, farmer_no_alt, @id_petani_comp_var
)
SET
    deleted_at = NULLIF(@deleted_at_var, ''),
    active = NULLIF(@active_var, ''),
    id_petani_comp = NULLIF(@id_petani_comp_var, '');
