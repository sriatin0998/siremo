<?php
include '../config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_transaksi = $_POST['id_transaksi'];
    $id_mobil     = $_POST['id_mobil'];
    $id_penyewa   = $_POST['id_penyewa'];
    $rating       = $_POST['rating'];
    $ulasan       = mysqli_real_escape_string($koneksi, $_POST['komentar']); // Ambil dari name="komentar"
    $tanggal      = date('Y-m-d H:i:s');

    // Masukkan ke kolom 'ulasan' sesuai gambar struktur database kamu
    $query = "INSERT INTO ulasan (id_mobil, id_penyewa, id_transaksi, ulasan, rating, tanggal) 
              VALUES ('$id_mobil', '$id_penyewa', '$id_transaksi', '$ulasan', '$rating', '$tanggal')";

    if (mysqli_query($koneksi, $query)) {
        echo "<script>alert('Terima kasih atas ulasannya!'); window.location='riwayat.php';</script>";
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
}
?>