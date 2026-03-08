<?php
include '../config.php'; 
cek_akses('admin'); 

$nama_admin = $_SESSION['nama'];
$status_konfirmasi = "";

// ==========================================
// LOGIKA 1: KONFIRMASI PEMBAYARAN (SETUJU)
// ==========================================
if (isset($_GET['aksi']) && $_GET['aksi'] == 'setuju' && isset($_GET['id'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);
    
    $query_get_mobil = "SELECT id_mobil FROM transaksi_sewa WHERE id_transaksi = '$id'";
    $result_mobil = mysqli_query($koneksi, $query_get_mobil);
    $data_tr = mysqli_fetch_assoc($result_mobil);
    $id_mobil = $data_tr['id_mobil'];

    // Update status transaksi jadi Disewa (Mobil sedang dipakai)
    $query_update_tr = "UPDATE transaksi_sewa SET status_pembayaran = 'Lunas', status_transaksi = 'Disewa' WHERE id_transaksi = '$id'";
    $query_update_mobil = "UPDATE mobil SET status_ketersediaan = 'Disewa' WHERE id_mobil = '$id_mobil'";
    
    if (mysqli_query($koneksi, $query_update_tr) && mysqli_query($koneksi, $query_update_mobil)) {
        header("location: transaksi.php?status=sukses");
        exit;
    }
}

// ==========================================
// LOGIKA 2: KONFIRMASI PENGEMBALIAN & DENDA
// ==========================================
if (isset($_POST['proses_kembali'])) {
    $id_tr = mysqli_real_escape_string($koneksi, $_POST['id_transaksi']);
    $nominal_denda = mysqli_real_escape_string($koneksi, $_POST['nominal_denda']);
    $ulasan_denda = mysqli_real_escape_string($koneksi, $_POST['ulasan_denda']);

    // Ambil ID mobil terkait transaksi ini
    $query_m = "SELECT id_mobil FROM transaksi_sewa WHERE id_transaksi = '$id_tr'";
    $res_m = mysqli_query($koneksi, $query_m);
    $data_m = mysqli_fetch_assoc($res_m);
    $id_mobil = $data_m['id_mobil'];

    // 1. Update data denda dan ubah status transaksi menjadi 'Selesai'
    $update_tr = "UPDATE transaksi_sewa SET 
                  denda = '$nominal_denda', 
                  ulasan_denda = '$ulasan_denda', 
                  status_transaksi = 'Selesai' 
                  WHERE id_transaksi = '$id_tr'";

    // 2. Kembalikan status mobil menjadi 'Tersedia'
    $update_mobil = "UPDATE mobil SET status_ketersediaan = 'Tersedia' WHERE id_mobil = '$id_mobil'";

    if (mysqli_query($koneksi, $update_tr) && mysqli_query($koneksi, $update_mobil)) {
        header("location: transaksi.php?status=kembali_sukses");
        exit;
    }
}

if (isset($_GET['status'])) {
    if ($_GET['status'] == 'sukses') $status_konfirmasi = "Pembayaran dikonfirmasi & Mobil sedang Disewa!";
    if ($_GET['status'] == 'kembali_sukses') $status_konfirmasi = "Mobil Berhasil Dikembalikan & Status Update!";
}

$query_read = "SELECT ts.*, p.nama, m.merek, m.model, m.status_ketersediaan as status_mobil_sekarang 
               FROM transaksi_sewa ts 
               LEFT JOIN penyewa p ON ts.id_penyewa = p.id_penyewa 
               LEFT JOIN mobil m ON ts.id_mobil = m.id_mobil 
               ORDER BY ts.id_transaksi DESC";
$result = mysqli_query($koneksi, $query_read);
?>

<<<<<<< HEAD
<?php include 'partials/header.php'; ?>
=======
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi - SIREMO</title>
    
    <link rel="stylesheet" href="../assets/style3.css"> 
    <link rel="stylesheet" href="../assets/style7.css"> 
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
                <li class="menu-item"><a href="kelola_penyewa.php">Data Penyewa</a></li>
                <li class="menu-item active-link"><a href="transaksi.php">Transaksi</a></li>
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
    .status-badge {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: bold;
        color: white;
        text-transform: uppercase;
        display: inline-block;
    }
    .status-lunas { background-color: #2ecc71; }
    .status-menunggu-konfirmasi { background-color: #f1c40f; color: #333; }
    .status-belum-bayar { background-color: #e74c3c; }
    .status-selesai { background-color: #34495e; }

    /* Modal Styling */
    .modal {
        display: none; 
        position: fixed; 
        z-index: 9999; 
        left: 0; top: 0;
        width: 100%; height: 100%; 
        background-color: rgba(0,0,0,0.8);
        align-items: center;
        justify-content: center;
    }
    .modal-content {
        background-color: white;
        padding: 20px;
        border-radius: 15px;
        position: relative;
        max-width: 500px;
        width: 90%;
        text-align: center;
        animation: zoomIn 0.3s ease;
    }
    @keyframes zoomIn {
        from {transform: scale(0.8); opacity: 0;}
        to {transform: scale(1); opacity: 1;}
    }
</style>

<div class="dashboard-container">
    <?php include 'partials/sidebar.php'; ?>

    <div class="main-content">
        <div class="background-image"></div> 
        <div class="overlay"></div> 
        
        <header class="main-header" style="padding: 20px; position: relative; z-index: 2;">
            <h1 class="title" style="color: white;">Manajemen Transaksi</h1> 
            <p class="greeting" style="color: white;"><i class="fas fa-user-circle"></i> Hii <?php echo htmlspecialchars($nama_admin); ?>!!</p>
            
            <?php if (!empty($status_konfirmasi)): ?>
                <div id="alert-notif" style="background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
                    <i class="fas fa-check-circle"></i> <?php echo $status_konfirmasi; ?>
                </div>
            <?php endif; ?>
        </header>

        <div class="content-box" style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); margin: 0 20px; overflow-x: auto;">
            <table class="data-table" style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8f9fa; text-align: left; border-bottom: 2px solid #dee2e6;">
                        <th style="padding: 12px;">No</th>
                        <th style="padding: 12px;">Penyewa</th>
                        <th style="padding: 12px;">Mobil</th>
                        <th style="padding: 12px;">Tgl Kembali</th>
                        <th style="padding: 12px;">Total</th>
                        <th style="padding: 12px; text-align: center;">Bukti</th>
                        <th style="padding: 12px;">Denda</th>
                        <th style="padding: 12px;">Ulasan Denda</th>
                        <th style="padding: 12px;">Status</th>
                        <th style="padding: 12px;">Status Mobil</th>
                        <th style="padding: 12px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; while($row = mysqli_fetch_assoc($result)): ?>
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 12px;"><?php echo $no++; ?></td>
                        <td style="padding: 12px;"><strong><?php echo htmlspecialchars($row['nama']); ?></strong></td>
                        <td style="padding: 12px;"><?php echo htmlspecialchars($row['merek']); ?></td>
                        <td style="padding: 12px;"><?php echo date('d/m/y', strtotime($row['tgl_rencana_kembali'])); ?></td>
                        <td style="padding: 12px;">Rp<?php echo number_format($row['total_bayar'], 0, ',', '.'); ?></td>
                        
                        <td style="padding: 12px; text-align: center;">
                            <?php if (!empty($row['bukti_pembayaran'])): ?>
                                <a href="#" class="view-proof-btn" data-img="../uploads/bukti_bayar/<?php echo $row['bukti_pembayaran']; ?>" style="color: #3498db;">
                                    <i class="fas fa-image fa-lg"></i>
                                </a>
                            <?php else: ?>
                                <i class="fas fa-times-circle" style="color: #ccc;"></i>
                            <?php endif; ?>
                        </td>

                        <td style="padding: 12px; color: #e74c3c; font-weight: bold;">
                            Rp<?php echo number_format($row['denda'] ?? 0, 0, ',', '.'); ?>
                        </td>

                        <td style="padding: 12px; font-size: 12px; color: #555;">
                            <?php echo htmlspecialchars($row['ulasan_denda'] ?? '-'); ?>
                        </td>

                        <td style="padding: 12px;">
                            <?php 
                                $status_p = $row['status_pembayaran'];
                                $class_status = strtolower(str_replace(' ', '-', $status_p)); 
                            ?>
                            <span class="status-badge status-<?php echo $class_status; ?>">
                                <?php echo htmlspecialchars($status_p); ?>
                            </span>
                        </td>

                        <td style="padding: 12px;">
    <?php 
        $st_mobil = $row['status_transaksi']; // Mengambil status dari transaksi
        $color = ($st_mobil == 'Disewa') ? '#e67e22' : '#2ecc71';
    ?>
    <span style="color: white; background: <?php echo $color; ?>; padding: 3px 8px; border-radius: 5px; font-size: 10px;">
        <?php echo ($st_mobil == 'Disewa') ? 'DALAM SEWA' : 'SUDAH KEMBALI'; ?>
    </span>
</td>
                        
                    <td style="padding: 12px; text-align: center; white-space: nowrap;">
    <?php if($row['status_transaksi'] == 'Disewa'): ?>
        <a href="javascript:void(0)" 
           class="btn-denda" 
           data-id="<?php echo $row['id_transaksi']; ?>" 
           title="Konfirmasi Mobil Kembali & Denda"
           style="background: #e67e22; color: white; padding: 6px 10px; border-radius: 5px; text-decoration: none;">
           <i class="fa fa-undo"></i> Kembali
        </a>
    <?php endif; ?>

                        <td style="padding: 12px; text-align: center; white-space: nowrap;">
                            <?php if($row['status_pembayaran'] != 'Lunas'): ?>
                                <a href="transaksi.php?aksi=setuju&id=<?php echo $row['id_transaksi']; ?>" 
                                   style="background: #2ecc71; color: white; padding: 6px 10px; border-radius: 5px; text-decoration: none;" 
                                   onclick="return confirm('Konfirmasi lunas?')">
                                    <i class="fa fa-check"></i>
                                </a>
                            <?php endif; ?>

                            <a href="hapus_transaksi.php?id=<?php echo $row['id_transaksi']; ?>" 
                               style="background: #e74c3c; color: white; padding: 6px 10px; border-radius: 5px; text-decoration: none; margin-left: 5px;" 
                               onclick="return confirm('Hapus data?')">
                                <i class="fa fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="modalBukti" class="modal">
    <div class="modal-content">
        <span class="close-btn" style="float: right; cursor: pointer; font-size: 28px;">&times;</span>
        <h3 style="margin-top: 0;">Bukti Pembayaran</h3>
        <hr>
        <img id="imgBukti" src="" style="width: 100%; border-radius: 8px; margin-top: 15px; border: 1px solid #ddd; display: block;">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
</div>

<div id="modalDenda" class="modal">
    <div class="modal-content" style="text-align: left;">
        <h3 style="margin-top: 0;">Konfirmasi Pengembalian Mobil</h3>
        <p style="font-size: 13px; color: #666;">Silahkan isi denda jika ada. Jika tidak ada, biarkan 0.</p>
        <hr>
        <form action="" method="POST" style="margin-top: 15px;">
            <input type="hidden" name="id_transaksi" id="modal_id_tr">
            
            <label>Nominal Denda (Rp)</label>
            <input type="number" name="nominal_denda" id="modal_nominal" value="0" class="form-control" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;" required>

            <label>Alasan Denda / Catatan Kondisi Mobil</label>
            <textarea name="ulasan_denda" id="modal_ulasan" rows="3" style="color: #333;" placeholder="alasan"></textarea>
            
            <div style="margin-top: 20px; text-align: right;">
                <button type="button" class="close-denda" style="padding: 10px 20px; background: #95a5a6; color: white; border: none; border-radius: 5px; cursor: pointer;">Batal</button>
                <button type="submit" name="proses_kembali" style="background: #27ae60; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">
                    Konfirmasi Mobil Kembali
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const modal = document.getElementById("modalBukti");
    const imgBukti = document.getElementById("imgBukti");
    const viewBtns = document.querySelectorAll(".view-proof-btn");
    const closeBtns = document.querySelectorAll(".close-btn");
    const modalDenda = document.getElementById("modalDenda");
    const btnDenda = document.querySelectorAll(".btn-denda");
    const closeDenda = document.querySelector(".close-denda");


    btnDenda.forEach(btn => {
    btn.onclick = function() {
        document.getElementById("modal_id_tr").value = this.getAttribute("data-id");
        document.getElementById("modal_nominal").value = this.getAttribute("data-denda");
        document.getElementById("modal_ulasan").value = this.getAttribute("data-ulasan");
        modalDenda.style.display = "flex";
    }
    });

closeDenda.onclick = () => {
    modalDenda.style.display = "none";
}

window.onclick = (e) => { 
    if (e.target == modal) {
        modal.style.display = "none";
        imgBukti.src = "";
    } 
    if (e.target == modalDenda) {
        modalDenda.style.display = "none";
    }
}

    viewBtns.forEach(btn => {
        btn.onclick = function(e) {
            e.preventDefault();
            const url = this.getAttribute("data-img");
            
            // Masukkan URL ke gambar dan langsung tampilkan modal
            imgBukti.src = url;
            modal.style.display = "flex";

            // Bagian imgBukti.onerror SUDAH DIHAPUS agar tidak ada alert lagi
        }
    });

    // Fungsi untuk menutup modal
    closeBtns.forEach(c => c.onclick = () => {
        modal.style.display = "none";
        imgBukti.src = "";
    });

    // Tutup modal jika klik di luar area gambar
    window.onclick = (e) => { 
        if (e.target == modal) {
            modal.style.display = "none";
            imgBukti.src = "";
        } 
    }

    // Notifikasi sukses (alert hijau di atas) akan hilang otomatis dalam 3 detik
    const notif = document.getElementById("alert-notif");
    if (notif) {
        setTimeout(() => {
            notif.style.transition = "opacity 0.5s ease";
            notif.style.opacity = "0";
            setTimeout(() => { notif.remove(); }, 500);
        }, 3000);
    }
</script>

</body>
</html>