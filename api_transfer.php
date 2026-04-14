<?php
session_start();
header("Content-Type: application/json");
include 'database.php';

if (!isset($_SESSION['id_pengguna'])) {
    echo json_encode(["status" => "error", "message" => "Sesi habis, silakan login kembali."]);
    exit;
}

$id_user = $_SESSION['id_pengguna'];
$method = $_SERVER['REQUEST_METHOD'];

// --- GET: AMBIL DAFTAR ASET MILIK USER ---
if ($method === 'GET') {
    $query = "SELECT id_aset, nama_aset FROM aset WHERE id_pengguna = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $id_user);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    echo json_encode([
        "status" => "success",
        "aset" => mysqli_fetch_all($result, MYSQLI_ASSOC)
    ]);
    exit;
}

// --- POST: PROSES TRANSFER ---
if ($method === 'POST') {
    $tanggal    = $_POST['tanggal'];
    $dari_aset  = intval($_POST['dari_aset']);
    $ke_aset    = intval($_POST['ke_aset']);
    $jumlah     = intval($_POST['jumlah']);
    $keterangan = $_POST['keterangan'];

    if ($dari_aset == $ke_aset) {
        echo json_encode(["status" => "error", "message" => "Aset asal dan tujuan tidak boleh sama!"]);
        exit;
    }

    // Mulai Transaksi SQL
    mysqli_begin_transaction($conn);

    try {
        // 1. Simpan ke tabel transfer (pastikan sudah tambah kolom id_pengguna di tabel ini)
        $q_insert = "INSERT INTO transfer (tanggal, dari_aset, ke_aset, jumlah, keterangan, id_pengguna) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt_i = mysqli_prepare($conn, $q_insert);
        mysqli_stmt_bind_param($stmt_i, "siiisi", $tanggal, $dari_aset, $ke_aset, $jumlah, $keterangan, $id_user);
        mysqli_stmt_execute($stmt_i);

        // 2. Kurangi Saldo Aset Asal
        $q_min = "UPDATE aset SET saldo = saldo - ? WHERE id_aset = ? AND id_pengguna = ?";
        $stmt_m = mysqli_prepare($conn, $q_min);
        mysqli_stmt_bind_param($stmt_m, "iii", $jumlah, $dari_aset, $id_user);
        mysqli_stmt_execute($stmt_m);

        // 3. Tambah Saldo Aset Tujuan
        $q_plus = "UPDATE aset SET saldo = saldo + ? WHERE id_aset = ? AND id_pengguna = ?";
        $stmt_p = mysqli_prepare($conn, $q_plus);
        mysqli_stmt_bind_param($stmt_p, "iii", $jumlah, $ke_aset, $id_user);
        mysqli_stmt_execute($stmt_p);

        mysqli_commit($conn);
        echo json_encode(["status" => "success", "message" => "Transfer berhasil disimpan!"]);
    } catch (Exception $e) {
        mysqli_rollback($conn);
        echo json_encode(["status" => "error", "message" => "Gagal memproses transfer."]);
    }
}
?>