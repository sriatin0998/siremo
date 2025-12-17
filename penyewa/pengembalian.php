<?php
session_start();
// Menghubungkan ke file konfigurasi database
include 'config.php';

// Data Dummy (Nantinya data ini diambil dari database berdasarkan ID Transaksi pengembalian)
$nama_mobil = "Toyota Fortuner";
$periode_sewa = "18.10.2025 12.30 - 19.10.2025 12.30";
$tanggal_kembali = "20.10.2025 12.00";
$lokasi = "Jl Widasari lama No 10 Indramayu";
$denda = 900000;

// Logika jika tombol konfirmasi ditekan
if (isset($_POST['konfirmasi'])) {
    // Proses update status di database bisa diletakkan di sini
    $pesan_sukses = "Pengembalian berhasil dikonfirmasi!";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Pengembalian - SIREMO</title>
    <link rel="stylesheet" href="../assets/style_konfirmasi.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <div class="page-wrapper">
        <header class="header-brand">
            <div class="brand-group">
                <i class="fas fa-car car-icon-orange"></i>
                <span class="logo-text">SIREMO</span>
            </div>
        </header>

        <main class="content-container">
            <h1 class="page-title">Konfirmasi Pengembalian</h1>

            <div class="confirmation-card">
                <h3>Detail Pengembalian</h3>
                
                <div class="detail-list">
                    <div class="detail-item">
                        <span class="label">Nama Mobil:</span>
                        <span class="value"><?php echo $nama_mobil; ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Periode Sewa:</span>
                        <span class="value"><?php echo $periode_sewa; ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Tanggal Pengembalian:</span>
                        <span class="value"><?php echo $tanggal_kembali; ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Lokasi:</span>
                        <span class="value"><?php echo $lokasi; ?></span>
                    </div>
                </div>

                <form action="" method="POST" class="confirmation-form">
                    <div class="denda-section">
                        <label for="denda">Denda</label>
                        <div class="input-wrapper">
                            <input type="text" id="denda" name="denda" 
                                value="Rp. <?php echo number_format($denda, 0, ',', '.'); ?>" readonly>
                        </div>
                    </div>

                    <div class="button-group">
                        <button type="submit" name="konfirmasi" class="btn-konfirmasi">Konfirmasi</button>
                    </div>
                </form>
            </div>
        </main>
    </div>

</body>
</html>