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
$username = $data1['username'];
if ($jabatan_valid == 'Admin') {
} else {
    header("Location: logout.php");
    exit;
}

//change password
$username = htmlspecialchars($_POST['username']);
$password_baru1 = $username;
    
   
                    $password_baru1 = password_hash($password_baru1, PASSWORD_DEFAULT);
                    mysqli_query($koneksi,"UPDATE account SET password = '$password_baru1' WHERE username =  '$username'");
                    echo "<script>alert('Password Berhasil Di Reset'); window.location='../view/VListAkun';</script>";exit;

           



	
