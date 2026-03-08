<?php
session_start();
include '../config.php';

// 1. Pastikan Session ID menggunakan nama yang benar sesuai saat login/register
if (!isset($_SESSION['id_user'])) {
    header("location: ../login.php");
    exit;
}

if (isset($_POST['update_profil'])) {
    $id_user_login = $_SESSION['id_user']; // Ambil ID User dari session
    
    // 2. Ambil data dari FORM (Pastikan variabel no_telepon sudah didefinisikan)
    $no_telepon = anti_injection($_POST['no_telepon']);
    $no_ktp     = anti_injection($_POST['no_ktp']);
    $no_sim     = anti_injection($_POST['no_sim']);
    $alamat     = anti_injection($_POST['alamat']);
    
    // 3. Ambil data lama untuk cek foto
    // Gunakan WHERE id_user karena ini adalah penyambung dari tabel users
    $data_lama = mysqli_query($koneksi, "SELECT foto_sim FROM penyewa WHERE id_user = '$id_user_login'");
    $row = mysqli_fetch_assoc($data_lama);
    $foto_lama = $row['foto_sim'] ?? '';

    // Proses Upload Foto SIM
    $foto_sim = $_FILES['foto_sim']['name'];
    
    if ($foto_sim != "") {
        $ekstensi_boleh = array('png', 'jpg', 'jpeg');
        $x = explode('.', $foto_sim);
        $ekstensi = strtolower(end($x));
        $file_tmp = $_FILES['foto_sim']['tmp_name'];
        
        // Penamaan file unik
        $nama_baru = "SIM-" . $id_user_login . "-" . date('YmdHis') . "." . $ekstensi;

        if (in_array($ekstensi, $ekstensi_boleh) === true) {
            if (move_uploaded_file($file_tmp, '../uploads/' . $nama_baru)) {
                
                // Hapus foto lama jika ada
                if (!empty($foto_lama) && file_exists('../uploads/' . $foto_lama)) {
                    unlink('../uploads/' . $foto_lama);
                }

                $query = "UPDATE penyewa SET
                          no_telepon='$no_telepon',
                          no_ktp='$no_ktp', 
                          no_sim='$no_sim', 
                          alamat='$alamat', 
                          foto_sim='$nama_baru' 
                          WHERE id_user='$id_user_login'";
            }
        } else {
            echo "<script>alert('Ekstensi file harus JPG atau PNG!'); window.location='profil.php';</script>";
            exit;
        }
    } else {
        // Jika tidak ada foto baru yang diupload
        $query = "UPDATE penyewa SET
                  no_telepon='$no_telepon', 
                  no_ktp='$no_ktp', 
                  no_sim='$no_sim', 
                  alamat='$alamat' 
                  WHERE id_user='$id_user_login'";
    }

    // 4. Eksekusi Query
    if (mysqli_query($koneksi, $query)) {
        echo "<script>alert('Profil berhasil diperbarui!'); window.location='profil.php';</script>";
    } else {
        echo "Gagal memperbarui database: " . mysqli_error($koneksi);
    }
}
?>