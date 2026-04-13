<?php
include 'database.php';

$pesan = '';

// Hapus Pendapatan
if (isset($_POST['hapus_pendapatan'])) {
    $id = intval($_POST['hapus_pendapatan']);
    $q = mysqli_query($conn, "SELECT id_aset, jumlah FROM pendapatan WHERE id_pendapatan = $id");
    if ($row = mysqli_fetch_assoc($q)) {
        mysqli_query($conn, "UPDATE aset SET saldo = saldo - {$row['jumlah']} WHERE id_aset = {$row['id_aset']}");
    }
    mysqli_query($conn, "DELETE FROM pendapatan WHERE id_pendapatan = $id");
    header("Location: " . $_SERVER['PHP_SELF'] . "?bulan={$_GET['bulan']}&tahun={$_GET['tahun']}&pesan=sukses");
    exit;
}

// Hapus Pengeluaran
if (isset($_POST['hapus_pengeluaran'])) {
    $id = intval($_POST['hapus_pengeluaran']);
    $q = mysqli_query($conn, "SELECT id_aset, jumlah FROM pengeluaran WHERE id_pengeluaran = $id");
    if ($row = mysqli_fetch_assoc($q)) {
        mysqli_query($conn, "UPDATE aset SET saldo = saldo + {$row['jumlah']} WHERE id_aset = {$row['id_aset']}");
    }
    mysqli_query($conn, "DELETE FROM pengeluaran WHERE id_pengeluaran = $id");
    header("Location: " . $_SERVER['PHP_SELF'] . "?bulan={$_GET['bulan']}&tahun={$_GET['tahun']}&pesan=sukses");
    exit;
}

// Hapus Transfer
if (isset($_POST['hapus_transfer'])) {
    $id = intval($_POST['hapus_transfer']);
    $q = mysqli_query($conn, "SELECT dari_aset, ke_aset, jumlah FROM transfer WHERE id_transfer = $id");
    if ($row = mysqli_fetch_assoc($q)) {
        mysqli_query($conn, "UPDATE aset SET saldo = saldo + {$row['jumlah']} WHERE id_aset = {$row['dari_aset']}");
        mysqli_query($conn, "UPDATE aset SET saldo = saldo - {$row['jumlah']} WHERE id_aset = {$row['ke_aset']}");
    }
    mysqli_query($conn, "DELETE FROM transfer WHERE id_transfer = $id");
    header("Location: " . $_SERVER['PHP_SELF'] . "?bulan={$_GET['bulan']}&tahun={$_GET['tahun']}&pesan=sukses");
    exit;
}

