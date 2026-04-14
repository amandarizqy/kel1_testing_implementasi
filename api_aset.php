<?php
// 1. WAJIB jalankan session_start di paling atas
session_start();
header("Content-Type: application/json");
include 'database.php';

// 2. Cek apakah user sudah login. Jika tidak, jangan beri data.
if (!isset($_SESSION['id_pengguna'])) {
    echo json_encode([
        "status" => "error",
        "message" => "Akses ditolak. Silakan login terlebih dahulu."
    ]);
    exit;
}

$id_login = $_SESSION['id_pengguna']; // Ambil ID dari session
$method = $_SERVER['REQUEST_METHOD'];

// --- MENGAMBIL DATA ASET (GET) ---
if ($method === 'GET') {
    // Tambahkan WHERE id_pengguna agar data tidak bercampur
    $query = "SELECT * FROM aset WHERE id_pengguna = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $id_login);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    echo json_encode([
        "status" => "success",
        "data" => $data
    ]);
}

// --- MENGHAPUS DATA ASET (POST) ---
if ($method === 'POST' && isset($_POST['action']) && $_POST['action'] === 'hapus') {
    $id_aset = intval($_POST['id_aset']);
    
    // Pastikan user hanya bisa menghapus aset MILIKNYA sendiri
    $stmt = mysqli_prepare($conn, "DELETE FROM aset WHERE id_aset = ? AND id_pengguna = ?");
    mysqli_stmt_bind_param($stmt, "ii", $id_aset, $id_login);
    
    if (mysqli_stmt_execute($stmt)) {
        echo json_encode([
            "status" => "success",
            "message" => "Aset berhasil dihapus!"
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Gagal menghapus aset."
        ]);
    }
}
?>