<?php session_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Input Pendapatan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">Manajemen</a>
        <div class="ms-auto text-white small">
            <i class="bi bi-person-circle"></i> <?php echo $_SESSION['nama']; ?>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <div class="card shadow-sm mx-auto p-4" style="max-width: 600px; border-radius: 15px;">
        <h2 class="text-center mb-4">Form Pendapatan</h2>
        <div id="alertPlaceholder"></div>
        
        <form id="formPendapatan">
            <div class="mb-3">
                <label class="form-label">Tanggal</label>
                <input type="date" class="form-control" name="tanggal" value="<?php echo date('Y-m-d'); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Pilih Aset</label>
                <select class="form-select" id="selectAset" name="aset" required>
                    <option value="">-- Memuat Aset... --</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Pilih Kategori</label>
                <select class="form-select" id="selectKategori" name="kategori" required>
                    <option value="">-- Memuat Kategori... --</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Jumlah (Rp)</label>
                <input type="number" class="form-control" name="jumlah" required placeholder="0">
            </div>

            <div class="mb-3">
                <label class="form-label">Keterangan</label>
                <textarea class="form-control" name="keterangan" rows="3"></textarea>
            </div>

            <div class="d-flex justify-content-between">
                <a href="Tambah.php" class="btn btn-secondary">Kembali</a>
                <button type="submit" class="btn btn-success px-4">Simpan Pendapatan</button>
            </div>
        </form>
    </div>
</div>

<script>
// 1. Ambil pilihan aset dan kategori saat halaman dimuat
async function loadOptions() {
    const res = await fetch('api_pendapatan.php');
    const data = await res.json();

    if (data.status === 'success') {
        const asetSelect = document.getElementById('selectAset');
        const katSelect = document.getElementById('selectKategori');

        asetSelect.innerHTML = '<option value="">-- Pilih Aset --</option>';
        data.aset.forEach(a => {
            asetSelect.innerHTML += `<option value="${a.id_aset}">${a.nama_aset}</option>`;
        });

        katSelect.innerHTML = '<option value="">-- Pilih Kategori --</option>';
        data.kategori.forEach(k => {
            katSelect.innerHTML += `<option value="${k.id_kategori}">${k.nama_kategori}</option>`;
        });
    }
}

// 2. Proses Simpan
document.getElementById('formPendapatan').addEventListener('submit', async (e) => {
    e.preventDefault();
    const fd = new FormData(e.target);

    const res = await fetch('api_pendapatan.php', { method: 'POST', body: fd });
    const result = await res.json();

    if (result.status === 'success') {
        alert(result.message);
        window.location.href = 'Tambah.php';
    } else {
        document.getElementById('alertPlaceholder').innerHTML = `
            <div class="alert alert-danger">${result.message}</div>`;
    }
});

document.addEventListener('DOMContentLoaded', loadOptions);
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>