<?php
include 'database.php';

// Proses hapus aset
if (isset($_POST['hapus_aset'])) {
    $id = $_POST['hapus_aset'];
    mysqli_query($conn, "DELETE FROM aset WHERE id_aset = '$id'");
    echo "<script>alert('Aset berhasil dihapus!'); window.location='Aset.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Aset</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .nav-link.active {
            font-weight: bold;
            color: #0d6efd !important;
        }
        .aside-box {
            background-color: #eaf4ff;
            border-radius: 10px;
            padding: 20px;
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
                <li class="nav-item"><a class="nav-link" href="Tambah.php">Tambah</a></li>
                <li class="nav-item"><a class="nav-link" href="Kategori.php">Kategori</a></li>
                <li class="nav-item"><a class="nav-link active" href="Aset.php">Aset</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- HEADER -->
<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Data Aset</h2>
        <a href="TambahAset.php" class="btn btn-primary">+ Tambah Aset</a>
    </div>

    <div class="row">
        <!-- TABEL -->
        <div class="col-lg-8">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Nama Aset</th>
                            <th>Saldo</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $query = "SELECT * FROM aset";
                    $result = mysqli_query($conn, $query);
                    $no = 1;

                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>
                                    <td>" . $no++ . "</td>
                                    <td>" . htmlspecialchars($row['nama_aset']) . "</td>
                                    <td>Rp " . number_format($row['saldo'], 0, ',', '.') . "</td>
                                    <td>" . htmlspecialchars($row['keterangan']) . "</td>
                                    <td>
                                        <a href='EditAset.php?id_aset=" . $row['id_aset'] . "' class='btn btn-warning btn-sm'>Edit</a>
                                        <form method='POST' class='d-inline' onsubmit=\"return confirm('Yakin ingin menghapus aset ini?')\">
                                            <input type='hidden' name='hapus_aset' value='" . $row['id_aset'] . "'>
                                            <button type='submit' class='btn btn-danger btn-sm'>Hapus</button>
                                        </form>
                                    </td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5' class='text-center'>Belum ada data aset.</td></tr>";
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- SIDEBAR -->
        <div class="col-lg-4">
            <div class="aside-box shadow-sm">
                <h5 class="fw-bold mb-2">💡 Penting</h5>
                <p class="mb-0">Jangan kebanyakan jajan ya! Kelola asetmu dengan bijak 💰</p>
            </div>
        </div>
    </div>
</div>

<!-- FOOTER -->
<footer class="bg-white text-center py-3 shadow-sm">
    <p class="mb-0 text-muted">&copy; Created by Kelompok 4</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
