<?php
// 1. Matikan laporan error agar tidak merusak format JSON jika ada peringatan kecil
error_reporting(0);

// 2. Mulai session
session_start();

// 3. Set header JSON
header("Content-Type: application/json");

// 4. Hubungkan database
include 'database.php';

// Cek koneksi secara internal (jangan di-echo langsung teksnya)
if (!$conn) {
    echo json_encode(["status" => "error", "message" => "Gagal terhubung ke database"]);
    exit;
}

// 5. Tangkap input
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($username) || empty($password)) {
    echo json_encode(["status" => "error", "message" => "Username dan password tidak boleh kosong"]);
    exit;
}

// 6. Query Cari User
$query = "SELECT * FROM pengguna WHERE username = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

// 7. Verifikasi

// ... kode sebelumnya ...

if ($user) {
    // Gunakan fungsi ini untuk mengecek password hash
    if (password_verify($password, $user['password'])) {
        session_start();
        $_SESSION['id_pengguna'] = $user['id_pengguna'];
        $_SESSION['nama'] = $user['nama_lengkap'];

        echo json_encode([
            "status" => "success",
            "message" => "Login Berhasil!"
        ]);
    } else {
        // Password salah
        echo json_encode(["status" => "error", "message" => "Password salah!"]);
    }
} else {
    // Username tidak ditemukan
    echo json_encode(["status" => "error", "message" => "Username tidak ditemukan!"]);
}