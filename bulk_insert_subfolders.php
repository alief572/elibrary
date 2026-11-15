<?php
/**
 * Script Bulking Subfolder untuk elibrary_v2
 * 
 * Penggunaan via Terminal/CLI:
 *   php bulk_insert_subfolders.php
 * 
 * Fitur:
 * - Mendukung target folder spesifik (Array ID Folder) ATAU target seluruh anak dari folder parent/divisi
 * - Dilengkapi proteksi anti-duplikasi (folder yang sudah ada tidak akan dibuat ulang)
 * - Mode $dry_run untuk pengujian sebelum eksekusi ke database
 * - Transaksi database (Atomic: jika ada 1 error, semua otomatis di-rollback)
 */

// 1. KONEKSI DATABASE (Membaca file .env aplikasi)
$env_path = __DIR__ . '/.env';
if (!file_exists($env_path)) {
    die("File .env tidak ditemukan di $env_path\n");
}
$env = parse_ini_file($env_path);

$host = $env['LIVE_DB_HOST'] ?? 'localhost';
$port = $env['LIVE_DB_PORT'] ?? 3306;
$user = $env['LIVE_DB_USER'] ?? 'root';
$pass = $env['LIVE_DB_PASS'] ?? '';
$db   = $env['LIVE_DB_NAME'] ?? 'elibrary_v2';

$mysqli = new mysqli($host, $user, $pass, $db, (int)$port);
if ($mysqli->connect_errno) {
    die("Koneksi Database Gagal: " . $mysqli->connect_error . "\n");
}

// =========================================================================
// 2. KONFIGURASI TARGET & DATA (SILAKAN UBAH DI SINI)
// =========================================================================

// Set TRUE jika hanya ingin simulasi (tanpa simpan ke DB)
// Set FALSE jika ingin langsung simpan ke database
$dry_run = false;

// ID User pembuat (default: 35)
$created_by_user_id = 35;

// Prefix nama folder (misal: INF/QLT/NB/, INF/HSE/NB/, INF/MKT/NB/, dll)
$prefix = 'INF/QLT/NB/';

// TEMPLATE 8 SUBFOLDER
$suffixes = [
    '001/ IDENTITAS ASSET',
    '002/ DOKUMEN ASSET',
    '003/ BAST',
    '004/ JADWAL PREVENTIVE',
    '005/ CHECKSHEET',
    '006/ KERUSAKAN DAN PERBAIKAN',
    '007/ HISTORY ASSET',
    '008/ DISPOSAL',
];

/**
 * METODE PEMILIHAN TARGET FOLDER:
 * Pilih salah satu opsi di bawah:
 * 
 * OPSI A: Mengisi ID Folder Karyawan secara spesifik
 *   Contoh: ['106acf0c2a34744', '106acf0c3a7e94e']
 * 
 * OPSI B: Mengisi ID Folder Induk/Divisi (Akan otomatis mengambil SEMUA folder karyawan di dalamnya)
 *   Contoh: '106acefb6c1e14c' (ID Divisi Quality)
 */
$target_folder_ids = []; // Contoh: ['106acf0c2a34744', '106acf0c3a7e94e']
$parent_division_id = ''; // Contoh: '106acefb6c1e14c' (Jika diisi, opsi ini yang dipakai)

// =========================================================================
// 3. LOGIKA EKSEKUSI
// =========================================================================

echo "=======================================================\n";
echo "SCRIPT BULK INSERT SUBFOLDER - ELIBRARY V2\n";
echo "Mode: " . ($dry_run ? "DRY-RUN (Simulasi)" : "LIVE EXECUTION") . "\n";
echo "Prefix: $prefix\n";
echo "=======================================================\n\n";

