<?php 
include '../config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id_user'])) {
    header("location: ../login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("location: riwayat.php");
    exit;
}

$id_transaksi = mysqli_real_escape_string($koneksi, $_GET['id']);
$id_user = $_SESSION['id_user'];

$query = mysqli_query($koneksi, "SELECT ts.*, m.merek, m.model, m.foto, m.plat_nomor, m.tarif_sewa_per_hari, p.nama, m.id_mobil
    FROM transaksi_sewa ts 
    JOIN mobil m ON ts.id_mobil = m.id_mobil 
    JOIN penyewa p ON ts.id_penyewa = p.id_penyewa
    WHERE ts.id_transaksi = '$id_transaksi' AND p.id_user = '$id_user'");

$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location='riwayat.php';</script>";
    exit;
}

// Cek apakah sudah memberikan ulasan
$cek_ulasan = mysqli_query($koneksi, "SELECT id_ulasan FROM ulasan WHERE id_transaksi = '$id_transaksi'");
$sudah_ulas = mysqli_num_rows($cek_ulasan) > 0;

$tgl_awal = new DateTime($data['tgl_sewa']);
$tgl_akhir = new DateTime($data['tgl_rencana_kembali']);
$selisih = $tgl_awal->diff($tgl_akhir);
$durasi_asli = ($selisih->days == 0) ? 1 : $selisih->days;

$harga_sewa_dasar = $data['tarif_sewa_per_hari'] * $durasi_asli;

include '../inc_penyewa/header.php'; 
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
    :root { --primary: #e67e22; --secondary: #fd7600ff; --light: #fbf5edf4; }
    body { background-color: var(--light); font-family: 'Inter', sans-serif; }
    .detail-page-wrapper { padding-top: 120px; padding-bottom: 50px; background-color: var(--light); min-height: 100vh; }
    .detail-container { max-width: 800px; margin: 0 auto; padding: 0 20px; }
    .btn-back { text-decoration: none; color: #eb6b10ff; font-size: 14px; margin-bottom: 20px; display: inline-block; }
    .detail-card { background: white; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 25px rgba(241, 8, 8, 0.05); border: 1px solid #eee; }
    .detail-header { background: var(--secondary); color: white; padding: 25px 30px; display: flex; justify-content: space-between; align-items: center; }
    .detail-header h2 { margin: 0; font-size: 20px; }
    .status-badge-detail { padding: 6px 15px; border-radius: 20px; font-size: 12px; font-weight: bold; background: rgba(255,255,255,0.2); }
    .detail-body { padding: 30px; }
    .info-grid { display: grid; grid-template-columns: 250px 1fr; gap: 30px; margin-bottom: 30px; }
    .info-grid img { width: 100%; border-radius: 15px; object-fit: cover; }
    .info-item { margin-bottom: 15px; }
    .info-item label { display: block; font-size: 11px; color: #94a3b8; text-transform: uppercase; font-weight: 700; }
    .info-item p { margin: 0; font-weight: 600; color: var(--secondary); }
    .invoice-box { background: #fdf7f7ff; border: 1px solid #f3e5d8; border-radius: 15px; padding: 20px; }
    .invoice-row { display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 14px; }
    .invoice-row.total { border-top: 2px dashed #e2e8f0; margin-top: 15px; padding-top: 15px; font-weight: 800; font-size: 18px; color: var(--primary); }
    .text-denda { color: #e74c3c; font-weight: bold; }
    .actions { display: flex; gap: 15px; margin-top: 30px; }
    .btn-action { flex: 1; padding: 14px; border-radius: 10px; text-align: center; text-decoration: none; font-weight: 700; border: none; cursor: pointer; transition: 0.3s; }
    .btn-print { background: #e2e8f0; color: #475569; }
    .btn-wa { background: #25d366; color: white; }
    .btn-review { background: var(--primary); color: white; }
    .btn-action:hover { opacity: 0.9; transform: translateY(-2px); }

    @media print {
        .btn-back, .actions, header, footer { display: none !important; }
        .detail-page-wrapper { padding-top: 0; }
        .detail-card { box-shadow: none; border: 1px solid #000; }
    }
</style>

<div class="detail-page-wrapper">
    <div class="detail-container">
        <a href="riwayat.php" class="btn-back"><i class="fa fa-arrow-left"></i> Kembali ke Riwayat</a>

        <div class="detail-card">
            <div class="detail-header">
                <div>
                    <span style="font-size: 10px; opacity: 0.7; font-weight: bold;">E-RECEIPT SIREMO</span>
                    <h2><strong>SRM-<?= date('Ymd', strtotime($data['tgl_sewa'])) . $data['id_transaksi']; ?></strong></h2>
                </div>
                <div class="status-badge-detail">
                    <?= strtoupper($data['status_transaksi']); ?>
                </div>
            </div>

            <div class="detail-body">
                <div class="info-grid">
                    <img src="../uploads/<?= $data['foto']; ?>" alt="Mobil">
                    <div>
                        <div class="info-item">
                            <label>Unit Kendaraan</label>
                            <p><?= $data['merek']; ?> <?= $data['model']; ?></p>
                        </div>
                        <div class="info-item">
                            <label>Nomor Plat</label>
                            <p><?= $data['plat_nomor']; ?></p>
                        </div>
                        <div class="info-item">
                            <label>Jadwal Sewa</label>
                            <p><?= date('d M Y', strtotime($data['tgl_sewa'])); ?> - <?= date('d M Y', strtotime($data['tgl_rencana_kembali'])); ?></p>
                        </div>
                        <div class="info-item">
                            <label>Nama Penyewa</label>
                            <p><?= $data['nama']; ?></p>
                        </div>
                    </div>
                </div>

                <h4 style="font-size: 16px; margin-bottom: 15px;"><i class="fa fa-receipt"></i> Rincian Biaya</h4>
                <div class="invoice-box">
                    <div class="invoice-row">
                        <span>Harga Sewa (<?= $durasi_asli; ?> Hari)</span>
                        <span>Rp <?= number_format($harga_sewa_dasar, 0, ',', '.'); ?></span>
                    </div>

                    <?php if ($data['denda'] > 0) : ?>
                    <div class="invoice-row text-denda">
                        <span>Denda (<?= $data['ulasan_denda']; ?>)</span>
                        <span>+ Rp <?= number_format($data['denda'], 0, ',', '.'); ?></span>
                    </div>
                    <?php endif; ?>

                    <div class="invoice-row total">
                        <span>Total Pembayaran</span>
                        <span>Rp <?= number_format($data['total_bayar'], 0, ',', '.'); ?></span>
                    </div>
                </div>

                <div class="actions">
                    <button onclick="window.print()" class="btn-action btn-print">
                        <i class="fa fa-print"></i> Cetak Struk
                    </button>

                    <?php if ($data['status_transaksi'] == 'Selesai') : ?>
                        <?php if (!$sudah_ulas) : ?>
                            <a href="ulasan.php?id=<?= $data['id_transaksi']; ?>" class="btn-action btn-review">
                                <i class="fa fa-star"></i> Beri Ulasan
                            </a>
                        <?php else : ?>
                            <button class="btn-action" disabled style="background: #d4edda; color: #155724; border: 1px solid #c3e6cb; cursor: default;">
                                <i class="fa fa-check-circle"></i> Sudah Diulas
                            </button>
                        <?php endif; ?>
                    <?php else : ?>
                        <a href="http://wa.me/62895402713691?text=Halo%20Admin,%20saya%20ingin%20bertanya%20tentang%20transaksi%20SRM-<?= date('Ymd') . $data['id_transaksi']; ?>" 
                           class="btn-action btn-wa" target="_blank">
                            <i class="fa-brands fa-whatsapp"></i> Hubungi Admin
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <p style="text-align: center; font-size: 12px; color: #94a3b8; margin-top: 20px;">
            Bukti pembayaran digital sah sebagai tanda terima penyewaan.
        </p>
    </div>
</div>

<?php include '../inc_penyewa/footer.php'; ?>