<?php
// pastikan session sudah aktif
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// proteksi halaman admin
if (!isset($_SESSION['status']) || $_SESSION['status'] !== 'login' || $_SESSION['role'] !== 'admin') {
    header("location: login.php");
    exit;
}

$nama_lengkap = $_SESSION['nama_lengkap'] ?? 'Admin';
?>

<div class="sidebar">
    
    <div href='../index.php' class="logo-siermo">
        <i class="fas fa-car car-icon-orange"></i>
        <h2 class="logo-text">SIREMO</h2> 
    </div>
    
    <ul class="sidebar-menu">
        <li class="menu-item">
            <a href="dashboard.php">Dashboard</a>
        </li>
        <li class="menu-item">
            <a href="data_mobil.php">Data Mobil</a>
        </li>
        <li class="menu-item">
            <a href="data_penyewa.php">Data Penyewa</a>
        </li>
        <li class="menu-item">
            <a href="transaksi.php">Transaksi</a>
        </li>
        <li class="menu-item">
            <a href="pengembalian.php">Pengembalian</a>
        </li>
        <li class="menu-item">
            <a href="ulasan.php">Ulasan</a>
        </li>

        <li class="menu-item-spacer"></li>
        <li class="menu-item logout-link">
            <a href="../logout.php">Logout</a>
        </li>
    </ul>
</div>