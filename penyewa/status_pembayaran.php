<?php
session_start();
// Menghubungkan ke file konfigurasi database di folder penyewa
include 'config.php';

// Data dummy untuk tampilan (Nantinya data ini diambil dari database berdasarkan ID Transaksi)
$status = "AKTIF";
$nama_mobil = "Toyota Fortuner";
$periode_sewa = "18.10.2025-19.10.2025";
$waktu_sewa = "18.10.2025, Pukul 16.00 WIB";
$lokasi = "Jl Widasari lama No 10 Indramayu";
$kontak_rental = "Slamet, 089200000000";
$jumlah_unit = "1 Unit";
$metode_pembayaran = "Transfer Bank";
$total_akhir = 1800000;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Berhasil - SIREMO</title>
    <link rel="stylesheet" href="../assets/status_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <div class="status-page-wrapper">
        <header class="header-logo">
            <a href="../index.php" class="back-arrow-btn" title="Kembali ke Home">
                <i class="fas fa-arrow-left"></i>
            </a>
            
            <div class="brand-group">
                <i class="fas fa-car car-icon-header"></i>
                <span class="logo-text">SIREMO</span>
            </div>
        </header>

        <div class="main-status-card">
            
            <div class="success-notification">
                <div class="check-circle">
                    <i class="fas fa-check"></i>
                </div>
                <div class="success-text">
                    <h1>Pembayaran Berhasil</h1>
                    <p>Terima Kasih, Lanjutkan PerpetualanganMu</p>
                    <button class="btn-detail-sewa">Detail Sewa</button>
                </div>
            </div>

            <div class="detail-box">
                <div class="status-badge-container">
                    <span class="status-badge">STATUS: <?php echo $status; ?></span>
                </div>
                
                <div class="info-grid">
                    <p><strong>Nama Mobil:</strong> <?php echo $nama_mobil; ?></p>
                    <p><strong>Periode Sewa:</strong> <?php echo $periode_sewa; ?></p>
                    <p><strong>Waktu Sewa:</strong> <?php echo $waktu_sewa; ?></p>
                    <p><strong>Lokasi:</strong> <?php echo $lokasi; ?></p>
                    <p><strong>Kontak Rental:</strong> <?php echo $kontak_rental; ?></p>
                    <p><strong>Jumlah:</strong> <?php echo $jumlah_unit; ?></p>
                </div>
            </div>

            <div class="payment-summary-box">
                <h3>Rincian Pembayaran</h3>
                <p>Metode Pembayaran: <?php echo $metode_pembayaran; ?></p>
                <p class="total-final">Total Akhir: Rp. <?php echo number_format($total_akhir, 0, ',', '.'); ?></p>
            </div>

        </div>
    </div>

</body>
</html>