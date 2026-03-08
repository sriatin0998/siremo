<?php
// 1. Koneksi dan Cek Akses
include '../config.php'; 
cek_akses('admin'); 

$nama_admin = $_SESSION['nama']; 

// 2. Logika Hapus Ulasan
if (isset($_GET['aksi']) && $_GET['aksi'] == 'hapus' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    // SESUAIKAN: Jika di database kolomnya bernama 'id_ulasan', ganti 'id' menjadi 'id_ulasan'
    $query_hapus = mysqli_query($koneksi, "DELETE FROM ulasan WHERE id_ulasan='$id'");
    
    if ($query_hapus) {
        header("location: ulasan.php?pesan=Ulasan berhasil dihapus");
    } else {
        header("location: ulasan.php?pesan=Gagal menghapus ulasan");
    }
    exit;
}

// 3. Query Ambil Data
$query = "SELECT u.*, m.merek, p.nama as nama_penyewa 
          FROM ulasan u
          JOIN mobil m ON u.id_mobil = m.id_mobil
          JOIN penyewa p ON u.id_penyewa = p.id_penyewa
          ORDER BY u.tanggal DESC";
$result = mysqli_query($koneksi, $query);
?>

<?php include 'partials/header.php'; ?>
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

        <div class="data-container" style="padding: 30px; position: relative; z-index: 2;">
            <header class="main-header">
                <h1 class="title" style="color: white; text-shadow: 2px 2px 4px rgba(0,0,0,0.5);">Ulasan Pelanggan</h1>
                
                <?php if (isset($_GET['pesan'])): ?>
                    <div id="alert-notif" style="background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 15px; border: 1px solid #c3e6cb;">
                        <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($_GET['pesan']); ?>
                    </div>
                <?php endif; ?>
            </header>

            <div class="content-box" style="background: white; padding: 25px; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.2); overflow-x: auto;">
                <table class="data-table" style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: #f8f9fa; text-align: left; border-bottom: 2px solid #dee2e6;">
                            <th style="padding: 15px;">No</th>
                            <th style="padding: 15px;">Tanggal</th>
                            <th style="padding: 15px;">Pelanggan</th>
                            <th style="padding: 15px;">Mobil</th>
                            <th style="padding: 15px;">Rating</th>
                            <th style="padding: 15px;">Ulasan</th>
                            <th style="padding: 15px; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($result) > 0): ?>
                            <?php $no = 1; while($row = mysqli_fetch_assoc($result)): ?>
                            <tr style="border-bottom: 1px solid #eee;">
                                <td style="padding: 15px;"><?php echo $no++; ?></td>
                                <td style="padding: 15px;"><?php echo date('d/m/Y H:i', strtotime($row['tanggal'])); ?></td>
                                <td style="padding: 15px;"><b><?php echo htmlspecialchars($row['nama_penyewa']); ?></b></td>
                                <td style="padding: 15px;"><?php echo htmlspecialchars($row['merek']); ?></td>
                                <td style="padding: 15px; color: #f1c40f;">
                                    <?php 
                                    for($i=1; $i<=5; $i++) {
                                        echo ($i <= $row['rating']) ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>';
                                    }
                                    ?>
                                </td>
                                <td style="padding: 15px;">"<?php echo htmlspecialchars($row['ulasan']); ?>"</td>
                                <td style="padding: 15px; text-align: center;">
                                    <a href="ulasan.php?aksi=hapus&id=<?php echo $row['id_ulasan']; ?>" 
                                       style="color: #e74c3c; text-decoration: none;" 
                                       onclick="return confirm('Yakin ingin menghapus ulasan ini?');">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="7" style="text-align: center; padding: 20px;">Belum ada ulasan.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    setTimeout(() => {
        const notif = document.getElementById('alert-notif');
        if(notif) notif.style.display = 'none';
    }, 3000);
</script>
</body>
</html>