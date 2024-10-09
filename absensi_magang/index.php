<?php
// Sertakan file koneksi.php untuk menghubungkan ke database
require 'koneksi.php';

$message = ""; // Variabel untuk menyimpan pesan notifikasi

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari formulir dan ubah ke huruf besar
    $nim = isset($_POST['nim']) ? strtoupper($_POST['nim']) : '';
    $nama = isset($_POST['nama']) ? strtoupper($_POST['nama']) : '';
    $jurusan_prodi = isset($_POST['jurusan_prodi']) ? strtoupper($_POST['jurusan_prodi']) : '';
    $asal_kampus = isset($_POST['asal_kampus']) ? strtoupper($_POST['asal_kampus']) : '';
    $tahun_angkatan = isset($_POST['tahun_angkatan']) ? strtoupper($_POST['tahun_angkatan']) : '';
    $tanggal = date('Y-m-d');
    $jam_masuk = null;
    $jam_keluar = null;
    $status = 'HADIR'; // Status default

    // Cek apakah sudah ada data absensi untuk NIM dan tanggal yang sama
    $sql = "SELECT * FROM absensi WHERE nim = ? AND tanggal = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$nim, $tanggal]);
    $existingRecord = $stmt->fetch(PDO::FETCH_ASSOC);

    if (isset($_POST['absen_masuk'])) {
        if ($existingRecord) {
            $message = "ANDA SUDAH ABSEN MASUK HARI INI.";
        } else {
            $jam_masuk = date('H:i:s');
            // Simpan absensi masuk ke database
            $sql = "INSERT INTO absensi (nim, nama, jurusan_prodi, asal_kampus, tahun_angkatan, tanggal, jam_masuk, status) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$nim, $nama, $jurusan_prodi, $asal_kampus, $tahun_angkatan, $tanggal, $jam_masuk, $status]);
            $message = "ABSEN MASUK BERHASIL.";
        }
    }

    if (isset($_POST['absen_keluar'])) {
        // Cek apakah record ada dan jam_keluar null
        if ($existingRecord && !isset($existingRecord['jam_keluar'])) {
            $jam_keluar = date('H:i:s');
            // Simpan record keluar ke database
            $sql = "UPDATE absensi SET jam_keluar = ? WHERE nim = ? AND tanggal = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$jam_keluar, $nim, $tanggal]);
            $message = "ABSEN KELUAR BERHASIL.";
        } else {
            $message = "ANDA BELUM ABSEN MASUK HARI INI ATAU SUDAH ABSEN KELUAR.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ABSENSI MAGANG</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-image: url('your-background-image-url.jpg'); /* Ganti dengan URL gambar latar belakang yang diinginkan */
            background-size: cover;
            background-repeat: no-repeat;
            font-family: 'Arial', sans-serif;
            color: #333;
        }
        .container {
            margin-top: 30px;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            background-color: rgba(255, 255, 255, 0.9); /* Menggunakan transparansi */
        }
        h1 {
            color: #0288d1;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
            font-size: 2.5rem; /* Ukuran font yang lebih besar */
        }
        .btn {
            margin-right: 10px;
            font-size: 1.2rem; /* Ukuran font tombol */
        }
        .footer-above {
            background-color: #2e7d32; /* Hijau tua */
            color: green;
            padding: 30px 0;
            text-align: center;
        }
        .footer-col h3 {
            margin-bottom: 15px;
            font-weight: bold;
            text-transform: uppercase; /* Mengubah teks menjadi huruf kapital */
        }
        .footer-col p {
            margin: 0 0 10px;
        }
        .footer-icons {
            margin-top: 15px;
        }
        .footer-icons a {
            margin: 0 2px;
            color: green;
            text-decoration: none;
            font-size: 1.5rem; /* Ukuran ikon sosial media */
            transition: transform 0.3s; /* Efek transisi saat hover */
            display: inline-flex;
            align-items: center;
        }
        .footer-icons a i {
            margin-right: 5px; /* Jarak antara ikon dan teks */
        }       
        .footer-icons a:hover {
            transform: scale(1.2); /* Memperbesar ikon saat hover */
        }
        .footer-bottom {
            font-size: 1rem; /* Ukuran font footer */
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center">FORM ABSENSI MAGANG BADAN PUSAT STATISTIK</h1>
        
        <!-- Notifikasi -->
        <?php if (!empty($message)): ?>
            <div class="alert alert-info" role="alert">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <!-- Form Absensi -->
        <form action="" method="POST">
            <div class="mb-3">
                <label for="nim" class="form-label">NIM:</label>
                <input type="text" id="nim" name="nim" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="nama" class="form-label">NAMA:</label>
                <input type="text" id="nama" name="nama" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="jurusan_prodi" class="form-label">JURUSAN/PRODI:</label>
                <input type="text" id="jurusan_prodi" name="jurusan_prodi" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="asal_kampus" class="form-label">ASAL KAMPUS:</label>
                <input type="text" id="asal_kampus" name="asal_kampus" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="tahun_angkatan" class="form-label">TAHUN ANGKATAN:</label>
                <input type="text" id="tahun_angkatan" name="tahun_angkatan" class="form-control" required>
            </div>
            <div class="mb-3">
                <button type="submit" name="absen_masuk" class="btn btn-primary">ABSEN MASUK</button>
                <button type="submit" name="absen_keluar" class="btn btn-danger">ABSEN KELUAR</button>
            </div>
        </form>
    </div>

    <!-- Footer -->
    <footer class="footer-above">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h3>BADAN PUSAT STATISTIK</h3>
                    <p>Jl. Suprapto N.5, Oebobo, Kec. Oebobo, Kota Kupang, NTT</p>
                    <p>Telp: (0380) 826289 | Faks: (0380) 833124</p>
                </div>
                <div class="col-md-6 text-end">
                    <h3>Sosial Media</h3>
                    <div class="footer-icons">
                        <a href="https://www.facebook.com/bps5300?mibextid=ZbWKwL" target="_blank" title="Facebook">
                            <i class="bi bi-facebook"></i> BPS Provinsi NTT
                        </a>
                        <a href="https://x.com/BPS_NTT?s=09" target="_blank" title="X">
                            <i</i>X @BPS_NTT
                        </a>
                        <a href="https://www.instagram.com/bps.ntt?igsh=MWVieTZybnc2b2UyYg==" target="_blank" title="Instagram">
                            <i class="bi bi-instagram"></i>bps.ntt
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2024 Badan Pusat Statistik. Semua hak dilindungi.</p>
            <p>&copy; By Tkj .22.</p>
        </div>
    </footer>
</body>
</html>
