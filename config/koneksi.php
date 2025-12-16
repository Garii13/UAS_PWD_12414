<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'gym_center_yogyakarta';

$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
