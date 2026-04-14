<?php session_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Input Pengeluaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">Manajemen</a>
        <div class="ms-auto text-white small">
            <i class="bi bi-person-circle"></i> Halo, <b><?php echo $_SESSION['nama']; ?></b>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <div class="card shadow-sm mx-auto p-4" style="max-width: 600px; border-radius: 15px; border-top: 5px solid #dc3545;">
        <h2 class="text-center mb-4 text-danger">Form Pengeluaran</h2>
        <div id="alertPlaceholder"></div>
        
        <form id="formPengeluaran">
            <div class="mb-3">
                <label class="form-label">Tanggal</label>
                <input type="date" class="form-control" name="tanggal" value="<?php echo date('Y-m-d'); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Pilih Aset (Sumber Dana)</label>
                <select class="form-select" id="selectAset" name="aset" required>
                    <option value="">-- Memuat Aset... --</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Kategori Pengeluaran</label>
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
                <textarea class="form-control" name="keterangan" rows="2"></textarea>
            </div>

            <div class="d-flex justify-content-between pt-3">
                <a href="Tambah.php" class="btn btn-outline-secondary">Kembali</a>
                <button type="submit" class="btn btn-danger px-4">Simpan Pengeluaran</button>
            </div>
        </form>
    </div>
</div>

<script>
async function loadOptions() {
    try {
        const res = await fetch('api_pengeluaran.php');
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
    } catch (e) {
        console.error("Gagal memuat opsi");
    }
}

document.getElementById('formPengeluaran').addEventListener('submit', async (e) => {
    e.preventDefault();
    const fd = new FormData(e.target);

    const res = await fetch('api_pengeluaran.php', { method: 'POST', body: fd });
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