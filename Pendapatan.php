<?php
include 'database.php';

// Ambil data aset dan kategori
$data_aset = mysqli_query($conn, "SELECT id_aset, nama_aset FROM aset");
$data_kategori = mysqli_query($conn, "SELECT id_kategori, nama_kategori FROM kategori_pendapatan");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tanggal = $_POST['tanggal'];
    $id_aset = $_POST['aset'];
    $id_kategori = $_POST['kategori'];
    $jumlah = $_POST['jumlah'];
    $keterangan = $_POST['keterangan'];

    // Simpan ke tabel pendapatan
    $query = "INSERT INTO pendapatan (tanggal, id_aset, id_kategori, jumlah, keterangan) 
              VALUES ('$tanggal', '$id_aset', '$id_kategori', '$jumlah', '$keterangan')";

    if (mysqli_query($conn, $query)) {
        // Tambahkan jumlah ke saldo aset
        $update_saldo = "UPDATE aset SET saldo = saldo + $jumlah WHERE id_aset = $id_aset";
        mysqli_query($conn, $update_saldo);

        echo "<script>alert('Pendapatan berhasil disimpan & saldo aset diperbarui!'); window.location='Tambah.php';</script>";
    } else {
        echo "Error: " . $query . "<br>" . mysqli_error($conn);
    }
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Input Pendapatan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
  <h2 class="text-center mb-4">Form Pendapatan</h2>
  <form method="POST" action="">
    <div class="mb-3">
      <label for="tanggal" class="form-label">Tanggal</label>
      <input type="date" class="form-control" id="tanggal" name="tanggal" required>
    </div>

    <div class="mb-3">
      <label for="aset" class="form-label">Pilih Aset</label>
      <select class="form-control" id="aset" name="aset" required>
        <option value="">-- Pilih Aset --</option>
        <?php while($aset = mysqli_fetch_assoc($data_aset)): ?>
          <option value="<?= $aset['id_aset'] ?>"><?= htmlspecialchars($aset['nama_aset']) ?></option>
        <?php endwhile; ?>
      </select>
    </div>

    <div class="mb-3">
      <label for="kategori" class="form-label">Pilih Kategori Pendapatan</label>
      <select class="form-control" id="kategori" name="kategori" required>
        <option value="">-- Pilih Kategori --</option>
        <?php while($kategori = mysqli_fetch_assoc($data_kategori)): ?>
          <option value="<?= $kategori['id_kategori'] ?>"><?= htmlspecialchars($kategori['nama_kategori']) ?></option>
        <?php endwhile; ?>
      </select>
    </div>

    <div class="mb-3">
      <label for="jumlah" class="form-label">Jumlah</label>
      <input type="number" class="form-control" id="jumlah" name="jumlah" required>
    </div>

    <div class="mb-3">
      <label for="keterangan" class="form-label">Keterangan</label>
      <textarea class="form-control" id="keterangan" name="keterangan" rows="3"></textarea>
    </div>

    <button type="submit" class="btn btn-success">Simpan</button>
    <a href="Tambah.php" class="btn btn-secondary">Kembali</a>
  </form>
</div>

</body>
</html>
