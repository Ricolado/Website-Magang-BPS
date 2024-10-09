<?php
// Konfigurasi database
$host = 'localhost';
$dbname = 'absensi_magang';
$username = 'root'; // Sesuaikan dengan username database Anda
$password = ''; // Sesuaikan dengan password database Anda

// Koneksi ke database menggunakan PDO
try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Setel mode error untuk PDO agar lebih mudah saat debug
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Setel pengaturan karakter agar mendukung UTF-8
    $conn->exec("set names utf8");
} catch (PDOException $e) {
    // Jika koneksi gagal, tampilkan pesan error
    die("Koneksi ke database gagal: " . $e->getMessage());
}
?>
