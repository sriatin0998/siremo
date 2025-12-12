<?php
session_start();
include '../config.php'; 

if (!isset($_SESSION['status']) || $_SESSION['status'] != 'login_admin' || $_SESSION['role'] != 'admin') {
    header("location: login.php");
    exit;
}

if (isset($_GET['id']) && isset($koneksi)) {
    $id_mobil = (int) mysqli_real_escape_string($koneksi, $_GET['id']); 

    $query_delete = "DELETE FROM mobil WHERE id_mobil = $id_mobil";

    if (mysqli_query($koneksi, $query_delete)) {
        header("location: data_mobil.php?status_del=sukses");
    } else {
        header("location: data_mobil.php?status_del=gagal");
    }
    exit;
} else {
    header("location: data_mobil.php");
    exit;
}