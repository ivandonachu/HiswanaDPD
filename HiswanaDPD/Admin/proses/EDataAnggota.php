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
$nama_anggota = htmlspecialchars($_POST['nama_anggota']);
$no_hp = htmlspecialchars($_POST['no_hp']);
$alamat = htmlspecialchars($_POST['alamat']);



           mysqli_query($koneksi,"UPDATE anggota SET nama_anggota = '$nama_anggota' , no_hp = '$no_hp' , alamat = '$alamat' WHERE kode_anggota =  '$kode_anggota'");
           echo "<script>alert('Data Anggota Berhasil di Edit'); window.location='../view/VListAnggota';</script>";exit;
    
      
	
