<?php
session_start();

include '../config.php'; 

if (!isset($_SESSION['status']) || $_SESSION['status'] != 'login_admin' || $_SESSION['role'] != 'admin') {
    header("location: login.php");
    exit;
}

$username = $_SESSION['username'];
$nama_lengkap = isset($_SESSION['nama_lengkap']) ? $_SESSION['nama_lengkap'] : 'Admin'; 

if (!function_exists('anti_injection')) {
    function anti_injection($data) {
        global $koneksi; 
        $filter = stripslashes(strip_tags(htmlspecialchars($data, ENT_QUOTES)));
        if (isset($koneksi) && is_object($koneksi) && method_exists($koneksi, 'real_escape_string')) {
            $filter = $koneksi->real_escape_string($filter);
        }
        return $filter;
    }
}

$status_tambah = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tambah_mobil'])) {
    
    $merk = anti_injection($_POST['merk']); 
    $model = anti_injection($_POST['model']);
    $tahun = anti_injection($_POST['tahun']);
    $plat = anti_injection($_POST['plat_nomor']);
    $tarif_raw = anti_injection($_POST['tarif_harian']); 
    $status_ketersediaan = 'Tersedia'; 

    if (isset($koneksi)) {
        $query_insert = "INSERT INTO mobil (merek, model, tahun, plat_nomor, tarif_sewa_per_hari, status_ketersediaan) 
                        VALUES ('$merk', '$model', '$tahun', '$plat', '$tarif_raw', '$status_ketersediaan')";
        
        if (mysqli_query($koneksi, $query_insert)) {
            header("location: data_mobil.php?status_add=sukses&merk=" . urlencode($merk) . "&model=" . urlencode($model));
            exit;
        } else {
            $status_tambah = "Gagal menambahkan mobil: " . mysqli_error($koneksi);
        }
    } else {
        $status_tambah = "⚠️ KESALAHAN: Koneksi database tidak ditemukan. Harap cek file config.php.";
    }
}

if (isset($_GET['status_add']) && $_GET['status_add'] == 'sukses') {
    $merk_tambah = isset($_GET['merk']) ? anti_injection($_GET['merk']) : 'Mobil Baru';
    $model_tambah = isset($_GET['model']) ? anti_injection($_GET['model']) : '';
    $status_tambah = "Mobil $merk_tambah $model_tambah berhasil ditambahkan.";
} elseif (isset($_GET['status_del']) && $_GET['status_del'] == 'sukses') {
    $status_tambah = "Data mobil berhasil dihapus.";
} elseif (isset($_GET['status_del']) && $_GET['status_del'] == 'gagal') {
    $status_tambah = "Gagal menghapus data mobil.";
} elseif (isset($_GET['status_edit']) && $_GET['status_edit'] == 'sukses') {
    $status_tambah = "Data mobil berhasil diperbarui.";
}

$data_mobil = [];
if (isset($koneksi)) {
    $query_select = "SELECT 
                        id_mobil AS id, 
                        merek, 
                        model, 
                        tahun, 
                        plat_nomor AS plat, 
                        CONCAT('Rp ', FORMAT(tarif_sewa_per_hari, 0)) AS tarif, 
                        status_ketersediaan AS status 
                    FROM mobil 
                    ORDER BY id_mobil DESC";

    $result = mysqli_query($koneksi, $query_select);
    if ($result) {
        $data_mobil = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        $status_tambah = "Error saat mengambil data mobil: " . mysqli_error($koneksi);
    }
    
} else {
    $data_mobil = [
    ];
    if (empty($status_tambah)) {
        $status_tambah = "⚠️ PERINGATAN: Data fiktif. Koneksi database hilang. Harap cek file config.php.";
    }
}
?>

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
                <span class="car-icon">🚗</span> 
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

        <div class="main-content">
            <div class="background-image"></div> 
            <div class="overlay"></div> 
            
            <header class="main-header">
                <h1 class="title">Data Mobil</h1> 
                <p class="greeting"><i class="fas fa-user-circle"></i> Hii <?php echo $nama_lengkap; ?>!!</p>
                
                <?php if (!empty($status_tambah)): ?>
                <div class="alert-message">
                    <?php echo $status_tambah; ?>
                </div>
                <?php endif; ?>

                <button class="tambah-ulasan-btn" id="tambah-mobil-btn">
                    <i class="fas fa-plus"></i> Tambah Mobil Baru
                </button>

            </header>

            <div class="content-box">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Merk</th>
                            <th>Model</th>
                            <th>Tahun</th>
                            <th>Plat Nomor</th>
                            <th>Tarif/Hari</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($data_mobil as $mobil): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo htmlspecialchars($mobil['merek']); ?></td>
                            <td><?php echo htmlspecialchars($mobil['model']); ?></td>
                            <td><?php echo htmlspecialchars($mobil['tahun']); ?></td>
                            <td><?php echo htmlspecialchars($mobil['plat']); ?></td>
                            <td><?php echo htmlspecialchars($mobil['tarif']); ?></td>
                            <td>
                                <span class="status-badge status-<?php echo strtolower(str_replace(' ', '-', $mobil['status'])); ?>">
                                    <?php echo htmlspecialchars($mobil['status']); ?>
                                </span>
                            </td>
                            <td class="action-btns">
                                <a href="edit_mobil.php?id=<?php echo htmlspecialchars($mobil['id']); ?>" class="btn-aksi btn-edit" title="Edit Data">
                                    <i class="fas fa-edit"></i>
                                </a>
                                
                                <a href="hapus_mobil.php?id=<?php echo htmlspecialchars($mobil['id']); ?>" 
                                class="btn-aksi btn-delete" 
                                title="Hapus Data"
                                onclick="return confirm('Apakah Anda yakin ingin menghapus mobil <?php echo htmlspecialchars($mobil['merek'] . ' ' . $mobil['model']); ?>?');">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($data_mobil)): ?>
                        <tr>
                            <td colspan="8" style="text-align: center; color: #777;">Belum ada data mobil yang tersedia.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
        </div>
    </div>
    
    <div id="modalTambahMobil" class="modal">
        <div class="modal-content">
            <span class="close-btn">&times;</span>
            <h2>Tambah Data Mobil Baru</h2>
            <form action="" method="POST">
                
                <div class="form-group">
                    <label for="merk">Merk Mobil</label>
                    <input type="text" id="merk" name="merk" required>
                </div>

                <div class="form-group">
                    <label for="model">Model Mobil</label>
                    <input type="text" id="model" name="model" required>
                </div>
                
                <div class="form-group">
                    <label for="tahun">Tahun Produksi</label>
                    <input type="number" id="tahun" name="tahun" min="2000" max="<?php echo date('Y'); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="plat_nomor">Plat Nomor</label>
                    <input type="text" id="plat_nomor" name="plat_nomor" required>
                </div>
                
                <div class="form-group">
                    <label for="tarif_harian">Tarif Harian (Contoh: 300000)</label>
                    <input type="number" id="tarif_harian" name="tarif_harian" required>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn-cancel close-btn">Batal</button>
                    <button type="submit" name="tambah_mobil" class="btn-submit">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        const modal = document.getElementById("modalTambahMobil");
        const btn = document.getElementById("tambah-mobil-btn");
        const closeBtns = document.querySelectorAll(".close-btn");
        const modalContent = document.querySelector(".modal-content");

        btn.onclick = function() {
            modal.style.display = "flex";
        }

        closeBtns.forEach(c => {
            c.onclick = function(event) {
                if (event.target.classList.contains('close-btn')) {
                    modal.style.display = "none";
                }
            }
        });
        
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</body>
</html>