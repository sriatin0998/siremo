<<<<<<< HEAD
<?php 
include '../config.php';
?>

<?php include '../inc_penyewa/header.php'; ?>

<link rel="stylesheet" href="../css_penyewa/style_mobil.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<main class="car-menu-container">
    <div class="menu-header">
        <span class="subtitle">Katalog Armada Lepas Kunci</span>
        <h2 class="title">Cari Mobil yang Pas Untukmu</h2>
        <div class="title-line"></div>
    </div>

    <section class="search-section">
    <div class="search-container">
        <div class="search-box">
            <input type="text" id="searchInput" placeholder="Cari mobil" onkeyup="liveSearch()">
            <button type="button" class="btn-search"><i class="fa fa-search"></i> Cari</button>
        </div>
    </div>
</section>

    <div class="filter-container">
        <button class="filter-btn active" onclick="filterSelection('all')">Semua Unit</button>
        <button class="filter-btn" onclick="filterSelection('keluarga')">Mobil Keluarga</button>
        <button class="filter-btn" onclick="filterSelection('city')">City Car</button>
        <button class="filter-btn" onclick="filterSelection('bus')">Bus & Minibus</button>
    </div>

    <div class="car-grid">
        <?php
        $query_sql = "SELECT * FROM mobil WHERE 1=1";
        
        if (isset($_GET['keyword']) && $_GET['keyword'] != '') {
            $keyword = mysqli_real_escape_string($koneksi, $_GET['keyword']);
            $query_sql .= " AND (merek LIKE '%$keyword%' OR model LIKE '%$keyword%')";
        }

        $result = mysqli_query($koneksi, $query_sql);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) : 
            $is_tersedia = ($row['status_ketersediaan'] == 'Tersedia');
            ?>
            
                <div class="car-card filterDiv <?php echo strtolower($row['kategori']); ?>">
                    <div class="badge"><?php echo strtoupper($row['kategori']); ?></div>
                    <div class="car-image">
                        <img src="../uploads/<?php echo $row['foto']; ?>" alt="<?php echo $row['merek']; ?>">
                    </div>
                    <div class="car-info">
                        <h3><?php echo htmlspecialchars($row['merek'] . " " . $row['model']); ?></h3>
                        <p class="price">Rp <?php echo number_format($row['tarif_sewa_per_hari'], 0, ',', '.'); ?> <span>/ hari</span></p>
                        <div class="card-features">
                            <span><i class="fa fa-calendar"></i> <?php echo $row['tahun']; ?></span>
                            <span><i class="fa fa-palette"></i> <?php echo $row['warna']; ?></span>
                        </div>
                        
                        <div class="button-group">
                            <a href="detail_mobil.php?id=<?php echo $row['id_mobil']; ?>" class="btn-detail">
                                <i class="fa fa-info-circle"></i> Detail
                            </a>
                            <a href="penyewaan.php?id=<?php echo $row['id_mobil']; ?>" class="btn-sewa">
                                Sewa Unit
                            </a>
                        </div>
                    </div>
                </div>
            <?php endwhile; 
        } else {
            echo "<div style='grid-column: 1/-1; text-align: center; padding: 50px;'>
                    <i class='fa fa-car-side' style='font-size: 50px; color: #ccc;'></i>
                    <p style='margin-top:10px;'>Mobil tidak ditemukan.</p>
                  </div>";
        }
        ?>
    </div>
</main>

<script>
  function liveSearch() {
    // 1. Ambil inputan user dan ubah ke huruf kecil
    let input = document.getElementById('searchInput').value.toLowerCase();
    
    // 2. Ambil semua kartu mobil
    let cards = document.getElementsByClassName('car-card');

    for (let i = 0; i < cards.length; i++) {
        // Ambil teks nama mobil (merek + model) di dalam tag h3
        let title = cards[i].querySelector('h3').innerText.toLowerCase();

        // 3. Jika huruf yang diketik ada di dalam judul mobil
        if (title.includes(input)) {
            cards[i].style.display = ""; // Tampilkan
        } else {
            cards[i].style.display = "none"; // Sembunyikan
        }
    }
}
// Fungsi JavaScript untuk Filter Kategori
function filterSelection(c) {
  var x, i;
  x = document.getElementsByClassName("filterDiv");
  if (c == "all") c = "";
  for (i = 0; i < x.length; i++) {
    w3RemoveClass(x[i], "show");
    if (x[i].className.indexOf(c) > -1) w3AddClass(x[i], "show");
  }
  
  // Update tombol active
  var btns = document.getElementsByClassName("filter-btn");
  for (var i = 0; i < btns.length; i++) {
    btns[i].classList.remove("active");
  }
  event.currentTarget.classList.add("active");
}

function w3AddClass(element, name) {
  var i, arr1, arr2;
  arr1 = element.className.split(" ");
  arr2 = name.split(" ");
  for (i = 0; i < arr2.length; i++) {
    if (arr1.indexOf(arr2[i]) == -1) {element.className += " " + arr2[i];}
  }
}

function w3RemoveClass(element, name) {
  var i, arr1, arr2;
  arr1 = element.className.split(" ");
  arr2 = name.split(" ");
  for (i = 0; i < arr2.length; i++) {
    while (arr1.indexOf(arr2[i]) > -1) {
      arr1.splice(arr1.indexOf(arr2[i]), 1);     
    }
  }
  element.className = arr1.join(" ");
}

// Jalankan filter 'all' saat pertama kali dimuat
filterSelection("all");
</script>

<?php include '../inc_penyewa/footer.php'; ?>
=======
<?php
session_start();
include 'config.php';

// Data Mobil sesuai gambar
$all_cars = [
    ['name' => 'TOYOTA AGYA', 'image' => 'assets/agya.png'],
    ['name' => 'TOYOTA CALYA', 'image' => 'assets/calya.png'],
    ['name' => 'DAIHATSU XENIA', 'image' => 'assets/xenia.png'],
    ['name' => 'HONDA BRIO', 'image' => 'assets/brio.png'],
    ['name' => 'TOYOTA AVANZA', 'image' => 'assets/avanza.png'],
    ['name' => 'TOYOTA FORTUNER', 'image' => 'assets/fortuner.png'],
    ['name' => 'TOYOTA HIACE COMMUTER', 'image' => 'assets/hiace.png'],
    ['name' => 'HYUNDAI H-1', 'image' => 'assets/h1.png'],
    ['name' => 'DAIHATSU GRANMAX BLIN VAN', 'image' => 'assets/granmax.png'],
];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Mobil - SIREMO</title>
    <link rel="stylesheet" href="../assets/style_mobil.css">
</head>
<body>

    <main class="mobil-container">
        <div class="car-grid">
            <?php foreach ($all_cars as $car): ?>
            <div class="car-card">
                <div class="car-title">
                    <h4><?php echo $car['name']; ?></h4>
                </div>
                <div class="car-image">
                    <img src="<?php echo $car['image']; ?>" alt="<?php echo $car['name']; ?>">
                </div>
                <div class="car-action">
                    <a href="#" class="btn-detail">Detail</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </main>

</body>
</html>
>>>>>>> b8d9290bc2e45757458286a8f3a7331f3067501e
