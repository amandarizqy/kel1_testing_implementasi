<?php
header("Content-Type: application/json");
include 'database.php';

$method = $_SERVER['REQUEST_METHOD'];

// Ambil Parameter Filter
$bulan = $_GET['bulan'] ?? date('m');
$tahun = $_GET['tahun'] ?? date('Y');

// --- LOGIKA MENGAMBIL DATA (GET) ---
if ($method === 'GET') {
    // 1. Ambil Pendapatan
    $qPendapatan = mysqli_query($conn, "SELECT p.*, k.nama_kategori FROM pendapatan p JOIN kategori_pendapatan k ON p.id_kategori = k.id_kategori WHERE MONTH(p.tanggal) = '$bulan' AND YEAR(p.tanggal) = '$tahun'");
    $pendapatan = mysqli_fetch_all($qPendapatan, MYSQLI_ASSOC);

    // 2. Ambil Pengeluaran
    $qPengeluaran = mysqli_query($conn, "SELECT p.*, k.nama_kategori FROM pengeluaran p JOIN kategori_pengeluaran k ON p.id_kategori = k.id_kategori WHERE MONTH(p.tanggal) = '$bulan' AND YEAR(p.tanggal) = '$tahun'");
    $pengeluaran = mysqli_fetch_all($qPengeluaran, MYSQLI_ASSOC);

    // 3. Ambil Transfer
    $qTransfer = mysqli_query($conn, "SELECT t.*, a1.nama_aset AS dari, a2.nama_aset AS ke FROM transfer t JOIN aset a1 ON t.dari_aset = a1.id_aset JOIN aset a2 ON t.ke_aset = a2.id_aset WHERE MONTH(t.tanggal) = '$bulan' AND YEAR(t.tanggal) = '$tahun'");
    $transfer = mysqli_fetch_all($qTransfer, MYSQLI_ASSOC);

    echo json_encode([
        "status" => "success",
        "data" => [
            "pendapatan" => $pendapatan,
            "pengeluaran" => $pengeluaran,
            "transfer" => $transfer
        ]
    ]);
}

// --- LOGIKA HAPUS DATA (POST/DELETE) ---
if ($method === 'POST' && isset($_POST['action'])) {
    $id = intval($_POST['id']);
    $action = $_POST['action'];

    if ($action === 'hapus_pendapatan') {
        $q = mysqli_query($conn, "SELECT id_aset, jumlah FROM pendapatan WHERE id_pendapatan = $id");
        if ($row = mysqli_fetch_assoc($q)) {
            mysqli_query($conn, "UPDATE aset SET saldo = saldo - {$row['jumlah']} WHERE id_aset = {$row['id_aset']}");
            mysqli_query($conn, "DELETE FROM pendapatan WHERE id_pendapatan = $id");
        }
    } 
    // ... Tambahkan logika hapus pengeluaran & transfer di sini (sama seperti kode lamamu)

    echo json_encode(["status" => "success", "message" => "Data berhasil dihapus"]);
}