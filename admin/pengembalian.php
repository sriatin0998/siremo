<?php
session_start();
include '../config.php'; 

if (!isset($_SESSION['status']) || $_SESSION['status'] != 'login_admin' || $_SESSION['role'] != 'admin') {
    header("location: login.php");
    exit;
}
$status_msg = "";

// Query untuk mengambil daftar mobil yang sedang disewa (ASUMSI status_ketersediaan='Disewa')
$query_mobil = "SELECT id_mobil, merek, plat_nomor FROM mobil WHERE status_ketersediaan = 'Disewa' ORDER BY merek ASC";
$result_mobil = mysqli_query($koneksi, $query_mobil);

if (isset($_POST['submit_pengembalian'])) {
    $kendaraan = mysqli_real_escape_string($koneksi, $_POST['kendaraan']);
    $tgl_pengembalian = mysqli_real_escape_string($koneksi, $_POST['tgl_pengembalian']);
    $keadaan_mobil = mysqli_real_escape_string($koneksi, $_POST['keadaan_mobil']);
    
    // ASUMSI:
    // 1. Kita cari id_transaksi yang sedang aktif berdasarkan merk kendaraan yang dikembalikan.
    // 2. Kita update tgl_aktual_kembali di tabel transaksi_sewa.
    // 3. Kita update status_ketersediaan mobil menjadi 'Tersedia'.

    // Cek ID Mobil
    $q_id_mobil = mysqli_query($koneksi, "SELECT id_mobil FROM mobil WHERE merk = '$kendaraan'");
    if (mysqli_num_rows($q_id_mobil) > 0) {
        $data_mobil = mysqli_fetch_assoc($q_id_mobil);
        $id_mobil = $data_mobil['id_mobil'];

        // 1. Update tgl_aktual_kembali di transaksi_sewa
        $update_transaksi = "UPDATE transaksi_sewa 
                            SET tgl_aktual_kembali = '$tgl_pengembalian', 
                            status_transaksi = 'Selesai' 
                             WHERE id_mobil = '$id_mobil' AND tgl_aktual_kembali IS NULL"; // Hanya update yang belum selesai

        // 2. Update status mobil
        $update_mobil = "UPDATE mobil SET status_ketersediaan = 'Tersedia' WHERE id_mobil = '$id_mobil'";
        
        if (mysqli_query($koneksi, $update_transaksi) && mysqli_query($koneksi, $update_mobil)) {
            $status_msg = "<div class='success-msg'>Pengembalian **" . htmlspecialchars($kendaraan) . "** berhasil dicatat!</div>";
            // Hitung denda jika perlu, dan arahkan ke halaman input denda jika Keadaan Mobil tidak bagus
        } else {
            $status_msg = "<div class='error-msg'>Gagal mencatat pengembalian: " . mysqli_error($koneksi) . "</div>";
        }
    } else {
        $status_msg = "<div class='error-msg'>Kendaraan tidak ditemukan dalam database.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengembalian - SIREMO</title>
    
    <link rel="stylesheet" href="../assets/style3.css"> 
    <link rel="stylesheet" href="../assets/style9.css"> <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
</head>
<body>
    <div class="dashboard-container">
        
        <div class="sidebar">
            <div class="logo-siermo">
                <span class="car-icon">🚗</span> 
                <h2 class="logo-text">SIREMO</h2>
                <i class="fa-solid fa-car-side"></i>
            </div>
            
            <ul class="sidebar-menu">
                <li class="menu-item"><a href="dashboard.php">Dashboard</a></li> 
                <li class="menu-item"><a href="data_mobil.php">Data Mobil</a></li>
                <li class="menu-item"><a href="kelola_penyewa.php">Data Penyewa</a></li>
                <li class="menu-item"><a href="transaksi.php">Transaksi</a></li>
                <li class="menu-item"><a href="tarif_sewa.php">Tarif Sewa</a></li>
                <li class="menu-item active-link"><a href="pengembalian.php">Pengembalian</a></li>
                <li class="menu-item"><a href="laporan_penyewaan.php">Laporan Penyewaan</a></li>
                <li class="menu-item"><a href="ulasan.php">Ulasan</a></li>
                
                <li class="menu-item-spacer"></li> 
                <li class="menu-item logout-link"><a href="logout.php">Logout</a></li>
            </ul>
        </div>

        <div class="main-content">
            <div class="background-image"></div> 
            <div class="overlay"></div> 
            
            <div class="pengembalian-container">
                
                <div class="pengembalian-header">
                    <a href="dashboard.php" class="back-arrow"><i class="fas fa-arrow-left"></i></a>
                    <h1 class="pengembalian-title">Pengembalian</h1>
                </div>
                
                <?php echo $status_msg; ?>

                <div class="form-card-pengembalian">
                    <form method="POST" action="pengembalian.php">
                        
                        <div class="form-group-pengembalian">
                            <label for="kendaraan">Kendaraan</label>
                            <select id="kendaraan" name="kendaraan" required>
                                <option value="" disabled selected>Pilih Merk Kendaraan</option>
                                <?php 
                                if (mysqli_num_rows($result_mobil) > 0) {
                                    while($row_mobil = mysqli_fetch_assoc($result_mobil)) {
                                        echo '<option value="' . htmlspecialchars($row_mobil['merk']) . '">' . htmlspecialchars($row_mobil['merk']) . ' (' . htmlspecialchars($row_mobil['plat_nomor']) . ')</option>';
                                    }
                                } else {
                                    echo '<option value="">-- Tidak ada mobil yang sedang disewa --</option>';
                                }
                                ?>
                            </select>
                        </div>
                        
                        <div class="form-group-pengembalian">
                            <label for="tgl_pengembalian">Tanggal Pengembalian</label>
                            <input type="date" id="tgl_pengembalian" name="tgl_pengembalian" required>
                        </div>
                        
                        <div class="form-group-pengembalian">
                            <label for="keadaan_mobil">Keadaan Mobil</label>
                            <input type="text" id="keadaan_mobil" name="keadaan_mobil" placeholder="Misal: Bagus, Lecet, Mesin panas" required>
                        </div>
                        
                        <div class="button-group-pengembalian">
                            <button type="submit" name="submit_pengembalian" class="submit-btn-pengembalian">Submit</button>
                            
                            <button type="button" class="denda-btn-pengembalian" onclick="window.location.href='input_denda.php'">Input Denda</button>
                        </div>
                    </form>
                </div>
            </div>
            
        </div>
    </div>
</body>
</html>