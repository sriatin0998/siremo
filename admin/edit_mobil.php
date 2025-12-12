<?php
session_start();
include '../config.php'; 

if (!isset($_SESSION['status']) || $_SESSION['status'] != 'login_admin' || $_SESSION['role'] != 'admin') {
    header("location: login.php");
    exit;
}

if (!function_exists('anti_injection')) {
    function anti_injection($data) {
        global $koneksi; 
        $filter = stripslashes(strip_tags(htmlspecialchars($data, ENT_QUOTES)));
        if (isset($koneksi) && is_object($koneksi) && method_exists($koneksi, 'real_escape_string')) {
            $filter = $koneksi->real_escape_string($filter);
        }
        return $filter;
    }
}

$id_mobil = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$mobil_data = null;
$error_message = '';

if ($id_mobil > 0 && isset($koneksi)) {
    $query_select_one = "SELECT * FROM mobil WHERE id_mobil = $id_mobil";
    $result = mysqli_query($koneksi, $query_select_one);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $mobil_data = mysqli_fetch_assoc($result);
    } else {
        $error_message = "Data mobil dengan ID $id_mobil tidak ditemukan.";
    }
} else if ($id_mobil == 0) {
    $error_message = "ID Mobil tidak valid.";
} else {
    $error_message = "Koneksi database gagal. Harap cek config.php.";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_mobil']) && isset($koneksi)) {
    $id_to_update = anti_injection($_POST['id_mobil']);
    $merk = anti_injection($_POST['merk']); 
    $model = anti_injection($_POST['model']);
    $tahun = anti_injection($_POST['tahun']);
    $plat = anti_injection($_POST['plat_nomor']);
    $tarif_raw = anti_injection($_POST['tarif_harian']); 
    $status_ketersediaan = anti_injection($_POST['status_ketersediaan']); 
    $query_update = "UPDATE mobil SET 
                    merek='$merk', 
                    model='$model', 
                    tahun='$tahun', 
                    plat_nomor='$plat', 
                    tarif_sewa_per_hari='$tarif_raw', 
                    status_ketersediaan='$status_ketersediaan' 
                    WHERE id_mobil='$id_to_update'";

    if (mysqli_query($koneksi, $query_update)) {
        header("location: data_mobil.php?status_edit=sukses");
        exit;
    } else {
        $error_message = "Gagal memperbarui data: " . mysqli_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Mobil</title>
<link rel="stylesheet" href="../assets/style4.css"> 
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .edit-container { 
            max-width: 600px; 
            margin: 50px auto; 
            padding: 30px; 
            background-color: white; 
            border-radius: 8px; 
            box-shadow: 0 0 10px rgba(0,0,0,0.1); 
        }
        .edit-container h2 { margin-bottom: 20px; color: #333; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input[type="text"], .form-group input[type="number"], .form-group select { 
            width: 100%; 
            padding: 10px; 
            border: 1px solid #ccc; 
            border-radius: 4px; 
            box-sizing: border-box; 
        }
        .form-actions { margin-top: 20px; display: flex; justify-content: space-between; }
        .btn-submit { background-color: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
        .btn-back { background-color: #6c757d; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; }
        .alert-error { padding: 10px; background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: .25rem; }
    </style>
</head>
<body>
    <div class="edit-container">
        <h2><i class="fas fa-edit"></i> Edit Data Mobil: <?php echo htmlspecialchars($mobil_data['merek'] ?? 'Mobil') . ' ' . htmlspecialchars($mobil_data['model'] ?? ''); ?></h2>
        
        <?php if (!empty($error_message)): ?>
            <div class="alert-error"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <?php if ($mobil_data): ?>
        <form action="" method="POST">
            <input type="hidden" name="id_mobil" value="<?php echo htmlspecialchars($mobil_data['id_mobil']); ?>">

            <div class="form-group">
                <label for="merk">Merk Mobil</label>
                <input type="text" id="merk" name="merk" value="<?php echo htmlspecialchars($mobil_data['merek']); ?>" required>
            </div>

            <div class="form-group">
                <label for="model">Model Mobil</label>
                <input type="text" id="model" name="model" value="<?php echo htmlspecialchars($mobil_data['model']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="tahun">Tahun Produksi</label>
                <input type="number" id="tahun" name="tahun" value="<?php echo htmlspecialchars($mobil_data['tahun']); ?>" min="2000" max="<?php echo date('Y'); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="plat_nomor">Plat Nomor</label>
                <input type="text" id="plat_nomor" name="plat_nomor" value="<?php echo htmlspecialchars($mobil_data['plat_nomor']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="tarif_harian">Tarif Harian (Contoh: 300000)</label>
                <input type="number" id="tarif_harian" name="tarif_harian" value="<?php echo htmlspecialchars($mobil_data['tarif_sewa_per_hari']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="status_ketersediaan">Status Ketersediaan</label>
                <select id="status_ketersediaan" name="status_ketersediaan" required>
                    <?php 
                        $statuses = ['Tersedia', 'Disewa', 'Perawatan'];
                        foreach ($statuses as $status):
                            $selected = ($status == $mobil_data['status_ketersediaan']) ? 'selected' : '';
                    ?>
                        <option value="<?php echo $status; ?>" <?php echo $selected; ?>>
                            <?php echo $status; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-actions">
                <a href="data_mobil.php" class="btn-back">Batal & Kembali</a>
                <button type="submit" name="edit_mobil" class="btn-submit">Simpan Perubahan</button>
            </div>
        </form>
        <?php else: ?>
            <div class="form-actions">
                <a href="data_mobil.php" class="btn-back">Kembali ke Data Mobil</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>