// Ambil daftar folder target
$targets = [];
if (!empty($parent_division_id)) {
    $parent_esc = $mysqli->real_escape_string($parent_division_id);
    $q = $mysqli->query("SELECT id, name, procedure_id, company_id FROM dir_records WHERE parent_id = '$parent_esc' AND status != 'DEL' ORDER BY name ASC");
    while ($r = $q->fetch_assoc()) {
        $targets[$r['id']] = $r;
    }
    echo "Mengambil target dari Folder Induk [$parent_division_id]: Ditemukan " . count($targets) . " folder.\n";
} elseif (!empty($target_folder_ids)) {
    $ids_in = "'" . implode("','", array_map([$mysqli, 'real_escape_string'], $target_folder_ids)) . "'";
    $q = $mysqli->query("SELECT id, name, procedure_id, company_id FROM dir_records WHERE id IN ($ids_in) AND status != 'DEL' ORDER BY name ASC");
    while ($r = $q->fetch_assoc()) {
        $targets[$r['id']] = $r;
    }
    echo "Mengambil target dari Daftar ID: Ditemukan " . count($targets) . " folder.\n";
} else {
    die("ERROR: Harap isi \$target_folder_ids atau \$parent_division_id pada konfigurasi script!\n");
}

if (empty($targets)) {
    die("Tidak ada folder target yang ditemukan.\n");
}

if (!$dry_run) {
    $mysqli->begin_transaction();
}

try {
    $total_created = 0;
    $total_skipped = 0;
    $now = date('Y-m-d H:i:s');

    $stmt = $mysqli->prepare("INSERT INTO dir_records (
        id, parent_id, procedure_id, flag_type, flag_confidential,
        confidential_group_ids, name, description, doc, file_name,
        link_url, ext, active, size, company_id,
        status, created_by, created_at
    ) VALUES (
        ?, ?, ?, 'FOLDER', '0',
        NULL, ?, NULL, NULL, NULL,
        NULL, NULL, 'Y', NULL, ?,
        'PUB', ?, ?
    )");

    foreach ($targets as $target_id => $target) {
        echo "\nTarget Folder: {$target['name']} [{$target_id}]\n";

        foreach ($suffixes as $suffix) {
            $folder_name = $prefix . $suffix;
            $name_esc = $mysqli->real_escape_string($folder_name);

            // Cek apakah sudah ada
            $chk = $mysqli->query("SELECT id FROM dir_records WHERE parent_id = '$target_id' AND name = '$name_esc' AND status != 'DEL'");
            if ($chk && $chk->num_rows > 0) {
                echo "  - [SKIP] Sudah ada: $folder_name\n";
                $total_skipped++;
                continue;
            }

            // Generate unique ID (15 karakter hex sesuai uniqid date(m))
            do {
                usleep(1500); // 1.5ms
                $new_id = uniqid(date('m'));
                $chk_id = $mysqli->query("SELECT id FROM dir_records WHERE id = '$new_id'");
            } while ($chk_id && $chk_id->num_rows > 0);

            if (!$dry_run) {
                $proc_id = $target['procedure_id'];
                $comp_id = (int)($target['company_id'] ?: 1);
                $stmt->bind_param("ssssiis", $new_id, $target_id, $proc_id, $folder_name, $comp_id, $created_by_user_id, $now);
                if (!$stmt->execute()) {
                    throw new Exception("Gagal insert $folder_name: " . $stmt->error);
                }
            }

            echo "  + [CREATE] [$new_id] $folder_name\n";
            $total_created++;
        }
    }

    if (!$dry_run) {
        $mysqli->commit();
        echo "\n=======================================================\n";
        echo "SUKSES: $total_created subfolder berhasil ditambahkan ke database! (Skipped: $total_skipped)\n";
        echo "=======================================================\n";
    } else {
        echo "\n=======================================================\n";
        echo "SIMULASI SELESAI: $total_created subfolder siap dibuat. (Skipped: $total_skipped)\n";
        echo "Ubah \$dry_run = false; lalu jalankan kembali untuk menyimpan ke database.\n";
        echo "=======================================================\n";
    }

} catch (Throwable $e) {
    if (!$dry_run) {
        $mysqli->rollback();
    }
    echo "\nGAGAL DAN DIBATALKAN (ROLLBACK): " . $e->getMessage() . "\n";
    exit(1);
}
