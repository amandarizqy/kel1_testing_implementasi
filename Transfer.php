<?php session_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Input Transfer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">Manajemen</a>
        <div class="ms-auto text-white small">
            <i class="bi bi-person-circle"></i> Halo, <b><?php echo htmlspecialchars($_SESSION['nama']); ?></b>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <div class="card shadow-sm mx-auto p-4" style="max-width: 600px; border-radius: 15px; border-top: 5px solid #ffc107;">
        <h2 class="text-center mb-4">Form Transfer</h2>
        <div id="alertPlaceholder"></div>

        <form id="formTransfer">
            <div class="mb-3">
                <label class="form-label">Tanggal</label>
                <input type="date" class="form-control" name="tanggal" value="<?php echo date('Y-m-d'); ?>" required>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Dari Aset (Sumber)</label>
                    <select class="form-select select-aset" name="dari_aset" required>
                        <option value="">-- Pilih --</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Ke Aset (Tujuan)</label>
                    <select class="form-select select-aset" name="ke_aset" required>
                        <option value="">-- Pilih --</option>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Jumlah (Rp)</label>
                <input type="number" class="form-control" name="jumlah" required placeholder="0">
            </div>

            <div class="mb-3">
                <label class="form-label">Keterangan</label>
                <textarea class="form-control" name="keterangan" rows="2" placeholder="Contoh: Pindah tabungan"></textarea>
            </div>

            <div class="d-flex justify-content-between pt-3">
                <a href="Tambah.php" class="btn btn-outline-secondary">Kembali</a>
                <button type="submit" class="btn btn-warning text-white px-4">Simpan Transfer</button>
            </div>
        </form>
    </div>
</div>

<script>
// Load Aset untuk kedua dropdown
async function loadAset() {
    try {
        const res = await fetch('api_transfer.php');
        const data = await res.json();

        if (data.status === 'success') {
            const selects = document.querySelectorAll('.select-aset');
            selects.forEach(select => {
                select.innerHTML = '<option value="">-- Pilih Aset --</option>';
                data.aset.forEach(a => {
                    select.innerHTML += `<option value="${a.id_aset}">${a.nama_aset}</option>`;
                });
            });
        }
    } catch (e) {
        console.error("Gagal memuat daftar aset");
    }
}

document.getElementById('formTransfer').addEventListener('submit', async (e) => {
    e.preventDefault();
    const fd = new FormData(e.target);

    const res = await fetch('api_transfer.php', { method: 'POST', body: fd });
    const result = await res.json();

    if (result.status === 'success') {
        alert(result.message);
        window.location.href = 'Tambah.php';
    } else {
        document.getElementById('alertPlaceholder').innerHTML = `
            <div class="alert alert-danger alert-dismissible fade show">
                ${result.message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>`;
    }
});

document.addEventListener('DOMContentLoaded', loadAset);
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>