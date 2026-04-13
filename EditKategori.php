<?php
include 'database.php';

if (!isset($_GET['id_kategori']) || !isset($_GET['jenis'])) {
    header("Location: Kategori.php");
    exit;
}

$id_kategori = intval($_GET['id_kategori']);
$jenis = $_GET['jenis'];

// Ambil data berdasarkan jenis
if ($jenis == 'Pendapatan') {
    $result = mysqli_query($conn, "SELECT * FROM kategori_pendapatan WHERE id_kategori = $id_kategori");
} elseif ($jenis == 'Pengeluaran') {
    $result = mysqli_query($conn, "SELECT * FROM kategori_pengeluaran WHERE id_kategori = $id_kategori");
} else {
    header("Location: Kategori.php");
    exit;
}

$data = mysqli_fetch_assoc($result);
if (!$data) {
    echo "Data tidak ditemukan!";
    exit;
}

// Proses update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama_kategori'];

    if ($jenis == 'Pendapatan') {
        mysqli_query($conn, "UPDATE kategori_pendapatan SET nama_kategori = '$nama' WHERE id_kategori = $id_kategori");
    } elseif ($jenis == 'Pengeluaran') {
        mysqli_query($conn, "UPDATE kategori_pengeluaran SET nama_kategori = '$nama' WHERE id_kategori = $id_kategori");
    }

    header("Location: Kategori.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Kategori</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="mb-4 text-center">Edit Kategori - <?= htmlspecialchars($jenis) ?></h2>
    <form method="POST" class="card p-4 shadow-sm mx-auto" style="max-width: 500px;">
        <div class="mb-3">
            <label for="nama_kategori" class="form-label">Nama Kategori</label>
            <input type="text" name="nama_kategori" id="nama_kategori" class="form-control" value="<?= htmlspecialchars($data['nama_kategori']) ?>" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Simpan Perubahan</button>
        <a href="Kategori.php" class="btn btn-secondary mt-2 w-100">Batal</a>
    </form>
</div>
</body>
</html>
