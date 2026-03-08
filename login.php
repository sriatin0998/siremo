<?php
include 'config.php';

// Jika sudah login, langsung lempar ke dashboard masing-masing
if (isset($_SESSION['status']) && $_SESSION['status'] == 'login') {
    if ($_SESSION['role'] == 'admin') { header("Location: admin/dashboard.php"); } 
    else { header("Location: penyewa/index.php"); }
    exit();
}

$pesan = ""; 
if (isset($_GET['pesan'])) {
    if ($_GET['pesan'] == "gagal") { $pesan = "Username atau Password salah!"; }
    if ($_GET['pesan'] == "logout") { $pesan = "Anda telah berhasil logout."; }
    if ($_GET['pesan'] == "hak_akses_ditolak") { $pesan = "Anda tidak memiliki izin akses!"; }
}

if (isset($_POST['login'])) {
    $user_input = $_POST['username'];
    $pass_input = $_POST['password']; 

    // MENGGUNAKAN PREPARED STATEMENT UNTUK KEAMANAN
    $stmt = $koneksi->prepare("SELECT id_user, nama_lengkap, password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $user_input);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        
        // VERIFIKASI PASSWORD 
        // Gunakan password_verify jika di database dipasword menggunakan password_hash()
        // Jika masih teks biasa, gunakan: if ($pass_input == $data['password'])
        if (password_verify($pass_input, $data['password']) || $pass_input == $data['password']) {
            $_SESSION['id_user'] = $data['id_user'];
            $_SESSION['nama']    = $data['nama_lengkap'];
            $_SESSION['role']    = $data['role'];
            $_SESSION['status']  = 'login';

            if ($data['role'] == 'admin') {
                header("Location: admin/dashboard.php");
            } else {
                header("Location: penyewa/index.php");
            }
            exit();
        } else {
            header("Location: login.php?pesan=gagal");
            exit();
        }
    } else {
        header("Location: login.php?pesan=gagal");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIREMO</title>
    <link rel="stylesheet" href="assets/style.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f4f1ea; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-box { background: white; padding: 40px; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); width: 100%; max-width: 400px; text-align: center; }
        .logo-text { color: #d35400; font-weight: bold; margin-bottom: 20px; font-size: 24px; }
        .input-group { margin-bottom: 15px; text-align: left; }
        .input-group label { display: block; margin-bottom: 5px; color: #555; }
        .input-group input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box; }
        .btn-login { background: #d35400; color: white; border: none; padding: 12px; width: 100%; border-radius: 8px; cursor: pointer; font-size: 16px; transition: 0.3s; }
        .btn-login:hover { background: #e67e22; }
        .alert { color: #e74c3c; margin-bottom: 15px; font-size: 14px; }
        .register-link { margin-top: 20px; font-size: 14px; color: #777; }
        .register-link a { color: #d35400; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>

<div class="login-box">
    <div class="logo-text">
        <i class="fas fa-car"></i> SIREMO
    </div>
    <h3>Selamat Datang</h3>
    <p>Silakan masuk ke akun Anda</p>

    <?php if ($pesan != ""): ?>
        <div class="alert"><?php echo $pesan; ?></div>
    <?php endif; ?>

    <form action="" method="POST">
        <div class="input-group">
            <label>Username</label>
            <input type="text" name="username" placeholder="Masukkan username" required>
        </div>
        <div class="input-group">
            <label>Password</label>
            <input type="password" name="password" placeholder="Masukkan password" required>
        </div>
        <button type="submit" name="login" class="btn-login">Masuk</button>
    </form>

    <div class="register-link">
        Belum punya akun? <a href="register.php">Daftar sebagai Penyewa</a>
    </div>
</div>

</body>
</html>