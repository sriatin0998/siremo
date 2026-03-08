<?php
include '../config.php';
cek_akses('admin');

// Ambil data untuk ringkasan (Sama seperti di dashboard)
$q_mobil = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM mobil");
$total_mobil = mysqli_fetch_assoc($q_mobil)['total'];

$q_income = mysqli_query($koneksi, "SELECT SUM(total_bayar) AS total FROM transaksi_sewa");
$pendapatan = mysqli_fetch_assoc($q_income)['total'] ?? 0;

// Ambil Detail Transaksi untuk Tabel
$query_tabel = mysqli_query($koneksi, "SELECT ts.*, m.merek, p.nama 
    FROM transaksi_sewa ts
    JOIN mobil m ON ts.id_mobil = m.id_mobil
    JOIN penyewa p ON ts.id_penyewa = p.id_penyewa
    ORDER BY ts.tgl_sewa DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Laporan Transaksi SIREMO</title>
    <style>
        body { font-family: sans-serif; margin: 30px; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #444; padding-bottom: 10px; }
        .stats-info { margin-bottom: 20px; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table, th, td { border: 1px solid #444; }
        th { background-color: #f2f2f2; padding: 10px; text-align: left; }
        td { padding: 8px; }
        .text-right { text-align: right; }
        .badge { padding: 4px 8px; border-radius: 4px; font-size: 11px; }
        
        @media print {
            .no-print { display: none; }
            @page { margin: 1cm; }
        }
    </style>
</head>
<body>

    <div class="no-print" style="margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 10px; cursor: pointer;">Klik untuk Simpan PDF / Cetak</button>
        <button onclick="window.close()" style="padding: 10px; cursor: pointer;">Tutup</button>
    </div>

    <div class="header">
        <h1>LAPORAN TRANSAKSI RENTAL MOBIL</h1>
        <p>Sistem Informasi SIREMO - Tanggal: <?= date('d/m/Y H:i'); ?></p>
    </div>

    <div class="stats-info">
        <p>Total Unit Mobil: <?= $total_mobil; ?></p>
        <p>Total Pendapatan: Rp <?= number_format($pendapatan, 0, ',', '.'); ?></p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>ID Transaksi</th>
                <th>Penyewa</th>
                <th>Mobil</th>
                <th>Tgl Sewa</th>
                <th>Status</th>
                <th>Total Bayar</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            while($row = mysqli_fetch_assoc($query_tabel)): 
            ?>
            <tr>
                <td><?= $no++; ?></td>
                <td>SRM-<?= $row['id_transaksi']; ?></td>
                <td><?= $row['nama']; ?></td>
                <td><?= $row['merek']; ?></td>
                <td><?= date('d/m/Y', strtotime($row['tgl_sewa'])); ?></td>
                <td><?= strtoupper($row['status_transaksi']); ?></td>
                <td class="text-right">Rp <?= number_format($row['total_bayar'], 0, ',', '.'); ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
        <tfoot>
            <tr style="font-weight: bold; background: #f9f9f9;">
                <td colspan="6" class="text-right">GRAND TOTAL</td>
                <td class="text-right">Rp <?= number_format($pendapatan, 0, ',', '.'); ?></td>
            </tr>
        </tfoot>
    </table>

    <div style="margin-top: 50px; text-align: right;">
        <p>Indramayu, <?= date('d F Y'); ?></p>
        <br><br><br>
        <p><strong>Admin SIREMO</strong></p>
    </div>

    <script>
        // Otomatis buka dialog print saat halaman dimuat
        window.onload = function() { window.print(); }
    </script>
</body>
</html>