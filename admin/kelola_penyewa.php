<?php
session_start();
include '../config.php'; // Pastikan path benar

// ===============================================
// 1. CEK LOGIN
// ===============================================
if (!isset($_SESSION['status']) || $_SESSION['status'] != 'login_admin' || $_SESSION['role'] != 'admin') {
    header("location: login.php");
    exit;
}

// ===============================================
// 2. LOGIKA CRUD (Disesuaikan dengan DB tanpa Email)
// ===============================================

$status_msg = "";

// --- A. TAMBAH DATA (CREATE) ---
if (isset($_POST['tambah_penyewa'])) {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama_penyewa']);
    $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat_penyewa']);
    $no_telepon = mysqli_real_escape_string($koneksi, $_POST['no_telepon_penyewa']);
    $no_ktp = mysqli_real_escape_string($koneksi, $_POST['no_ktp_penyewa']);
    
    // Perhatikan urutan dan nama kolom di INSERT
    $query = "INSERT INTO penyewa (nama, alamat, no_ktp, no_telepon) 
            VALUES ('$nama', '$alamat', '$no_ktp', '$no_telepon')";
            
    if (mysqli_query($koneksi, $query)) {
        $status_msg = "<div class='success-msg'>Data Penyewa berhasil ditambahkan!</div>";
    } else {
        $status_msg = "<div class='error-msg'>Error: " . mysqli_error($koneksi) . "</div>";
    }
}

// --- B. HAPUS DATA (DELETE) ---
if (isset($_GET['aksi']) && $_GET['aksi'] == 'hapus' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $query = "DELETE FROM penyewa WHERE id_penyewa='$id'"; 
    if (mysqli_query($koneksi, $query)) {
        $status_msg = "<div class='success-msg'>Data Penyewa ID:$id berhasil dihapus!</div>";
        header("refresh:1; url=data_penyewa.php"); 
    } else {
        $status_msg = "<div class='error-msg'>Error menghapus: " . mysqli_error($koneksi) . "</div>";
    }
}

// --- C. EDIT DATA (UPDATE) ---
if (isset($_POST['edit_penyewa'])) {
    $id = (int)$_POST['id_penyewa'];
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama_penyewa']);
    $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat_penyewa']);
    $no_telepon = mysqli_real_escape_string($koneksi, $_POST['no_telepon_penyewa']);
    $no_ktp = mysqli_real_escape_string($koneksi, $_POST['no_ktp_penyewa']);
    
    // Perhatikan nama kolom di UPDATE
    $query = "UPDATE penyewa SET 
                nama='$nama', 
                alamat='$alamat', 
                no_ktp='$no_ktp', 
                no_telepon='$no_telepon' 
            WHERE id_penyewa='$id'";
            
    if (mysqli_query($koneksi, $query)) {
        $status_msg = "<div class='success-msg'>Data Penyewa ID:$id berhasil diupdate!</div>";
        header("refresh:1; url=data_penyewa.php"); 
    } else {
        $status_msg = "<div class='error-msg'>Error mengupdate: " . mysqli_error($koneksi) . "</div>";
    }
}

// ===============================================
// 3. PENGAMBILAN DATA (READ)
// ===============================================
// Hapus kolom email dari SELECT
$query_read = "SELECT id_penyewa, nama, alamat, no_ktp, no_telepon FROM penyewa ORDER BY id_penyewa ASC";
$result = mysqli_query($koneksi, $query_read);

