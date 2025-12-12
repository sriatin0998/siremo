<?php
// Ganti dengan detail database Anda
$host = "localhost";
$user = "root";
$pass = "";
$db   = "siremo_app"; // Pastikan ini sesuai dengan nama database Anda (siremo)

$conn = new mysqli($host, $user, $pass, $db);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

date_default_timezone_set('Asia/Jakarta');
?>