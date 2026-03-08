<?php
include '../config.php';
cek_akses('admin');

if (isset($_POST['submit_kembali'])) {
    // Ambil data dari form kembalikan_mobil.php
    $id_transaksi = anti_injection($_POST['id_transaksi']);
    $id_mobil     = anti_injection($_POST['id_mobil']);
    $denda         = (int)anti_injection($_POST['denda']); // Pastikan angka
    $ulasan_denda = anti_injection($_POST['ulasan_denda']);
    $tgl_aktual   = date('Y-m-d');

    // 1. Ambil total_bayar lama untuk dijumlahkan dengan denda
    $query_lama = mysqli_query($koneksi, "SELECT total_bayar FROM transaksi_sewa WHERE id_transaksi = '$id_transaksi'");
    $data_lama = mysqli_fetch_assoc($query_lama);
    $total_bayar_baru = $data_lama['total_bayar'] + $denda;

    // 2. Update tabel transaksi_sewa
    // Menambahkan kolom total_bayar agar nilai denda masuk ke pendapatan total
    $query_transaksi = "UPDATE transaksi_sewa SET 
                        status_transaksi = 'Selesai', 
                        tgl_aktual_kembali = '$tgl_aktual',
                        denda = '$denda',
                        ulasan_denda = '$ulasan_denda',
                        total_bayar = '$total_bayar_baru' 
                        WHERE id_transaksi = '$id_transaksi'";

    // 3. Update tabel mobil
    $query_mobil = "UPDATE mobil SET status_ketersediaan = 'Tersedia' WHERE id_mobil = '$id_mobil'";

    // Jalankan transaksi database (Transaction)
    mysqli_begin_transaction($koneksi);

    try {
        mysqli_query($koneksi, $query_transaksi);
        mysqli_query($koneksi, $query_mobil);
        
        mysqli_commit($koneksi); // Simpan perubahan
        header("location: pengembalian.php?pesan=Mobil+berhasil+dikembalikan.+Denda+sebesar+Rp".number_format($denda)."+tercatat.");
        exit;
    } catch (Exception $e) {
        mysqli_rollback($koneksi); // Batalkan jika ada yang gagal
        echo "Gagal memproses pengembalian: " . $e->getMessage();
    }
} else {
    header("location: transaksi.php");
    exit;
}
?>