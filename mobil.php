<?php
session_start();
include 'penyewa/config.php'; 

// Data Mobil LENGKAP untuk Halaman Mobil (Gabungan semua gambar mobil)
$all_cars = [
    ['name' => 'TOYOTA AGYA', 'image' => 'assets/agya.png', 'price' => 'Rp 350.000/hari'],
    ['name' => 'TOYOTA CALYA', 'image' => 'assets/calya.png', 'price' => 'Rp 400.000/hari'],
    ['name' => 'DAIHATSU XENIA', 'image' => 'assets/xenia.png', 'price' => 'Rp 450.000/hari'],
    ['name' => 'HONDA BRIO', 'image' => 'assets/brio.png', 'price' => 'Rp 300.000/hari'],
    ['name' => 'TOYOTA AVANZA', 'image' => 'assets/avanza.png', 'price' => 'Rp 450.000/hari'],
    ['name' => 'TOYOTA FORTUNER', 'image' => 'assets/fortuner.png', 'price' => 'Rp 800.000/hari'],
    ['name' => 'TOYOTA HIACE COMMUTER', 'image' => 'assets/hiace.png', 'price' => 'Rp 1.000.000/hari'],
    ['name' => 'HYUNDAI H-1', 'image' => 'assets/h1.png', 'price' => 'Rp 900.000/hari'],
    ['name' => 'DAIHATSU GRANMAX BLIN VAN', 'image' => 'assets/granmax.png', 'price' => 'Rp 500.000/hari'],
];

// Logika Cek Status Login
$is_logged_in = isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true;
$user_name = $is_logged_in ? $_SESSION['nama_lengkap'] : 'Pengunjung';
$user_role = $is_logged_in ? $_SESSION['role'] : '';
$admin_link = ($user_role == 'admin') ? '<a href="admin/kelola_mobil.php" class="nav-button">Admin Dashboard</a>' : '';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="stylesheet" href="assets/style_mobil.css"> 
</head>
<body>
    
    <nav class="navbar nav-transparent"> 
        <div class="logo">
            <img src="assets/car_icon_orange.png" alt="Logo Mobil" class="car-icon">
            <span>SIREMO</span>
        </div>
        <div class="nav-links">
            <a href="index.php" class="nav-button">Home</a>
            <a href="mobil.php" class="nav-button active">Mobil</a>
            <a href="#" class="nav-button">Penyewaan</a>
            <a href="#" class="nav-button">Pengembalian</a>
            <a href="#" class="nav-button">Ulasan</a>
            
            <?php // ... (Logika login/logout) ... ?>
            <?php if ($is_logged_in): ?>
                <span class="nav-greeting nav-text-dark">Halo, <?php echo htmlspecialchars(explode(' ', $user_name)[0]); ?>!</span>
                <a href="penyewa/logout.php" class="nav-button btn-auth">Logout</a>
            <?php else: ?>
                <a href="penyewa/login.php" class="nav-button btn-auth">Login/Daftar</a>
            <?php endif; ?>
        </div>
    </nav>

    <header class="hero-section hero-mobil"></header>

    <main class="extended-cars-section mobil-page-content">
        <div class="extended-car-list">
            <?php foreach ($all_cars as $car): ?>
            <div class="car-card extended">
                <h4><?php echo $car['name']; ?></h4>
                <img src="<?php echo $car['image']; ?>" alt="<?php echo $car['name']; ?>">
                <a href="#" class="btn-detail">Detail</a>
            </div>
            <?php endforeach; ?>
        </div>
        
        <a href="#" class="scroll-up-arrow" id="scrollUp">
            </a>
    </main>
    
    </body>
</html>