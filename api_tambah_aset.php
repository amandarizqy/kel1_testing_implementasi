<?php
session_start();
header("Content-Type: application/json");
include 'database.php';

// 1. Cek apakah user sudah login
if (!isset($_SESSION['id_pengguna'])) {
    echo json_encode(["success" => false, "message" => "Sesi habis, silakan login kembali."]);
    exit;
}

$id_login = $_SESSION['id_pengguna']; // ID diambil dari session, bukan dari form

// 2. Tangkap data dari FormData
$nama_aset  = $_POST['nama_aset'] ?? '';
$saldo      = $_POST['saldo'] ?? 0;
$keterangan = $_POST['keterangan'] ?? '';

// 3. Validasi sederhana
if (empty($nama_aset)) {
    echo json_encode(["success" => false, "message" => "Nama aset wajib diisi!"]);
    exit;
}

// 4. Simpan ke database menggunakan Prepared Statement
$query = "INSERT INTO aset (nama_aset, saldo, keterangan, id_pengguna) VALUES (?, ?, ?, ?)";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "sisi", $nama_aset, $saldo, $keterangan, $id_login);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode([
        "success" => true,
        "message" => "Aset '$nama_aset' berhasil disimpan."
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Gagal menyimpan ke database: " . mysqli_error($conn)
    ]);
}
?>