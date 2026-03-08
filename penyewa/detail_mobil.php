<?php 
include '../config.php';

if (!isset($_GET['id'])) {
    header("location: mobil.php");
    exit;
}

$id = mysqli_real_escape_string($koneksi, $_GET['id']);
$query = mysqli_query($koneksi, "SELECT * FROM mobil WHERE id_mobil = '$id'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    header("location: mobil.php");
    exit;
}
?>

<?php include '../inc_penyewa/header.php'; ?>

<link rel="stylesheet" href="../css_penyewa/style_detail.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
    /* Tambahan Style untuk Ulasan */
    .ulasan-section { margin-top: 50px; padding-top: 30px; border-top: 2px solid #eee; }
    .ulasan-card { background: #fff; border-radius: 12px; padding: 20px; margin-bottom: 15px; border: 1px solid #f0f0f0; box-shadow: 0 2px 5px rgba(0,0,0,0.02); }
    .ulasan-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; }
    .ulasan-user { font-weight: 700; color: #2c3e50; font-size: 15px; }
    .ulasan-rating { color: #f1c40f; font-size: 14px; }
    .ulasan-text { color: #555; font-style: italic; line-height: 1.6; font-size: 14px; margin: 0; }
    .ulasan-date { font-size: 11px; color: #999; margin-top: 10px; display: block; }
    .no-review { text-align: center; padding: 40px; color: #bdc3c7; background: #f9f9f9; border-radius: 12px; border: 1px dashed #ddd; }
</style>

<div class="detail-container">
    <div class="detail-image">
        <img src="../uploads/<?php echo $data['foto']; ?>" alt="<?php echo htmlspecialchars($data['merek']); ?>">
    </div>

    <div class="detail-content">
        <span class="category-badge"><?php echo strtoupper($data['kategori']); ?></span>
        <h1><?php echo htmlspecialchars($data['merek'] . " " . $data['model']); ?></h1>
        <div class="price-tag">
            Rp <?php echo number_format($data['tarif_sewa_per_hari'], 0, ',', '.'); ?> <span>/ hari</span>
        </div>

        <div class="specs-grid">
            <div class="spec-item">
                <i class="fa fa-calendar"></i>
                <span>Tahun: <?php echo $data['tahun']; ?></span>
            </div>
            <div class="spec-item">
                <i class="fa fa-palette"></i>
                <span>Warna: <?php echo htmlspecialchars($data['warna']); ?></span>
            </div>
            <div class="spec-item">
                <i class="fa fa-id-card"></i>
                <span>Plat: <?php echo htmlspecialchars($data['plat_nomor']); ?></span>
            </div>
            <div class="spec-item">
                <i class="fa fa-check-circle"></i>
                <span>Status: <?php echo $data['status_ketersediaan']; ?></span>
            </div>
        </div>
        
        <div style="margin-top: 25px;">
            <h3>Deskripsi Mobil</h3>
            <p style="color: #666;">
                <?php 
                    $deskripsi_bersih = str_replace(['\r', '\n', 'rn'], ' ', $data['deskripsi']);
                    echo nl2br(htmlspecialchars($deskripsi_bersih)); 
                ?>
            </p>
        </div>

        <div class="ulasan-section">
            <h3 style="margin-bottom: 20px;"><i class="fa fa-star" style="color: #e67e22;"></i> Ulasan Pengguna</h3>
            
            <?php
            $q_ulasan = mysqli_query($koneksi, "SELECT u.*, p.nama 
                                                FROM ulasan u 
                                                JOIN penyewa p ON u.id_penyewa = p.id_penyewa 
                                                WHERE u.id_mobil = '$id' 
                                                ORDER BY u.tanggal DESC");

            if(mysqli_num_rows($q_ulasan) > 0):
                while($u = mysqli_fetch_assoc($q_ulasan)):
            ?>
                <div class="ulasan-card">
                    <div class="ulasan-header">
                        <span class="ulasan-user"><?php echo htmlspecialchars($u['nama']); ?></span>
                        <div class="ulasan-rating">
                            <?php 
                                for($i=1; $i<=5; $i++) {
                                    echo ($i <= $u['rating']) ? "★" : "☆";
                                }
                            ?>
                        </div>
                    </div>
                    <p class="ulasan-text">"<?php echo htmlspecialchars($u['ulasan']); ?>"</p>
                    <span class="ulasan-date"><?php echo date('d M Y, H:i', strtotime($u['tanggal'])); ?></span>
                </div>
            <?php 
                endwhile;
            else:
            ?>
                <div class="no-review">
                    <i class="fa-regular fa-comments fa-2x" style="margin-bottom: 10px; display: block;"></i>
                    Belum ada ulasan untuk mobil ini.
                </div>
            <?php endif; ?>
        </div>
        <div class="action-buttons" style="margin-top: 40px;">
            <a href="mobil.php" class="btn-back">
                <i class="fa fa-arrow-left"></i> Kembali
            </a>
            <?php if($data['status_ketersediaan'] == 'Tersedia'): ?>
                <a href="penyewaan.php?id=<?php echo $data['id_mobil']; ?>" class="btn-rent">
                    <i class="fa fa-key"></i> Sewa Mobil Sekarang
                </a>
            <?php else: ?>
                <a href="#" class="btn-rent" style="background: #ccc; cursor: not-allowed; box-shadow: none;">
                    Sedang Disewa
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include '../inc_penyewa/footer.php'; ?>