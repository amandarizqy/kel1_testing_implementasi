<?php
session_start();
if (!isset($_SESSION['id_pengguna'])) {
    header("Location: login.php");
    exit;
}

// Tangkap data dari URL (dikirim dari tombol edit di Kategori.php)
$id = $_GET['id'] ?? '';
$nama = $_GET['nama'] ?? '';
$jenis = $_GET['jenis'] ?? '';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Kategori</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container py-5">
        <div class="card shadow-sm mx-auto p-4" style="max-width: 500px; border-radius: 15px;">
            <h4 class="text-center mb-4">Edit Kategori</h4>
            
            <form id="formEditKategori">
                <input type="hidden" id="edit_id" value="<?= htmlspecialchars($id) ?>">
                <input type="hidden" id="old_jenis" value="<?= htmlspecialchars($jenis) ?>">

                <div class="mb-3">
                    <label class="form-label">Nama Kategori</label>
                    <input type="text" id="edit_nama" class="form-control" value="<?= htmlspecialchars($nama) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Jenis (Tetap)</label>
                    <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($jenis) ?>" readonly>
                    <small class="text-muted">Jenis kategori tidak dapat diubah.</small>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="Kategori.php" class="btn btn-outline-secondary">Batal</a>
                    <button type="submit" class="btn btn-warning">Perbarui Kategori</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('formEditKategori').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const fd = new FormData();
            fd.append('action', 'update');
            fd.append('id_kategori', document.getElementById('edit_id').value);
            fd.append('nama_kategori', document.getElementById('edit_nama').value);
            fd.append('jenis', document.getElementById('old_jenis').value);

            try {
                const res = await fetch('api_kategori.php', { method: 'POST', body: fd });
                const result = await res.json();
                
                if (result.status === 'success') {
                    alert(result.message);
                    window.location.href = 'Kategori.php';
                } else {
                    alert(result.message);
                }
            } catch (err) {
                alert("Terjadi kesalahan sistem.");
            }
        });
    </script>
</body>
</html>