<?php
session_start();

// Proteksi: Jika tidak ada session, lempar ke login
if (!isset($_SESSION['id_pengguna'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Transaksi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .nav-link.active { font-weight: bold; color: #0d6efd !important; }
        footer { margin-top: 50px; text-align: center; color: #888; }
        .table-responsive { min-height: 200px; }
    </style>
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">Manajemen</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="Catatan.php">Catatan</a></li>
                <li class="nav-item"><a class="nav-link active" href="Tambah.php">Tambah</a></li>
                <li class="nav-item"><a class="nav-link" href="Kategori.php">Kategori</a></li>
                <li class="nav-item"><a class="nav-link" href="Aset.php">Aset</a></li>
            </ul>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle active" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle"></i> Halo, <?php echo htmlspecialchars($_SESSION['nama']); ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item text-danger" href="logout.php">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4" id="alertContainer"></div>

<div class="container my-5 text-center">
    <h2 class="mb-4">Tambah Transaksi</h2>
    <div class="d-grid gap-3 mx-auto" style="max-width: 300px;">
        <a id="btn-pendapatan" href="Pendapatan.php" class="btn btn-success btn-lg py-3 disabled">Pendapatan</a>
        <a id="btn-pengeluaran" href="Pengeluaran.php" class="btn btn-danger btn-lg py-3 disabled">Pengeluaran</a>
        <a id="btn-transfer" href="Transfer.php" class="btn btn-warning btn-lg py-3 text-white disabled">Transfer</a>
    </div>
    
    <div id="btnAksiCepat" class="mt-4" style="display:none;">
        <div class="d-flex justify-content-center gap-2">
            <a href="TambahAset.php" class="btn btn-sm btn-outline-primary">+ Aset</a>
            <a href="Kategori.php" class="btn btn-sm btn-outline-secondary">Kelola Kategori</a>
        </div>
    </div>
</div>

<footer class="bg-white text-center py-3 shadow-sm">
    <p class="mb-0 text-muted">&copy; Created by Kelompok 4</p>
</footer>

<script>
async function cekKesiapan() {
    try {
        const response = await fetch('api_cek_kesiapan.php');
        const data = await response.json();

        if (data.status === 'success') {
            const container = document.getElementById('alertContainer');
            let alerts = '';
            let butuhAksi = false;

            if (data.siap_pendapatan) {
                document.getElementById('btn-pendapatan').classList.remove('disabled');
            } else {
                alerts += `<div class="alert alert-warning small py-2">Lengkapi <b>Aset</b> & <b>Kategori Pendapatan</b>.</div>`;
                butuhAksi = true;
            }

            if (data.siap_pengeluaran) {
                document.getElementById('btn-pengeluaran').classList.remove('disabled');
            } else {
                alerts += `<div class="alert alert-warning small py-2">Lengkapi <b>Aset</b> & <b>Kategori Pengeluaran</b>.</div>`;
                butuhAksi = true;
            }

            if (data.siap_transfer) {
                document.getElementById('btn-transfer').classList.remove('disabled');
            } else {
                alerts += `<div class="alert alert-warning small py-2">Butuh minimal <b>2 Aset</b> untuk Transfer.</div>`;
                butuhAksi = true;
            }

            container.innerHTML = alerts;
            if (butuhAksi) document.getElementById('btnAksiCepat').style.display = 'block';
        }
    } catch (e) {
        console.error("Gagal mengecek kesiapan data");
    }
}

document.addEventListener('DOMContentLoaded', cekKesiapan);
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>