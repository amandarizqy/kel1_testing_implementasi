<?php
header("Content-Type: application/json");
include 'database.php';

$response = ["success" => false, "message" => ""];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitasi input untuk mencegah SQL Injection
    $nama_aset = mysqli_real_escape_string($conn, $_POST['nama_aset']);
    $saldo = mysqli_real_escape_string($conn, $_POST['saldo']);
    $keterangan = mysqli_real_escape_string($conn, $_POST['keterangan']);
    $id_pengguna = mysqli_real_escape_string($conn, $_POST['id_pengguna']); // Diperlukan sesuai skema DB sebelumnya

    if (empty($nama_aset) || empty($id_pengguna)) {
        $response["message"] = "Data tidak lengkap!";
    } else {
        $query = "INSERT INTO aset (nama_aset, saldo, keterangan, id_pengguna) 
                  VALUES ('$nama_aset', '$saldo', '$keterangan', '$id_pengguna')";

        if (mysqli_query($conn, $query)) {
            $response["success"] = true;
            $response["message"] = "Aset berhasil disimpan!";
        } else {
            $response["message"] = "Database error: " . mysqli_error($conn);
        }
    }
} else {
    $response["message"] = "Method tidak diizinkan";
}

echo json_encode($response);