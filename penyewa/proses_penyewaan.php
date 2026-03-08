<?php
include '../config.php';
session_start();

// 1. Validasi Login
if (!isset($_SESSION['id_user'])) {
    header("location: ../login.php");
    exit;
}

if (isset($_POST['konfirmasi_sewa'])) {
    // 2. Ambil Data dari Form
    $id_penyewa   = mysqli_real_escape_string($koneksi, $_POST['id_penyewa']);
    $id_mobil     = mysqli_real_escape_string($koneksi, $_POST['id_mobil']);
    $tgl_sewa     = mysqli_real_escape_string($koneksi, $_POST['tgl_sewa']);
    $tgl_kembali  = mysqli_real_escape_string($koneksi, $_POST['tgl_kembali']);
    $metode_bayar = mysqli_real_escape_string($koneksi, $_POST['metode_bayar']);
    $total_bayar  = mysqli_real_escape_string($koneksi, $_POST['total_bayar']);
    $status_sewa  = "Menunggu Konfirmasi"; // Status default saat booking baru

    // 3. Validasi Tanggal (Server Side)
    if (strtotime($tgl_kembali) < strtotime($tgl_sewa)) {
        echo "<script>alert('Tanggal kembali tidak valid!'); window.history.back();</script>";
        exit;
    }

    // 4. Proses Upload Bukti Bayar (Jika memilih Transfer/E-Wallet)
    $nama_file_bukti = "";
    if ($metode_bayar !== "Tunai") {
        if (!empty($_FILES['bukti_bayar']['name'])) {
            $target_dir = "../uploads/bukti_bayar/";
            
            // Buat folder jika belum ada
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            $ekstensi_diperbolehkan = array('png', 'jpg', 'jpeg');
            $x = explode('.', $_FILES['bukti_bayar']['name']);
            $ekstensi = strtolower(end($x));
            $file_tmp = $_FILES['bukti_bayar']['tmp_name'];
            
            // Penamaan file unik: id_user + timestamp
            $nama_file_bukti = "BUKTI_" . $_SESSION['id_user'] . "_" . time() . "." . $ekstensi;

            if (in_array($ekstensi, $ekstensi_diperbolehkan) === true) {
                move_uploaded_file($file_tmp, $target_dir . $nama_file_bukti);
            } else {
                echo "<script>alert('Format bukti bayar harus JPG/PNG!'); window.history.back();</script>";
                exit;
            }
        } else {
            echo "<script>alert('Mohon unggah bukti pembayaran!'); window.history.back();</script>";
            exit;
        }
    }

    // 5. Insert ke Database (Disesuaikan dengan tabel transaksi_sewa)
$query = "INSERT INTO transaksi_sewa (
            id_penyewa, 
            id_mobil, 
            tgl_sewa, 
            tgl_rencana_kembali, 
            total_bayar, 
            bukti_pembayaran, 
            status_transaksi
          ) VALUES (
            '$id_penyewa', 
            '$id_mobil', 
            '$tgl_sewa', 
            '$tgl_kembali', 
            '$total_bayar', 
            '$nama_file_bukti', 
            'Pending'
          )";

if (mysqli_query($koneksi, $query)) {
    // 6. Update Status Mobil (Gunakan kolom yang ada di tabel mobil Anda)
    // Jika di tabel mobil kolomnya bernama 'status', gunakan:
    // mysqli_query($koneksi, "UPDATE mobil SET status = 'Disewa' WHERE id_mobil = '$id_mobil'");

    echo "<script>
            alert('Pemesanan berhasil! Mohon tunggu konfirmasi admin.');
            window.location='transaksi_selesai.php?status=success';
          </script>";
} else {
    echo "Gagal menyimpan data: " . mysqli_error($koneksi);
}
} else {
    // Jika mencoba akses langsung tanpa POST
    header("location: mobil.php");
    exit;
}
?>