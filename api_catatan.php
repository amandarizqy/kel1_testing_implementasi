<?php
session_start(); // 1. Wajib aktifkan session di paling atas
header("Content-Type: application/json");
include 'database.php';

// 2. Cek apakah user sudah login
if (!isset($_SESSION['id_pengguna'])) {
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit;
}

$id_user = $_SESSION['id_pengguna'];
$method = $_SERVER['REQUEST_METHOD'];

// Ambil Parameter Filter
$bulan = $_GET['bulan'] ?? date('m');
$tahun = $_GET['tahun'] ?? date('Y');

// --- LOGIKA MENGAMBIL DATA (GET) ---
if ($method === 'GET') {
    // 3. Ambil Pendapatan (JOIN ke aset untuk ambil nama_aset)
    $qPendapatan = mysqli_query($conn, "
        SELECT p.*, k.nama_kategori, a.nama_aset 
        FROM pendapatan p 
        JOIN kategori_pendapatan k ON p.id_kategori = k.id_kategori 
        JOIN aset a ON p.id_aset = a.id_aset
        WHERE p.id_pengguna = $id_user 
        AND MONTH(p.tanggal) = '$bulan' 
        AND YEAR(p.tanggal) = '$tahun'
    ");
    $pendapatan = mysqli_fetch_all($qPendapatan, MYSQLI_ASSOC);

    // 4. Ambil Pengeluaran (JOIN ke aset untuk ambil nama_aset)
    $qPengeluaran = mysqli_query($conn, "
        SELECT p.*, k.nama_kategori, a.nama_aset 
        FROM pengeluaran p 
        JOIN kategori_pengeluaran k ON p.id_kategori = k.id_kategori 
        JOIN aset a ON p.id_aset = a.id_aset
        WHERE p.id_pengguna = $id_user 
        AND MONTH(p.tanggal) = '$bulan' 
        AND YEAR(p.tanggal) = '$tahun'
    ");
    $pengeluaran = mysqli_fetch_all($qPengeluaran, MYSQLI_ASSOC);

    // 5. Ambil Transfer
    $qTransfer = mysqli_query($conn, "
        SELECT t.*, a1.nama_aset AS dari, a2.nama_aset AS ke 
        FROM transfer t 
        JOIN aset a1 ON t.dari_aset = a1.id_aset 
        JOIN aset a2 ON t.ke_aset = a2.id_aset 
        WHERE t.id_pengguna = $id_user 
        AND MONTH(t.tanggal) = '$bulan' 
        AND YEAR(t.tanggal) = '$tahun'
    ");
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
        // Tambahkan cek id_pengguna agar tidak bisa hapus punya orang lain
        $q = mysqli_query($conn, "SELECT id_aset, jumlah FROM pendapatan WHERE id_pendapatan = $id AND id_pengguna = $id_user");
        if ($row = mysqli_fetch_assoc($q)) {
            mysqli_query($conn, "UPDATE aset SET saldo = saldo - {$row['jumlah']} WHERE id_aset = {$row['id_aset']}");
            mysqli_query($conn, "DELETE FROM pendapatan WHERE id_pendapatan = $id");
        }
    } 
    // ... Logika hapus pengeluaran & transfer mengikuti pola yang sama (tambahkan id_pengguna)

    echo json_encode(["status" => "success", "message" => "Data berhasil dihapus"]);
}