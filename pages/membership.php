<?php
session_start();
require_once '../config/koneksi.php';
require_once '../config/functions_membership.php';

if (!isset($_SESSION['login'])) {
    header("Location: ../auth/login.php");
    exit();
}

$uid             = $_SESSION['user_id'];
$error_message   = "";
$success_message = "";

if (isset($_POST['simpan'])) {
    $periode = mysqli_real_escape_string($conn, $_POST['periode']);

    if (empty($periode)) {
        $error_message = "Silakan pilih periode membership!";
    } else {
        $validasi_periode = validasiPeriodeMembership($periode);

        if (!$validasi_periode['valid']) {
            $error_message = $validasi_periode['pesan'];
        } else {
            $cek_aktif = cekMembershipAktif($conn, $uid);

            if ($cek_aktif['aktif']) {
                $error_message = $cek_aktif['pesan'];
            } else {
                $cek_kapasitas = cekKapasitasGym($conn);

                if (!$cek_kapasitas['tersedia']) {
                    $error_message = $cek_kapasitas['pesan'];
                } else {
                    $harga = hitungTotalMembership($periode);

                    if (isset($harga['error'])) {
                        $error_message = $harga['pesan'];
                    } else {
                        $tanggal_mulai = date('Y-m-d');

                        $sql = "INSERT INTO membership (user_id, tanggal_mulai, periode_bulan, total_bayar, status) 
                                VALUES ('$uid', '$tanggal_mulai', '$periode', '{$harga['total']}', 'Aktif')";

                        if (mysqli_query($conn, $sql)) {
                            $_SESSION['membership_success'] =
                                "Membership {$harga['nama_periode']} berhasil dibeli! Berlaku hingga " .
                                date('d-m-Y', strtotime("+$periode months", strtotime($tanggal_mulai)));
                            header("Location: riwayat.php");
                            exit();
                        } else {
                            $error_message = "Gagal membeli membership. Silakan coba lagi.";
                        }
                    }
                }
            }
        }
    }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Form Membership - Gym Center Yogyakarta</title>
    <link rel="stylesheet" type="text/css" href="../assets/style.css" />
</head>
<body class="has-sidebar">
<div id="wrapper">
    <div id="header"><h1>Form Membership</h1></div>

    <div id="nav">
        <ul>
            <li><a href="../dashboard.php">Home</a></li>
            <li><a href="riwayat.php">Riwayat</a></li>
        </ul>
        <div id="logout-section">
            <a href="../auth/logout.php" onclick="return confirm('Yakin keluar?');">Logout</a>
        </div>
    </div>

    <div id="content">
        <?php if ($error_message != ""): ?>
            <div class="error-box"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <?php if (isset($_SESSION['membership_success'])): ?>
            <div class="success-box">
                <?php
                    echo $_SESSION['membership_success'];
                    unset($_SESSION['membership_success']);
                ?>
            </div>
        <?php endif; ?>

        <div class="info-box">
            <span id="info_harga">Silakan pilih periode membership untuk melihat harga.</span>
        </div>

        <form action="" method="post">
            <div>
                <label>Periode Membership:</label>
                <select name="periode" id="periode" onchange="hitungTotal();" required="required">
                    <option value="">Pilih Periode</option>
                    <option value="1"  data-harga="<?php echo HARGA_BULANAN;   ?>" data-nama="1 Bulan">
                        1 Bulan - Rp <?php echo number_format(HARGA_BULANAN, 0, ',', '.'); ?>
                    </option>
                    <option value="3"  data-harga="<?php echo HARGA_3_BULAN;   ?>" data-nama="3 Bulan">
                        3 Bulan - Rp <?php echo number_format(HARGA_3_BULAN, 0, ',', '.'); ?>
                    </option>
                    <option value="6"  data-harga="<?php echo HARGA_6_BULAN;   ?>" data-nama="6 Bulan">
                        6 Bulan - Rp <?php echo number_format(HARGA_6_BULAN, 0, ',', '.'); ?>
                    </option>
                    <option value="12" data-harga="<?php echo HARGA_TAHUNAN;   ?>" data-nama="1 Tahun">
                        1 Tahun - Rp <?php echo number_format(HARGA_TAHUNAN, 0, ',', '.'); ?>
                    </option>
                </select>
            </div>

            <div>
                <input type="submit" name="simpan" id="btn_submit" value="Beli Membership" />
            </div>
        </form>
    </div>

    <div id="footer"><p>&copy; 2025 Gym Center Yogyakarta. All Rights Reserved.</p></div>
</div>

<script type="text/javascript">
function hitungTotal() {
    var periodeSelect = document.getElementById('periode');
    var info          = document.getElementById('info_harga');

    if (periodeSelect.value === '') {
        info.innerHTML = 'Silakan pilih periode membership terlebih dahulu.';
        return;
    }

    var harga = parseInt(
        periodeSelect.options[periodeSelect.selectedIndex].getAttribute('data-harga')
    );
    var nama  = periodeSelect.options[periodeSelect.selectedIndex].getAttribute('data-nama');

    info.innerHTML =
        '<strong>Membership ' + nama + '</strong><br/>' +
        'Harga: Rp ' + formatRupiah(harga) + '<br/>' +
        '<strong>Total: Rp ' + formatRupiah(harga) + '</strong>';
}

function formatRupiah(angka) {
    return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

hitungTotal();
</script>
</body>
</html>
