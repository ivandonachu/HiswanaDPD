<?php
session_start();
include 'koneksi.php';
if (!isset($_SESSION["login"])) {
    header("Location: logout.php");
    exit;
}
$username = $_COOKIE['username'];
$result1 = mysqli_query($koneksi, "SELECT * FROM account WHERE username = '$username'");
$data1 = mysqli_fetch_array($result1);
$jabatan_valid = $data1['jabatan'];
if ($jabatan_valid == 'Admin') {
} else {
    header("Location: logout.php");
    exit;
}


$kode_anggota = htmlspecialchars($_POST['kode_anggota']);

		$query = mysqli_query($koneksi,"DELETE FROM anggota WHERE kode_anggota = '$kode_anggota'");
		echo "<script>alert('Data Anggota Berhasil Di Hapus'); window.location='../view/VListAnggota';</script>";exit;
	