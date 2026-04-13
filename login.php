<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - Manajemen Keuangan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center" style="height: 100vh;">
    <div class="container">
        <div class="card shadow-sm mx-auto" style="max-width: 400px;">
            <div class="card-body p-4">
                <h3 class="text-center mb-4">Login</h3>
                <form id="loginForm">
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" id="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" id="password" class="form-control" required>
                    </div>
                    <button type="submit" id="btnMasuk" class="btn btn-primary w-100">Masuk</button>
                </form>
                <div id="pesan" class="mt-3 text-center small"></div>
            </div>
        </div>
    </div>

    <script>
    document.getElementById('loginForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        console.log("Tombol ditekan!"); // Cek di F12 Console

        const divPesan = document.getElementById('pesan');
        const btn = document.getElementById('btnMasuk');
        
        divPesan.innerHTML = "Sedang mencoba login...";
        btn.disabled = true;

        const fd = new FormData();
        fd.append('username', document.getElementById('username').value);
        fd.append('password', document.getElementById('password').value);

        try {
            console.log("Mengirim data ke api_login.php...");
            const res = await fetch('api_login.php', { 
                method: 'POST', 
                body: fd 
            });

            // Cek apakah file api_login.php ada
            if(!res.ok) throw new Error("File api_login.php tidak ditemukan atau Error 500");

            const data = await res.json();
            console.log("Respon dari server:", data);

            if (data.status === 'success') {
                divPesan.innerHTML = `<span class="text-success">${data.message} Mengalihkan...</span>`;
                setTimeout(() => window.location.href = 'Catatan.php', 1500);
            } else {
                divPesan.innerHTML = `<span class="text-danger">${data.message}</span>`;
                btn.disabled = false;
            }
        } catch (error) {
            console.error("Terjadi kesalahan:", error);
            divPesan.innerHTML = `<span class="text-danger">Error: ${error.message}</span>`;
            btn.disabled = false;
        }
    });
    </script>
</body>
</html>