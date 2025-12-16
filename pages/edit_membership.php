<?php
session_start();
require_once '../config/koneksi.php';
require_once '../config/functions_membership.php';

if (!isset($_SESSION['login'])) {
    header("Location: ../auth/login.php");
    exit();
}

$uid           = $_SESSION['user_id'];
$id_membership = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : 0;
$error_message = "";

$query  = "SELECT * FROM membership WHERE id='$id_membership' AND user_id='$uid'";
$result = mysqli_query($conn, $query);
$data   = mysqli_fetch_array($result, MYSQLI_ASSOC);

if (!$data) {
    header("Location: riwayat.php");
    exit();
}

if (isset($_POST['update'])) {
    $periode = mysqli_real_escape_string($conn, $_POST['periode']);

    if (empty($periode)) {
        $error_message = "Silakan pilih periode membership!";
    } else {
        $validasi_periode = validasiPeriodeMembership($periode);

        if (!$validasi_periode['valid']) {
            $error_message = $validasi_periode['pesan'];
        } else {
            $tanggal_mulai      = strtotime($data['tanggal_mulai']);
            $tanggal_akhir_lama = strtotime("+{$data['periode_bulan']} months", $tanggal_mulai);

            if (time() > $tanggal_akhir_lama) {
                $error_message = "Membership sudah expired. Tidak bisa diupdate.";
            } else {
                $harga = hitungTotalMembership($periode);

                if (isset($harga['error'])) {
                    $error_message = $harga['pesan'];
                } else {
                    $tanggal_akhir_baru = date('Y-m-d', strtotime("+$periode months", $tanggal_mulai));

                    $sql = "UPDATE membership SET 
                                periode_bulan='$periode',
                                total_bayar='{$harga['total']}'
                            WHERE id='$id_membership' AND user_id='$uid'";

                    if (mysqli_query($conn, $sql)) {
                        $_SESSION['membership_success'] =
                            "Membership berhasil diupdate! Berlaku hingga $tanggal_akhir_baru";
                        header("Location: riwayat.php");
                        exit();
                    } else {
                        $error_message = "Gagal menyimpan perubahan.";
                    }
                }
            }
        }
    }
}

$periodes = [
    '1'  => '1 Bulan',
    '3'  => '3 Bulan',
    '6'  => '6 Bulan',
    '12' => '1 Tahun'
];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Edit Membership - Gym Center Yogyakarta</title>
    <link rel="stylesheet" type="text/css" href="../assets/style.css" />
</head>
<body class="has-sidebar">
<div id="wrapper">
    <div id="header"><h1>Edit Membership</h1></div>

    <div id="nav">
        <ul>
            <li><a href="../dashboard.php">Home</a></li>
            <li><a href="riwayat.php">Kembali</a></li>
        </ul>
        <div id="logout-section">
            <a href="../auth/logout.php" onclick="return confirm('Yakin keluar?');">Logout</a>
        </div>
    </div>

    <div id="content">
        <?php if ($error_message != ""): ?>
            <div class="error-box"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <div class="info-box">
            <span id="info_harga">Ubah periode membership. Harga akan dihitung ulang.</span>
        </div>

        <form action="" method="post">
            <div>
                <label>Periode Membership:</label>
                <select name="periode" id="periode" onchange="hitungTotal();" required="required">
                    <?php foreach ($periodes as $key => $nama): 
                        $selected  = ($key == $data['periode_bulan']) ? 'selected="selected"' : '';
                        $constName = 'HARGA_' . strtoupper(str_replace(' ', '_', $nama));
                        $harga_val = defined($constName) ? constant($constName) : 0;
                    ?>
                        <option value="<?php echo $key; ?>"
                                data-harga="<?php echo $harga_val; ?>"
                                data-nama="<?php echo $nama; ?>"
                                <?php echo $selected; ?>>
                            <?php echo $nama; ?> - Rp <?php echo number_format($harga_val, 0, ',', '.'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <input type="submit" name="update" id="btn_submit" value="Simpan Perubahan" />
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
