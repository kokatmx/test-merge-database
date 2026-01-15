-- Disable strict mode
SET sql_mode = '';
USE new_database;


UPDATE geko_lahan g
INNER JOIN old_database.bhl_lahan_staging b
    ON g.lahan_no = b.kd_lahan COLLATE utf8mb4_unicode_ci
SET
    g.exists_in_bhl = 1,
    g.data_source = 'merged',
    g.updated_at = NOW()
WHERE (b.deleted_at IS NULL OR CAST(b.deleted_at AS CHAR) LIKE '0000-00-00%');

SELECT 'Merged via kd_lahan' AS Info, ROW_COUNT() AS Total;


UPDATE geko_lahan g
INNER JOIN old_database.bhl_lahan_staging b
    ON g.lahan_no = b.no_lahan COLLATE utf8mb4_unicode_ci
SET
    g.exists_in_bhl = 1,
    g.data_source = 'merged',
    g.updated_at = NOW()
WHERE g.data_source != 'merged' -- Jangan overwrite yang sudah match di step 1A
AND (b.deleted_at IS NULL OR CAST(b.deleted_at AS CHAR) LIKE '0000-00-00%');

SELECT 'Merged via no_lahan (fallback)' AS Info, ROW_COUNT() AS Total;

INSERT INTO geko_lahan (
    lahan_no, document_no, internal_code, land_area, planting_area,
    longitude, latitude, coordinate, elevation, village,
    farmer_no, mu_no, target_area, tutupan_lahan, active,
    data_source, exists_in_geko, exists_in_bhl, created_at, updated_at
)
SELECT
    b.no_lahan,
    MAX(b.no_dok),
    COALESCE(MAX(b.kd_lahan), MAX(b.id_lahan)),
    MAX(b.luas_lahan), MAX(b.luas_tanam),
    MAX(b.lng), MAX(b.lat), MAX(b.koordinat), MAX(b.elevasi),
    CONCAT('ID:', MAX(b.id_desa)),
    MAX(b.kd_petani), MAX(b.kd_mu), MAX(b.kd_ta), MAX(b.tutup_lahan),
    1,
    'bhl', 0, 1, NOW(), NOW()
FROM old_database.bhl_lahan_staging b
LEFT JOIN geko_lahan g1 ON b.kd_lahan COLLATE utf8mb4_unicode_ci = g1.lahan_no
LEFT JOIN geko_lahan g2 ON b.no_lahan COLLATE utf8mb4_unicode_ci = g2.lahan_no
WHERE g1.id IS NULL
AND g2.id IS NULL
AND (b.deleted_at IS NULL OR CAST(b.deleted_at AS CHAR) LIKE '0000-00-00%')
GROUP BY b.no_lahan;

SELECT 'New BHL Data Inserted' AS Info, ROW_COUNT() AS Total;

-- =========================================
-- FINAL SUMMARY
-- =========================================
SELECT 'Total Lahan' as Metric, COUNT(*) as Value FROM geko_lahan
UNION ALL
SELECT 'GEKO Only', COUNT(*) FROM geko_lahan WHERE exists_in_geko = 1 AND exists_in_bhl = 0
UNION ALL
SELECT 'BHL Only', COUNT(*) FROM geko_lahan WHERE exists_in_geko = 0 AND exists_in_bhl = 1
UNION ALL
SELECT 'Merged', COUNT(*) FROM geko_lahan WHERE exists_in_geko = 1 AND exists_in_bhl = 1;
