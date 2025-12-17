<?php
session_start();
// Pastikan path ke config.php sudah benar
include '../config.php'; 

// Cek status login
if (!isset($_SESSION['status']) || $_SESSION['status'] != 'login_admin' || $_SESSION['role'] != 'admin') {
    header("location: login.php");
    exit;
}

// ==========================================================
// PENGAMBILAN DATA TRANSAKSI (READ) - DISESUAIKAN
// ==========================================================
// DB STRUCTURE REFERENCE:
// Tabel 'transaksi_sewa' memiliki: id_transaksi, id_mobil, id_penyewa, tgl_sewa, tgl_rencana_kembali, total_bayar.
// Tabel 'penyewa' memiliki: id_penyewa, nama.
// ASUMSI: Tabel 'mobil' memiliki: id_mobil, merk. (Diperlukan untuk kolom Merk)

$query_read = "
    SELECT 
        ts.id_transaksi, 
        p.nama, 
        m.merek, 
        ts.tgl_sewa,             /* Tgl Sewa */
        ts.tgl_rencana_kembali,  /* Tgl Rencana Kembali */
        ts.total_bayar,          /* Total Biaya */
        ts.lama_sewa_hari        /* Lama Sewa diperlukan untuk menghitung Harga per hari */
    FROM transaksi_sewa ts
    LEFT JOIN penyewa p ON ts.id_penyewa = p.id_penyewa
    LEFT JOIN mobil m ON ts.id_mobil = m.id_mobil /* Pastikan tabel 'mobil' ada dan punya kolom 'merk' */
    ORDER BY ts.id_transaksi ASC
";
$result = mysqli_query($koneksi, $query_read);

// Error handling
if (!$result) {
    die("Query Transaksi Error: " . mysqli_error($koneksi));
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi - SIREMO</title>
    
    <link rel="stylesheet" href="../assets/style3.css"> 
    <link rel="stylesheet" href="../assets/style7.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
</head>
<body>
    <div class="dashboard-container">
        
        <div class="sidebar">
            <div class="logo-siermo">
                <i class="fa-solid fa-car-side"></i>
                <h2 class="logo-text">SIREMO</h2> 
            </div>
            
            <ul class="sidebar-menu">
                <li class="menu-item"><a href="dashboard.php">Dashboard</a></li> 
                <li class="menu-item"><a href="data_mobil.php">Data Mobil</a></li>
                <li class="menu-item"><a href="kelola_penyewa.php">Data Penyewa</a></li>
                <li class="menu-item active-link"><a href="transaksi.php">Transaksi</a></li>
                <li class="menu-item"><a href="tarif_sewa.php">Tarif Sewa</a></li>
                <li class="menu-item"><a href="pengembalian.php">Pengembalian</a></li>
                <li class="menu-item"><a href="laporan_penyewaan.php">Laporan Penyewaan</a></li>
                <li class="menu-item"><a href="ulasan.php">Ulasan</a></li>
                
                <li class="menu-item-spacer"></li> 
                <li class="menu-item logout-link"><a href="logout.php">Logout</a></li>
            </ul>
        </div>

        <div class="main-content">
            <div class="background-image"></div> 
            <div class="overlay"></div> 
            
            <div class="transaksi-container">
                
                <div class="transaksi-header">
                    <a href="dashboard.php" class="back-arrow"><i class="fas fa-arrow-left"></i></a>
                    <h1 class="transaksi-title">Transaksi</h1>
                </div>
                
                <div class="data-table-card">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th style="width: 5%;">No</th>
                                <th style="width: 15%;">Nama</th>
                                <th style="width: 15%;">Merk</th>
                                <th style="width: 25%;">Tanggal</th>
                                <th style="width: 15%;">Harga</th>
                                <th style="width: 15%;">Total</th>
                                <th style="width: 10%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            if (mysqli_num_rows($result) > 0) {
                                while($row = mysqli_fetch_assoc($result)): 
                                    
                                    // Hitung Harga Sewa per Hari
                                    $lama_sewa = (int)$row['lama_sewa_hari'];
                                    $total_bayar = (float)$row['total_bayar'];
                                    
                                    // Mencegah pembagian nol
                                    $harga_per_hari = ($lama_sewa > 0) ? ($total_bayar / $lama_sewa) : 0;
                                    
                                    // Format Tanggal sesuai gambar (DD.MM.YYYY-DD.MM.YYYY)
                                    $tgl_mulai = date('d.m.Y', strtotime($row['tgl_sewa']));
                                    $tgl_selesai = date('d.m.Y', strtotime($row['tgl_rencana_kembali']));
                                    
                                    $tanggal_display = ($row['tgl_sewa'] == $row['tgl_rencana_kembali']) ? $tgl_mulai : $tgl_mulai . '-' . $tgl_selesai;
                                    
                                    // Format Rupiah
                                    $harga_rp = 'Rp.' . number_format($harga_per_hari, 0, ',', '.');
                                    $total_rp = 'Rp.' . number_format($total_bayar, 0, ',', '.');
                                ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo htmlspecialchars($row['nama']); ?></td>
                                    <td><?php echo htmlspecialchars($row['merk']); ?></td>
                                    <td><?php echo $tanggal_display; ?></td>
                                    <td><?php echo $harga_rp; ?></td>
                                    <td><?php echo $total_rp; ?></td>
                                    <td class="action-cell">
                                        <a href="#" class="action-btn simpan-btn">[Simpan]</a>
                                    </td>
                                </tr>
                            <?php endwhile; 
                            } else {
                                echo '<tr><td colspan="7" style="height: 200px; text-align: center; padding: 20px;">Belum ada data transaksi yang tercatat.</td></tr>';
                            }
                            ?>
                            <tr><td colspan="7" style="height: 40px; border-bottom: none;"></td></tr>
                            <tr><td colspan="7" style="height: 40px; border-bottom: none;"></td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
        </div>
    </div>
</body>
</html>