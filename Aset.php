<?php
session_start();
// Jika belum login, tendang kembali ke halaman login
if (!isset($_SESSION['id_pengguna'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Aset - API Mode</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .nav-link.active { font-weight: bold; color: #0d6efd !important; }
        .aside-box { background-color: #eaf4ff; border-radius: 10px; padding: 20px; }
        footer { margin-top: 50px; }
        footer { margin-top: 50px; text-align: center; color: #888; }
        .table-responsive { min-height: 200px; }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
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
                <li class="nav-item"><a class="nav-link" href="Tambah.php">Tambah</a></li>
                <li class="nav-item"><a class="nav-link" href="Kategori.php">Kategori</a></li>
                <li class="nav-item"><a class="nav-link active" href="Aset.php">Aset</a></li>
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

<div class="container my-5">
    <div id="alert-container"></div>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Data Aset</h2>
        <a href="TambahAset.php" class="btn btn-primary">+ Tambah Aset</a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="table-responsive">
                <table class="table table-bordered table-striped bg-white shadow-sm">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Nama Aset</th>
                            <th>Saldo</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tabel-aset">
                        </tbody>
                </table>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="aside-box shadow-sm">
                <h5 class="fw-bold mb-2">💡 Penting</h5>
                <p class="mb-0">Jangan kebanyakan jajan ya! Kelola asetmu dengan bijak 💰</p>
            </div>
        </div>
    </div>
</div>

<footer class="bg-white text-center py-3 shadow-sm">
    <p class="mb-0 text-muted">&copy; Created by Kelompok 4</p>
</footer>

<script>
// 1. Fungsi untuk Load Data dari API
async function fetchAset() {
    const tbody = document.getElementById('tabel-aset');
    tbody.innerHTML = '<tr><td colspan="5" class="text-center">Memuat data...</td></tr>';

    try {
        const response = await fetch('api_aset.php');
        const result = await response.json();

        if (result.status === 'success') {
            tbody.innerHTML = '';
            if (result.data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" class="text-center">Belum ada data aset.</td></tr>';
                return;
            }

            result.data.forEach((row, index) => {
                const formattedSaldo = new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(row.saldo);

                tbody.innerHTML += `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${row.nama_aset}</td>
                        <td>${formattedSaldo}</td>
                        <td>${row.keterangan || '-'}</td>
                        <td>
                            <a href="EditAset.php?id_aset=${row.id_aset}" class="btn btn-warning btn-sm">Edit</a>
                            <button onclick="hapusAset(${row.id_aset})" class="btn btn-danger btn-sm">Hapus</button>
                        </td>
                    </tr>`;
            });
        }
    } catch (error) {
        console.error("Error fetching data:", error);
        tbody.innerHTML = '<tr><td colspan="5" class="text-center text-danger">Gagal mengambil data dari server.</td></tr>';
    }
}

// 2. Fungsi untuk Hapus Aset via API
async function hapusAset(id) {
    if (!confirm('Yakin ingin menghapus aset ini?')) return;

    const formData = new FormData();
    formData.append('id_aset', id);
    formData.append('action', 'hapus');

    try {
        const response = await fetch('api_aset.php', {
            method: 'POST',
            body: formData
        });
        const result = await response.json();

        if (result.status === 'success') {
            alert(result.message);
            fetchAset(); // Refresh tabel setelah hapus
        } else {
            alert(result.message);
        }
    } catch (error) {
        console.error("Error:", error);
        alert("Gagal menghubungi server.");
    }
}

// Jalankan fungsi saat halaman dimuat
document.addEventListener('DOMContentLoaded', fetchAset);
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>