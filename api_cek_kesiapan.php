<?php
session_start();
header("Content-Type: application/json");
include 'database.php';

if (!isset($_SESSION['id_pengguna'])) {
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit;
}

$id_user = $_SESSION['id_pengguna'];

// Fungsi pembantu untuk menghitung data per user
function hitungData($conn, $tabel, $id_user) {
    $query = "SELECT COUNT(*) as total FROM $tabel WHERE id_pengguna = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $id_user);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $data = mysqli_fetch_assoc($res);
    return $data['total'];
}

$jml_aset = hitungData($conn, 'aset', $id_user);
$jml_kat_pendapatan = hitungData($conn, 'kategori_pendapatan', $id_user);
$jml_kat_pengeluaran = hitungData($conn, 'kategori_pengeluaran', $id_user);

echo json_encode([
    "status" => "success",
    "siap_pendapatan" => ($jml_aset > 0 && $jml_kat_pendapatan > 0),
    "siap_pengeluaran" => ($jml_aset > 0 && $jml_kat_pengeluaran > 0),
    "siap_transfer" => ($jml_aset > 1)
]);