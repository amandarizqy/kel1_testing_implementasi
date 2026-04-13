<?php
include 'database.php';

$id = $_GET['id'];

// Ambil data pengeluaran sebelum diedit
$data_lama = mysqli_query($conn, "SELECT * FROM pengeluaran WHERE id_pengeluaran = $id");
$row = mysqli_fetch_assoc($data_lama);

// Ambil pilihan aset & kategori
$asets = mysqli_query($conn, "SELECT id_aset, nama_aset FROM aset");
$kategori = mysqli_query($conn, "SELECT id_kategori, nama_kategori FROM kategori_pengeluaran");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $tanggal = $_POST['tanggal'];
  $id_aset_baru = $_POST['id_aset'];
  $id_kategori = $_POST['id_kategori'];
  $jumlah_baru = $_POST['jumlah'];
  $keterangan = $_POST['keterangan'];

  $id_aset_lama = $row['id_aset'];
  $jumlah_lama = $row['jumlah'];

  // 1. Kembalikan saldo lama
  mysqli_query($conn, "UPDATE aset SET saldo = saldo + $jumlah_lama WHERE id_aset = $id_aset_lama");

  // 2. Kurangi saldo baru
  mysqli_query($conn, "UPDATE aset SET saldo = saldo - $jumlah_baru WHERE id_aset = $id_aset_baru");

  // 3. Update data pengeluaran
  mysqli_query($conn, "UPDATE pengeluaran SET 
    tanggal='$tanggal', 
    id_aset='$id_aset_baru', 
    id_kategori='$id_kategori', 
    jumlah='$jumlah_baru', 
    keterangan='$keterangan' 
    WHERE id_pengeluaran = $id");

  echo "<script>alert('Pengeluaran berhasil diperbarui!'); window.location='Catatan.php';</script>";
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Edit Pengeluaran</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-5">
  <h2>Edit Pengeluaran</h2>
  <form method="POST">
    <input type="date" name="tanggal" value="<?= $row['tanggal'] ?>" class="form-control mb-2" required>
    
    <select name="id_aset" class="form-control mb-2" required>
      <?php while($a = mysqli_fetch_assoc($asets)): ?>
        <option value="<?= $a['id_aset'] ?>" <?= $row['id_aset'] == $a['id_aset'] ? 'selected' : '' ?>>
          <?= $a['nama_aset'] ?>
        </option>
      <?php endwhile; ?>
    </select>

    <select name="id_kategori" class="form-control mb-2" required>
      <?php while($k = mysqli_fetch_assoc($kategori)): ?>
        <option value="<?= $k['id_kategori'] ?>" <?= $row['id_kategori'] == $k['id_kategori'] ? 'selected' : '' ?>>
          <?= $k['nama_kategori'] ?>
        </option>
      <?php endwhile; ?>
    </select>

    <input type="number" name="jumlah" value="<?= $row['jumlah'] ?>" class="form-control mb-2" required>
    <textarea name="keterangan" class="form-control mb-3"><?= $row['keterangan'] ?></textarea>

    <button class="btn btn-success">Simpan Perubahan</button>
    <a href="Catatan.php" class="btn btn-secondary">Batal</a>
  </form>
</body>
</html>
