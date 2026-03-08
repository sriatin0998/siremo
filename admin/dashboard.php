<?php
// WAJIB ditaruh di baris paling atas!
include '../config.php'; 
cek_akses('admin'); 

// Ambil data session sesuai key di login.php
// Perbaikan: gunakan variabel yang konsisten
$nama_admin = $_SESSION['nama']; 

/* ======================
    DATA STATISTIK DINAMIS
====================== */

// 1. Total Mobil
$q_mobil = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM mobil");
$total_mobil = mysqli_fetch_assoc($q_mobil)['total'];

// 2. Total Penyewa
$q_penyewa = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM penyewa");
$total_penyewa = mysqli_fetch_assoc($q_penyewa)['total'];

// 3. Pendapatan (Pastikan kolom 'total_bayar' ada di tabel 'transaksi_sewa')
$q_income = mysqli_query($koneksi, "SELECT SUM(total_bayar) AS total FROM transaksi_sewa");
$res_income = mysqli_fetch_assoc($q_income);
$pendapatan = $res_income['total'] ?? 0;

// 4. Total Transaksi
$q_trans = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM transaksi_sewa");
$transaksi = mysqli_fetch_assoc($q_trans)['total'];

// 5. Data Pengembalian 
// Mengambil jumlah transaksi yang status_transaksi nya sudah 'selesai'
$q_kembali = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM transaksi_sewa WHERE status_transaksi = 'selesai'");

if($q_kembali) {
    $res_kembali = mysqli_fetch_assoc($q_kembali);
    $pengembalian = $res_kembali['total'];
} else {
    // Jika query gagal (misal kolom typo), set ke 0 agar tidak error
    $pengembalian = 0; 
}

// 6. Tarif Sewa (Pastikan kolom 'tarif_sewa_per_hari' ada di tabel 'mobil')
$q_tarif = mysqli_query($koneksi, "SELECT MIN(tarif_sewa_per_hari) AS t_min, MAX(tarif_sewa_per_hari) AS t_max FROM mobil");
$d_tarif = mysqli_fetch_assoc($q_tarif);
$tarif_min = number_format($d_tarif['t_min'] ?? 0, 0, ',', '.');
$tarif_max = number_format($d_tarif['t_max'] ?? 0, 0, ',', '.');
?>

<?php include 'partials/header.php'; ?> 

<div class="dashboard-container">
    <?php include 'partials/sidebar.php'; ?>

    <div class="main-content">
        <div class="background-image"></div> 
        <div class="overlay"></div> 
        
        <style>
/* Style tombol cetak */
.btn-cetak {
    background-color: #e67e22;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: bold;
    transition: 0.3s;
}

.btn-cetak:hover {
    background-color: #d35400;
}

/* CSS KHUSUS CETAK PDF */
@media print {
    /* Sembunyikan Sidebar, Tombol Cetak, dan Overlay Background */
    .sidebar, .no-print, .background-image, .overlay, .btn-back {
        display: none !important;
    }

    /* Atur layout konten agar memenuhi halaman */
    .main-content {
        margin-left: 0 !important;
        padding: 0 !important;
        width: 100% !important;
    }

    .dashboard-container {
        display: block !important;
    }

    /* Buat card terlihat jelas di PDF */
    .card {
        border: 1px solid #ccc !important;
        break-inside: avoid;
        background: white !important;
        color: black !important;
        box-shadow: none !important;
        margin-bottom: 10px !important;
    }

    .card-value {
        color: #e67e22 !important;
    }

    /* Tambahkan Judul Laporan di bagian atas saat di-print */
    body::before {
        content: "LAPORAN RINGKASAN SISTEM RENTAL MOBIL (SIREMO)";
        display: block;
        text-align: center;
        font-size: 20px;
        font-weight: bold;
        margin-bottom: 20px;
    }
}
</style>
        
        <header class="main-header">
            <h1 class="title">Sistem Rental Mobil</h1>
            <p class="greeting">
                <i class="fas fa-user-circle"></i> 
                Hii <?php echo htmlspecialchars($nama_admin); ?>!!
            </p>
           <div class="no-print" style="margin-bottom: 20px;">
    <a href="cetak_laporan.php" target="_blank" class="btn-cetak" style="text-decoration: none; display: inline-block;">
        <i class="fas fa-print"></i> Cetak Laporan Tabel (PDF)
    </a>
</div>
</header>
        

        <div class="cards-grid">
            
            <!-- TOTAL MOBIL -->
            <div class="card" onclick="window.location='data_mobil.php'" style="cursor:pointer;">
    <div class="card-content">
        <p class="card-label">Total Mobil</p>
        <h2 class="card-value"><?php echo $total_mobil; ?></h2>
    </div>
</div>


            <!-- TOTAL PENYEWA -->
            <div class="card">
                <div class="card-content">
                    <p class="card-label">Total Penyewa</p>
                    <h2 class="card-value"><?php echo $total_penyewa; ?></h2>
                </div>
            </div>

            <!-- PENDAPATAN -->
            <div class="card">
                <div class="card-content">
                    <p class="card-label">Pendapatan</p>
                    <h2 class="card-value large-value">
                        Rp.<br><?php echo number_format($pendapatan, 0, ',', '.'); ?>
                    </h2>
                </div>
            </div>

            <!-- TRANSAKSI -->
            <div class="card">
                <div class="card-content">
                    <p class="card-label">Transaksi</p>
                    <h2 class="card-value"><?php echo $transaksi; ?></h2>
                </div>
            </div>

            <!-- PENGEMBALIAN -->
            <div class="card">
                <div class="card-content">
                    <p class="card-label">Pengembalian</p>
                    <h2 class="card-value"><?php echo $pengembalian; ?></h2>
                </div>
            </div>

            <!-- TARIF SEWA -->
            <div class="card">
                <div class="card-content">
                    <p class="card-label">Tarif Sewa</p>
                    <h2 class="card-value large-value">
                        Rp.<?php echo $tarif_min; ?> -<br>
                        <?php echo $tarif_max; ?>
                    </h2>
                </div>
            </div>

        </div>
    </div>
</div>
</body>
</html>