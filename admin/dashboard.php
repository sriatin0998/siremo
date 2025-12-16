<?php
session_start();
include '../config.php'; 

if (!isset($_SESSION['status']) || $_SESSION['status'] != 'login_admin' || $_SESSION['role'] != 'admin') {
    header("location: login.php");
    exit;
}

$username = $_SESSION['username'];
$nama_lengkap = isset($_SESSION['nama_lengkap']) ? $_SESSION['nama_lengkap'] : 'Admin'; 

$total_mobil = 9;
$total_penyewa = 4;
$pendapatan = "Rp. 6.325.000";
$transaksi = 4;
$pengembalian = 0;
$tarif_sewa = "Rp.275.000 - 1.300.000";
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Rental Mobil Dashboard</title>
    <link rel="stylesheet" href="../assets/style3.css"> 
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
                <li class="menu-item active-link"><a href="dashboard.php">Dashboard</a></li> 
                <li class="menu-item"><a href="data_mobil.php">Data Mobil</a></li>
                <li class="menu-item"><a href="kelola_penyewa.php">Data Penyewa</a></li>
                <li class="menu-item"><a href="transaksi.php">Transaksi</a></li>
                <li class="menu-item"><a href="tarif_sewa.php">Tarif Sewa</a></li>
                <li class="menu-item"><a href="pengembalian.php">Pengembalian</a></li>
                <li class="menu-item"><a href="laporan_ulasan.php">Laporan Penyewaan</a></li>
                <li class="menu-item"><a href="ulasan.php">Ulasan</a></li>
                
                <li class="menu-item-spacer"></li> 
                <li class="menu-item logout-link"><a href="logout.php">Logout</a></li>
            </ul>
        </div>

        <div class="main-content">
            <div class="background-image"></div> 
            <div class="overlay"></div> 
            
            <header class="main-header">
                <h1 class="title">Sistem Rental Mobil</h1>
                <p class="greeting">Hii <?php echo $nama_lengkap; ?>!!</p>
                
                <button class="tambah-ulasan-btn">
                    <i class="fas fa-plus-circle"></i> Tambah Ulasan
                </button>
            </header>

            <div class="cards-grid">
                
                <div class="card">
                    <div class="card-content">
                        <p class="card-label">Total Mobil</p>
                        <h2 class="card-value"><?php echo $total_mobil; ?></h2>
                    </div>
                </div>

                <div class="card">
                    <div class="card-content">
                        <p class="card-label">Total Penyewa</p>
                        <h2 class="card-value"><?php echo $total_penyewa; ?></h2>
                    </div>
                </div>

                <div class="card">
                    <div class="card-content">
                        <p class="card-label">Pendapatan</p>
                        <h2 class="card-value large-value">Rp.<br><?php echo substr($pendapatan, 5); ?></h2>
                    </div>
                </div>

                <div class="card">
                    <div class="card-content">
                        <p class="card-label">Transaksi</p>
                        <h2 class="card-value"><?php echo $transaksi; ?></h2>
                    </div>
                </div>

                <div class="card">
                    <div class="card-content">
                        <p class="card-label">Pengembalian</p>
                        <h2 class="card-value"><?php echo $pengembalian; ?></h2>
                    </div>
                </div>

                <div class="card">
                    <div class="card-content">
                        <p class="card-label">Tarif Sewa</p>
                        <h2 class="card-value large-value">Rp.275.000 -<br>1.300.000</h2>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</body>
</html>