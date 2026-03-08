<?php 
include '../config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id_user'])) {
    header("location: ../login.php");
    exit;
}

include '../inc_penyewa/header.php'; 

$id_user = $_SESSION['id_user'];

// Query untuk mengambil ID Penyewa
$q_penyewa = mysqli_query($koneksi, "SELECT id_penyewa FROM penyewa WHERE id_user = '$id_user'");
$d_penyewa = mysqli_fetch_assoc($q_penyewa);
$id_penyewa = $d_penyewa['id_penyewa'];

// 1. Ambil Transaksi Aktif (Belum Selesai)
$query_aktif = mysqli_query($koneksi, "SELECT ts.*, m.merek, m.model, m.foto, m.plat_nomor 
    FROM transaksi_sewa ts 
    JOIN mobil m ON ts.id_mobil = m.id_mobil 
    WHERE ts.id_penyewa = '$id_penyewa' AND ts.status_transaksi != 'Selesai'
    ORDER BY ts.id_transaksi DESC");

// 2. Ambil Riwayat Selesai (Termasuk kolom denda dan ulasan_denda)
$query_selesai = mysqli_query($koneksi, "SELECT ts.*, m.merek, m.model, m.foto 
    FROM transaksi_sewa ts 
    JOIN mobil m ON ts.id_mobil = m.id_mobil 
    WHERE ts.id_penyewa = '$id_penyewa' AND ts.status_transaksi = 'Selesai'
    ORDER BY ts.id_transaksi DESC");
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
    :root { --primary: #e67e22; --secondary: #2c3e50; --light: #fbf5edf4; }
    body { background-color: var(--light); font-family: 'Inter', sans-serif; }
    .riwayat-container { max-width: 900px; margin: 40px auto; padding: 100px 20px 40px 20px; min-height: 80vh; }

    /* Tab Styling */
    .tab-wrapper { display: flex; background: #eee; padding: 5px; border-radius: 12px; margin-bottom: 30px; gap: 5px; }
    .tab-btn { flex: 1; padding: 12px; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; transition: 0.3s; background: transparent; color: #666; }
    .tab-btn.active { background: white; color: var(--primary); box-shadow: 0 2px 5px rgba(0,0,0,0.1); }

    /* Content Styling */
    .tab-content { display: none; animation: fadeIn 0.4s ease; }
    .tab-content.active { display: block; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

    /* Card Styling */
    .card-riwayat { 
        background: white; border-radius: 15px; padding: 20px; 
        display: flex; gap: 20px; align-items: center; 
        margin-bottom: 15px; border: 1px solid #eef2f7;
        box-shadow: 0 4px 6px rgba(0,0,0,0.02);
        transition: 0.3s;
    }
    .card-riwayat img { width: 130px; height: 85px; object-fit: cover; border-radius: 10px; }
    
    /* Status Badge */
    .status-badge { padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: bold; text-transform: uppercase; }
    .status-pending { background: #fff3cd; color: #856404; }
    .status-disewa { background: #d1ecf1; color: #0c5460; }
    .status-selesai { background: #d4edda; color: #155724; }

    .btn-detail { padding: 8px 16px; border-radius: 8px; border: 1px solid #ddd; text-decoration: none; color: #333; font-size: 13px; font-weight: 600; transition: 0.3s; }
    .btn-detail:hover { background: var(--secondary); color: white; }

    /* Fine Alert Box */
    .denda-box { margin-top: 8px; padding: 8px 12px; background: #fff5f5; border-radius: 8px; border: 1px solid #feb2b2; }
</style>

<div class="riwayat-container">
    <h2 style="text-align: center; margin-bottom: 30px;">Riwayat Transaksi</h2>

    <div class="tab-wrapper">
        <button class="tab-btn active" onclick="openTab(event, 'aktif')">
            <i class="fa-solid fa-car-side"></i> Sewa Aktif (<?= mysqli_num_rows($query_aktif); ?>)
        </button>
        <button class="tab-btn" onclick="openTab(event, 'selesai')">
            <i class="fa-solid fa-clock-history"></i> Riwayat Selesai (<?= mysqli_num_rows($query_selesai); ?>)
        </button>
    </div>

    <div id="aktif" class="tab-content active">
        <?php if(mysqli_num_rows($query_aktif) > 0): ?>
            <?php while($row = mysqli_fetch_assoc($query_aktif)): ?>
                <div class="card-riwayat">
                    <img src="../uploads/<?= $row['foto']; ?>">
                    <div style="flex-grow: 1;">
                        <span class="status-badge <?= ($row['status_transaksi'] == 'Pending') ? 'status-pending' : 'status-disewa'; ?>">
                            <?= $row['status_transaksi']; ?>
                        </span>
                        <h3 style="margin: 5px 0;"><?= $row['merek']; ?> <?= $row['model']; ?></h3>
                        <p style="font-size: 13px; color: #7f8c8d; margin: 0;">
                            <i class="fa-regular fa-calendar"></i> s/d <?= date('d M Y', strtotime($row['tgl_rencana_kembali'])); ?>
                        </p>
                    </div>
                    <div>
                        <a href="detail_riwayat.php?id=<?= $row['id_transaksi']; ?>" class="btn-detail">Detail</a>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div style="text-align: center; padding: 50px; color: #bdc3c7;">
                <i class="fa-solid fa-folder-open fa-3x"></i>
                <p>Belum ada sewa yang aktif.</p>
            </div>
        <?php endif; ?>
    </div>

    <div id="selesai" class="tab-content">
        <?php if(mysqli_num_rows($query_selesai) > 0): ?>
            <?php while($row_s = mysqli_fetch_assoc($query_selesai)): ?>
                <div class="card-riwayat" style="border-left: 5px solid <?= ($row_s['denda'] > 0) ? '#e74c3c' : '#27ae60'; ?>;">
                    <img src="../uploads/<?= $row_s['foto']; ?>" style="<?= ($row_s['denda'] > 0) ? '' : 'filter: grayscale(100%); opacity: 0.7;'; ?>">
                    
                    <div style="flex-grow: 1;">
                        <span class="status-badge status-selesai">Selesai</span>
                        <h3 style="margin: 5px 0; color: #2c3e50;"><?= $row_s['merek']; ?> <?= $row_s['model']; ?></h3>
                        <p style="font-size: 13px; color: #7f8c8d; margin: 0;">
                            Total: <b>Rp <?= number_format($row_s['total_bayar'], 0, ',', '.'); ?></b>
                        </p>

                        <?php if($row_s['denda'] > 0): ?>
                            <div class="denda-box">
                                <p style="font-size: 12px; color: #c53030; margin: 0; font-weight: bold;">
                                    <i class="fa-solid fa-triangle-exclamation"></i> Ada Denda: Rp <?= number_format($row_s['denda'], 0, ',', '.'); ?>
                                </p>
                                <p style="font-size: 11px; color: #742a2a; margin: 0;">
                                    Alasan: <?= htmlspecialchars($row_s['ulasan_denda'] ?: 'Ketidakterlambatan/Kerusakan'); ?>
                                </p>
                            </div>
                        <?php else: ?>
                            <p style="font-size: 11px; color: #27ae60; margin-top: 5px;">
                                <i class="fa-solid fa-check-circle"></i> Selesai tanpa denda.
                            </p>
                        <?php endif; ?>
                    </div>

                    <div>
                        <a href="detail_riwayat.php?id=<?= $row_s['id_transaksi']; ?>" class="btn-detail">Lihat</a>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div style="text-align: center; padding: 50px; color: #bdc3c7;">
                <i class="fa-solid fa-box-archive fa-3x"></i>
                <p>Belum ada riwayat transaksi selesai.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    function openTab(evt, tabName) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tab-content");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tab-btn");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].classList.remove("active");
        }
        document.getElementById(tabName).style.display = "block";
        evt.currentTarget.classList.add("active");
    }
</script>

<?php include '../inc_penyewa/footer.php'; ?>