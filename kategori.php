<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Kategori - API</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

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

    <div class="container my-4">
        <h2 class="text-center mb-3">Tambah Kategori</h2>
        <form id="formKategori" class="card p-3 shadow-sm mx-auto" style="max-width: 700px;">
            <div class="row g-2 align-items-end">
                <div class="col-md-5">
                    <label class="form-label small mb-1">Nama Kategori</label>
                    <input type="text" id="nama_kategori" class="form-control form-control-sm" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label small mb-1">Jenis</label>
                    <select id="jenis" class="form-select form-select-sm" required>
                        <option value="Pendapatan">Pendapatan</option>
                        <option value="Pengeluaran">Pengeluaran</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary btn-sm w-100">Simpan</button>
                </div>
            </div>
        </form>

        <h3 class="text-center my-4">Data Kategori</h3>
        <div class="table-responsive shadow-sm">
            <table class="table table-bordered table-striped bg-white text-center">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Nama Kategori</th>
                        <th>Jenis</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="tabel-kategori">
                    </tbody>
            </table>
        </div>
    </div>

    <script>
        // Ambil Data
        async function loadKategori() {
            const res = await fetch('api_kategori.php');
            const result = await res.json();
            const tbody = document.getElementById('tabel-kategori');
            tbody.innerHTML = '';

            if (result.status === 'success') {
                result.data.forEach((row, index) => {
                    const badge = row.jenis === 'Pendapatan' ? 'success' : 'danger';
                    tbody.innerHTML += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${row.nama}</td>
                            <td><span class="badge bg-${badge}">${row.jenis}</span></td>
                            <td>
                                <button onclick="hapusKategori(${row.id}, '${row.jenis}')" class="btn btn-sm btn-danger">Hapus</button>
                            </td>
                        </tr>`;
                });
            }
        }

        // Tambah Data
        document.getElementById('formKategori').addEventListener('submit', async (e) => {
            e.preventDefault();
            const fd = new FormData();
            fd.append('action', 'tambah');
            fd.append('nama_kategori', document.getElementById('nama_kategori').value);
            fd.append('jenis', document.getElementById('jenis').value);

            const res = await fetch('api_kategori.php', { method: 'POST', body: fd });
            const result = await res.json();
            
            if (result.status === 'success') {
                document.getElementById('formKategori').reset();
                loadKategori();
            }
        });

        // Hapus Data
        async function hapusKategori(id, jenis) {
            if (!confirm('Hapus kategori ini?')) return;
            const fd = new FormData();
            fd.append('action', 'hapus');
            fd.append('id_kategori', id);
            fd.append('jenis', jenis);

            await fetch('api_kategori.php', { method: 'POST', body: fd });
            loadKategori();
        }

        document.addEventListener('DOMContentLoaded', loadKategori);
    </script>
</body>
</html>