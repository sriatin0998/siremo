<?php
include '../config.php';
cek_akses('admin');

$id = $_GET['id'];
// Ambil data mobil lama
$query = mysqli_query($koneksi, "SELECT * FROM mobil WHERE id_mobil = '$id'");
$data = mysqli_fetch_assoc($query);

// Jika ID tidak ditemukan
if (!$data) {
    header("location: data_mobil.php");
    exit;
}

if (isset($_POST['update_mobil'])) {
    $merk      = anti_injection($_POST['merk']);
    $model     = anti_injection($_POST['model']);
    $tahun     = anti_injection($_POST['tahun']);
    $plat      = anti_injection($_POST['plat_nomor']);
    $tarif     = anti_injection($_POST['tarif_harian']);
    $status    = anti_injection($_POST['status']);
    $kategori  = anti_injection($_POST['kategori']);
    $warna     = anti_injection($_POST['warna']);      // Tambahan Warna
    $deskripsi = anti_injection($_POST['deskripsi']);  // Tambahan Deskripsi

    // Cek apakah ada upload foto baru
    if ($_FILES['foto']['name'] != "") {
        $foto_name = $_FILES['foto']['name'];
        $foto_tmp = $_FILES['foto']['tmp_name'];
        $ekstensiFile = strtolower(pathinfo($foto_name, PATHINFO_EXTENSION));
        $namaFileBaru = time() . '_' . str_replace(' ', '', $plat) . '.' . $ekstensiFile;
        $targetPath = "../uploads/" . $namaFileBaru;

        // Hapus foto lama jika ada
        if (!empty($data['foto']) && file_exists("../uploads/" . $data['foto'])) {
            unlink("../uploads/" . $data['foto']);
        }

        move_uploaded_file($foto_tmp, $targetPath);
        
        // Query UPDATE dengan FOTO baru
        $query_update = "UPDATE mobil SET 
            merek='$merk', 
            model='$model', 
            tahun='$tahun', 
            plat_nomor='$plat', 
            tarif_sewa_per_hari='$tarif', 
            status_ketersediaan='$status', 
            kategori='$kategori', 
            warna='$warna', 
            deskripsi='$deskripsi', 
            foto='$namaFileBaru' 
            WHERE id_mobil='$id'";
    } else {
        // Query UPDATE TANPA ganti foto
        $query_update = "UPDATE mobil SET 
            merek='$merk', 
            model='$model', 
            tahun='$tahun', 
            plat_nomor='$plat', 
            tarif_sewa_per_hari='$tarif', 
            status_ketersediaan='$status', 
            kategori='$kategori', 
            warna='$warna', 
            deskripsi='$deskripsi' 
            WHERE id_mobil='$id'";
    }

    if (mysqli_query($koneksi, $query_update)) {
        header("location: data_mobil.php?status_edit=sukses");
        exit;
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
}
?>

<?php include 'partials/header.php'; ?>
<div class="dashboard-container">
    <?php include 'partials/sidebar.php'; ?>
    <div class="main-content">
        <div class="content-box">
            <h2>Edit Data Mobil</h2>
            <hr>
            <form action="" method="POST" enctype="multipart/form-data" style="margin-top: 20px;">
                
                <div class="form-group">
                    <label>Merk & Model Mobil</label>
                    <div style="display: flex; gap: 10px;">
                        <input type="text" name="merk" value="<?php echo htmlspecialchars($data['merek']); ?>" required style="flex: 1;">
                        <input type="text" name="model" value="<?php echo htmlspecialchars($data['model']); ?>" required style="flex: 1;">
                    </div>
                </div>

                <div class="form-group">
                    <label>Tahun, Plat Nomor, & Warna</label>
                    <div style="display: flex; gap: 10px;">
                        <input type="number" name="tahun" value="<?php echo $data['tahun']; ?>" required style="width: 25%;">
                        <input type="text" name="plat_nomor" value="<?php echo htmlspecialchars($data['plat_nomor']); ?>" required style="width: 40%;">
                        <input type="text" name="warna" value="<?php echo htmlspecialchars($data['warna']); ?>" required style="width: 35%;">
                    </div>
                </div>

                <div class="form-group">
                    <label>Tarif Harian (Rp)</label>
                    <input type="number" name="tarif_harian" value="<?php echo $data['tarif_sewa_per_hari']; ?>" required>
                </div>

                <div class="form-group">
                    <label>Kategori Mobil</label>
                    <select name="kategori" required style="width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ddd;">
                        <option value="keluarga" <?php if($data['kategori'] == 'keluarga') echo 'selected'; ?>>Mobil Keluarga</option>
                        <option value="city" <?php if($data['kategori'] == 'city') echo 'selected'; ?>>City Car</option>
                        <option value="bus" <?php if($data['kategori'] == 'bus') echo 'selected'; ?>>Bus & Minibus</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Status Ketersediaan</label>
                    <select name="status" required style="width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ddd;">
                        <option value="Tersedia" <?php if($data['status_ketersediaan'] == 'Tersedia') echo 'selected'; ?>>Tersedia</option>
                        <option value="Disewa" <?php if($data['status_ketersediaan'] == 'Disewa') echo 'selected'; ?>>Disewa</option>
                        <option value="Maintenance" <?php if($data['status_ketersediaan'] == 'Maintenance') echo 'selected'; ?>>Maintenance</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Deskripsi Mobil</label>
                    <textarea name="deskripsi" rows="4" required style="width: 100%; border: 1px solid #ddd; border-radius: 5px; padding: 10px;"><?php echo htmlspecialchars($data['deskripsi']); ?></textarea>
                </div>

                <div class="form-group">
                    <label>Foto Mobil (Biarkan kosong jika tidak ingin diganti)</label><br>
                    <?php if(!empty($data['foto'])): ?>
                        <div style="margin-bottom: 10px;">
                            <small>Foto saat ini:</small><br>
                            <img src="../uploads/<?php echo $data['foto']; ?>" width="150" style="border-radius: 5px; border: 1px solid #ddd;">
                        </div>
                    <?php endif; ?>
                    <input type="file" name="foto" accept="image/*">
                </div>

                <div class="form-actions" style="margin-top: 30px; border-top: 1px solid #eee; padding-top: 20px;">
                    <a href="data_mobil.php" class="btn-cancel" style="text-decoration: none; padding: 10px 20px; background: #666; color: #fff; border-radius: 5px; margin-right: 10px;">Batal</a>
                    <button type="submit" name="update_mobil" class="btn-submit" style="padding: 10px 25px; background: #FF9F43; color: white; border: none; border-radius: 5px; font-weight: bold; cursor: pointer;">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>