<?php
include 'database.php';

if (isset($_GET['id_aset'])) {
    $id_aset = intval($_GET['id_aset']);

    // Hapus data yang berkaitan di tabel lain (wajib urutannya!)
    mysqli_query($conn, "DELETE FROM pendapatan WHERE id_aset = $id_aset");
    mysqli_query($conn, "DELETE FROM pengeluaran WHERE id_aset = $id_aset");
    mysqli_query($conn, "DELETE FROM transfer WHERE dari_aset = $id_aset OR ke_aset = $id_aset");

    // Hapus data di tabel aset
    $hapus_aset = "DELETE FROM aset WHERE id_aset = $id_aset";
    if (mysqli_query($conn, $hapus_aset)) {
        echo "<script>alert('Data aset & semua data terkait berhasil dihapus!'); window.location.href='Aset.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus data aset! Error: " . mysqli_error($conn) . "'); window.location.href='Aset.php';</script>";
    }
} else {
    echo "<script>alert('ID tidak ditemukan!'); window.location.href='Aset.php';</script>";
}
?>
