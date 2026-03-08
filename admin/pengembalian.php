<?php
include '../config.php'; 
cek_akses('admin'); 

$nama_admin = $_SESSION['nama'];

// Query mengambil data yang SUDAH SELESAI
$query_read = "SELECT ts.*, p.nama, m.merek, m.plat_nomor 
               FROM transaksi_sewa ts
               JOIN penyewa p ON ts.id_penyewa = p.id_penyewa 
               JOIN mobil m ON ts.id_mobil = m.id_mobil 
               WHERE ts.status_transaksi = 'Selesai'
               ORDER BY ts.tgl_aktual_kembali DESC";
$result = mysqli_query($koneksi, $query_read);
?>

<?php include 'partials/header.php'; ?>

<style>
    .data-table th { background: #f8f9fa; padding: 15px; border-bottom: 2px solid #dee2e6; color: #333; }
    .data-table td { padding: 15px; border-bottom: 1px solid #eee; vertical-align: middle; }
    .badge-selesai { background: #27ae60; color: white; padding: 5px 12px; border-radius: 20px; font-size: 11px; font-weight: bold; }
    .text-denda { color: #e74c3c; font-weight: bold; }
    /* Style tambahan untuk tombol kembali di atas */
    .btn-back-top {
        display: inline-block;
        color: white; 
        text-decoration: none; 
        background: rgba(0,0,0,0.4); 
        padding: 6px 12px; 
        border-radius: 5px;
        font-size: 13px;
        margin-top: 10px;
        transition: 0.3s;
    }
    .btn-back-top:hover { background: rgba(0,0,0,0.6); }
</style>

<div class="dashboard-container">
    <?php include 'partials/sidebar.php'; ?>

    <div class="main-content">
        <div class="background-image"></div> 
        <div class="overlay"></div> 

        <div class="top-bar" style="display: flex; justify-content: space-between; align-items: center; padding: 10px 30px; position: relative; z-index: 3;">
            <div></div>
            <p class="greeting" style="color: white; margin: 0; font-weight: bold;">
                <i class="fas fa-user-circle"></i> Hii <?php echo htmlspecialchars($nama_admin); ?>!!
            </p>
        </div>

        <div class="data-container" style="padding: 20px; position: relative; z-index: 2;">
            <header class="main-header">
                <h1 class="title" style="color: white; text-shadow: 2px 2px 4px rgba(0,0,0,0.5); margin-bottom: 5px;">Riwayat Pengembalian Mobil</h1>
                
                <a href="transaksi.php" class="btn-back-top">
                    <i class="fas fa-arrow-left"></i> Kembali ke Manajemen Transaksi
                </a>

                <?php if (isset($_GET['pesan'])): ?>
                    <div id="alert-notif" style="background: #d4edda; color: #155724; padding: 12px; border-radius: 8px; margin-top: 15px; border: 1px solid #c3e6cb;">
                        <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($_GET['pesan']); ?>
                    </div>
                <?php endif; ?>
            </header>

            <div class="content-box" style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); margin-top: 20px; overflow-x: auto;">
                <table class="data-table" style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Penyewa</th>
                            <th>Mobil</th>
                            <th>Tgl Kembali</th>
                            <th>Denda</th>
                            <th>Alasan Denda</th>
                            <th>Total Bayar</th>
                            <th style="text-align: center;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($result) > 0): ?>
                            <?php $no = 1; while($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><b><?php echo htmlspecialchars($row['nama']); ?></b></td>
                                <td><?php echo htmlspecialchars($row['merek']); ?> <br><small>(<?php echo htmlspecialchars($row['plat_nomor']); ?>)</small></td>
                                <td><?php echo ($row['tgl_aktual_kembali']) ? date('d/m/Y', strtotime($row['tgl_aktual_kembali'])) : '-'; ?></td>
                                <td class="text-denda">
                                    Rp <?php echo number_format($row['denda'], 0, ',', '.'); ?>
                                </td>
                                <td><small><?php echo htmlspecialchars($row['ulasan_denda'] ?: '-'); ?></small></td>
                                <td style="font-weight: bold;">Rp <?php echo number_format($row['total_bayar'], 0, ',', '.'); ?></td>
                                <td style="text-align: center;">
                                    <span class="badge-selesai">SELESAI</span>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="8" style="text-align: center; padding: 40px; color: #999;">Belum ada riwayat pengembalian.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    const notif = document.getElementById("alert-notif");
    if (notif) {
        setTimeout(() => { 
            notif.style.transition = "opacity 0.5s ease";
            notif.style.opacity = "0"; 
            setTimeout(() => notif.remove(), 500); 
        }, 4000);
    }
</script>
</body>
</html>