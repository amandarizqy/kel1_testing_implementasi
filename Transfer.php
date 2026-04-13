<?php
include 'database.php';

// Ambil daftar aset
$data_aset = mysqli_query($conn, "SELECT id_aset, nama_aset FROM aset");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $tanggal = $_POST['tanggal'];
  $dari_aset = $_POST['dari_aset'];
  $ke_aset = $_POST['ke_aset'];
  $jumlah = $_POST['jumlah'];
  $keterangan = $_POST['keterangan'];

  // Cegah transfer ke aset yang sama
  if ($dari_aset == $ke_aset) {
    echo "<script>alert('Transfer gagal: Aset asal dan tujuan tidak boleh sama.'); window.location='';</script>";
    exit;
  }

  // Simpan ke tabel transfer
  $query = "INSERT INTO transfer (tanggal, dari_aset, ke_aset, jumlah, keterangan) 
            VALUES ('$tanggal', '$dari_aset', '$ke_aset', '$jumlah', '$keterangan')";

  if (mysqli_query($conn, $query)) {
    // Kurangi saldo dari_aset
    mysqli_query($conn, "UPDATE aset SET saldo = saldo - $jumlah WHERE id_aset = $dari_aset");
    // Tambah saldo ke_aset
    mysqli_query($conn, "UPDATE aset SET saldo = saldo + $jumlah WHERE id_aset = $ke_aset");

    echo "<script>alert('Transfer berhasil disimpan.'); window.location='Tambah.php';</script>";
  } else {
    echo "Error: " . $query . "<br>" . mysqli_error($conn);
  }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Input Transfer</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
  <h2 class="text-center mb-4">Form Transfer</h2>
  <form method="POST" action="">
    <div class="mb-3">
      <label for="tanggal" class="form-label">Tanggal</label>
      <input type="date" class="form-control" id="tanggal" name="tanggal" required>
    </div>

    <div class="mb-3">
      <label for="dari_aset" class="form-label">Dari Aset</label>
      <select class="form-control" id="dari_aset" name="dari_aset" required>
        <option value="">-- Pilih Aset Sumber --</option>
        <?php mysqli_data_seek($data_aset, 0); while ($row = mysqli_fetch_assoc($data_aset)): ?>
          <option value="<?= $row['id_aset'] ?>"><?= htmlspecialchars($row['nama_aset']) ?></option>
        <?php endwhile; ?>
      </select>
    </div>

    <div class="mb-3">
      <label for="ke_aset" class="form-label">Ke Aset</label>
      <select class="form-control" id="ke_aset" name="ke_aset" required>
        <option value="">-- Pilih Aset Tujuan --</option>
        <?php mysqli_data_seek($data_aset, 0); while ($row = mysqli_fetch_assoc($data_aset)): ?>
          <option value="<?= $row['id_aset'] ?>"><?= htmlspecialchars($row['nama_aset']) ?></option>
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

    <button type="submit" class="btn btn-warning text-white">Simpan</button>
    <a href="Tambah.php" class="btn btn-secondary">Kembali</a>
  </form>
</div>

</body>
</html>
