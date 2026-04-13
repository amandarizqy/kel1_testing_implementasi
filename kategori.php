<?php
include 'database.php';

// Tambah kategori
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama_kategori'];
    $jenis = $_POST['jenis'];

    if ($jenis === 'Pendapatan') {
        mysqli_query($conn, "INSERT INTO kategori_pendapatan (nama_kategori) VALUES ('$nama')");
    } elseif ($jenis === 'Pengeluaran') {
        mysqli_query($conn, "INSERT INTO kategori_pengeluaran (nama_kategori) VALUES ('$nama')");
    }

    header("Location: Kategori.php");
    exit;
}

// Hapus kategori
if (isset($_GET['hapus']) && isset($_GET['id_kategori']) && isset($_GET['jenis'])) {
    $id_kategori = intval($_GET['id_kategori']);
    $jenis = $_GET['jenis'];

    if ($jenis == 'Pendapatan') {
        mysqli_query($conn, "DELETE FROM kategori_pendapatan WHERE id_kategori = $id_kategori");
    } elseif ($jenis == 'Pengeluaran') {
        mysqli_query($conn, "DELETE FROM kategori_pengeluaran WHERE id_kategori = $id_kategori");
    }

    header("Location: Kategori.php");
    exit;
}

// Ambil semua data kategori
$data = [];

$q1 = mysqli_query($conn, "SELECT id_kategori, nama_kategori FROM kategori_pendapatan");
while ($row = mysqli_fetch_assoc($q1)) {
    $data[] = [
        'id_kategori' => $row['id_kategori'],
        'nama_kategori' => $row['nama_kategori'],
        'jenis' => 'Pendapatan'
    ];
}

$q2 = mysqli_query($conn, "SELECT id_kategori, nama_kategori FROM kategori_pengeluaran");
while ($row = mysqli_fetch_assoc($q2)) {
    $data[] = [
        'id_kategori' => $row['id_kategori'],
        'nama_kategori' => $row['nama_kategori'],
        'jenis' => 'Pengeluaran'
    ];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Kategori</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .nav-link.active {
            font-weight: bold;
            color: #0d6efd !important;
        }
        .nav-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
      <div class="container">
        <a class="navbar-brand fw-bold" href="#">Manajemen</a>
        <div class="collapse navbar-collapse">
          <ul class="navbar-nav ms-auto">
            <li class="nav-item"><a class="nav-link" href="Catatan.php">Catatan</a></li>
            <li class="nav-item"><a class="nav-link" href="Tambah.php">Tambah</a></li>
            <li class="nav-item"><a class="nav-link active" href="Kategori.php">Kategori</a></li>
            <li class="nav-item"><a class="nav-link" href="Aset.php">Aset</a></li>
          </ul>
        </div>
      </div>
    </nav>

    <!-- Form Tambah Kategori -->
    <div class="container my-3">
        <h2 class="text-center mb-3">Tambah Kategori</h2>
        <form method="POST" action="Kategori.php" class="card p-3 shadow-sm mx-auto" style="max-width: 700px;">
            <div class="row g-2 align-items-end">
                <div class="col-md-5">
                    <label for="nama_kategori" class="form-label small mb-1">Nama Kategori</label>
                    <input type="text" name="nama_kategori" id="nama_kategori" class="form-control form-control-sm" required>
                </div>
                <div class="col-md-4">
                    <label for="jenis" class="form-label small mb-1">Jenis</label>
                    <select name="jenis" id="jenis" class="form-select form-select-sm" required>
                        <option value="Pendapatan">Pendapatan</option>
                        <option value="Pengeluaran">Pengeluaran</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small mb-1 invisible">Tambah</label>
                    <button type="submit" class="btn btn-primary btn-sm w-100">Tambah Kategori</button>
                </div>
            </div>
        </form>
    </div>


        <!-- Tabel Data -->
        <div class="container my-4">  <!-- <- container memberi padding horiz -->
            <h3 class="text-center mb-3">Data Kategori</h3>

            <div class="table-responsive px-2 px-md-4"> <!-- extra padding jika perlu -->
                <table class="table table-bordered table-striped shadow-sm bg-white">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Nama Kategori</th>
                            <th>Jenis</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        foreach ($data as $row) {
                            $id    = $row['id_kategori'];
                            $nama  = htmlspecialchars($row['nama_kategori']);
                            $jenis = $row['jenis'];
                            $badge = ($jenis === 'Pendapatan') ? 'success' : 'danger';
                            echo "<tr>
                                    <td>{$no}</td>
                                    <td>{$nama}</td>
                                    <td><span class='badge bg-{$badge}'>{$jenis}</span></td>
                                    <td>
                                        <a href='EditKategori.php?id_kategori={$id}&jenis={$jenis}' class='btn btn-sm btn-warning me-1'>Edit</a>
                                        <a href='Kategori.php?hapus=true&id_kategori={$id}&jenis={$jenis}' class='btn btn-sm btn-danger' onclick=\"return confirm('Yakin ingin menghapus?')\">Hapus</a>
                                    </td>
                                </tr>";
                            $no++;
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <footer class="mt-5 mb-3 text-center">
            <p>&copy; Created by Kelompok 4</p>
        </footer>

</body>
</html>

