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
$username_asli = $data1['username'];
if ($jabatan_valid == 'Admin') {
} else {
    header("Location: logout.php");
    exit;
}


$username = htmlspecialchars($_POST['username']);



$sql_data = mysqli_query($koneksi, "SELECT * FROM account WHERE username = '$username'");
$data = mysqli_fetch_array($sql_data);
$username_data = $data['username'];

      if($username_asli == $username_data){
        echo "<script>alert('Akun ini tidak bisa dihapus'); window.location='../view/VListAkun';</script>";exit;
      }



		$query = mysqli_query($koneksi,"DELETE FROM account WHERE username = '$username'");


        echo "<script>alert('Akun Berhasil Di Hapus'); window.location='../view/VListAkun';</script>";exit;
	
	