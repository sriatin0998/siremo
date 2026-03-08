<?php 
include '../config.php';
session_start();

// 1. Validasi Login
if (!isset($_SESSION['id_user'])) {
    header("location: ../login.php");
    exit;
}

$id_user = $_SESSION['id_user'];

// 2. Ambil data transaksi TERAKHIR dari user ini
// Kita join dengan tabel mobil untuk mendapatkan nama unit mobilnya
$query = "SELECT t.*, m.merek, m.model, p.nama 
          FROM transaksi_sewa t
          JOIN mobil m ON t.id_mobil = m.id_mobil
          JOIN penyewa p ON t.id_penyewa = p.id_penyewa
          WHERE p.id_user = '$id_user'
          ORDER BY t.id_transaksi DESC LIMIT 1";

$result = mysqli_query($koneksi, $query);
$data = mysqli_fetch_assoc($result);

// Jika tidak ada data transaksi, kembalikan ke index
if (!$data) {
    header("location: index.php");
    exit;
}

include '../inc_penyewa/header.php'; 
?>

<link rel="stylesheet" href="../css_penyewa/style_transaksi.css">

<main class="success-page-wrapper">
    <div class="container">
        <div class="success-card">
            <div class="success-icon">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            
            <h1>Pemesanan Berhasil!</h1>
            <p class="thanks-msg">Terima kasih <strong><?= $data['nama']; ?></strong>, pesanan Anda sedang kami verifikasi.</p>

            <div class="receipt-box">
                <div class="receipt-header">
                    <span>E-RECEIPT SIREMO</span>
                    <div class="receipt-line"></div>
                </div>
                
                <div class="receipt-body">
                    <div class="receipt-row">
                        <span>Kode Booking</span>
                        <strong>SRM-<?= date('Ymd') . $data['id_transaksi']; ?></strong>
                    </div>
                    <div class="receipt-row">
                        <span>Status</span>
                        <strong class="status-pending"><?= $data['status_transaksi']; ?></strong>
                    </div>
                    <div class="receipt-row">
                        <span>Unit Mobil</span>
                        <strong><?= $data['merek'] . " " . $data['model']; ?></strong>
                    </div>
                    <div class="receipt-row">
                        <span>Total Bayar</span>
                        <strong>Rp <?= number_format($data['total_bayar'], 0, ',', '.'); ?></strong>
                    </div>
                </div>

                <div class="receipt-footer">
                    <p>Mohon tunggu 5-10 menit untuk proses verifikasi dokumen oleh admin kami.</p>
                </div>
            </div>

            <div class="success-actions">
                <a href="riwayat.php" class="btn-secondary-custom">Lihat Riwayat Sewa</a>
                <a href="index.php" class="btn-primary-custom">Kembali ke Beranda</a>
            </div>
        </div>
    </div>
</main>

<?php include '../inc_penyewa/footer.php'; ?>