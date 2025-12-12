<?php
include 'config.php';
session_start();

// Jika sudah login, arahkan sesuai role
if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) {
    if ($_SESSION['role'] == 'admin') {
        header("Location: ../admin/index.php"); // Arahkan Admin
    } else {
        header("Location: ../index.php"); // Arahkan Penyewa
    }
    exit;
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    // Query sekarang menggunakan tabel users
    $sql = "SELECT id_user, nama_lengkap, password, role FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        
        // Verifikasi password
        if (password_verify($password, $row['password'])) {
            // Login sukses
            $_SESSION['user_logged_in'] = true;
            $_SESSION['id_user'] = $row['id_user'];
            $_SESSION['nama_lengkap'] = $row['nama_lengkap'];
            $_SESSION['role'] = $row['role']; // Simpan role

            // Arahkan berdasarkan role
            if ($row['role'] == 'admin') {
                header("Location: ../admin/kelola_mobil.php"); // Contoh halaman Admin
            } else {
                header("Location: ../index.php"); // Contoh halaman Penyewa/Publik
            }
            exit;
        } else {
            $error = "Email atau Kata Sandi salah.";
        }
    } else {
        $error = "Email atau Kata Sandi salah.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Pengguna - SIREMO</title>
    <link rel="stylesheet" href="../assets/style_penyewa1.css"> 
</head>
<body>
    <div class="background-image">
        <div class="form-container">
            <div class="logo">
                <span>SIREMO</span>
            </div>
            
            <div class="form-card">
                <h2>MASUK</h2>
                
                <?php if ($error): ?>
                    <p class="error-message"><?php echo $error; ?></p>
                <?php endif; ?>

                <form action="login.php" method="POST">
                    <input type="email" name="email" placeholder="Email" required>
                    <input type="password" name="password" placeholder="Kata Sandi" required>
                    
                    <a href="#" class="lupa-sandi">Lupa Kata Sandi?</a>
                    
                    <button type="submit" class="btn-submit">MASUK</button>
                </form>

                <p class="daftar-link">
                    Belum Punya Akun? <a href="register.php">Daftar</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>