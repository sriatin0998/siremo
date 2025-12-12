<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$host = "localhost"; 
$user = "root";     
$pass = "";         
$db_name = "siremo_app"; 


$koneksi = mysqli_connect($host, $user, $pass, $db_name);

if (mysqli_connect_errno()) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

/**
 * Fungsi untuk membersihkan input data dari potensi serangan SQL Injection dan XSS dasar.
 * @param string $data Data input dari user.
 * @return string Data yang sudah diamankan.
 */
function anti_injection($data){
    global $koneksi;
    $filter = mysqli_real_escape_string($koneksi, $data);
    $filter = stripslashes(strip_tags(htmlspecialchars($filter, ENT_QUOTES)));
    return $filter;
}

function admin_guard() {
    $login_page = '../login.php'; 
    
    if (!isset($_SESSION['status']) || $_SESSION['status'] != 'login_admin' || $_SESSION['role'] != 'admin') {
        session_unset();
        session_destroy();
        header("location: " . $login_page);
        exit;
    }
}