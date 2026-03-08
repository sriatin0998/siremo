<<<<<<< HEAD
<?php 
include '../config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Validasi Login
if (!isset($_SESSION['id_user'])) {
    header("location: ../login.php");
    exit;
}

// 2. Ambil ID Transaksi dari URL
if (!isset($_GET['id'])) {
    header("location: riwayat.php");
    exit;
}

$id_transaksi = mysqli_real_escape_string($koneksi, $_GET['id']);
$id_user = $_SESSION['id_user'];

// 3. Pastikan transaksi benar-benar selesai dan milik user tersebut
$query = mysqli_query($koneksi, "SELECT ts.*, m.merek, m.model, m.foto 
    FROM transaksi_sewa ts 
    JOIN mobil m ON ts.id_mobil = m.id_mobil 
    JOIN penyewa p ON ts.id_penyewa = p.id_penyewa
    WHERE ts.id_transaksi = '$id_transaksi' AND p.id_user = '$id_user' AND ts.status_transaksi = 'Selesai'");

$data = mysqli_fetch_assoc($query);

// Jika transaksi tidak valid atau belum selesai, tendang balik ke riwayat
if (!$data) {
    echo "<script>alert('Ulasan hanya bisa diberikan untuk transaksi yang sudah selesai.'); window.location='riwayat.php';</script>";
    exit;
}

include '../inc_penyewa/header.php';
?>

<style>
    body { background-color: #fbf5edf4; font-family: 'Inter', sans-serif; }
    .review-page-wrapper { padding-top: 120px; padding-bottom: 50px; background-color: #fbf5edf4; min-height: 100vh; }
    .review-card { max-width: 500px; margin: 0 auto; background: white; border-radius: 20px; padding: 30px; box-shadow: 0 10px 25px rgba(241, 8, 8, 0.05); text-align: center; }

    .car-preview img { width: 150px; height: 100px; object-fit: cover; border-radius: 10px; margin-bottom: 15px; }
    
    .star-rating { display: flex; flex-direction: row-reverse; justify-content: center; gap: 10px; margin: 20px 0; }
    .star-rating input { display: none; }
    .star-rating label { font-size: 35px; color: #ddd; cursor: pointer; transition: 0.2s; }
    .star-rating input:checked ~ label, 
    .star-rating label:hover, 
    .star-rating label:hover ~ label { color: #f1c40f; }

    .form-control { width: 100%; border: 1px solid #e2e8f0; border-radius: 12px; padding: 15px; font-family: inherit; margin-bottom: 20px; resize: none; }
    .btn-submit { width: 100%; background: #e67e22; color: white; border: none; padding: 15px; border-radius: 12px; font-weight: 700; cursor: pointer; transition: 0.3s; }
    .btn-submit:hover { background: #d35400; }
    .btn-skip { display: block; margin-top: 15px; color: #94a3b8; text-decoration: none; font-size: 14px; }
</style>

<div class="review-page-wrapper">
    <div class="review-card">
        <div class="car-preview">
            <img src="../uploads/<?= $data['foto']; ?>" alt="Mobil">
            <h3 style="margin: 0;"><?= $data['merek']; ?> <?= $data['model']; ?></h3>
            <p style="color: #64748b; font-size: 14px;">Bagaimana pengalaman berkendara Anda?</p>
        </div>

        <form action="proses_ulasan.php" method="POST">
            <input type="hidden" name="id_transaksi" value="<?= $data['id_transaksi']; ?>">
            <input type="hidden" name="id_mobil" value="<?= $data['id_mobil']; ?>">
            <input type="hidden" name="id_penyewa" value="<?= $data['id_penyewa']; ?>">

            <div class="star-rating">
                <input type="radio" id="5" name="rating" value="5" required/><label for="5">★</label>
                <input type="radio" id="4" name="rating" value="4"/><label for="4">★</label>
                <input type="radio" id="3" name="rating" value="3"/><label for="3">★</label>
                <input type="radio" id="2" name="rating" value="2"/><label for="2">★</label>
                <input type="radio" id="1" name="rating" value="1"/><label for="1">★</label>
            </div>

            <textarea name="komentar" class="form-control" rows="4" placeholder="Tulis masukan Anda di sini (opsional)..."></textarea>
            
            <button type="submit" class="btn-submit">Kirim Ulasan</button>
            <a href="detail_riwayat.php?id=<?= $id_transaksi; ?>" class="btn-skip" style="color: #94a3b8;">Nanti Saja</a>
        </form>
    </div>
</div>

<?php include '../inc_penyewa/footer.php'; ?>
=======
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
>>>>>>> b8d9290bc2e45757458286a8f3a7331f3067501e
