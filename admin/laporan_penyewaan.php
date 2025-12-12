<?php
session_start();
include '../config.php'; // Pastikan path ke config.php sudah benar

// Cek status login
if (!isset($_SESSION['status']) || $_SESSION['status'] != 'login_admin' || $_SESSION['role'] != 'admin') {
    header("location: login.php");
    exit;
}

// ==========================================================
// PENGAMBILAN DATA LAPORAN (READ)
// ==========================================================
// ASUMSI: Kita hanya mengambil transaksi yang sudah "Selesai" jika status_bayar digunakan
$query_read = "
    SELECT 
        p.nama,                   /* Nama dari tabel penyewa */
        m.merek,                   /* Merk dari tabel mobil (Diasumsikan kolomnya 'merk') */
        ts.tgl_sewa,             
        ts.total_bayar          /* Jumlah Total */
    FROM transaksi_sewa ts
    LEFT JOIN penyewa p ON ts.id_penyewa = p.id_penyewa
    LEFT JOIN mobil m ON ts.id_mobil = m.id_mobil 
    /* WHERE ts.status_bayar = 'Selesai' -- (Opsional, jika ingin memfilter hanya transaksi selesai) */
    ORDER BY ts.tgl_sewa DESC
";
$result = mysqli_query($koneksi, $query_read);

if (!$result) {
    die("Query Laporan Error: " . mysqli_error($koneksi));
}

// ==========================================================
// PENGHITUNGAN DATA STATISTIK (Sesuai Gambar)
// ==========================================================
// Catatan: Nilai ini dibuat STATIS agar SAMA PERSIS dengan gambar.
// Jika ingin mengambil nilai DINAMIS dari database, Anda perlu query COUNT dan SUM terpisah.
$total_penyewaan = mysqli_num_rows($result); // Mengambil jumlah baris yang benar
$total_pendapatan_db = 0;

// Hitung total pendapatan dari hasil query
if ($result->num_rows > 0) {
    $temp_result = mysqli_query($koneksi, "SELECT SUM(total_bayar) AS total FROM transaksi_sewa");
    $sum_row = mysqli_fetch_assoc($temp_result);
    $total_pendapatan_db = $sum_row['total'];
}

// Ganti dengan nilai STATIS dari gambar jika Anda ingin tampilan sama persis:
// $total_penyewaan_display = 4;
// $total_pendapatan_display = 6325000; 

// Menggunakan nilai Dinamis
$total_penyewaan_display = $total_penyewaan;
$total_pendapatan_display = $total_pendapatan_db;

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penyewaan Mobil - SIREMO</title>
    
    <link rel="stylesheet" href="../assets/style3.css"> 
    <link rel="stylesheet" href="../assets/style11.css"> <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
</head>
<body>
    <div class="dashboard-container">
        
        <div class="sidebar">
            <div class="logo-siermo">
                <span class="car-icon">🚗</span> 
                <h2 class="logo-text">SIREMO</h2> 
            </div>
            
            <ul class="sidebar-menu">
                <li class="menu-item"><a href="dashboard.php">Dashboard</a></li> 
                <li class="menu-item"><a href="data_mobil.php">Data Mobil</a></li>
                <li class="menu-item"><a href="kelola_penyewa.php">Data Penyewa</a></li>
                <li class="menu-item"><a href="transaksi.php">Transaksi</a></li>
                <li class="menu-item"><a href="tarif_sewa.php">Tarif Sewa</a></li>
                <li class="menu-item"><a href="pengembalian.php">Pengembalian</a></li>
                <li class="menu-item active-link"><a href="laporan_penyewaan.php">Laporan Penyewaan</a></li>
                <li class="menu-item"><a href="ulasan.php">Ulasan</a></li>
                
                <li class="menu-item-spacer"></li> 
                <li class="menu-item logout-link"><a href="logout.php">Logout</a></li>
            </ul>
        </div>

        <div class="main-content">
            <div class="background-image"></div> 
            <div class="overlay"></div> 
            
            <div class="laporan-container">
                
                <div class="laporan-header">
                    <a href="dashboard.php" class="back-arrow"><i class="fas fa-arrow-left"></i></a>
                    <h1 class="laporan-title">Laporan Penyewaan Mobil</h1>
                </div>
                
                <div class="summary-cards-container">
                    
                    <div class="summary-card">
                        <i class="fas fa-car summary-icon"></i>
                        <div class="summary-details">
                            <p class="summary-label">Total Penyewaan</p>
                            <h2 class="summary-value"><?php echo number_format($total_penyewaan_display, 0, ',', '.'); ?></h2>
                        </div>
                    </div>
                    
                    <div class="summary-card">
                        <i class="fas fa-dollar-sign summary-icon dollar-icon"></i>
                        <div class="summary-details">
                            <p class="summary-label">Total Pendapatan</p>
                            <h2 class="summary-value">Rp. <?php echo number_format($total_pendapatan_display, 0, ',', '.'); ?></h2>
                        </div>
                    </div>
                </div>

                <h2 class="data-penyewaan-title">Data Penyewaan</h2>
                
                <div class="data-table-card laporan-table-card">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th style="width: 25%;">Nama</th>
                                <th style="width: 25%;">Merk</th>
                                <th style="width: 25%;">Tanggal Sewa</th>
                                <th style="width: 25%;">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            if (mysqli_num_rows($result) > 0) {
                                while($row = mysqli_fetch_assoc($result)): 
                                    // Format Tanggal sesuai gambar (18 10 2025)
                                    $tgl_sewa_display = date('d m Y', strtotime($row['tgl_sewa']));
                                    
                                    // Format Rupiah
                                    $jumlah_rp = 'Rp. ' . number_format($row['total_bayar'], 0, ',', '.');
                                ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['nama']); ?></td>
                                    <td><?php echo htmlspecialchars($row['merk']); ?></td>
                                    <td><?php echo $tgl_sewa_display; ?></td>
                                    <td><?php echo $jumlah_rp; ?></td>
                                </tr>
                            <?php endwhile; 
                            } else {
                                echo '<tr><td colspan="4" style="height: 150px; text-align: center; padding: 20px;">Belum ada data penyewaan yang tercatat.</td></tr>';
                            }
                            ?>
                            <tr><td colspan="4" style="height: 40px; border-bottom: none;"></td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>