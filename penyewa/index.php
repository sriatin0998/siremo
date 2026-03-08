<?php 
session_start();
include '../config.php';
// Halaman ini sengaja tidak menggunakan cek_akses() agar bisa dilihat publik
?>

<?php include '../inc_penyewa/header.php'; ?>

<link rel="stylesheet" href="../css_penyewa/style_index.css">
<style>
html {
    scroll-behavior: smooth;
}
</style>
<main>
    <section class="hero-home">
        <div class="hero-content">
            <h1>Kendali Penuh di Tangan Anda</h1>
            <p>Sewa mobil lepas kunci dengan proses instan dan armada terawat.</p>
            
            <form class="booking-widget" action="<?= isset($_SESSION['status']) ? 'mobil.php' : '../login.php' ?>" method="GET">
                <div class="input-group">
                    <label><i class="fa fa-calendar-alt"></i> Tanggal Mulai</label>
                    <input type="date" name="tgl_mulai" min="<?= date('Y-m-d'); ?>" required>
                </div>
                <div class="input-group">
                    <label><i class="fa fa-clock"></i> Jam Ambil</label>
                    <input type="time" name="jam_ambil" required>
                </div>
                <div class="input-group">
                    <label><i class="fa fa-calendar-check"></i> Tanggal Kembali</label>
                    <input type="date" name="tgl_kembali" min="<?= date('Y-m-d'); ?>" required>
                </div>

                <?php if(!isset($_SESSION['status'])): ?>
                    <button type="button" onclick="alert('Silakan login terlebih dahulu untuk menyewa armada'); window.location='../login.php';" class="btn-search">
                        Login untuk Sewa
                    </button>
                <?php else: ?>
                    <button type="submit" class="btn-search">Cari Armada</button>
                <?php endif; ?>
            </form>
        </div>
    </section>

    <section class="section-container">
        <div class="section-title">
            <h2>Kenapa Lepas Kunci di SIREMO?</h2>
            <div class="underline"></div>
        </div>
        <div class="services-grid">
            <div class="service-card">
                <i class="fa fa-shield-alt"></i>
                <h3>Privasi Terjamin</h3>
                <p>Nikmati perjalanan pribadi bersama keluarga atau teman tanpa gangguan orang asing.</p>
            </div>
            <div class="service-card">
                <i class="fa fa-route"></i>
                <h3>Bebas Rute</h3>
                <p>Tentukan tujuan dan rute perjalanan Anda sendiri tanpa batasan waktu sopir.</p>
            </div>
            <div class="service-card">
                <i class="fa fa-tags"></i>
                <h3>Harga Lebih Hemat</h3>
                <p>Biaya sewa lebih terjangkau karena tanpa biaya tambahan jasa pengemudi.</p>
            </div>
        </div>
    </section>

    <section id="langkah-sewa" class="how-it-works">
        <div class="section-title">
            <h2 style="color: rgba(79, 38, 7, 1);">Langkah Mudah Ambil Unit</h2>
        </div>
        <div class="steps-container">
            <div class="step">
                <div class="step-number">1</div>
                <h4>Pilih Unit</h4>
                <p>Cari mobil yang sesuai dengan gaya berkendara Anda.</p>
            </div>
            <div class="step">
                <div class="step-number">2</div>
                <h4>Upload SIM A</h4>
                <p>Lengkapi verifikasi identitas secara digital di sistem kami.</p>
            </div>
            <div class="step">
                <div class="step-number">3</div>
                <h4>Bayar Online</h4>
                <p>Lakukan pembayaran DP atau pelunasan melalui transfer bank.</p>
            </div>
            <div class="step">
                <div class="step-number">4</div>
                <h4>Ambil Mobil</h4>
                <p>Datang ke lokasi atau unit kami antar ke lokasi Anda.</p>
            </div>
        </div>
    </section>
</main>

<?php include '../inc_penyewa/footer.php'; ?>