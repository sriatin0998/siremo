<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Koneksi Database
$host = "localhost"; 
$user = "root";     
$pass = "";         
$db_name = "siremo_app"; 

$koneksi = mysqli_connect($host, $user, $pass, $db_name);

if (mysqli_connect_errno()) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

/**
 * Fungsi Cek Akses Universal
 */
function cek_akses($role_wajib) {
    // 1. Cek apakah session status sudah 'login'
    if (!isset($_SESSION['status']) || $_SESSION['status'] != 'login') {
        echo "<script>alert('Anda harus login!'); window.location='../login.php';</script>"; // Tambahkan ../
        exit;
    }
    
    // 2. Cek apakah role sesuai (admin/penyewa)
    if (!isset($_SESSION['role']) || $_SESSION['role'] != $role_wajib) {
        echo "<script>alert('Akses Ditolak!'); window.location='../login.php?pesan=hak_akses_ditolak';</script>"; // Tambahkan ../
        exit;
    }
}

function anti_injection($data){
    global $koneksi;
    $filter = mysqli_real_escape_string($koneksi, $data);
    return stripslashes(strip_tags(htmlspecialchars($filter, ENT_QUOTES)));
}
?>