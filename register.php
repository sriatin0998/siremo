<?php
session_start();
include 'config.php'; 

if (isset($_SESSION['status']) && $_SESSION['status'] == 'login_admin' && $_SESSION['role'] == 'admin') {
    header("location: admin/dashboard.php");
    exit;
}

$error_message = "";
$success_message = "";


if (isset($_POST['register'])) {
    
    $username = mysqli_real_escape_string($koneksi, $_POST['username']); 
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $nama_lengkap = mysqli_real_escape_string($koneksi, $_POST['nama_lengkap']);
    $password_input = $_POST['password']; 
    
    if (empty($username) || empty($email) || empty($password_input) || empty($nama_lengkap)) {
        $error_message = "Semua field harus diisi!";
    } else {
        $check_query = mysqli_query($koneksi, "SELECT username, email FROM users WHERE username='$username' OR email='$email'");
        
        if (mysqli_num_rows($check_query) > 0) {
            $error_message = "Username atau Email sudah terdaftar!";
        } else {
            $hashed_password = password_hash($password_input, PASSWORD_DEFAULT);
            
            $role = 'admin'; 

            $insert_query = "INSERT INTO users (username, email, password, nama_lengkap, role) 
                            VALUES ('$username', '$email', '$hashed_password', '$nama_lengkap', '$role')";
            
            if (mysqli_query($koneksi, $insert_query)) {
                $success_message = "Pendaftaran Admin berhasil! Silakan <a href='login.php' style='color: #ff8c00; font-weight: bold;'>Login</a>.";
            } else {
                $error_message = "Registrasi gagal: " . mysqli_error($koneksi);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Admin - SIREMO</title>
    <link rel="stylesheet" href="assets/style2.css"> 
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <div class="logo">
                <span class="car-icon">🚗</span> 
                <h1>SIREMO</h1>
            </div>
            <h2>Register Admin</h2>
            
            <?php 
            if (!empty($error_message)) {
                echo "<div class='error-msg'>$error_message</div>";
            } elseif (!empty($success_message)) {
                echo "<div style='color: green; margin-bottom: 15px;'>$success_message</div>";
            }
            ?>

            <form action="register.php" method="POST">
                <input type="text" name="username" placeholder="Username" required>
                
                <input type="email" name="email" placeholder="Email" required>
                
                <input type="text" name="nama_lengkap" placeholder="Nama Lengkap" required>
                
                <input type="password" name="password" placeholder="Password" required>
                
                <button type="submit" name="register" class="login-button">REGISTER</button>
            </form>
            
            <p style="margin-top: 20px; font-size: 0.9em;">
                Sudah punya akun? <a href="login.php" style="color: #ff8c00; text-decoration: none; font-weight: bold;">Login di sini</a>
            </p>
        </div>
    </div>
</body>
</html>