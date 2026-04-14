<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Aset - Modern UI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .loading-spinner { display: none; }
        .card { border-radius: 15px; border: none; }
    </style>
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">Manajemen Keuangan</a>
    </div>
</nav>

<div class="container py-5">
    <div class="card shadow-sm mx-auto p-4" style="max-width: 800px;">
        <h4 class="mb-4 text-center">Tambah Aset Baru</h4>

        <div id="alertPlaceholder"></div>

        <form id="formAset">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nama Aset</label>
                    <input type="text" class="form-control" name="nama_aset" placeholder="Contoh: Tabungan Bank, E-Wallet" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Saldo Awal (Rp)</label>
                    <input type="number" class="form-control" name="saldo" value="0" required>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Keterangan</label>
                    <textarea class="form-control" name="keterangan" rows="2" placeholder="Catatan tambahan (Opsional)"></textarea>
                </div>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="Aset.php" class="btn btn-outline-secondary px-4">Kembali</a>
                <button type="submit" class="btn btn-primary px-4" id="btnSimpan">
                    <span class="spinner-border spinner-border-sm loading-spinner" id="spinner"></span>
                    Simpan Aset
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('formAset').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const btn = document.getElementById('btnSimpan');
    const spinner = document.getElementById('spinner');
    const alertPlaceholder = document.getElementById('alertPlaceholder');

    // UI Loading State
    btn.disabled = true;
    spinner.style.display = 'inline-block';
    alertPlaceholder.innerHTML = '';

    const formData = new FormData(this);

    try {
        const response = await fetch('api_tambah_aset.php', {
            method: 'POST',
            body: formData
        });

        if (!response.ok) throw new Error("Gagal menghubungi server.");

        const data = await response.json();

        if(data.success) {
            alertPlaceholder.innerHTML = `
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Berhasil!</strong> ${data.message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>`;
            document.getElementById('formAset').reset(); 
        } else {
            throw new Error(data.message);
        }
    } catch (error) {
        alertPlaceholder.innerHTML = `
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Gagal!</strong> ${error.message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>`;
    } finally {
        btn.disabled = false;
        spinner.style.display = 'none';
    }
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>