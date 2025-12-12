<?php
session_start(); 
include '../config.php';

if (!isset($koneksi)) {
    die("Error: Koneksi database tidak ditemukan. Pastikan config.php sudah benar.");
}

if (isset($_SESSION['status']) && $_SESSION['status'] == 'login_admin' && $_SESSION['role'] == 'admin') {
    header("location: dashboard.php"); 
    exit;
}

$error_message = "";

if (isset($_POST['login'])) {

    $username_input = anti_injection($_POST['username']);
    $password_input = $_POST['password']; 
    
    if (empty($username_input) || empty($password_input)) {
        header('location: login.php?alert=belum_lengkap'); 
        exit;
    } 

    $query = mysqli_query($koneksi, "SELECT id_user, username, password, nama_lengkap, role FROM users WHERE username='$username_input' AND role='admin'");
    $data = mysqli_fetch_assoc($query);

    if ($data) {
        if (password_verify($password_input, $data['password'])) {
            
            $_SESSION['id_user'] = $data['id_user'];
            $_SESSION['username'] = $data['username'];
            $_SESSION['nama_lengkap'] = $data['nama_lengkap'];
            $_SESSION['role'] = $data['role']; 
            $_SESSION['status'] = "login_admin";
            
            header("location: dashboard.php");
            exit;

        } else {
            $error_message = "Password salah!";
        }
    } else {
        $error_message = "Username tidak ditemukan atau Anda tidak memiliki hak akses Admin!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - SIREMO</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <div class="logo">
                <span class="car-icon">🚗</span> 
                <h1>SIREMO</h1>
            </div>
            <h2>Login Admin</h2>
            
            <?php 
            if (!empty($error_message)) {
                echo "<div class='error-msg'>$error_message</div>";
            } 
            if (isset($_GET['alert']) && $_GET['alert'] == 'belum_lengkap') {
                echo "<div class='error-msg'>Username dan Password harus diisi!</div>";
            }
            ?>
            
            <form action="login.php" method="POST">
                <input type="text" name="username" placeholder="Username" required>
                <input type="email" name="email" placeholder="Email" required> 
                <input type="password" name="password" placeholder="Password" required>
                
                <button type="submit" name="login" class="login-button">LOGIN</button> 
            </form>
            
            <p style="margin-top: 20px; font-size: 0.9em;">
                Belum punya akun? <a href="register.php" style="color: #ff8c00; text-decoration: none; font-weight: bold;">Register di sini</a>
            </p>
        </div>
    </div>
</body>
</html>