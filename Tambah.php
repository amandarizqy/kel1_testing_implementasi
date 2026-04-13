<?php include 'database.php'; ?>

<?php
// Cek apakah tabel aset dan kategori sudah memiliki data
$cek_aset = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM aset"));
$cek_kat_pendapatan = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM kategori_pendapatan"));
$cek_kat_pengeluaran = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM kategori_pengeluaran"));

$siap_pendapatan = $cek_aset > 0 && $cek_kat_pendapatan > 0;
$siap_pengeluaran = $cek_aset > 0 && $cek_kat_pengeluaran > 0;
$siap_transfer = $cek_aset > 1; // karena transfer perlu minimal 2 aset
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Transaksi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .nav-link.active {
            font-weight: bold;
            color: #0d6efd !important;
        }
        footer {
            margin-top: 100px;
        }
    </style>
</head>
<body class="bg-light">

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">Manajemen</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="Catatan.php">Catatan</a></li>
                <li class="nav-item"><a class="nav-link active" href="Tambah.php">Tambah</a></li>
                <li class="nav-item"><a class="nav-link" href="Kategori.php">Kategori</a></li>
                <li class="nav-item"><a class="nav-link" href="Aset.php">Aset</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- ALERT + TOMBOL -->
<div class="container mt-4">
    <?php if (!$siap_pendapatan): ?>
        <div class="alert alert-warning">
            Silakan isi <strong>Aset</strong> dan <strong>Kategori Pendapatan</strong> terlebih dahulu sebelum menambahkan Pendapatan.
        </div>
    <?php endif; ?>

    <?php if (!$siap_pengeluaran): ?>
        <div class="alert alert-warning">
            Silakan isi <strong>Aset</strong> dan <strong>Kategori Pengeluaran</strong> terlebih dahulu sebelum menambahkan Pengeluaran.
        </div>
    <?php endif; ?>

    <?php if (!$siap_transfer): ?>
        <div class="alert alert-warning">
            Silakan isi minimal <strong>2 Aset</strong> terlebih dahulu sebelum melakukan Transfer.
        </div>
    <?php endif; ?>

    <?php if (!$siap_pendapatan || !$siap_pengeluaran || !$siap_transfer): ?>
        <div class="d-flex gap-3 mt-3">
            <a href="TambahAset.php" class="btn btn-primary">+ Tambah Aset</a>
            <a href="Kategori.php" class="btn btn-secondary">Kelola Kategori</a>
        </div>
    <?php endif; ?>
</div>

<!-- BUTTON -->
<div class="container my-5 text-center">
    <h2 class="mb-4">Tambah Transaksi</h2>

    <div class="d-grid gap-3 mx-auto" style="max-width: 300px;">
        <a href="<?= $siap_pendapatan ? 'Pendapatan.php' : '#' ?>" class="btn btn-success btn-lg py-3 <?= !$siap_pendapatan ? 'disabled' : '' ?>">Pendapatan</a>
        <a href="<?= $siap_pengeluaran ? 'Pengeluaran.php' : '#' ?>" class="btn btn-danger btn-lg py-3 <?= !$siap_pengeluaran ? 'disabled' : '' ?>">Pengeluaran</a>
        <a href="<?= $siap_transfer ? 'Transfer.php' : '#' ?>" class="btn btn-warning btn-lg py-3 text-white <?= !$siap_transfer ? 'disabled' : '' ?>">Transfer</a>
    </div>
</div>

<!-- FOOTER -->
<footer class="bg-white text-center py-3 shadow-sm">
    <p class="mb-0 text-muted">&copy; Created by Kelompok 4</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
