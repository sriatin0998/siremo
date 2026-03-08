<<<<<<< HEAD
<?php 
include '../config.php';
session_start();

// 1. Validasi Login
if (!isset($_SESSION['id_user'])) {
    header("location: ../login.php");
    exit;
}

// 2. Ambil Data Penyewa (Profil)
$id_login = $_SESSION['id_user'];
$query_user = mysqli_query($koneksi, "SELECT * FROM penyewa WHERE id_user = '$id_login'");
$data_user = mysqli_fetch_assoc($query_user);

// 3. Validasi Kelengkapan Data Identitas (Gatekeeping)
if (empty($data_user['no_ktp']) || empty($data_user['foto_sim']) || empty($data_user['alamat'])) {
    echo "<script>
            alert('Mohon maaf, Anda harus melengkapi KTP, SIM, dan Alamat di profil sebelum melakukan booking.');
            window.location='profil.php';
          </script>";
    exit;
}

// 4. Logika Pengambilan Data Mobil (Support Navbar & Button)
if (isset($_GET['id'])) {
    // JALUR 1: Klik "Sewa Sekarang" di katalog
    $id_mobil = mysqli_real_escape_string($koneksi, $_GET['id']);
    $query_mobil = mysqli_query($koneksi, "SELECT * FROM mobil WHERE id_mobil = '$id_mobil'");
    $mobil = mysqli_fetch_assoc($query_mobil);

    if (!$mobil) {
        header("location: mobil.php");
        exit;
    }
} else {
    // JALUR 2: Klik menu "Booking" di Navbar secara langsung
    $query_default = mysqli_query($koneksi, "SELECT * FROM mobil WHERE status_ketersediaan = 'Tersedia' LIMIT 1");
    $mobil = mysqli_fetch_assoc($query_default);

    if (!$mobil) {
        echo "<script>
                alert('Maaf, saat ini tidak ada armada yang tersedia. Silakan hubungi admin.');
                window.location='index.php';
              </script>";
        exit;
    }
    $id_mobil = $mobil['id_mobil'];
}

