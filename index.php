<?php
// WAJIB: Memulai sesi untuk mengakses status login
session_start();

// Sertakan file koneksi database. Path disesuaikan karena index.php di luar folder penyewa.
include 'penyewa/config.php'; 

// Data dummy mobil populer (tetap pakai array ini, atau ganti dengan query database)
$cars = [
    ['name' => 'HONDA BRIO', 'image' => 'assets/honda_brio.jpg', 'price' => 'Rp 300.000/hari'], 
    ['name' => 'TOYOTA AVANZA', 'image' => 'assets/toyota_avanza.jpg', 'price' => 'Rp 450.000/hari'],
    ['name' => 'TOYOTA FORTUNER', 'image' => 'assets/toyota_fortuner.jpg', 'price' => 'Rp 800.000/hari'],
];

// --- LOGIKA CEK STATUS LOGIN DAN DATA PENGGUNA ---
$is_logged_in = false;
$user_name = 'Pengunjung';
$user_role = '';
$admin_link = '';

if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) {
    $is_logged_in = true;
    $user_name = $_SESSION['nama_lengkap'];
    $user_role = $_SESSION['role'];

    // Cek Role untuk tautan Admin
    if ($user_role == 'admin') {
        $admin_link = '<a href="admin/kelola_mobil.php" class="nav-button">Admin Dashboard</a>';
    }
}
// --------------------------------------------------
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIREMO - Home Penyewa</title>
    <link rel="stylesheet" href="assets/style_home.css"> 
</head>
<body>

    <nav class="navbar">
        <div class="logo">
            <img src="assets/car_icon.png" alt="Logo Mobil" class="car-icon">
            <span>SIREMO</span>
        </div>
        <div class="nav-links">
            <a href="index.php" class="nav-button active">Home</a>
            <a href="mobil.php">Mobil</a>
            <a href="#">Penyewaan</a>
            <a href="#">Ulasan</a>
            
            <?php echo $admin_link; // Tampilkan Admin link jika ada ?>

            <?php if ($is_logged_in): ?>
                <span class="nav-greeting">Halo, <?php echo htmlspecialchars(explode(' ', $user_name)[0]); ?>!</span>
                <a href="penyewa/logout.php" class="nav-button btn-auth">Logout</a>
            <?php else: ?>
                <a href="penyewa/login.php" class="nav-button btn-auth active">Login/Daftar</a>
            <?php endif; ?>
        </div>
    </nav>

    <header class="hero-section">
        <div class="hero-content">
            <h1>Ingin Berpetualangan Tapi Bingung Ngga Ada Mobil?</h1>
            <h2>SIREMO Hadir Untuk Menemani Anda Berpetualang</h2>
            <p>Petualangan Anda Dimulai Di Sini!!</p>
            <a href="mobil.php" class="btn-search">Cari Mobil</a>
        </div>
    </header>

    <section class="popular-cars">
        <h3>Mobil Populer</h3>
        <div class="car-list">
            <?php 
            // Loop data mobil
            foreach ($cars as $car): 
            ?>
            <div class="car-card">
                <h4><?php echo $car['name']; ?></h4>
                <img src="<?php echo $car['image']; ?>" alt="<?php echo $car['name']; ?>">
                <p class="car-price"><?php echo $car['price']; ?></p>
            </div>
            <?php 
            endforeach; 
            // Tutup koneksi database
            $conn->close();
            ?>
        </div>
    </section>

</body>
</html>