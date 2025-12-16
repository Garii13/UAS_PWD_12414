<?php
session_start();
include 'config/koneksi.php';

if (!isset($_SESSION['login'])) {
    header("Location: auth/login.php");
    exit();
}

$nama_user  = $_SESSION['nama'];
$page_title = "Dashboard";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="id" lang="id">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php echo $page_title; ?> - Gym Center Yogyakarta</title>
    <link rel="stylesheet" type="text/css" href="assets/style.css" />
</head>
<body class="has-sidebar">
<div id="wrapper">
    <div id="header">
        <h1>Dashboard Member - Gym Center Yogyakarta</h1>
    </div>

    <div id="nav">
        <ul>
            <li><a href="dashboard.php">Home</a></li>
            <li><a href="pages/workoutplans.php">Workout Plans</a></li>
            <li><a href="pages/membership_status.php">Status Membership</a></li>
            <li><a href="pages/riwayat.php">Riwayat</a></li>
            <li><a href="pages/profil.php">Profil</a></li>
        </ul>

        <div id="logout-section">
            <a href="auth/logout.php"
               onclick="return confirm('Apakah Anda yakin ingin keluar?');">Logout</a>
        </div>
    </div>

    <div id="content">
        <div class="welcome-section">
            <h2>Halo, <?php echo htmlspecialchars($nama_user); ?>!</h2>
            <div class="info-box">
                <p>Selamat datang di Gym Center Yogyakarta. Lacak progress Anda, ikuti workout plans, dan tingkatkan kebugaran bersama kami!</p>
            </div>
        </div>

        <div class="features-showcase">
            <h3>Fitur Utama Gym Kami</h3>
            <div class="features-grid">
                <div class="feature-card">
                    <img src="assets/img/workout.jpg" alt="Workout Plans" class="feature-img" />
                    <h4>Workout Plans</h4>
                    <p>Rencana latihan pribadi yang disesuaikan dengan tujuan Anda, dari pemula hingga ahli.</p>
                </div>

                <div class="feature-card">
                    <img src="assets/img/progress.jpg" alt="Progress Tracking" class="feature-img" />
                    <h4>Progress Tracking</h4>
                    <p>Pantau perkembangan Anda dengan grafik dan laporan harian untuk motivasi maksimal.</p>
                </div>

                <div class="feature-card">
                    <img src="assets/img/classes.jpg" alt="Kelas Gratis" class="feature-img" />
                    <h4>Kelas Gratis</h4>
                    <p>Bergabunglah dalam kelas yoga, pilates, dan HIIT yang dipandu instruktur profesional.</p>
                </div>

                <div class="feature-card">
                    <img src="assets/img/equipment.jpg" alt="Peralatan Modern" class="feature-img" />
                    <h4>Peralatan Modern</h4>
                    <p>Akses ke peralatan gym terkini untuk latihan yang efektif dan aman.</p>
                </div>
            </div>
        </div>

        <div class="progress-section">
            <h3>Progress Minggu Ini</h3>
            <p>Workout Selesai: 5/7 hari</p>
            <div class="progress-bar">
                <div class="progress-fill" style="width: 70%;"></div>
            </div>
            <p>Tingkatkan lagi! Anda sudah 70% menuju target mingguan.</p>
        </div>
    </div>

    <div id="footer">
        <p>&copy; 2025 Gym Center Yogyakarta. All Rights Reserved.</p>
    </div>
</div>
</body>
</html>
