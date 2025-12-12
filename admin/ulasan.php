<?php
// ==========================================================
// BAGIAN 1: LOGIKA PHP & KONEKSI DATABASE
// ==========================================================
session_start();
// Pastikan path ke config.php sudah benar
include '../config.php'; 

// Cek status login
if (!isset($_SESSION['status']) || $_SESSION['status'] != 'login_admin' || $_SESSION['role'] != 'admin') {
    header("location: login.php");
    exit;
}

// Fungsi untuk menampilkan Rating Bintang (Menggunakan Font Awesome)
function displayStars($rating) {
    $output = '';
    $rating = (int)$rating;
    
    // Looping 5 kali untuk 5 bintang (sesuai visual gambar)
    for ($i = 1; $i <= 5; $i++) {
        $output .= '<i class="fas fa-star review-star"></i>'; 
    }
    return $output;
}

// Query untuk mengambil data ulasan dari tabel 'ulasan'
$sql = "SELECT id, nama, ulasan, rating FROM ulasan ORDER BY id DESC";
$result = mysqli_query($koneksi, $sql);

if (!$result) {
    // Tampilkan error query jika terjadi masalah koneksi/tabel
    die("Query Error: " . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ulasan Pelanggan - Sistem Rental Mobil</title>
    
    <link rel="stylesheet" href="../assets/style3.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
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
                <li class="menu-item"><a href="laporan_penyewaan.php">Laporan Penyewaan</a></li>
                <li class="menu-item active-link"><a href="ulasan.php">Ulasan</a></li> 
                
                <li class="menu-item-spacer"></li> 
                <li class="menu-item logout-link"><a href="logout.php">Logout</a></li>
            </ul>
        </div>

        <div class="main-content">
            <div class="background-image"></div> 
            <div class="overlay"></div> 
            
            <div class="ulasan-container">
                
                <div class="ulasan-header">
                    <a href="dashboard.php" class="back-arrow"><i class="fas fa-arrow-left"></i></a>
                    <h1 class="ulasan-title">Ulasan</h1>
                </div>

                <button class="tambah-komentar-btn" onclick="window.location.href='tambah_ulasan.php'">
                    <i class="fas fa-plus-circle"></i> Tambah Komentar
                </button>

                <div class="ulasan-box">
                    <table class="ulasan-table">
                        <thead>
                            <tr>
                                <th style="width: 20%;">Nama</th>
                                <th style="width: 45%;">Ulasan</th>
                                <th style="width: 20%;">Rating</th>
                                <th style="width: 15%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // ==========================================================
                            // BAGIAN 3: LOOPING DATA ULASAN DARI DATABASE
                            // ==========================================================
                            if (mysqli_num_rows($result) > 0) {
                                while($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($row["nama"]) . "</td>";
                                    echo "<td>" . htmlspecialchars($row["ulasan"]) . "</td>";
                                    echo "<td class='rating-cell'>" . displayStars($row["rating"]) . "</td>";
                                    // Tautan Aksi Hapus
                                    echo "<td><a href='hapus_ulasan.php?id=" . $row["id"] . "' class='action-link'>[ Hapus ]</a></td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo '<tr><td colspan="4" style="text-align: center; padding: 30px;">Belum ada ulasan saat ini.</td></tr>';
                            }
                            ?>
                            
                            <tr><td colspan="4" style="height: 50px; border-bottom: none;"></td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>