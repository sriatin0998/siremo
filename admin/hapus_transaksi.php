<?php
include '../config.php';
session_start();

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // 1. Ambil data id_mobil dari transaksi ini sebelum dihapus
    $cek = mysqli_query($koneksi, "SELECT id_mobil FROM transaksi_sewa WHERE id_transaksi = '$id'");
    $data = mysqli_fetch_assoc($cek);
    $id_mobil = $data['id_mobil'];

    // 2. Hapus data transaksi
    $query_hapus = mysqli_query($koneksi, "DELETE FROM transaksi_sewa WHERE id_transaksi = '$id'");

    if ($query_hapus) {
        // 3. Kembalikan status mobil menjadi Tersedia
        mysqli_query($koneksi, "UPDATE mobil SET status_ketersediaan = 'Tersedia' WHERE id_mobil = '$id_mobil'");
        
        echo "<script>alert('Transaksi berhasil dihapus dan status mobil telah dikembalikan.'); window.location='transaksi.php';</script>";
    } else {
        echo "Gagal menghapus: " . mysqli_error($koneksi);
    }
}