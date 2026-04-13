<?php
include 'database.php';

// Cek apakah ada id di URL
if (isset($_GET['id_aset'])) {
    $id_aset = $_GET['id_aset'];

    // Ambil data dari database
    $query = "SELECT * FROM aset WHERE id_aset = $id_aset";
    $result = mysqli_query($conn, $query);
    $data = mysqli_fetch_assoc($result);

    if (!$data) {
        echo "<script>alert('Data aset tidak ditemukan!'); window.location.href='Aset.php';</script>";
        exit;
    }
} else {
    echo "<script>alert('ID tidak ditemukan! Kembali ke halaman Aset.'); window.location.href='Aset.php';</script>";
    exit;
}

// Proses update jika form disubmit
if (isset($_POST['submit'])) {
    $nama_aset = $_POST['nama_aset'];
    $saldo = $_POST['saldo'];
    $keterangan = $_POST['keterangan'];

    $update = "UPDATE aset SET nama_aset='$nama_aset', saldo='$saldo', keterangan='$keterangan' WHERE id_aset=$id_aset";
    if (mysqli_query($conn, $update)) {
        echo "<script>alert('Data aset berhasil diupdate!'); window.location.href='Aset.php';</script>";
        exit;
    } else {
        echo "Gagal update data!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Aset</title>
    <link rel="stylesheet" href="UAS.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Edit Aset</h2>
    <form method="post">
        <div class="mb-3">
            <label for="nama_aset" class="form-label">Nama Aset</label>
            <input type="text" class="form-control" id="nama_aset" name="nama_aset" value="<?php echo htmlspecialchars($data['nama_aset']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="saldo" class="form-label">Saldo</label>
            <input type="number" class="form-control" id="saldo" name="saldo" value="<?php echo htmlspecialchars($data['saldo']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="keterangan" class="form-label">Keterangan</label>
            <textarea class="form-control" id="keterangan" name="keterangan" rows="3" required><?php echo htmlspecialchars($data['keterangan']); ?></textarea>
        </div>
        <button type="submit" name="submit" class="btn btn-success">Simpan Perubahan</button>
        <a href="Aset.php" class="btn btn-secondary">Batal</a>
    </form>
</div>
</body>
</html>
