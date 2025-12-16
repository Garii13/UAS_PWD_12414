<?php
define('HARGA_BULANAN',   150000);
define('HARGA_3_BULAN',   400000);
define('HARGA_6_BULAN',   750000);
define('HARGA_TAHUNAN',  1400000);
define('MAX_MEMBER_PER_GYM', 500);

function validasiPeriodeMembership($periode) {
    $periode_valid = ['1', '3', '6', '12'];
    if (!in_array($periode, $periode_valid)) {
        return [
            'valid' => false,
            'pesan' => 'Periode membership tidak valid. Pilih 1, 3, 6, atau 12 bulan.'
        ];
    }
    return ['valid' => true, 'pesan' => ''];
}

function cekMembershipAktif($conn, $user_id) {
    $query  = "SELECT id, tanggal_mulai, periode_bulan 
               FROM membership 
               WHERE user_id = '$user_id' AND status = 'Aktif'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $membership    = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $tanggal_mulai = strtotime($membership['tanggal_mulai']);
        $tanggal_akhir = strtotime("+{$membership['periode_bulan']} months", $tanggal_mulai);

        if (time() < $tanggal_akhir) {
            return [
                'aktif' => true,
                'pesan' => 'Anda sudah memiliki membership aktif hingga ' .
                           date('d-m-Y', $tanggal_akhir) . '.'
            ];
        }
    }

    return ['aktif' => false, 'pesan' => 'Belum memiliki membership aktif.'];
}

function cekKapasitasGym($conn) {
    $query  = "SELECT COUNT(*) as total_member FROM membership WHERE status = 'Aktif'";
    $result = mysqli_query($conn, $query);
    $row    = mysqli_fetch_array($result, MYSQLI_ASSOC);

    if ($row['total_member'] >= MAX_MEMBER_PER_GYM) {
        return [
            'tersedia' => false,
            'pesan'    => 'Kapasitas gym penuh. Silakan coba lagi nanti.'
        ];
    }

    return ['tersedia' => true, 'pesan' => ''];
}

function hitungTotalMembership($periode) {
    switch ($periode) {
        case '1':
            $harga = HARGA_BULANAN;
            $nama_periode = '1 Bulan';
            break;
        case '3':
            $harga = HARGA_3_BULAN;
            $nama_periode = '3 Bulan';
            break;
        case '6':
            $harga = HARGA_6_BULAN;
            $nama_periode = '6 Bulan';
            break;
        case '12':
            $harga = HARGA_TAHUNAN;
            $nama_periode = '1 Tahun';
            break;
        default:
            return ['error' => true, 'pesan' => 'Periode tidak valid.'];
    }

    return [
        'nama_periode' => $nama_periode,
        'harga'        => $harga,
        'total'        => $harga
    ];
}
