<?php
// WAJIB: Memulai sesi
session_start();

// Sertakan file koneksi database dari folder yang sama
include 'config.php'; 

// --- LOGIKA CEK STATUS LOGIN DAN DATA PENGGUNA (Mengikuti index.php) ---
$is_logged_in = isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true;
$user_name = $is_logged_in ? $_SESSION['nama_lengkap'] : 'Pengunjung';
$user_role = $is_logged_in ? $_SESSION['role'] : '';
$admin_link = ($user_role == 'admin') ? '<a href="../admin/kelola_mobil.php">Admin Dashboard</a>' : '';

// Jika formulir dikirim, tambahkan logika PHP di sini untuk proses penyimpanan/validasi.
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['lanjutkan_pembayaran'])) {
    // === L O G I K A P R O S E S P E N Y E W A A N D I S I N I ===
    
    // Contoh pengambilan data input teks (gunakan anti_injection jika Anda sudah mendefinisikannya di config.php)
    $nama_lengkap = isset($_POST['nama_lengkap']) ? htmlspecialchars($_POST['nama_lengkap']) : '';
    // $alamat_lengkap = ...
    // ...
    
    // Contoh penanganan upload file
    // $ktp_file = $_FILES['ktp_file']; 
    // Lanjutkan proses upload dan simpan data ke database...
    
    $pesan_status = "<p style='color: green; font-weight: bold; text-align: center;'>Data penyewaan untuk $nama_lengkap telah diterima (Langkah selanjutnya: Pembayaran).</p>";
} else {
    $pesan_status = "";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIREMO - Penyewaan</title>
    <link rel="stylesheet" href="../assets/style_penyewaan.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

    <header class="navbar">
        <div class="logo">
            <i class="fas fa-car car-icon"></i>
            <span>SIREMO</span>
        </div>
        <nav class="nav-links">
            <a href="../index.php">Home</a>
            <a href="../mobil.php">Mobil</a>
            <a href="penyewaan.php" class="active">Penyewaan</a>
            <a href="#">Pengembalian</a> 
            <a href="#">Ulasan</a> <?php echo $admin_link; ?>
            
            <?php if ($is_logged_in): ?>
                <span class="nav-greeting">Halo, <?php echo htmlspecialchars(explode(' ', $user_name)[0]); ?>!</span>
                <a href="logout.php" class="btn-auth">Logout</a>
            <?php else: ?>
                <a href="login.php" class="btn-auth">Login/Daftar</a>
            <?php endif; ?>
        </nav>
    </header>

    <main class="container">
        <?php echo $pesan_status; ?>
        <form action="penyewaan.php" method="post" enctype="multipart/form-data" class="rental-form">
            
            <div class="data-section">
                <h2>Lengkapi Data & Dokumen<br>Penyewaan</h2>
                
                <div class="input-group">
                    <label for="nama_lengkap">Nama Lengkap</label>
                    <input type="text" id="nama_lengkap" name="nama_lengkap" required>
                </div>
                
                <div class="input-group">
                    <label for="alamat_lengkap">Alamat Lengkap</label>
                    <input type="text" id="alamat_lengkap" name="alamat_lengkap" required>
                </div>
                
                <div class="input-group">
                    <label for="no_tlp">No Tlp</label>
                    <input type="tel" id="no_tlp" name="no_tlp" required>
                </div>
                
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <button type="submit" name="lanjutkan_pembayaran" class="lanjutkan-pembayaran-btn">Lanjutkan Pembayaran</button>
            </div>

            <div class="upload-section">
                <h2>Upload Dokumen (Foto KTP,<br>SIM, Bukti Pembayaran)</h2>
                
                <div class="upload-block">
                    <h3>KTP</h3>
                    <div class="upload-box">
                        <i class="fas fa-camera camera-icon"></i>
                        <label for="ktp_file" class="file-label">Pilih File/ Ambil Foto</label>
                        <input type="file" id="ktp_file" name="ktp_file" accept="image/*" class="file-input" required>
                    </div>
                </div>

                <div class="upload-block">
                    <h3>SIM</h3>
                    <div class="upload-box">
                        <i class="fas fa-camera camera-icon"></i>
                        <label for="sim_file" class="file-label">Pilih File/ Ambil Foto</label>
                        <input type="file" id="sim_file" name="sim_file" accept="image/*" class="file-input" required>
                    </div>
                </div>
                
                <div class="upload-block">
                    <h3>Bukti Pembayaran</h3>
                    <div class="upload-box">
                        <i class="fas fa-camera camera-icon"></i>
                        <label for="bukti_pembayaran_file" class="file-label">Pilih File/ Ambil Foto</label>
                        <input type="file" id="bukti_pembayaran_file" name="bukti_pembayaran_file" accept="image/*" class="file-input" required>
                    </div>
                </div>
            </div>
            
        </form>
    </main>

</body>
</html>