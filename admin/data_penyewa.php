<?php
// 1. Hubungkan ke config pusat dan kunci akses khusus Admin
include '../config.php'; 
cek_akses('admin'); 

$nama_admin = $_SESSION['nama']; 

// 2. Logika Hapus Data
$status_msg = "";
if (isset($_GET['aksi']) && $_GET['aksi'] == 'hapus' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $query_hapus = "DELETE FROM penyewa WHERE id_penyewa='$id'"; 
    
    if (mysqli_query($koneksi, $query_hapus)) {
        $status_msg = "<div style='background: #d4edda; color: #155724; padding: 15px; margin-bottom: 20px; border-radius: 5px;'>Data Penyewa berhasil dihapus!</div>";
        header("refresh:1; url=data_penyewa.php"); 
    } else {
        $status_msg = "<div style='background: #f8d7da; color: #721c24; padding: 15px; margin-bottom: 20px; border-radius: 5px;'>Gagal menghapus: " . mysqli_error($koneksi) . "</div>";
    }
}

// 3. Pengambilan Data untuk Tabel
$query_read = "SELECT * FROM penyewa ORDER BY id_penyewa DESC";
$result = mysqli_query($koneksi, $query_read);
?>

<?php include 'partials/header.php'; ?>
<div class="dashboard-container">
    <?php include 'partials/sidebar.php'; ?>

    <div class="main-content">
        <div class="background-image"></div> 
        <div class="overlay"></div> 

        <div class="data-container" style="padding: 20px; position: relative; z-index: 2;">
            <div class="data-header">
                <h1 class="data-title" style="color: white;">Manajemen Data Penyewa</h1>
            </div>

            <p class="greeting"><i class="fas fa-user-circle"></i> Hii <?php echo htmlspecialchars($nama_admin); ?>!!</p>
            <div style="height: 30px;"></div> 
            <div class="data-table-card">

            <?php echo $status_msg; ?>

            <div class="data-table-card" style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                <table class="data-table" style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: #f8f9fa; text-align: left; border-bottom: 2px solid #dee2e6;">
                        <th style="padding: 12px;">No</th>
                        <th style="padding: 12px;">Nama Lengkap</th>
                        <th style="padding: 12px;">Email</th>
                        <th style="padding: 12px;">Identitas (KTP/SIM)</th>
                        <th style="padding: 12px;">Dokumen SIM</th>
                        <th style="padding: 12px;">No. Telepon</th>
                        <th style="padding: 12px;">Alamat</th>
                        <th style="padding: 12px; text-align: center;">Aksi</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        if (mysqli_num_rows($result) > 0) {
                            while($row = mysqli_fetch_assoc($result)): ?>
                            <tr style="border-bottom: 1px solid #eee;">
                                <td style="padding: 12px;"><?php echo $no++; ?></td>
                                <td style="padding: 12px;"><strong><?php echo htmlspecialchars($row['nama']); ?></strong></td>
                                <td style="padding: 12px;"><?php echo htmlspecialchars($row['email']); ?></td>
                                <td style="padding: 12px;">
                            <div style="font-size: 12px;"><b>KTP:</b> <?php echo htmlspecialchars($row['no_ktp']); ?></div>
                            <div style="font-size: 12px; color: #555;"><b>SIM:</b> <?php echo htmlspecialchars($row['no_sim']); ?></div>
                        </td>
                        <td style="padding: 12px; text-align: center;">
    <?php if(!empty($row['foto_sim'])): ?>
        <a href="../uploads/<?php echo $row['foto_sim']; ?>" target="_blank">
            <img src="../uploads/<?php echo $row['foto_sim']; ?>" style="width: 50px; height: 35px; object-fit: cover; border-radius: 3px; border: 1px solid #ddd; display: block; margin-bottom: 5px;">
        </a>
        <a href="../uploads/<?php echo $row['foto_sim']; ?>" target="_blank" style="color: #e67e22; text-decoration: none; font-size: 10px; font-weight: bold;">
            <i class="fas fa-search-plus"></i> Perbesar
        </a>
    <?php else: ?>
        <span style="color: #ccc; font-size: 11px;">Belum Tersedia</span>
    <?php endif; ?>
</td>
    <td style="padding: 12px;"><?php echo htmlspecialchars($row['no_telepon']); ?></td>
    <td style="padding: 12px;"><?php echo htmlspecialchars($row['alamat']); ?></td>
    <td style="padding: 12px; text-align: center;">
        <a href="data_penyewa.php?aksi=hapus&id=<?php echo $row['id_penyewa']; ?>" 
           style="color: #dc3545; text-decoration: none;"
           onclick="return confirm('Hapus data penyewa ini?');">
           <i class="fas fa-trash"></i>
        </a>
    </td>
</tr>
                        <?php endwhile; 
                        } else {
                            echo '<tr><td colspan="7" style="text-align: center; padding: 30px; color: #999;">Belum ada penyewa yang mendaftar.</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
</html>