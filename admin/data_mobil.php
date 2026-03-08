<?php
// 1. Hubungkan ke config pusat dan kunci akses khusus Admin
include '../config.php'; 
cek_akses('admin'); 

// 2. Ambil data session
$nama_admin = $_SESSION['nama'];

/* ======================
    LOGIKA TAMBAH MOBIL + UPLOAD FOTO
   ====================== */
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tambah_mobil'])) {
    $merk      = anti_injection($_POST['merk']); 
    $model     = anti_injection($_POST['model']);
    $tahun     = anti_injection($_POST['tahun']);
    $plat      = anti_injection($_POST['plat_nomor']);
    $tarif     = anti_injection($_POST['tarif_harian']); 
    $kategori  = anti_injection($_POST['kategori']); 
    $warna     = anti_injection($_POST['warna']);
    $deskripsi = anti_injection($_POST['deskripsi']);
    $status_ketersediaan = 'Tersedia'; 

    $foto_name = $_FILES['foto']['name'];
    $foto_tmp  = $_FILES['foto']['tmp_name'];
    $foto_err  = $_FILES['foto']['error'];
    
    if ($foto_err === 0) {
        $ekstensiValid = ['jpg', 'jpeg', 'png'];
        $ekstensiFile  = strtolower(pathinfo($foto_name, PATHINFO_EXTENSION));

        if (in_array($ekstensiFile, $ekstensiValid)) {
            $namaFileBaru = time() . '_' . str_replace(' ', '', $plat) . '.' . $ekstensiFile;
            $targetPath   = "../uploads/" . $namaFileBaru;

            if (move_uploaded_file($foto_tmp, $targetPath)) {
                $query_insert = "INSERT INTO mobil (merek, model, tahun, plat_nomor, tarif_sewa_per_hari, status_ketersediaan, foto, kategori, warna, deskripsi) 
                                 VALUES ('$merk', '$model', '$tahun', '$plat', '$tarif', '$status_ketersediaan', '$namaFileBaru', '$kategori', '$warna', '$deskripsi')";
                
                if (mysqli_query($koneksi, $query_insert)) {
                    header("location: data_mobil.php?status_add=sukses");
                    exit;
                } else {
                    $status_tambah = "Gagal simpan ke database: " . mysqli_error($koneksi);
                }
            } else {
                $status_tambah = "Gagal memindahkan file ke folder uploads. Pastikan folder ../uploads/ ada.";
            }
        } else {
            $status_tambah = "Ekstensi file tidak didukung (Gunakan JPG/PNG).";
        }
    } else {
        $status_tambah = "Error saat upload foto. Kode Error: " . $foto_err;
    }
}

/* ======================
    AMBIL DATA MOBIL
   ====================== */
$query_select = "SELECT id_mobil AS id, merek, model, tahun, plat_nomor AS plat, 
                        tarif_sewa_per_hari AS tarif, status_ketersediaan AS status, 
                        foto, kategori, warna, deskripsi 
                 FROM mobil ORDER BY id_mobil DESC";
$result = mysqli_query($koneksi, $query_select);
$data_mobil = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<<<<<<< HEAD
<?php include 'partials/header.php'; ?>
=======
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIREMO - Data Mobil</title>
    <link rel="stylesheet" href="../assets/style4.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        
    </style>
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
                <li class="menu-item active-link"><a href="data_mobil.php">Data Mobil</a></li> 
                <li class="menu-item"><a href="kelola_penyewa.php">Data Penyewa</a></li>
                <li class="menu-item"><a href="transaksi.php">Transaksi</a></li>
                <li class="menu-item"><a href="tarif_sewa.php">Tarif Sewa</a></li>
                <li class="menu-item"><a href="pengembalian.php">Pengembalian</a></li>
                <li class="menu-item"><a href="laporan_penyewaan.php">Laporan Penyewaan</a></li>
                <li class="menu-item"><a href="ulasan.php">Ulasan</a></li>
                
                <li class="menu-item-spacer"></li> 
                <li class="menu-item logout-link"><a href="logout.php">Logout</a></li>
            </ul>
        </div>
>>>>>>> b8d9290bc2e45757458286a8f3a7331f3067501e