// Filter
$bulan = isset($_GET['bulan']) ? $_GET['bulan'] : date('m');
$tahun = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');
$pesan = isset($_GET['pesan']) && $_GET['pesan'] === 'sukses' ? 'Data berhasil dihapus!' : '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Catatan Keuangan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .nav-link.active { font-weight: bold; color: #0d6efd !important; }
        footer { margin-top: 50px; text-align: center; color: #888; }
    </style>
</head>
<body class="bg-light">

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">Manajemen</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link active" href="Catatan.php">Catatan</a></li>
                <li class="nav-item"><a class="nav-link" href="Tambah.php">Tambah</a></li>
                <li class="nav-item"><a class="nav-link" href="Kategori.php">Kategori</a></li>
                <li class="nav-item"><a class="nav-link" href="Aset.php">Aset</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container my-3">
    <h2 class="text-center mb-4">Ringkasan Keuangan</h2>

    <?php if ($pesan): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $pesan ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- FILTER -->
    <form method="GET" class="card p-2 shadow-sm mx-auto mb-3" style="max-width: 500px;">
    <div class="row g-2 align-items-end">
        <div class="col-4">
            <label for="bulan" class="form-label small mb-1">Bulan:</label>
            <select name="bulan" id="bulan" class="form-select form-select-sm">
                <?php for ($i = 1; $i <= 12; $i++): ?>
                    <option value="<?= str_pad($i, 2, '0', STR_PAD_LEFT) ?>" <?= ($i == $bulan) ? 'selected' : '' ?>>
                        <?= date('F', mktime(0, 0, 0, $i, 1)) ?>
                    </option>
                <?php endfor; ?>
            </select>
        </div>
        <div class="col-4">
            <label for="tahun" class="form-label small mb-1">Tahun:</label>
            <select name="tahun" id="tahun" class="form-select form-select-sm">
                <?php for ($y = 2022; $y <= date('Y'); $y++): ?>
                    <option value="<?= $y ?>" <?= ($y == $tahun) ? 'selected' : '' ?>><?= $y ?></option>
                <?php endfor; ?>
            </select>
        </div>
        <div class="col-4">
            <label class="form-label small mb-1 invisible">Filter</label>
            <button type="submit" class="btn btn-primary btn-sm w-100">Filter</button>
        </div>
    </div>
    </form>




    <!-- PENDAPATAN -->
    <h4 class="mt-2">Pendapatan</h4>
    <div class="table-responsive">
        <table class="table table-bordered bg-white shadow-sm">
            <thead class="table-dark">
                <tr>
                    <th>Tanggal</th>
                    <th>Keterangan</th>
                    <th>Kategori</th>
                    <th>Jumlah</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $qPendapatan = mysqli_query($conn, "
                    SELECT p.id_pendapatan, p.tanggal, p.keterangan, p.jumlah, k.nama_kategori 
                    FROM pendapatan p
                    JOIN kategori_pendapatan k ON p.id_kategori = k.id_kategori
                    WHERE MONTH(p.tanggal) = '$bulan' AND YEAR(p.tanggal) = '$tahun'");
                while ($row = mysqli_fetch_assoc($qPendapatan)): ?>
                    <tr>
                        <td><?= $row['tanggal'] ?></td>
                        <td><?= htmlspecialchars($row['keterangan']) ?></td>
                        <td><?= htmlspecialchars($row['nama_kategori']) ?></td>
                        <td>Rp<?= number_format($row['jumlah'], 0, ',', '.') ?></td>
                        <td>
                            <a href="EditPendapatan.php?id=<?= $row['id_pendapatan'] ?>" class="btn btn-sm btn-warning">Edit</a>
                            <form method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus?')">
                                <input type="hidden" name="hapus_pendapatan" value="<?= $row['id_pendapatan'] ?>">
                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- PENGELUARAN -->
    <h4 class="mt-2">Pengeluaran</h4>
    <div class="table-responsive">
        <table class="table table-bordered bg-white shadow-sm">
            <thead class="table-dark">
                <tr>
                    <th>Tanggal</th>
                    <th>Keterangan</th>
                    <th>Kategori</th>
                    <th>Jumlah</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $qPengeluaran = mysqli_query($conn, "
                    SELECT p.id_pengeluaran, p.tanggal, p.keterangan, p.jumlah, k.nama_kategori 
                    FROM pengeluaran p
                    JOIN kategori_pengeluaran k ON p.id_kategori = k.id_kategori
                    WHERE MONTH(p.tanggal) = '$bulan' AND YEAR(p.tanggal) = '$tahun'");
                while ($row = mysqli_fetch_assoc($qPengeluaran)): ?>
                    <tr>
                        <td><?= $row['tanggal'] ?></td>
                        <td><?= htmlspecialchars($row['keterangan']) ?></td>
                        <td><?= htmlspecialchars($row['nama_kategori']) ?></td>
                        <td>Rp<?= number_format($row['jumlah'], 0, ',', '.') ?></td>
                        <td>
                            <a href="EditPengeluaran.php?id=<?= $row['id_pengeluaran'] ?>" class="btn btn-sm btn-warning">Edit</a>
                            <form method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus?')">
                                <input type="hidden" name="hapus_pengeluaran" value="<?= $row['id_pengeluaran'] ?>">
                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- TRANSFER -->
    <h4 class="mt-2">Transfer</h4>
    <div class="table-responsive">
        <table class="table table-bordered bg-white shadow-sm">
            <thead class="table-dark">
                <tr>
                    <th>Tanggal</th>
                    <th>Dari Aset</th>
                    <th>Ke Aset</th>
                    <th>Jumlah</th>
                    <th>Keterangan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $qTransfer = mysqli_query($conn, "
                    SELECT t.id_transfer, t.tanggal, a1.nama_aset AS dari, a2.nama_aset AS ke, t.jumlah, t.keterangan 
                    FROM transfer t 
                    JOIN aset a1 ON t.dari_aset = a1.id_aset 
                    JOIN aset a2 ON t.ke_aset = a2.id_aset
                    WHERE MONTH(t.tanggal) = '$bulan' AND YEAR(t.tanggal) = '$tahun'");
                while ($row = mysqli_fetch_assoc($qTransfer)): ?>
                    <tr>
                        <td><?= $row['tanggal'] ?></td>
                        <td><?= $row['dari'] ?></td>
                        <td><?= $row['ke'] ?></td>
                        <td>Rp<?= number_format($row['jumlah'], 0, ',', '.') ?></td>
                        <td><?= htmlspecialchars($row['keterangan']) ?></td>
                        <td>
                            <a href="EditTransfer.php?id=<?= $row['id_transfer'] ?>" class="btn btn-sm btn-warning">Edit</a>
                            <form method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus?')">
                                <input type="hidden" name="hapus_transfer" value="<?= $row['id_transfer'] ?>">
                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<footer class="mt-5 mb-3 text-center">
    <p>&copy; Created by Kelompok 4</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
