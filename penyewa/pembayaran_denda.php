<?php
session_start();
// Menghubungkan ke file konfigurasi database
include 'config.php';

// Data Dummy (Nantinya data ini diambil dari database berdasarkan transaksi denda)
$nama_mobil = "Toyota Fortuner";
$periode_sewa = "2 Hari";
$tanggal_pengembalian = "20.10.2025 12.00";
$total_denda = 900000;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Denda - SIREMO</title>
    <link rel="stylesheet" href="../assets/style_denda.css">
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
            <h1 class="page-title">Pilih Metode Pembayaran</h1>

            <div class="card white-card payment-card">
                <div class="payment-option">
                    <i class="fas fa-university icon-small"></i>
                    <span>Transfer Bank</span>
                </div>
                <div class="payment-option">
                    <i class="fas fa-mobile-alt icon-small"></i>
                    <span>E-Wallet (OVO, GoPay, Dana)</span>
                </div>
                <div class="payment-option">
                    <i class="fas fa-credit-card icon-small"></i>
                    <span>Tunai</span>
                </div>
            </div>

            <div class="card white-card detail-card">
                <div class="info-row">
                    <span class="label">Nama Mobil:</span>
                    <span class="value"><?php echo $nama_mobil; ?></span>
                </div>
                <div class="info-row">
                    <span class="label">Periode Sewa:</span>
                    <span class="value"><?php echo $periode_sewa; ?></span>
                </div>
                <div class="info-row">
                    <span class="label">Tanggal Pengembalian:</span>
                    <span class="value"><?php echo $tanggal_pengembalian; ?></span>
                </div>
                <div class="info-row total-row">
                    <span class="label">Total Denda:</span>
                    <span class="value">Rp. <?php echo number_format($total_denda, 0, ',', '.'); ?></span>
                </div>
            </div>

            <div class="button-center">
                <button onclick="window.location.href='status_pembayaran.php'" class="btn-selanjutnya">Selanjutnya</button>
            </div>
        </main>
    </div>

</body>
</html>