<style>
    /* Reset & Base Style */
    .badge-status { padding: 6px 14px; border-radius: 20px; font-size: 11px; font-weight: 600; color: white; text-transform: uppercase; }
    .status-tersedia { background-color: #2ecc71; }
    .status-disewa { background-color: #e74c3c; }

    /* Modal Styling */
    .modal { 
        display: none; 
        position: fixed; 
        z-index: 1000; 
        left: 0; top: 0; 
        width: 100%; height: 100%; 
        background-color: rgba(0,0,0,0.6); 
        backdrop-filter: blur(5px); /* Efek blur latar belakang */
        align-items: center; 
        justify-content: center; 
        transition: all 0.3s ease;
    }

    .modal-content { 
        background: #ffffff; 
        padding: 30px; 
        border-radius: 15px; 
        width: 700px; /* Diperlebar */
        max-width: 95%; 
        position: relative; 
        box-shadow: 0 20px 40px rgba(0,0,0,0.4);
        animation: slideDown 0.4s ease-out;
    }

    @keyframes slideDown {
        from { transform: translateY(-50px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        border-bottom: 2px solid #f1f1f1;
        padding-bottom: 15px;
    }

    .modal-header h3 { margin: 0; color: #333; font-size: 1.5rem; }

    /* Form Styling */
    .form-row { display: flex; gap: 20px; margin-bottom: 15px; }
    .form-group { flex: 1; display: flex; flex-direction: column; }
    .form-group label { margin-bottom: 8px; font-weight: 600; color: #555; font-size: 14px; }
    
    .form-control { 
        padding: 12px; 
        border: 1px solid #ddd; 
        border-radius: 8px; 
        font-size: 14px;
        transition: border-color 0.3s;
    }

    .form-control:focus { border-color: #f39c12; outline: none; box-shadow: 0 0 5px rgba(243, 156, 18, 0.2); }

    /* Button Styling */
    .modal-footer { text-align: right; margin-top: 25px; padding-top: 15px; border-top: 1px solid #f1f1f1; }
    
    .btn-simpan { 
        background: linear-gradient(135deg, #f39c12, #e67e22); 
        color: white; border: none; 
        padding: 12px 30px; 
        border-radius: 8px; 
        cursor: pointer; 
        font-weight: bold; 
        font-size: 15px;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .btn-simpan:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(230, 126, 34, 0.4); }

    .btn-batal { 
        background: #ecf0f1; 
        color: #7f8c8d; border: none; 
        padding: 12px 25px; 
        border-radius: 8px; 
        cursor: pointer; 
        font-weight: 600;
        margin-right: 10px;
    }
    .btn-batal:hover { background: #dfe6e9; color: #2d3436; }
</style>

<div class="dashboard-container">
    <?php include 'partials/sidebar.php'; ?>

    <div class="main-content">
        <div class="background-image"></div> 
        <div class="overlay"></div>

        <header class="main-header">
            <h1 class="title">Data Mobil</h1> 
            <p class="greeting"><i class="fas fa-user-circle"></i> Hii <?php echo htmlspecialchars($nama_admin); ?>!!</p>
            
            <button class="tambah-ulasan-btn" id="tambah-mobil-btn">
                <i class="fas fa-plus-circle"></i> Tambah Mobil Baru
            </button>
        </header>

        <?php if(isset($status_tambah)): ?>
            <div style="background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin: 10px 0;">
                <?php echo $status_tambah; ?>
            </div>
        <?php endif; ?>

        <div class="content-box">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Foto</th>
                        <th>Merk & Model</th>
                        <th>Plat</th>
                        <th>Kategori</th>
                        <th>Warna</th>
                        <th>Deskripsi</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($data_mobil as $mobil): ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td>
                            <img src="../uploads/<?php echo $mobil['foto']; ?>" width="80" style="border-radius: 5px;">
                        </td>
                        <td>
                            <strong><?php echo htmlspecialchars($mobil['merek']); ?></strong><br>
                            <small><?php echo htmlspecialchars($mobil['model']); ?> (<?php echo $mobil['tahun']; ?>)</small>
                        </td>
                        <td><?php echo htmlspecialchars($mobil['plat']); ?></td>
                        <td><small><?php echo htmlspecialchars($mobil['kategori']); ?></small></td>
                        <td><?php echo htmlspecialchars($mobil['warna']); ?></td>
                        <td class="deskripsi-text"><?php echo htmlspecialchars($mobil['deskripsi']); ?></td>
                        <td>
                            <?php $class_warna = ($mobil['status'] == 'Tersedia') ? 'status-tersedia' : 'status-disewa'; ?>
                            <span class="badge-status <?php echo $class_warna; ?>">
                                <?php echo htmlspecialchars($mobil['status']); ?>
                            </span>
                        </td>
                        <td class="action-btns">
                            <a href="edit_mobil.php?id=<?php echo $mobil['id']; ?>" class="btn-aksi btn-edit"><i class="fas fa-edit"></i></a>
                            <a href="hapus_mobil.php?id=<?php echo $mobil['id']; ?>" class="btn-aksi btn-delete" onclick="return confirm('Hapus mobil ini?');"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="modalTambahMobil" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fas fa-car"></i> Tambah Unit Mobil Baru</h3>
            <span class="close-btn" style="cursor:pointer; font-size: 24px; color: #95a5a6;">&times;</span>
        </div>

        <form action="data_mobil.php" method="POST" enctype="multipart/form-data">
            <div class="form-row">
                <div class="form-group">
                    <label>Merk Mobil</label>
                    <input type="text" name="merk" placeholder="merk" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Model Mobil</label>
                    <input type="text" name="model" placeholder="model" class="form-control" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Tahun Keluaran</label>
                    <input type="number" name="tahun" placeholder="tahun" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Nomor Plat</label>
                    <input type="text" name="plat_nomor" placeholder="plat" class="form-control" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Tarif Sewa Per Hari (Rp)</label>
                    <input type="number" name="tarif_harian" placeholder="tarif harian" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Kategori</label>
                    <select name="kategori" class="form-control" required>
                        <option value="" disabled selected>Pilih Kategori</option>
                        <option value="Keluarga">Keluarga</option>
                        <option value="City Car">City Car</option>
                        <option value="Bus/MiniBus">Bus/MiniBus</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Warna Kendaraan</label>
                    <input type="text" name="warna" placeholder="warna" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Foto Mobil</label>
                    <input type="file" name="foto" class="form-control" accept="image/*" required>
                </div>
            </div>

            <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="3" placeholder="Deskripsi singkat mobil..."></textarea>
            </div>

            <div class="modal-footer" style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 25px; padding-top: 20px; border-top: 1px solid #eee;">
                <button type="button" class="btn-batal close-btn" style="margin: 0; min-width: 100px;">Batal</button>
                <button type="submit" name="tambah_mobil" class="btn-simpan" style="min-width: 150px;">
                    <i class="fas fa-save"></i> Simpan Mobil
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const modal = document.getElementById("modalTambahMobil");
    const btn = document.getElementById("tambah-mobil-btn");
    const closeBtns = document.querySelectorAll(".close-btn");

    btn.onclick = () => modal.style.display = "flex";
    closeBtns.forEach(c => c.onclick = () => modal.style.display = "none");
    window.onclick = (e) => { if (e.target == modal) modal.style.display = "none"; }
</script>