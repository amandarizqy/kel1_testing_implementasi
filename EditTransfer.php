<?php
include 'database.php';

$id = $_GET['id'];
$row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM transfer WHERE id_transfer = $id"));

$asets = mysqli_query($conn, "SELECT id_aset, nama_aset FROM aset");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tanggal = $_POST['tanggal'];
    $dari_aset_baru = $_POST['dari_aset'];
    $ke_aset_baru = $_POST['ke_aset'];
    $jumlah_baru = $_POST['jumlah'];
    $keterangan = $_POST['keterangan'];

    // Data lama
    $dari_aset_lama = $row['dari_aset'];
    $ke_aset_lama = $row['ke_aset'];
    $jumlah_lama = $row['jumlah'];

    // 1. Kembalikan saldo lama
    mysqli_query($conn, "UPDATE aset SET saldo = saldo + $jumlah_lama WHERE id_aset = $dari_aset_lama");
    mysqli_query($conn, "UPDATE aset SET saldo = saldo - $jumlah_lama WHERE id_aset = $ke_aset_lama");

    // 2. Kurangi saldo baru dan tambahkan ke tujuan baru
    mysqli_query($conn, "UPDATE aset SET saldo = saldo - $jumlah_baru WHERE id_aset = $dari_aset_baru");
    mysqli_query($conn, "UPDATE aset SET saldo = saldo + $jumlah_baru WHERE id_aset = $ke_aset_baru");

    // 3. Simpan perubahan transfer
    mysqli_query($conn, "UPDATE transfer SET 
        tanggal='$tanggal', 
        dari_aset='$dari_aset_baru', 
        ke_aset='$ke_aset_baru', 
        jumlah='$jumlah_baru', 
        keterangan='$keterangan'
        WHERE id_transfer = $id");

    echo "<script>alert('Transfer berhasil diperbarui!'); window.location='Catatan.php';</script>";
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Edit Transfer</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-5">
  <h2>Edit Transfer</h2>
  <form method="POST">
    <input type="date" name="tanggal" value="<?= $row['tanggal'] ?>" class="form-control mb-2" required>

    <!-- Dari Aset -->
    <select name="dari_aset" class="form-control mb-2" required>
      <?php 
      mysqli_data_seek($asets, 0); // reset
      while($a = mysqli_fetch_assoc($asets)): ?>
        <option value="<?= $a['id_aset'] ?>" <?= $row['dari_aset'] == $a['id_aset'] ? 'selected' : '' ?>>
          <?= $a['nama_aset'] ?>
        </option>
      <?php endwhile; ?>

    </select>

    <!-- Ke Aset -->
    <select name="ke_aset" class="form-control mb-2" required>
      <?php 
      mysqli_data_seek($asets, 0); // reset lagi
      while($a = mysqli_fetch_assoc($asets)): ?>
        <option value="<?= $a['id_aset'] ?>" <?= $row['ke_aset'] == $a['id_aset'] ? 'selected' : '' ?>>
          <?= $a['nama_aset'] ?>
        </option>
      <?php endwhile; ?>
    </select>

    <input type="number" name="jumlah" value="<?= $row['jumlah'] ?>" class="form-control mb-2" required>
    <textarea name="keterangan" class="form-control mb-3"><?= $row['keterangan'] ?></textarea>

    <button class="btn btn-warning text-white">Simpan Perubahan</button>
    <a href="Catatan.php" class="btn btn-secondary">Batal</a>
  </form>
</body>
</html>
