<?php
include 'config.php';
session_start();

$success = '';
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $conn->real_escape_string($_POST['nama']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];
    $conf_password = $_POST['conf_password'];
    $role = 'penyewa'; // Otomatis set role sebagai 'penyewa' untuk pendaftaran publik
    $username = explode('@', $email)[0]; // Menggunakan bagian email sebagai username

    if ($password !== $conf_password) {
        $error = "Konfirmasi Kata Sandi tidak cocok.";
    } else {
        // Cek apakah email sudah terdaftar
        $check_sql = "SELECT id_user FROM users WHERE email = '$email'";
        $check_result = $conn->query($check_sql);

        if ($check_result->num_rows > 0) {
            $error = "Email sudah terdaftar. Silakan gunakan email lain atau login.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert data ke tabel users
            $insert_sql = "INSERT INTO users (username, password, nama_lengkap, role, email) VALUES ('$username', '$hashed_password', '$nama', '$role', '$email')";

            if ($conn->query($insert_sql) === TRUE) {
                $success = "Pendaftaran berhasil! Silakan masuk.";
            } else {
                $error = "Terjadi kesalahan saat pendaftaran: " . $conn->error;
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
    <title>Daftar Penyewa - SIREMO</title>
    <link rel="stylesheet" href="../assets/style_penyewa1.css">
</head>
<body>
    <div class="background-image">
        <div class="form-container">
            <div class="logo">
                <span>SIREMO</span>
            </div>
            
            <div class="form-card">
                <h2>DAFTAR</h2>
                
                <?php if ($success): ?>
                    <p class="success-message"><?php echo $success; ?></p>
                <?php endif; ?>
                <?php if ($error): ?>
                    <p class="error-message"><?php echo $error; ?></p>
                <?php endif; ?>

                <form action="register.php" method="POST">
                    <input type="text" name="nama" placeholder="Nama" required value="<?php echo isset($_POST['nama']) ? htmlspecialchars($_POST['nama']) : ''; ?>">
                    <input type="email" name="email" placeholder="Email" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                    <input type="password" name="password" placeholder="Password" required>
                    <input type="password" name="conf_password" placeholder="Conf Password" required>
                    
                    <button type="submit" class="btn-submit">DAFTAR</button>
                </form>
                
                <p class="login-link">
                    Sudah Punya Akun? <a href="login.php">Masuk</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>