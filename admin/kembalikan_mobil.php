<?php
include '../config.php';
cek_akses('admin');

$nama_admin = $_SESSION['nama'];
$id = anti_injection($_GET['id']);

$query = mysqli_query($koneksi, "SELECT ts.*, m.merek, m.model, m.id_mobil FROM transaksi_sewa ts 
                                 JOIN mobil m ON ts.id_mobil = m.id_mobil 
                                 WHERE ts.id_transaksi = '$id'");
$data = mysqli_fetch_assoc($query);

// Jika data tidak ditemukan, balikkan ke transaksi
if (!$data) { header("location: transaksi.php"); exit; }

// Hitung Denda Telat Otomatis
$tgl_rencana = new DateTime($data['tgl_rencana_kembali']);
$tgl_sekarang = new DateTime(date('Y-m-d'));
$denda_telat = 0;
$pesan_telat = "";

if ($tgl_sekarang > $tgl_rencana) {
    $selisih = $tgl_sekarang->diff($tgl_rencana)->days;
    $denda_telat = $selisih * 50000; // 50rb per hari
    $pesan_telat = "Terlambat $selisih hari";
}
?>

<?php include 'partials/header.php'; ?>

<div class="dashboard-container">
    <?php include 'partials/sidebar.php'; ?>

    <div class="main-content">
        <div class="background-image"></div> 
        <div class="overlay"></div> 

        <header class="main-header" style="padding: 20px; position: relative; z-index: 2;">
            <h1 class="title">Proses Pengembalian</h1> 
            <p class="greeting">Input denda dan cek kondisi mobil</p>
        </header>

        <div class="content-box" style="background: white; padding: 30px; border-radius: 10px; max-width: 600px; margin: 0 auto; position: relative; z-index: 2;">
            <div style="margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid #eee;">
                <h3 style="margin: 0; color: #333;">Konfirmasi Unit Mobil</h3>
                <p style="color: #666; font-size: 14px;">Mobil: <strong><?= $data['merek']; ?> <?= $data['model']; ?></strong></p>
            </div>

            <form action="proses_kembali.php" method="POST">
                <input type="hidden" name="id_transaksi" value="<?= $id; ?>">
                <input type="hidden" name="id_mobil" value="<?= $data['id_mobil']; ?>">

                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: bold;">Nominal Denda (Rp)</label>
                    <input type="number" name="denda" value="<?= $denda_telat; ?>" 
                           style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; font-size: 16px;">
                    <?php if($denda_telat > 0): ?>
                        <small style="color: #e74c3c;">* Terdeteksi keterlambatan otomatis</small>
                    <?php endif; ?>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: bold;">Alasan / Ulasan Denda</label>
                    <textarea name="ulasan_denda" rows="4" 
                              style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px;" 
                              placeholder="Contoh: Terlambat, Body lecet, atau BBM tidak penuh"><?= $pesan_telat; ?></textarea>
                </div>
                
                <div style="display: flex; gap: 10px;">
                    <button type="submit" name="submit_kembali" 
                            style="flex: 2; background: #e67e22; color: white; border: none; padding: 15px; border-radius: 5px; cursor: pointer; font-weight: bold; font-size: 16px;">
                        <i class="fas fa-save"></i> Selesaikan Transaksi
                    </button>
                    <a href="transaksi.php" 
                       style="flex: 1; text-align: center; background: #95a5a6; color: white; padding: 15px; border-radius: 5px; text-decoration: none; font-weight: bold;">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>