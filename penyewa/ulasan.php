<?php
session_start();
// Menghubungkan ke file konfigurasi database jika diperlukan
include 'config.php';

// Data Dummy Ulasan (Nantinya data ini bisa diambil dari database)
$reviews = [
    [
        'nama' => 'Sri Atin',
        'foto' => '../assets/avatar1.jpg', // Pastikan Anda memiliki file gambar di folder assets
        'komentar' => 'Mobilnya bagus, pokoknya TOP'
    ],
    [
        'nama' => 'Umroh',
        'foto' => '../assets/avatar2.jpg',
        'komentar' => 'Mobilnya wangi, bersih keren!!'
    ],
    [
        'nama' => 'Ekamay',
        'foto' => '../assets/avatar3.jpg',
        'komentar' => 'Langganan banget rental disini Sukaa'
    ],
    [
        'nama' => 'Nur Aini',
        'foto' => '../assets/avatar4.jpg',
        'komentar' => 'Rentalnya best!!'
    ]
];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Review - SIREMO</title>
    <link rel="stylesheet" href="../assets/style_ulasan.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <div class="page-wrapper">
        <header class="header-brand">
            <div class="brand-group">
                <i class="fas fa-car car-icon-orange"></i>
                <span class="logo-text">SIREMO</span>
            </div>
        </header>

        <main class="content-container">
            <div class="review-header">
                <div class="rating-summary">
                    <h2>Customer Review</h2>
                    <div class="score">4.8 / 5</div>
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                </div>
                
                <div class="action-section">
                    <button class="btn-tambah" onclick="window.location.href='tambah_ulasan.php'">
                        <i class="fas fa-plus-circle"></i> Tambah Ulasan
                    </button>
                </div>
            </div>

            <div class="reviews-grid">
                <?php foreach ($reviews as $review): ?>
                <div class="review-card">
                    <div class="user-info">
                        <img src="<?php echo $review['foto']; ?>" alt="Foto <?php echo $review['nama']; ?>" class="avatar">
                        <div class="text-details">
                            <h4 class="user-name"><?php echo $review['nama']; ?></h4>
                            <p class="comment"><?php echo $review['komentar']; ?></p>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </main>
    </div>

</body>
</html>