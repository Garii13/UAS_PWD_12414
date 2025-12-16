<?php
session_start();
require_once '../config/koneksi.php';
require_once '../config/functions_membership.php';

if (!isset($_SESSION['login'])) {
    header("Location: ../auth/login.php");
    exit();
}

$uid        = $_SESSION['user_id'];
$nama_user  = $_SESSION['nama'];
$email_user = $_SESSION['email'];

$query_user = mysqli_query($conn, "SELECT * FROM users WHERE id = '$uid'");
$user       = mysqli_fetch_assoc($query_user);

$cek_membership   = cekMembershipAktif($conn, $uid);
$membership_aktif = $cek_membership['aktif'];
$pesan_membership = $cek_membership['pesan'];

$membership_detail = null;
if ($membership_aktif) {
    $query_membership = mysqli_query(
        $conn,
        "SELECT * FROM membership 
         WHERE user_id = '$uid' AND status = 'Aktif' 
         ORDER BY tanggal_mulai DESC LIMIT 1"
    );
    $membership_detail = mysqli_fetch_assoc($query_membership);
}

if (isset($_POST['upload_foto'])) {
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $target_dir = "../assets/uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $target_file   = $target_dir . basename($_FILES["foto"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $check = getimagesize($_FILES["foto"]["tmp_name"]);
        if ($check !== false && in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
            if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
                $foto_path = "assets/uploads/" . basename($_FILES["foto"]["name"]);
                mysqli_query($conn, "UPDATE users SET foto = '$foto_path' WHERE id = '$uid'");
                $_SESSION['foto'] = $foto_path;

                header("Location: membership_status.php");
                exit();
            } else {
                $error_foto = "Gagal upload foto.";
            }
        } else {
            $error_foto = "File bukan gambar atau format tidak didukung.";
        }
    } else {
        $error_foto = "Pilih file foto terlebih dahulu.";
    }
}

$page_title = "Status Membership";
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
        <h1>Status Membership - Gym Center Yogyakarta</h1>
    </div>

    <div id="nav">
        <ul>
            <li><a href="../dashboard.php">Home</a></li>
            <li><a href="membership.php">Beli Membership</a></li>
            <li><a href="riwayat.php">Riwayat</a></li>
            <li><a href="profil.php">Profil</a></li>
        </ul>
        <div id="logout-section">
            <a href="../auth/logout.php"
               onclick="return confirm('Apakah Anda yakin ingin keluar?');">Logout</a>
        </div>
    </div>

    <div id="content">
        <h2>Status Membership Anda</h2>

        <div class="info-box">
            <h3>Identitas Diri</h3>
            <p><strong>Nama:</strong> <?php echo htmlspecialchars($nama_user); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($email_user); ?></p>
            <p><strong>Tempat, Tanggal Lahir:</strong>
                <?php echo htmlspecialchars(($user['tempat_lahir'] ?? '') . ', ' . ($user['tanggal_lahir'] ?? '')); ?>
            </p>
            <p><strong>Jenis Kelamin:</strong>
                <?php echo htmlspecialchars($user['jenis_kelamin'] ?? ''); ?>
            </p>
            <p><strong>Alamat Domisili:</strong>
                <?php echo htmlspecialchars($user['alamat_domisili'] ?? ''); ?>
            </p>
            <p><strong>Berat/Tinggi Badan:</strong>
                <?php echo htmlspecialchars(($user['berat_badan'] ?? '') . ' kg / ' . ($user['tinggi_badan'] ?? '') . ' cm'); ?>
            </p>
            <p><strong>Riwayat Penyakit:</strong>
                <?php echo htmlspecialchars($user['riwayat_penyakit'] ?? '-'); ?>
            </p>

            <h4>Foto Diri</h4>
            <?php if (!empty($user['foto'])): ?>
                <img src="../<?php echo htmlspecialchars($user['foto']); ?>" alt="Foto Profil"
                     style="max-width: 150px; height: auto; border: 1px solid #ccc;" />
            <?php else: ?>
                <p>Belum ada foto. Upload foto Anda.</p>
            <?php endif; ?>

            <form action="" method="post" enctype="multipart/form-data">
                <label>Pilih Foto:</label>
                <input type="file" name="foto" accept="image/*" required />
                <input type="submit" name="upload_foto" value="Upload Foto" />
            </form>
            <?php if (isset($error_foto)): ?>
                <p style="color: red;"><?php echo $error_foto; ?></p>
            <?php endif; ?>
        </div>

        <?php if ($membership_aktif && $membership_detail): ?>
            <div class="success-box">
                <h3>Member Berlaku</h3>
                <p><strong>Tanggal Mulai:</strong>
                    <?php echo date('d-m-Y', strtotime($membership_detail['tanggal_mulai'])); ?></p>
                <p><strong>Periode:</strong> <?php echo $membership_detail['periode_bulan']; ?> Bulan</p>
                <p><strong>Tanggal Akhir:</strong>
                    <?php echo date(
                        'd-m-Y',
                        strtotime(
                            "+{$membership_detail['periode_bulan']} months",
                            strtotime($membership_detail['tanggal_mulai'])
                        )
                    ); ?>
                </p>
                <p><strong>Status:</strong> Aktif</p>
                <p><strong>Total Bayar:</strong>
                    Rp <?php echo number_format($membership_detail['total_bayar'], 0, ',', '.'); ?></p>
            </div>
        <?php else: ?>
            <div class="error-box">
                <p><?php echo $pesan_membership; ?></p>
                <p><a href="membership.php" class="btn btn-primary">Beli Membership Sekarang</a></p>
            </div>
        <?php endif; ?>
    </div>

    <div id="footer">
        <p>&copy; 2025 Gym Center Yogyakarta. All Rights Reserved.</p>
    </div>
</div>
</body>
</html>
