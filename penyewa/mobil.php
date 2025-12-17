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