<?php 
session_start();
include '../config.php';
include '../inc_penyewa/header.php'; 
?>

<link rel="stylesheet" href="../css_penyewa/style_index.css">
<style>
    .tnc-container {
        max-width: 800px;
        margin: 50px auto;
        padding: 30px;
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        line-height: 1.8;
    }
    .tnc-container h2 { color: #4f2607; margin-bottom: 20px; border-bottom: 2px solid #ff8c00; display: inline-block; }
    .tnc-container h3 { color: #ff8c00; margin-top: 25px; font-size: 1.2rem; }
    .tnc-container ul { padding-left: 20px; }
</style>

<main style="background: #fdf2e9; padding: 20px 0;">
    <div class="tnc-container">
        <h2>Syarat & Ketentuan</h2>
        <p>Selamat datang di SIREMO. Mohon baca syarat dan ketentuan penyewaan lepas kunci di bawah ini dengan saksama:</p>

        <h3>1. Persyaratan Identitas</h3>
        <ul>
            <li>Penyewa wajib memiliki **SIM A** yang masih aktif.</li>
            <li>Mengunggah foto SIM asli saat verifikasi.</li>
            <li>Menjamin keaslian data yang diunggah ke sistem.</li>
        </ul>

        <h3>2. Penggunaan Armada</h3>
        <ul>
            <li>Kendaraan hanya boleh digunakan di wilayah yang telah disepakati.</li>
            <li>Dilarang menggunakan kendaraan untuk tindak kriminal atau balapan.</li>
            <li>Penyewa bertanggung jawab penuh atas kebersihan dan keamanan unit selama masa sewa.</li>
        </ul>

        <h3>3. Pembayaran & Pembatalan</h3>
        <ul>
            <li>Pembayaran dilakukan di muka (DP) melalui transfer bank yang tersedia maupun tunai.</li>
            <li>Pembatalan kurang dari 24 jam sebelum jadwal ambil akan dikenakan denda 50%.</li>
        </ul>

        <h3>4. Kerusakan & Kehilangan</h3>
        <ul>
            <li>Segala bentuk kerusakan (lecet, penyok) menjadi tanggung jawab penyewa.</li>
            <li>Jika terjadi kehilangan, penyewa wajib mengganti unit sesuai dengan nilai pasar yang berlaku, beserta denda dengan nominal yang ditentukan.</li>
        </ul>

        <div style="margin-top: 30px; text-align: center;">
            <a href="index.php" class="btn-search" style="text-decoration: none; padding: 10px 20px;">Kembali ke Beranda</a>
        </div>
    </div>
</main>

<?php include '../inc_penyewa/footer.php'; ?>