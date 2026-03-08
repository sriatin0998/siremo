<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start(); 
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIREMO - Rental Mobil Lepas Kunci</title>
    <link rel="stylesheet" href="../css_penyewa/style_global.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<header class="main-header">
    <nav class="navbar">
        <div class="logo">
            <i class="fas fa-car car-icon-orange"></i> SIREMO
        </div>
        
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="mobil.php">Mobil</a></li>
            <li><a href="penyewaan.php">Booking</a></li>
            <li><a href="riwayat.php">Riwayat</a></li>
        </ul>

        <div class="header-auth">
            <?php if (isset($_SESSION['status']) && $_SESSION['status'] == 'login'): ?>
                <a href="profil.php" class="user-profile-link" style="text-decoration: none; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-circle-user" style="color: #e67e22; font-size: 1.3rem;"></i>
                    <span class="user-name" style="color: #333; font-weight: 600;">
                        <?= $_SESSION['nama']; ?>
                    </span>
                </a>
            <?php else: ?>
                <a href="../login.php" class="btn-login">Login / Daftar</a>
            <?php endif; ?>
        </div>
    </nav>
</header>