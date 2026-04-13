<?php
include 'database.php';
$id = $_GET['id'];

// Ambil data lama
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM pendapatan WHERE id_pendapatan = $id"));
$asets = mysqli_query($conn, "SELECT * FROM aset");
$kategori = mysqli_query($conn, "SELECT * FROM kategori_pendapatan");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tanggal = $_POST['tanggal'];
    $id_aset = $_POST['id_aset'];
    $id_kategori = $_POST['id_kategori'];
    $jumlah_baru = $_POST['jumlah'];
    $keterangan = $_POST['keterangan'];

    $jumlah_lama = $data['jumlah'];
    $aset_lama = $data['id_aset'];

    // Hitung perubahan saldo
    if ($id_aset == $aset_lama) {
        $selisih = $jumlah_baru - $jumlah_lama;
        mysqli_query($conn, "UPDATE aset SET saldo = saldo + $selisih WHERE id_aset = $id_aset");
    } else {
        mysqli_query($conn, "UPDATE aset SET saldo = saldo - $jumlah_lama WHERE id_aset = $aset_lama");
        mysqli_query($conn, "UPDATE aset SET saldo = saldo + $jumlah_baru WHERE id_aset = $id_aset");
    }

    // Update pendapatan
    mysqli_query($conn, "UPDATE pendapatan SET 
        tanggal='$tanggal', 
        id_aset='$id_aset', 
        id_kategori='$id_kategori', 
        jumlah='$jumlah_baru', 
        keterangan='$keterangan' 
        WHERE id_pendapatan = $id");

    echo "<script>alert('Pendapatan berhasil diperbarui!'); window.location='Catatan.php';</script>";
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Edit Pendapatan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-5">
  <h2>Edit Pendapatan</h2>
  <form method="POST">
    <input type="date" name="tanggal" value="<?= $data['tanggal'] ?>" class="form-control mb-2" required>
    
    <select name="id_aset" class="form-control mb-2" required>
      <?php while($a = mysqli_fetch_assoc($asets)): ?>
        <option value="<?= $a['id_aset'] ?>" <?= $data['id_aset'] == $a['id_aset'] ? 'selected' : '' ?>>
          <?= $a['nama_aset'] ?>
        </option>
      <?php endwhile; ?>
    </select>

    <select name="id_kategori" class="form-control mb-2" required>
      <?php while($k = mysqli_fetch_assoc($kategori)): ?>
        <option value="<?= $k['id_kategori'] ?>" <?= $data['id_kategori'] == $k['id_kategori'] ? 'selected' : '' ?>>
          <?= $k['nama_kategori'] ?>
        </option>
      <?php endwhile; ?>
    </select>

    <input type="number" name="jumlah" value="<?= $data['jumlah'] ?>" class="form-control mb-2" required>
    <textarea name="keterangan" class="form-control mb-3"><?= $data['keterangan'] ?></textarea>

    <button class="btn btn-success">Simpan Perubahan</button>
    <a href="Catatan.php" class="btn btn-secondary">Batal</a>
  </form>
</body>
</html>
