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
$tanggal_awal = htmlspecialchars($_POST['tanggal1']);
$tanggal_akhir = htmlspecialchars($_POST['tanggal2']);
$no_pemasukan = htmlspecialchars($_POST['no_pemasukan']);

	

		$query = mysqli_query($koneksi,"DELETE FROM pemasukan WHERE no_pemasukan = '$no_pemasukan'");



	
		echo "<script>alert('Data Pemasukan Berhasil di Hapus'); window.location='../view/VPemasukan?tanggal1=$tanggal_awal&tanggal2=$tanggal_akhir';</script>";exit;
	