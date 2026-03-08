<?php
include '../config.php';
cek_akses('penyewa');

// Ambil ID dari session
$id_login = $_SESSION['id_user']; 

// Ambil data hanya dari tabel penyewa
$query = mysqli_query($koneksi, "SELECT * FROM penyewa WHERE id_user = '$id_login'");

if(mysqli_num_rows($query) > 0) {
    $user = mysqli_fetch_assoc($query);
    
    $nama_tampil    = $user['nama']; 
    $email_tampil   = $user['email'];
    $no_telepon     = $user['no_telepon'] ?? '';
    $no_ktp         = $user['no_ktp'] ?? '';
    $no_sim         = $user['no_sim'] ?? '';
    $alamat         = $user['alamat'] ?? '';
} else {
    echo "Data profil tidak ditemukan di tabel penyewa!";
    exit;
}
?>

<div class="profile-page-container" style="max-width: 800px; margin: 40px auto; padding: 0 20px; font-family: 'Segoe UI', sans-serif;">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <div style="margin-bottom: 20px;">
        <a href="index.php" style="text-decoration: none; color: #e67e22; font-weight: 600; display: inline-flex; align-items: center; gap: 8px;">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Beranda
        </a>
    </div>

    <div class="profile-card" style="background: white; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); overflow: hidden; border: 1px solid #eee;">
        
        <div class="profile-header" style="background: #fdf0e6; padding: 30px; text-align: center;">
            <div class="avatar-big" style="width: 80px; height: 80px; background: #e67e22; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; margin: 0 auto 15px; font-weight: bold;">
                <?= strtoupper(substr($nama_tampil, 0, 1)); ?>
            </div>
            <h2 style="margin: 0; color: #333;"><?= htmlspecialchars($nama_tampil); ?></h2>
            <p style="color: #666; font-size: 0.9rem;"><?= htmlspecialchars($email_tampil); ?></p>
        </div>

        <div class="profile-body" style="padding: 30px;">
            <form action="update_profil.php" method="POST" enctype="multipart/form-data">
                <h3 style="font-size: 1.1rem; margin-bottom: 20px; border-left: 4px solid #e67e22; padding-left: 10px;">Informasi Identitas & Kontak</h3>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                    <div>
                        <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 8px; color: #888;">Nama Lengkap</label>
                        <input type="text" value="<?= htmlspecialchars($nama_tampil); ?>" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 10px; background-color: #f5f5f5;" readonly>
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 8px; color: #888;">Email </label>
                        <input type="text" value="<?= htmlspecialchars($email_tampil); ?>" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 10px; background-color: #f5f5f5;" readonly>
                    </div>
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 8px;">Nomor Telepon / WhatsApp</label>
                    <input type="text" name="no_telepon" value="<?= htmlspecialchars($no_telepon); ?>" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 10px;" required>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                    <div>
                        <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 8px;">No. KTP</label>
                        <input type="text" name="no_ktp" value="<?= htmlspecialchars($no_ktp); ?>" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 10px;" required>
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 8px;">No. SIM A</label>
                        <input type="text" name="no_sim" value="<?= htmlspecialchars($no_sim); ?>" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 10px;" required>
                    </div>
                </div>

                <div class="input-group-modern" style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 8px;">Foto SIM A (Format: JPG/PNG)</label>
                    
                    <?php 
                    $path_foto = "../uploads/" . $user['foto_sim'];
                    if (!empty($user['foto_sim']) && file_exists($path_foto)): 
                    ?>
                        <div style="margin-bottom: 15px; background: #f9f9f9; padding: 10px; border-radius: 10px; border: 1px solid #eee;">
                            <p style="font-size: 12px; color: #2ecc71; margin-bottom: 8px;"><i class="fas fa-check-circle"></i> SIM Terverifikasi:</p>
                            <img src="<?= $path_foto; ?>?t=<?= time(); ?>" 
                                 alt="Foto SIM" 
                                 style="width: 100%; max-width: 300px; border-radius: 8px; border: 1px solid #ddd;">
                        </div>
                    <?php else: ?>
                        <p style="font-size: 12px; color: #e74c3c; margin-bottom: 8px;">
                            <i class="fas fa-times-circle"></i> Belum ada foto SIM yang diunggah.
                        </p>
                    <?php endif; ?>

                    <input type="file" name="foto_sim" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 10px; background: #fff;">
                    <small style="color: #888; font-size: 11px;">*Abaikan jika tidak ingin mengganti foto SIM</small>
                </div>

                <div style="margin-bottom: 25px;">
                    <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 8px;">Alamat Lengkap</label>
                    <textarea name="alamat" rows="3" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 10px; resize: none;" required><?= htmlspecialchars($alamat); ?></textarea>
                </div>

                <button type="submit" name="update_profil" style="width: 100%; background: #e67e22; color: white; border: none; padding: 14px; border-radius: 10px; font-weight: bold; cursor: pointer;">
                    Simpan Perubahan Profil
                </button>
            </form>

            <hr style="border: 0; border-top: 1px solid #eee; margin: 30px 0;">

            <div style="text-align: center;">
                <a href="../logout.php" style="color: #ff4d4d; text-decoration: none; font-weight: 600; font-size: 0.9rem;">
                    <i class="fa-solid fa-right-from-bracket"></i> Logout Sekarang
                </a>
            </div>
        </div>
    </div>
</div>