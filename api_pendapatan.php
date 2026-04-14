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

// --- GET: AMBIL PILIHAN ASET & KATEGORI ---
if ($method === 'GET') {
    $aset = mysqli_query($conn, "SELECT id_aset, nama_aset FROM aset WHERE id_pengguna = $id_user");
    $kategori = mysqli_query($conn, "SELECT id_kategori, nama_kategori FROM kategori_pendapatan WHERE id_pengguna = $id_user");

    echo json_encode([
        "status" => "success",
        "aset" => mysqli_fetch_all($aset, MYSQLI_ASSOC),
        "kategori" => mysqli_fetch_all($kategori, MYSQLI_ASSOC)
    ]);
    exit;
}

// --- POST: SIMPAN TRANSAKSI ---
if ($method === 'POST') {
    $tanggal = $_POST['tanggal'];
    $id_aset = $_POST['aset'];
    $id_kategori = $_POST['kategori'];
    $jumlah = $_POST['jumlah'];
    $keterangan = $_POST['keterangan'];

    // Gunakan transaksi SQL agar jika salah satu gagal, semua dibatalkan
    mysqli_begin_transaction($conn);

    try {
        // 1. Simpan ke tabel pendapatan (Pastikan tabel ini punya kolom id_pengguna)
        $q_insert = "INSERT INTO pendapatan (tanggal, id_aset, id_kategori, jumlah, keterangan, id_pengguna) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $q_insert);
        mysqli_stmt_bind_param($stmt, "siiisi", $tanggal, $id_aset, $id_kategori, $jumlah, $keterangan, $id_user);
        mysqli_stmt_execute($stmt);

        // 2. Update Saldo Aset (Hanya jika aset tersebut milik user yang login)
        $q_update = "UPDATE aset SET saldo = saldo + ? WHERE id_aset = ? AND id_pengguna = ?";
        $stmt_u = mysqli_prepare($conn, $q_update);
        mysqli_stmt_bind_param($stmt_u, "iii", $jumlah, $id_aset, $id_user);
        mysqli_stmt_execute($stmt_u);

        mysqli_commit($conn);
        echo json_encode(["status" => "success", "message" => "Pendapatan berhasil disimpan!"]);
    } catch (Exception $e) {
        mysqli_rollback($conn);
        echo json_encode(["status" => "error", "message" => "Gagal menyimpan data."]);
    }
}
?>