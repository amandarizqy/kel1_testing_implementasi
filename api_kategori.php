<?php
session_start();
header("Content-Type: application/json");
include 'database.php';

// Proteksi Session
if (!isset($_SESSION['id_pengguna'])) {
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit;
}

$id_user = $_SESSION['id_pengguna'];
$method = $_SERVER['REQUEST_METHOD'];

// --- GET: AMBIL SEMUA KATEGORI ---
if ($method === 'GET') {
    $data = [];
    
    // Ambil Kategori Pendapatan
    $q1 = mysqli_query($conn, "SELECT id_kategori, nama_kategori FROM kategori_pendapatan WHERE id_pengguna = $id_user");
    while ($row = mysqli_fetch_assoc($q1)) {
        $data[] = ['id' => $row['id_kategori'], 'nama' => $row['nama_kategori'], 'jenis' => 'Pendapatan'];
    }

    // Ambil Kategori Pengeluaran
    $q2 = mysqli_query($conn, "SELECT id_kategori, nama_kategori FROM kategori_pengeluaran WHERE id_pengguna = $id_user");
    while ($row = mysqli_fetch_assoc($q2)) {
        $data[] = ['id' => $row['id_kategori'], 'nama' => $row['nama_kategori'], 'jenis' => 'Pengeluaran'];
    }

    echo json_encode(["status" => "success", "data" => $data]);
}

// --- POST: TAMBAH ATAU HAPUS ---
if ($method === 'POST') {
    $action = $_POST['action'] ?? '';

    // LOGIKA TAMBAH
    if ($action === 'tambah') {
        $nama = $_POST['nama_kategori'];
        $jenis = $_POST['jenis'];
        $tabel = ($jenis === 'Pendapatan') ? 'kategori_pendapatan' : 'kategori_pengeluaran';

        $stmt = mysqli_prepare($conn, "INSERT INTO $tabel (nama_kategori, id_pengguna) VALUES (?, ?)");
        mysqli_stmt_bind_param($stmt, "si", $nama, $id_user);
        
        if (mysqli_stmt_execute($stmt)) {
            echo json_encode(["status" => "success", "message" => "Kategori berhasil ditambah"]);
        }
    }

    // LOGIKA HAPUS
    if ($action === 'hapus') {
        $id = intval($_POST['id_kategori']);
        $jenis = $_POST['jenis'];
        $tabel = ($jenis === 'Pendapatan') ? 'kategori_pendapatan' : 'kategori_pengeluaran';

        $stmt = mysqli_prepare($conn, "DELETE FROM $tabel WHERE id_kategori = ? AND id_pengguna = ?");
        mysqli_stmt_bind_param($stmt, "ii", $id, $id_user);

        if (mysqli_stmt_execute($stmt)) {
            echo json_encode(["status" => "success", "message" => "Kategori berhasil dihapus"]);
        }
    }
}
?>