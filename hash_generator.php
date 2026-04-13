<?php
// Ganti '1234' dengan password yang kamu inginkan
$password_asli = '1234'; 
$hash_baru = password_hash($password_asli, PASSWORD_DEFAULT);

echo "<h3>Password Asli: " . $password_asli . "</h3>";
echo "<h3>Hash yang dihasilkan (Copy kode di bawah ini):</h3>";
echo "<textarea cols='70' rows='3' readonly>" . $hash_baru . "</textarea>";
?>