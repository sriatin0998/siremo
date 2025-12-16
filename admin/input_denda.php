<?php
session_start();
include '../config.php'; 

if (!isset($_SESSION['status']) || $_SESSION['status'] != 'login_admin' || $_SESSION['role'] != 'admin') {
    header("location: login.php");
    exit;
}
$status_msg = "";

if (isset($_POST['submit_denda'])) {
    $tarif_denda = mysqli_real_escape_string($koneksi, $_POST['tarif_denda']);
    $alasan = mysqli_real_escape_string($koneksi, $_POST['alasan']);
    
    // Pesan Sukses untuk tampilan
    $status_msg = "<div class='success-msg'>Denda sebesar Rp. " . number_format($tarif_denda, 0, ',', '.') . " untuk alasan **" . htmlspecialchars($alasan) . "** berhasil dicatat (Simulasi).</div>";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Denda - SIREMO</title>
    
    <link rel="stylesheet" href="../assets/style3.css"> 
    <link rel="stylesheet" href="../assets/style_form.css"> 
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
            
            <div class="form-page-container full-width">
                
                <div class="form-header header-with-sidebar">
                    <a href="pengembalian.php" class="back-arrow"><i class="fas fa-arrow-left"></i></a>
                    <h1 class="form-title">Denda</h1>
                </div>
                
                <?php echo $status_msg; ?>

                <div class="form-card form-centered">
                    <form method="POST" action="input_denda.php">
                        
                        <div class="form-group">
                            <label for="tarif_denda">Tarif Denda</label>
                            <input type="number" id="tarif_denda" name="tarif_denda" placeholder="Masukkan angka tarif denda" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="alasan">Alasan</label>
                            <input type="text" id="alasan" name="alasan" placeholder="Misal: Keterlambatan 1 hari, Lecet di pintu" required>
                        </div>
                        
                        <button type="submit" name="submit_denda" class="submit-denda-btn">Submit</button>
                    </form>
                </div>
            </div>
            
        </div>
    </div>
    </body>
</html>