<?php
session_start();
include 'config.php'; 

$error_message = "";
$success_message = "";

if (isset($_POST['register'])) {
    $nama     = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $email    = mysqli_real_escape_string($koneksi, $_POST['email']);
    $username = mysqli_real_escape_string($koneksi, $_POST['username']); // Tambahkan input username di form
    $password = $_POST['password'];

    if (empty($nama) || empty($email) || empty($password)) {
        $error_message = "Semua kolom wajib diisi!";
    } else {
        // 1. Cek apakah email sudah ada di tabel users
        $cek_user = mysqli_query($koneksi, "SELECT email FROM users WHERE email='$email'");
        
        if (mysqli_num_rows($cek_user) > 0) {
            $error_message = "Email sudah terdaftar!";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // 2. INSERT ke tabel USERS (Data Utama Login)
            $query_user = "INSERT INTO users (username, password, nama_lengkap, role, email) 
                           VALUES ('$username', '$hashed_password', '$nama', 'penyewa', '$email')";
            
            if (mysqli_query($koneksi, $query_user)) {
                // 3. Ambil ID_USER yang baru saja dibuat
                $last_id = mysqli_insert_id($koneksi);
                
                // 4. INSERT ke tabel PENYEWA (Data Profil)
                // Kita masukkan id_user sebagai penghubung (Foreign Key)
                $query_penyewa = "INSERT INTO penyewa (id_user, nama, email) 
                                  VALUES ('$last_id', '$nama', '$email')";
                
                if (mysqli_query($koneksi, $query_penyewa)) {
                    $success_message = "Pendaftaran Berhasil! Silakan login untuk melengkapi profil.";
                } else {
                    $error_message = "Gagal membuat profil: " . mysqli_error($koneksi);
                }
            } else {
                $error_message = "Gagal mendaftar: " . mysqli_error($koneksi);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar SIREMO</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Menggunakan style yang sudah Anda buat, hanya disesuaikan sedikit */
        body, html { height: 100%; margin: 0; font-family: 'Poppins', sans-serif; background: #f4f7f6; }
        .bg-container {
            background-image: url('assets/img/road.jpg'); 
            background-size: cover; background-position: center;
            height: 100vh; display: flex; align-items: center; justify-content: center;
        }
        .register-card {
            background: white; padding: 40px; border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2); width: 100%; max-width: 400px; text-align: center;
        }
        .logo-section h1 { color: #e67e22; margin: 0; font-size: 28px; letter-spacing: 2px; }
        .subtitle { color: #888; margin: 10px 0 25px 0; font-size: 14px; }
        .form-group { text-align: left; margin-bottom: 15px; }
        .form-group label { display: block; font-size: 13px; color: #444; margin-bottom: 5px; font-weight: 600; }
        input {
            width: 100%; padding: 12px; border: 1.5px solid #eee; border-radius: 10px;
            box-sizing: border-box; font-size: 14px; background: #f9f9f9;
        }
        .btn-register {
            width: 100%; padding: 12px; background: #e67e22; border: none; color: white;
            font-size: 16px; font-weight: bold; border-radius: 10px; cursor: pointer; margin-top: 10px;
        }
        .alert { padding: 10px; border-radius: 10px; margin-bottom: 15px; font-size: 13px; }
        .alert-error { background: #fee2e2; color: #b91c1c; }
        .alert-success { background: #dcfce7; color: #15803d; }
    </style>
</head>
<body>
    <div class="bg-container">
        <div class="register-card">
            <div class="logo-section">
                <h1><i class="fas fa-car"></i> SIREMO</h1>
                <p class="subtitle">Buat akun penyewa Anda</p>
            </div>

            <?php if ($success_message): ?>
                <div class="alert alert-success"><?php echo $success_message; ?></div>
            <?php endif; ?>

            <?php if ($error_message): ?>
                <div class="alert alert-error"><?php echo $error_message; ?></div>
            <?php endif; ?>

            <form action="" method="POST">
                
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama" placeholder="Masukkan nama Anda" required>
                </div>
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" placeholder="Masukkan username Anda" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" placeholder="nama@email.com" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Min. 6 karakter" required>
                </div>
                
                <button type="submit" name="register" class="btn-register">Daftar Akun</button>
            </form>

            <div style="margin-top: 20px; font-size: 13px;">
                Sudah punya akun? <a href="login.php" style="color:#e67e22; text-decoration:none; font-weight:bold;">Login</a>
            </div>
        </div>
    </div>
</body>
</html>