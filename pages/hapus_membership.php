<?php
session_start();
require_once '../config/koneksi.php';

if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header("Location: ../auth/login.php");
    exit();
}

if (!isset($_GET["id"])) {
    header("Location: riwayat.php");
    exit();
}

$uid           = $_SESSION['user_id'];
$id_membership = mysqli_real_escape_string($conn, $_GET["id"]);

$sql = "DELETE FROM membership WHERE id='$id_membership' AND user_id='$uid'";
mysqli_query($conn, $sql);

header("Location: riwayat.php");
exit();
