<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../auth/login.php");
    exit();
}

$page_title = "Workout Plans";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php echo $page_title; ?> - Gym Center Yogyakarta</title>
    <link rel="stylesheet" type="text/css" href="../assets/style.css" />
</head>
<body class="has-sidebar">
<div id="wrapper">
    <div id="header">
        <h1>Workout Plans - Gym Center Yogyakarta</h1>
    </div>

    <div id="nav">
        <ul>
            <li><a href="../dashboard.php">Home</a></li>
            <li><a href="membership_status.php">Status Membership</a></li>
            <li><a href="riwayat.php">Riwayat</a></li>
            <li><a href="profil.php">Profil</a></li>
        </ul>
        <div id="logout-section">
            <a href="../auth/logout.php"
               onclick="return confirm('Apakah Anda yakin ingin keluar?');">Logout</a>
        </div>
    </div>

    <div id="content">
        <h2>Rencana Latihan Anda</h2>
        <div class="info-box">
            <p>Ikuti workout plans ini untuk mencapai tujuan kebugaran Anda. Pastikan membership aktif untuk akses penuh.</p>
        </div>

        <div class="features-showcase">
            <h3>Workout Plans Tersedia</h3>
            <div class="features-grid">
                <div class="feature-card">
                    <img src="../assets/img/beginner.jpg" alt="Beginner Plan" class="feature-img" />
                    <h4>Pemula</h4>
                    <p>Rencana latihan ringan untuk pemula. Fokus pada dasar kekuatan dan kardio.</p>
                    <p><strong>Durasi:</strong> 4 minggu</p>
                </div>

                <div class="feature-card">
                    <img src="../assets/img/intermediate.jpg" alt="Intermediate Plan" class="feature-img" />
                    <h4>Menengah</h4>
                    <p>Latihan intens untuk meningkatkan kekuatan dan daya tahan.</p>
                    <p><strong>Durasi:</strong> 8 minggu</p>
                </div>

                <div class="feature-card">
                    <img src="../assets/img/advanced.jpg" alt="Advanced Plan" class="feature-img" />
                    <h4>Lanjutan</h4>
                    <p>Program latihan tinggi untuk atlet dan profesional.</p>
                    <p><strong>Durasi:</strong> 12 minggu</p>
                </div>

                <div class="feature-card">
                    <img src="../assets/img/weightloss.jpg" alt="Weight Loss Plan" class="feature-img" />
                    <h4>Penurunan Berat Badan</h4>
                    <p>Kombinasi kardio dan latihan kekuatan untuk membakar lemak.</p>
                    <p><strong>Durasi:</strong> 6 minggu</p>
                </div>
            </div>
        </div>

        <div class="info-box">
            <p>Jika Anda belum memiliki membership aktif,
                <a href="membership.php">beli membership</a> terlebih dahulu untuk mengakses workout plans lengkap.</p>
        </div>
    </div>

    <div id="footer">
        <p>&copy; 2025 Gym Center Yogyakarta. All Rights Reserved.</p>
    </div>
</div>
</body>
</html>
