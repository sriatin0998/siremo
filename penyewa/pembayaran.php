<?php
// Memulai session untuk mendapatkan data penyewaan
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Sertakan config jika diperlukan untuk koneksi database nantinya
include 'config.php';

// Contoh data dinamis (Nantinya bisa diambil dari session transaksi penyewaan)
$nama_mobil = "Toyota Fortuner";
$periode_sewa = 2;
$total_bayar = 1800000;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Metode Pembayaran - SIREMO</title>
    <link rel="stylesheet" href="../assets/style_pembayaran.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="payment-wrapper">
        <h1 class="page-title">Pilih Metode Pembayaran</h1>

        <form action="status_pembayaran.php" method="POST">
            <div class="card payment-selection">
                <label class="method-option" id="transfer-bank-opt">
                    <div class="method-info">
                        <i class="fas fa-university icon-orange"></i>
                        <span>Transfer Bank</span>
                    </div>
                    <input type="radio" name="metode" value="bank" id="radio-bank" required>
                    <span class="checkmark"></span>
                </label>

                <label class="method-option">
                    <div class="method-info">
                        <i class="fas fa-wallet icon-orange"></i>
                        <span>E-Wallet (OVO, GoPay, Dana)</span>
                    </div>
                    <input type="radio" name="metode" value="ewallet">
                    <span class="checkmark"></span>
                </label>

                <label class="method-option">
                    <div class="method-info">
                        <i class="fas fa-money-bill-wave icon-orange"></i>
                        <span>Tunai</span>
                    </div>
                    <input type="radio" name="metode" value="tunai">
                    <span class="checkmark"></span>
                </label>
            </div>

            <div class="card transaction-detail">
                <p><strong>Nama Mobil:</strong> <?php echo htmlspecialchars($nama_mobil); ?></p>
                <p><strong>Periode Sewa:</strong> <?php echo $periode_sewa; ?> Hari</p>
                <div class="price-section">
                    <p><strong>Total Pembayaran:</strong></p>
                    <p class="amount">Rp. <?php echo number_format($total_bayar, 0, ',', '.'); ?></p>
                </div>
            </div>

            <div class="button-center">
                <button type="submit" name="cek_status" class="btn-cek-status">Cek Status</button>
                <link href="status_pembayaran.php">
            </div>
        </form>
    </div>

    <script>
    document.getElementById('transfer-bank-opt').addEventListener('click', function() {
        // Memberi sedikit jeda agar visual radio button terpilih terlihat
        setTimeout(function() {
            // Arahkan ke halaman detail bank
            window.location.href = 'rincian_bank.php';
        }, 300); 
    });
    </script>
</body>
</html>