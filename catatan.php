<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catatan Keuangan - API Version</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .nav-link.active { font-weight: bold; color: #0d6efd !important; }
        footer { margin-top: 50px; text-align: center; color: #888; }
        .table-responsive { min-height: 200px; }
    </style>
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">Manajemen</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link active" href="catatan.php">Catatan</a></li>
                <li class="nav-item"><a class="nav-link" href="Tambah.php">Tambah</a></li>
                <li class="nav-item"><a class="nav-link" href="Kategori.php">Kategori</a></li>
                <li class="nav-item"><a class="nav-link" href="Aset.php">Aset</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container my-3">
    <h2 class="text-center mb-4">Ringkasan Keuangan</h2>

    <div id="alert-container"></div>

    <form id="filterForm" class="card p-2 shadow-sm mx-auto mb-4" style="max-width: 500px;">
        <div class="row g-2 align-items-end">
            <div class="col-4">
                <label for="bulan" class="form-label small mb-1">Bulan:</label>
                <select id="bulan" class="form-select form-select-sm">
                    <?php 
                    $currMonth = date('m');
                    for ($i = 1; $i <= 12; $i++): 
                        $val = str_pad($i, 2, '0', STR_PAD_LEFT);
                    ?>
                        <option value="<?= $val ?>" <?= ($val == $currMonth) ? 'selected' : '' ?>>
                            <?= date('F', mktime(0, 0, 0, $i, 1)) ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="col-4">
                <label for="tahun" class="form-label small mb-1">Tahun:</label>
                <select id="tahun" class="form-select form-select-sm">
                    <?php 
                    $currYear = date('Y');
                    for ($y = 2022; $y <= $currYear; $y++): ?>
                        <option value="<?= $y ?>" <?= ($y == $currYear) ? 'selected' : '' ?>><?= $y ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="col-4">
                <button type="submit" class="btn btn-primary btn-sm w-100">Filter</button>
            </div>
        </div>
    </form>

    <h4 class="mt-4">Pendapatan</h4>
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
            <tbody id="tabel-pendapatan">
                </tbody>
        </table>
    </div>

    <h4 class="mt-4">Pengeluaran</h4>
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
            <tbody id="tabel-pengeluaran">
                </tbody>
        </table>
    </div>

    <h4 class="mt-4">Transfer</h4>
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
            <tbody id="tabel-transfer">
                </tbody>
        </table>
    </div>
</div>

<footer class="mt-5 mb-3">
    <p>&copy; 2026 - Created by Kelompok 4</p>
</footer>

<script>
// 1. Fungsi Utama Ambil Data dari API
async function loadAllData() {
    const bulan = document.getElementById('bulan').value;
    const tahun = document.getElementById('tahun').value;
    
    // Tampilkan loading sederhana
    document.querySelectorAll('tbody').forEach(tb => tb.innerHTML = '<tr><td colspan="6" class="text-center">Memuat...</td></tr>');

    try {
        const response = await fetch(`api_catatan.php?bulan=${bulan}&tahun=${tahun}`);
        const result = await response.json();

        if (result.status === 'success') {
            renderPendapatan(result.data.pendapatan);
            renderPengeluaran(result.data.pengeluaran);
            renderTransfer(result.data.transfer);
        }
    } catch (error) {
        console.error("Fetch Error:", error);
        showAlert("Gagal mengambil data dari server", "danger");
    }
}

// 2. Fungsi Render Tabel Pendapatan
function renderPendapatan(data) {
    const tbody = document.getElementById('tabel-pendapatan');
    tbody.innerHTML = data.length ? '' : '<tr><td colspan="5" class="text-center">Tidak ada data.</td></tr>';
    
    data.forEach(item => {
        tbody.innerHTML += `
            <tr>
                <td>${item.tanggal}</td>
                <td>${item.keterangan}</td>
                <td>${item.nama_kategori}</td>
                <td>Rp${parseInt(item.jumlah).toLocaleString('id-ID')}</td>
                <td>
                    <button onclick="hapusData(${item.id_pendapatan}, 'hapus_pendapatan')" class="btn btn-sm btn-danger">Hapus</button>
                </td>
            </tr>`;
    });
}

// 3. Fungsi Render Tabel Pengeluaran
function renderPengeluaran(data) {
    const tbody = document.getElementById('tabel-pengeluaran');
    tbody.innerHTML = data.length ? '' : '<tr><td colspan="5" class="text-center">Tidak ada data.</td></tr>';
    
    data.forEach(item => {
        tbody.innerHTML += `
            <tr>
                <td>${item.tanggal}</td>
                <td>${item.keterangan}</td>
                <td>${item.nama_kategori}</td>
                <td>Rp${parseInt(item.jumlah).toLocaleString('id-ID')}</td>
                <td>
                    <button onclick="hapusData(${item.id_pengeluaran}, 'hapus_pengeluaran')" class="btn btn-sm btn-danger">Hapus</button>
                </td>
            </tr>`;
    });
}

// 4. Fungsi Render Tabel Transfer
function renderTransfer(data) {
    const tbody = document.getElementById('tabel-transfer');
    tbody.innerHTML = data.length ? '' : '<tr><td colspan="6" class="text-center">Tidak ada data.</td></tr>';
    
    data.forEach(item => {
        tbody.innerHTML += `
            <tr>
                <td>${item.tanggal}</td>
                <td>${item.dari}</td>
                <td>${item.ke}</td>
                <td>Rp${parseInt(item.jumlah).toLocaleString('id-ID')}</td>
                <td>${item.keterangan}</td>
                <td>
                    <button onclick="hapusData(${item.id_transfer}, 'hapus_transfer')" class="btn btn-sm btn-danger">Hapus</button>
                </td>
            </tr>`;
    });
}

// 5. Fungsi Hapus Data via API
async function hapusData(id, action) {
    if (!confirm('Yakin ingin menghapus?')) return;

    const formData = new FormData();
    formData.append('id', id);
    formData.append('action', action);

    try {
        const response = await fetch('api_catatan.php', {
            method: 'POST',
            body: formData
        });
        const result = await response.json();
        
        if (result.status === 'success') {
            showAlert(result.message, "success");
            loadAllData(); // Refresh data tanpa reload halaman
        }
    } catch (error) {
        showAlert("Gagal menghapus data", "danger");
    }
}

// 6. Fungsi Alert
function showAlert(msg, type) {
    const container = document.getElementById('alert-container');
    container.innerHTML = `
        <div class="alert alert-${type} alert-dismissible fade show">
            ${msg}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>`;
}

// Event Listeners
document.getElementById('filterForm').addEventListener('submit', (e) => {
    e.preventDefault();
    loadAllData();
});

document.addEventListener('DOMContentLoaded', loadAllData);
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>