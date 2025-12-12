<?php
session_start();
include '../config.php'; // Pastikan path ke config.php sudah benar

// Cek status login
if (!isset($_SESSION['status']) || $_SESSION['status'] != 'login_admin' || $_SESSION['role'] != 'admin') {
    header("location: login.php");
    exit;
}

$status_msg = "";

// ==========================================================
// LOGIKA TAMBAH/SIMPAN TARIF (CRUD LOGIC)
// ==========================================================
if (isset($_POST['submit_tarif'])) {
    $kendaraan = mysqli_real_escape_string($koneksi, $_POST['kendaraan']);
    $tarif_sewa = mysqli_real_escape_string($koneksi, $_POST['tarif_sewa']);
    
    // ASUMSI: Data tarif disimpan di tabel 'mobil' pada kolom 'tarif_sewa_per_hari'
    // Kita akan UPDATE data mobil berdasarkan nama kendaraan (Merk)
    $query = "UPDATE mobil SET tarif_sewa_per_hari = '$tarif_sewa' WHERE merek = '$kendaraan'";

    if (mysqli_query($koneksi, $query)) {
        if (mysqli_affected_rows($koneksi) > 0) {
            $status_msg = "<div class='success-msg'>Tarif untuk **" . htmlspecialchars($kendaraan) . "** berhasil diupdate!</div>";
        } else {
            $status_msg = "<div class='error-msg'>Gagal update: Kendaraan **" . htmlspecialchars($kendaraan) . "** tidak ditemukan.</div>";
        }
    } else {
        $status_msg = "<div class='error-msg'>Error SQL: " . mysqli_error($koneksi) . "</div>";
    }
}

// Query untuk mendapatkan daftar kendaraan (Merk) untuk dropdown (optional, jika mau fungsional)
$query_mobil = "SELECT id_mobil, merek, tarif_sewa_per_hari FROM mobil ORDER BY merek ASC";
$result_mobil = mysqli_query($koneksi, $query_mobil);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tarif Sewa - SIREMO</title>
    
    <link rel="stylesheet" href="../assets/style3.css"> 
    <link rel="stylesheet" href="../assets/style8.css"> <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
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
                <li class="menu-item active-link"><a href="tarif_sewa.php">Tarif Sewa</a></li>
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
            
            <div class="tarif-container">
                
                <div class="tarif-header">
                    <a href="dashboard.php" class="back-arrow"><i class="fas fa-arrow-left"></i></a>
                    <h1 class="tarif-title">Tarif Sewa</h1>
                </div>
                
                <?php echo $status_msg; ?>

                <div class="form-card-tarif">
                    <form method="POST" action="tarif_sewa.php">
                        <input type="hidden" name="submit_tarif" value="1">
                        
                        <div class="form-group-tarif">
                            <label for="kendaraan">Kendaraan</label>
                            <select id="kendaraan" name="kendaraan" required>
                                <option value="" disabled selected>Pilih Merk Kendaraan</option>
                                <?php 
                                if (mysqli_num_rows($result_mobil) > 0) {
                                    while($row_mobil = mysqli_fetch_assoc($result_mobil)) {
                                        // Tampilkan Merk mobil
                                        echo '<option value="' . htmlspecialchars($row_mobil['merk']) . '">'. htmlspecialchars($row_mobil['merk']) . ' (Rp. ' . number_format($row_mobil['tarif_sewa_per_hari'], 0, ',', '.') . ')</option>';
                                    }
                                } else {
                                    echo '<option value="">-- Tambahkan Mobil Dulu --</option>';
                                }
                                ?>
                            </select>
                        </div>
                        
                        <div class="form-group-tarif">
                            <label for="tarif_sewa">Tarif Sewa</label>
                            <input type="number" id="tarif_sewa" name="tarif_sewa" placeholder="Masukkan angka tarif sewa" required>
                        </div>
                        
                        <button type="submit" class="submit-tarif-btn">Submit</button>
                    </form>
                </div>
            </div>
            
        </div>
    </div>
</body>
</html>