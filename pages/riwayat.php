<?php
session_start();
require_once '../config/koneksi.php';

if (!isset($_SESSION['login'])) {
    header("Location: ../auth/login.php");
    exit();
}

$uid = $_SESSION['user_id'];

$query  = "SELECT * FROM membership WHERE user_id = '$uid' ORDER BY tanggal_mulai DESC";
$result = mysqli_query($conn, $query);

$riwayat_list = array();

while ($row = mysqli_fetch_assoc($result)) {
    $tanggal_mulai  = strtotime($row['tanggal_mulai']);
    $tanggal_akhir  = strtotime("+{$row['periode_bulan']} months", $tanggal_mulai);
    $row['tanggal_akhir']  = date('d-m-Y', $tanggal_akhir);
    $row['status_display'] = (time() < $tanggal_akhir) ? 'Aktif' : 'Expired';
    $riwayat_list[]        = $row;
}

$page_title = "Riwayat Membership";
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
        <h1>Riwayat Membership</h1>
    </div>

    <div id="nav">
        <ul>
            <li><a href="../dashboard.php">Home</a></li>
            <li><a href="membership.php">Beli Membership</a></li>
            <li><a href="profil.php">Profil</a></li>
        </ul>
        <div id="logout-section">
            <a href="../auth/logout.php"
               onclick="return confirm('Apakah Anda yakin ingin keluar?');">Logout</a>
        </div>
    </div>

    <div id="content">
        <?php if (isset($_SESSION['membership_success'])): ?>
            <div class="success-box">
                <?php
                    echo $_SESSION['membership_success'];
                    unset($_SESSION['membership_success']);
                ?>
            </div>
        <?php endif; ?>

        <?php if (empty($riwayat_list)): ?>
            <div class="info-box">
                <p>Belum ada riwayat membership.
                    <a href="membership.php">Beli membership sekarang</a></p>
            </div>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Tanggal Mulai</th>
                        <th>Periode</th>
                        <th>Tanggal Akhir</th>
                        <th>Total Bayar</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($riwayat_list as $membership): ?>
                        <tr>
                            <td><?php echo date('d-m-Y', strtotime($membership['tanggal_mulai'])); ?></td>
                            <td><?php echo $membership['periode_bulan']; ?> Bulan</td>
                            <td><?php echo $membership['tanggal_akhir']; ?></td>
                            <td>Rp <?php echo number_format($membership['total_bayar'], 0, ',', '.'); ?></td>
                            <td><?php echo $membership['status_display']; ?></td>
                            <td>
                                <?php if ($membership['status_display'] == 'Aktif'): ?>
                                    <a href="edit_membership.php?id=<?php echo $membership['id']; ?>">Edit</a> |
                                    <a href="hapus_membership.php?id=<?php echo $membership['id']; ?>"
                                       onclick="return confirm('Yakin ingin membatalkan membership ini?')">
                                       Batal</a>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <div id="footer">
        <p>&copy; 2025 Gym Center Yogyakarta. All Rights Reserved.</p>
    </div>
</div>
</body>
</html>