include '../inc_penyewa/header.php'; 
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
    :root { --primary: #e67e22; --dark: #2c3e50; --light: #fbf5edf4; }
    body { background-color: var(--light); font-family: 'Poppins', sans-serif; }
    .checkout-wrapper { max-width: 1100px; margin: 40px auto; padding: 20px; }
    .card-custom { background: white; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); padding: 30px; margin-bottom: 20px; }
    .section-title { color: var(--dark); font-weight: 700; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }
    .section-title i { color: var(--primary); }
    .info-box { background: #fff9f4; border: 1px dashed var(--primary); border-radius: 10px; padding: 15px; margin-top: 15px; }
    .summary-card { position: sticky; top: 20px; }
    .btn-confirm { width: 100%; background: var(--primary); color: white; border: none; padding: 15px; border-radius: 10px; font-weight: bold; cursor: pointer; transition: 0.3s; font-size: 16px; }
    .btn-confirm:hover { background: #d35400; transform: translateY(-2px); }
    .form-control-static { background: #f9f9f9; padding: 12px; border-radius: 8px; border: 1px solid #ddd; display: block; width: 100%; margin-top: 5px; }
    input[type="date"] { width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #ddd; }
    
    /* Tombol Pilih Mobil Lain */
    .btn-change { 
        background: #fdf2e9; 
        color: var(--primary); 
        border: 1px solid var(--primary); 
        padding: 8px 12px; 
        border-radius: 8px; 
        text-decoration: none; 
        transition: 0.3s; 
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .btn-change:hover { background: var(--primary); color: white; }
</style>

<div class="checkout-wrapper">
    <div style="text-align: center; margin-bottom: 40px;">
        <h1 style="color: var(--dark);">Konfirmasi Pemesanan</h1>
        <p style="color: #7f8c8d;">Lengkapi detail perjalanan Anda untuk armada <strong>Lepas Kunci</strong></p>
    </div>

    <form action="proses_penyewaan.php" method="POST" enctype="multipart/form-data">
        <div style="display: grid; grid-template-columns: 1.6fr 1fr; gap: 30px;">
            
            <div>
                <div class="card-custom">
                    <h3 class="section-title"><i class="fa-solid fa-user-shield"></i> Data Identitas</h3>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div>
                            <label style="font-size: 13px; font-weight: 600;">Nama Lengkap</label>
                            <span class="form-control-static"><?= $data_user['nama']; ?></span>
                        </div>
                        <div>
                            <label style="font-size: 13px; font-weight: 600;">Nomor SIM A</label>
                            <span class="form-control-static"><?= $data_user['no_sim']; ?></span>
                        </div>
                    </div>
                </div>

                <div class="card-custom">
                    <h3 class="section-title"><i class="fa-solid fa-calendar-days"></i> Durasi Sewa</h3>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div>
                            <label style="font-size: 13px; font-weight: 600;">Tanggal Mulai</label>
                            <input type="date" name="tgl_sewa" required id="tgl_sewa">
                        </div>
                        <div>
                            <label style="font-size: 13px; font-weight: 600;">Tanggal Selesai</label>
                            <input type="date" name="tgl_kembali" required id="tgl_kembali">
                        </div>
                    </div>
                </div>

                <div class="card-custom">
                    <h3 class="section-title"><i class="fa-solid fa-credit-card"></i> Metode Pembayaran</h3>
                    <select name="metode_bayar" id="pilih_metode" required style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #ddd; margin-bottom: 15px;">
                        <option value="" disabled selected>Pilih Cara Pembayaran...</option>
                        <option value="Tunai">Bayar Tunai</option>
                        <option value="Transfer Bank">Transfer Bank</option>
                        <option value="E-Wallet">E-Wallet</option>
                    </select>

                    <div id="sub_bank" style="display: none; margin-bottom: 15px;">
                        <label style="font-size: 13px; font-weight: 600;">Pilih Bank:</label>
                        <select id="pilih_bank" class="form-control-static" style="background: white;">
                            <option value="" disabled selected>-- Pilih Bank --</option>
                            <option value="BCA" data-norek="123-456-7890">BCA</option>
                            <option value="Mandiri" data-norek="987-654-3210">Mandiri</option>
                            <option value="BNI" data-norek="555-666-7777">BNI</option>
                        </select>
                    </div>

                    <div id="sub_ewallet" style="display: none; margin-bottom: 15px;">
                        <label style="font-size: 13px; font-weight: 600;">Pilih E-Wallet:</label>
                        <select id="pilih_app" class="form-control-static" style="background: white;">
                            <option value="" disabled selected>-- Pilih Aplikasi --</option>
                            <option value="DANA" data-norek="0812-3456-7890">DANA</option>
                            <option value="OVO" data-norek="0812-3456-7890">OVO</option>
                            <option value="GoPay" data-norek="0812-3456-7890">GoPay</option>
                        </select>
                    </div>

                    <div id="box_digital" style="display: none;" class="info-box">
                        <div style="background: #fff; padding: 10px; border-radius: 5px; border: 1px solid #ffccbc; margin-bottom: 10px;">
                            <p id="instruksi_teks" style="margin: 0; font-weight: bold; color: #d35400;"></p>
                        </div>
                        <div>
                            <label style="font-size: 13px; font-weight: 600; display: block; margin-bottom: 5px;">Unggah Bukti Transfer</label>
                            <input type="file" name="bukti_bayar" id="input_bukti">
                        </div>
                    </div>
                </div>
            </div>

            <div class="summary-card">
                <div class="card-custom">
                    <h3 class="section-title">Ringkasan Biaya</h3>
                    
                    <div style="display: flex; gap: 15px; margin-bottom: 20px; align-items: center;">
                        <img src="../uploads/<?= $mobil['foto']; ?>" style="width: 100px; height: 65px; object-fit: cover; border-radius: 10px;">
                        <div style="flex-grow: 1;">
                            <h4 style="margin: 0;"><?= $mobil['merek']; ?></h4>
                            <p style="margin: 0; font-size: 13px; color: #7f8c8d;"><?= $mobil['model']; ?></p>
                        </div>
                        <a href="mobil.php" class="btn-change" title="Ganti Mobil">
                            <i class="fa-solid fa-car-side"></i>
                        </a>
                    </div>

                    <div style="border-top: 1px solid #eee; padding-top: 15px;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                            <span>Harga / Hari</span>
                            <span>Rp <?= number_format($mobil['tarif_sewa_per_hari'], 0, ',', '.'); ?></span>
                        </div>
                        <div style="display: flex; justify-content: space-between; font-weight: bold; font-size: 18px; color: var(--primary); margin-top: 20px;">
                            <span>Total Bayar</span>
                            <span id="display_total">Rp <?= number_format($mobil['tarif_sewa_per_hari'], 0, ',', '.'); ?></span>
                        </div>
                    </div>

                    <input type="hidden" name="id_mobil" value="<?= $id_mobil; ?>">
                    <input type="hidden" name="id_penyewa" value="<?= $data_user['id_penyewa']; ?>">
                    <input type="hidden" name="harga_harian" id="harga_harian" value="<?= $mobil['tarif_sewa_per_hari']; ?>">
                    <input type="hidden" name="total_bayar" id="total_bayar_input" value="<?= $mobil['tarif_sewa_per_hari']; ?>">

                    <button type="submit" name="konfirmasi_sewa" class="btn-confirm" style="margin-top: 30px;">
                        Konfirmasi Sewa <i class="fa-solid fa-paper-plane" style="margin-left: 10px;"></i>
                    </button>
                    <p style="text-align: center; font-size: 11px; color: #95a5a6; margin-top: 15px;">
                        <i class="fa-solid fa-shield-halved"></i> Transaksi Aman & Terenkripsi
                    </p>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    const pilihMetode = document.getElementById('pilih_metode');
    const subBank = document.getElementById('sub_bank');
    const subEwallet = document.getElementById('sub_ewallet');
    const boxDigital = document.getElementById('box_digital');
    const instruksi = document.getElementById('instruksi_teks');
    const inputBukti = document.getElementById('input_bukti');
    const pilihBank = document.getElementById('pilih_bank');
    const pilihApp = document.getElementById('pilih_app');

    function resetDisplay() {
        subBank.style.display = 'none';
        subEwallet.style.display = 'none';
        boxDigital.style.display = 'none';
        inputBukti.required = false;
        instruksi.innerText = '';
    }

    pilihMetode.addEventListener('change', function() {
        resetDisplay();
        if (this.value === 'Transfer Bank') subBank.style.display = 'block';
        else if (this.value === 'E-Wallet') subEwallet.style.display = 'block';
    });

    pilihBank.addEventListener('change', function() {
        const norek = this.options[this.selectedIndex].getAttribute('data-norek');
        boxDigital.style.display = 'block';
        inputBukti.required = true;
        instruksi.innerHTML = `<i class="fa-solid fa-university"></i> Transfer ke ${this.value}: <br> <span style="font-size: 18px;">${norek}</span>`;
    });

    pilihApp.addEventListener('change', function() {
        const norek = this.options[this.selectedIndex].getAttribute('data-norek');
        boxDigital.style.display = 'block';
        inputBukti.required = true;
        instruksi.innerHTML = `<i class="fa-solid fa-mobile-screen"></i> Bayar via ${this.value}: <br> <span style="font-size: 18px;">${norek}</span>`;
    });

    const tglSewa = document.getElementById('tgl_sewa');
    const tglKembali = document.getElementById('tgl_kembali');
    const hargaHarian = parseInt(document.getElementById('harga_harian').value);
    const displayTotal = document.getElementById('display_total');
    const totalInput = document.getElementById('total_bayar_input');

    function hitungTotal() {
        if (tglSewa.value && tglKembali.value) {
            const start = new Date(tglSewa.value);
            const end = new Date(tglKembali.value);
            if (end >= start) {
                const diffTime = Math.abs(end - start);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) || 1; 
                const total = diffDays * hargaHarian;
                displayTotal.innerText = 'Rp ' + total.toLocaleString('id-ID');
                totalInput.value = total;
            } else {
                alert("Tanggal kembali tidak boleh sebelum tanggal sewa!");
                tglKembali.value = "";
            }
        }
    }
    tglSewa.addEventListener('change', hitungTotal);
    tglKembali.addEventListener('change', hitungTotal);
</script>

<?php include '../inc_penyewa/footer.php'; ?>
=======
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
>>>>>>> b8d9290bc2e45757458286a8f3a7331f3067501e
