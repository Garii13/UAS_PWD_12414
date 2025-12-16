<?php
session_start();
require_once '../config/koneksi.php';

if (!isset($_SESSION['login'])) {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if (isset($_POST['update'])) {
    $nama             = mysqli_real_escape_string($conn, $_POST['nama']);
    $email            = mysqli_real_escape_string($conn, $_POST['email']);
    $tempat_lahir     = mysqli_real_escape_string($conn, $_POST['tempat_lahir']);
    $tanggal_lahir    = mysqli_real_escape_string($conn, $_POST['tanggal_lahir']);
    $jenis_kelamin    = mysqli_real_escape_string($conn, $_POST['jenis_kelamin']);
    $alamat_domisili  = mysqli_real_escape_string($conn, $_POST['alamat_domisili']);
    $berat_badan      = mysqli_real_escape_string($conn, $_POST['berat_badan']);
    $tinggi_badan     = mysqli_real_escape_string($conn, $_POST['tinggi_badan']);
    $riwayat_penyakit = mysqli_real_escape_string($conn, $_POST['riwayat_penyakit']);

    $sql = "UPDATE users SET 
                nama_lengkap     = '$nama',
                email            = '$email',
                tempat_lahir     = '$tempat_lahir',
                tanggal_lahir    = '$tanggal_lahir',
                jenis_kelamin    = '$jenis_kelamin',
                alamat_domisili  = '$alamat_domisili',
                berat_badan      = '$berat_badan',
                tinggi_badan     = '$tinggi_badan',
                riwayat_penyakit = '$riwayat_penyakit'
            WHERE id = '$user_id'";
    mysqli_query($conn, $sql);

    $_SESSION['nama'] = $nama;

    header("Location: ../dashboard.php");
    exit();
}

if (isset($_POST['hapus'])) {
    $sql_membership = "DELETE FROM membership WHERE user_id = '$user_id'";
    mysqli_query($conn, $sql_membership);

    $sql = "DELETE FROM users WHERE id = '$user_id'";
    mysqli_query($conn, $sql);

    session_destroy();
    header("Location: ../index.html");
    exit();
}

$query = mysqli_query($conn, "SELECT * FROM users WHERE id = '$user_id'");
$user  = mysqli_fetch_assoc($query);

$page_title = "Profil";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Profil - Gym Center Yogyakarta</title>
    <link rel="stylesheet" type="text/css" href="../assets/style.css" />
</head>
<body class="has-sidebar">
<div id="wrapper">
    <div id="header"><h1>Profil Saya</h1></div>

    <div id="nav">
        <ul>
            <li><a href="../dashboard.php">Home</a></li>
            <li><a href="membership_status.php">Status Membership</a></li>
            <li><a href="riwayat.php">Riwayat</a></li>
        </ul>
        <div id="logout-section">
            <a href="../auth/logout.php"
               onclick="return confirm('Apakah Anda yakin ingin keluar?');">Logout</a>
        </div>
    </div>

    <div id="content">
        <form action="" method="post"
              onsubmit="return confirm('Apakah Anda yakin ingin menyimpan perubahan profil?');">

            <div>
                <label>Nama:</label>
                <input type="text" name="nama"
                       value="<?php echo htmlspecialchars($user['nama_lengkap']); ?>" />
            </div>

            <div>
                <label>Email:</label>
                <input type="text" name="email"
                       value="<?php echo htmlspecialchars($user['email']); ?>" />
            </div>

            <div>
                <label>Tempat Lahir:</label>
                <input type="text" name="tempat_lahir"
                       value="<?php echo htmlspecialchars($user['tempat_lahir'] ?? ''); ?>" />
            </div>

            <div>
                <label>Tanggal Lahir:</label>
                <input type="date" name="tanggal_lahir"
                       value="<?php echo htmlspecialchars($user['tanggal_lahir'] ?? ''); ?>" />
            </div>

            <div>
                <label>Jenis Kelamin:</label>
                <select name="jenis_kelamin">
                    <option value="">Pilih</option>
                    <option value="Male"
                        <?php if (($user['jenis_kelamin'] ?? '') === 'Male') echo 'selected="selected"'; ?>>
                        Male
                    </option>
                    <option value="Female"
                        <?php if (($user['jenis_kelamin'] ?? '') === 'Female') echo 'selected="selected"'; ?>>
                        Female
                    </option>
                </select>
            </div>

            <div>
                <label>Alamat Domisili:</label>
                <textarea name="alamat_domisili" rows="3"><?php
                    echo htmlspecialchars($user['alamat_domisili'] ?? '');
                ?></textarea>
            </div>

            <div>
                <label>Berat Badan (kg):</label>
                <input type="number" step="0.1" name="berat_badan"
                       value="<?php echo htmlspecialchars($user['berat_badan'] ?? ''); ?>" />
            </div>

            <div>
                <label>Tinggi Badan (cm):</label>
                <input type="number" step="0.1" name="tinggi_badan"
                       value="<?php echo htmlspecialchars($user['tinggi_badan'] ?? ''); ?>" />
            </div>

            <div>
                <label>Riwayat Penyakit:</label>
                <textarea name="riwayat_penyakit" rows="3"><?php
                    echo htmlspecialchars($user['riwayat_penyakit'] ?? '');
                ?></textarea>
            </div>

            <div>
                <input type="submit" name="update" value="Simpan" />
            </div>
        </form>

        <hr />

        <form action="" method="post"
              onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun? Data tidak dapat dikembalikan.');">
            <div>
                <input type="submit" name="hapus" value="Hapus Akun"
                       style="background-color:red; color:white;" />
            </div>
        </form>
    </div>

    <div id="footer"><p>&copy; 2025 Gym Center Yogyakarta. All Rights Reserved.</p></div>
</div>
</body>
</html>
