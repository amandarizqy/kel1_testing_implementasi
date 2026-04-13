<?php
header("Content-Type: application/json");
include 'database.php';

$method = $_SERVER['REQUEST_METHOD'];

// --- MENGAMBIL DATA ASET (GET) ---
if ($method === 'GET') {
    $query = "SELECT * FROM aset";
    $result = mysqli_query($conn, $query);
    
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
    $id = intval($_POST['id_aset']);
    
    // Gunakan Prepared Statement agar lebih aman (Best Practice API)
    $stmt = mysqli_prepare($conn, "DELETE FROM aset WHERE id_aset = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    
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