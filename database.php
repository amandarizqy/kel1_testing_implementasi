<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "testing_keuangan";

$conn = mysqli_connect($host, $user, $pass, $db);

// Kode ini HARUS di dalam tag PHP
if (!$conn) {
    // Gunakan header JSON agar konsisten jika terjadi error koneksi
    header("Content-Type: application/json");
    echo json_encode(["status" => "error", "message" => "Koneksi database gagal: " . mysqli_connect_error()]);
    exit;
}
?>