// Ambil data untuk mode edit
$data_edit = null;
if (isset($_GET['aksi']) && $_GET['aksi'] == 'edit' && isset($_GET['id'])) {
    $id_edit = (int)$_GET['id'];
    // Hapus kolom email dari SELECT
    $query_edit = "SELECT id_penyewa, nama, alamat, no_ktp, no_telepon FROM penyewa WHERE id_penyewa='$id_edit'";
    $result_edit = mysqli_query($koneksi, $query_edit);
    $data_edit = mysqli_fetch_assoc($result_edit);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Penyewa - SIREMO</title>
    
    <link rel="stylesheet" href="../assets/style3.css"> 
    <link rel="stylesheet" href="../assets/style6.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
</head>
<body>
    <div class="dashboard-container">
        
        <div class="sidebar">
            <div class="logo-siermo">
                <i class="fa-solid fa-car-side"></i>
                <h2 class="logo-text">SIREMO</h2> 
            </div>
            
            <ul class="sidebar-menu">
                <li class="menu-item"><a href="dashboard.php">Dashboard</a></li> 
                <li class="menu-item"><a href="data_mobil.php">Data Mobil</a></li>
                <li class="menu-item active-link"><a href="kelola_penyewa.php">Data Penyewa</a></li> 
                <li class="menu-item"><a href="transaksi.php">Transaksi</a></li>
                <li class="menu-item"><a href="tarif_sewa.php">Tarif Sewa</a></li>
                <li class="menu-item"><a href="pengembalian.php">Pengembalian</a></li>
                <li class="menu-item"><a href="laporan_penyewaan.php">Laporan Penyewaan</a></li>
                <li class="menu-item"><a href="ulasan.php">Ulasan</a></li>
                
                <li class="menu-item-spacer"></li> 
                <li class="menu-item logout-link"><a href="logout.php">Logout</a></li>
            </ul>
        </div>
        <div class="main-content">
            <div class="background-image"></div> 
            <div class="overlay"></div> 
            
            <div class="data-container">
                
                <div class="data-header">
                    <a href="dashboard.php" class="back-arrow"><i class="fas fa-arrow-left"></i></a>
                    <h1 class="data-title">Data Penyewa</h1>
                </div>

                <button class="tambah-data-btn" onclick="document.getElementById('modal-tambah').style.display='flex'">
                    <i class="fas fa-plus-circle"></i> Data Pelanggan
                </button>
                
                <?php echo $status_msg; ?>
                
                <?php if ($data_edit): ?>
                <div class="modal-edit-inline">
                    <div class="form-card edit-form-box">
                        <h3 class="form-title">Edit Data Penyewa (ID: <?php echo $data_edit['id_penyewa']; ?>)</h3>
                        <form method="POST" action="data_penyewa.php">
                            <input type="hidden" name="id_penyewa" value="<?php echo $data_edit['id_penyewa']; ?>">
                            <input type="hidden" name="edit_penyewa" value="1">
                            
                            <div class="form-group">
                                <label for="nama_penyewa">Nama Lengkap</label>
                                <input type="text" id="nama_penyewa" name="nama_penyewa" value="<?php echo htmlspecialchars($data_edit['nama']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="alamat_penyewa">Alamat</label>
                                <input type="text" id="alamat_penyewa" name="alamat_penyewa" value="<?php echo htmlspecialchars($data_edit['alamat']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="no_telepon_penyewa">No. Telepon</label>
                                <input type="text" id="no_telepon_penyewa" name="no_telepon_penyewa" value="<?php echo htmlspecialchars($data_edit['no_telepon']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="no_ktp_penyewa">No. KTP</label>
                                <input type="text" id="no_ktp_penyewa" name="no_ktp_penyewa" value="<?php echo htmlspecialchars($data_edit['no_ktp']); ?>" required>
                            </div>
                            <button type="submit" class="submit-btn"><i class="fas fa-save"></i> Simpan Perubahan</button>
                            <a href="data_penyewa.php" class="cancel-btn">Batalkan Edit</a>
                        </form>
                    </div>
                </div>
                <?php endif; ?>


                <div class="data-table-card">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th style="width: 5%;">No</th>
                                <th style="width: 20%;">Nama</th>
                                <th style="width: 25%;">Alamat</th>
                                <th style="width: 20%;">No Tlp</th>
                                <th style="width: 15%;">Foto</th>
                                <th style="width: 15%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            if (mysqli_num_rows($result) > 0) {
                                while($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo htmlspecialchars($row['nama']); ?></td>
                                    <td><?php echo htmlspecialchars($row['alamat']); ?></td>
                                    <td><?php echo htmlspecialchars($row['no_telepon']); ?></td>
                                    <td>(ktp, sim, pembayaran)</td> 
                                    <td class="action-cell">
                                        <a href="data_penyewa.php?aksi=edit&id=<?php echo $row['id_penyewa']; ?>" class="action-btn edit-btn">[Edit]</a>
                                        <a href="data_penyewa.php?aksi=hapus&id=<?php echo $row['id_penyewa']; ?>" class="action-btn delete-btn" onclick="return confirm('Yakin ingin menghapus data <?php echo $row['nama']; ?>?');">[Hapus]</a>
                                    </td>
                                </tr>
                            <?php endwhile; 
                            } else {
                                echo '<tr><td colspan="6" style="height: 200px; text-align: center; padding: 20px;">Belum ada data penyewa yang tercatat.</td></tr>';
                            }
                            ?>
                            <tr><td colspan="6" style="height: 40px; border-bottom: none;"></td></tr>
                            <tr><td colspan="6" style="height: 40px; border-bottom: none;"></td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
        </div>
    </div>

    <div id="modal-tambah" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="document.getElementById('modal-tambah').style.display='none'">&times;</span>
            <div class="form-card">
                <h3 class="form-title"><i class="fas fa-plus"></i> Tambah Data Penyewa Baru</h3>
                <form method="POST" action="data_penyewa.php">
                    <input type="hidden" name="tambah_penyewa" value="1">

                    <div class="form-group">
                        <label for="add_nama">Nama Lengkap</label>
                        <input type="text" id="add_nama" name="nama_penyewa" required>
                    </div>
                    <div class="form-group">
                        <label for="add_alamat">Alamat</label>
                        <input type="text" id="add_alamat" name="alamat_penyewa" required>
                    </div>
                    <div class="form-group">
                        <label for="add_no_telepon">No. Telepon</label>
                        <input type="text" id="add_no_telepon" name="no_telepon_penyewa" required>
                    </div>
                    <div class="form-group">
                        <label for="add_no_ktp">No. KTP</label>
                        <input type="text" id="add_no_ktp" name="no_ktp_penyewa" required>
                    </div>
                    <button type="submit" class="submit-btn"><i class="fas fa-save"></i> Tambahkan Penyewa</button>
                    <button type="button" class="cancel-btn" onclick="document.getElementById('modal-tambah').style.display='none'">Batal</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>