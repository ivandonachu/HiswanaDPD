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

$nama = htmlspecialchars($_POST['nama']);
$username = htmlspecialchars($_POST['username']);
$password1 = htmlspecialchars($_POST['password1']);
$password2 = htmlspecialchars($_POST['password2']);
$jabatan = htmlspecialchars($_POST['jabatan']);


    
    $sql_username = mysqli_query($koneksi, "SELECT * FROM account  WHERE username = '$username'");
    $jml_char_pw = strlen($password1);
    $jml_char_username = strlen($username);
    if (count(explode(' ', $username)) > 1){
        echo "<script>alert('username tidak boleh ada spasi'); window.location='../view/VListAkun';</script>";exit;
    }
    else if ($jml_char_username<8){
        echo "<script>alert('username harus lebih dari 8 huruf'); window.location='../view/VListAkun';</script>";exit;
    }
    else if(mysqli_num_rows($sql_username) === 1 ){

        echo "<script>
            alert('username sudah di terdaftar !'); window.location='../view/VListAkun'; </script>";
                return false;
    }
    else if (count(explode(' ', $password1)) > 1){
        echo "<script>alert('password tidak boleh ada spasi'); window.location='../view/VListAkun';</script>";exit;
    }
    else if($jml_char_pw < 8){
        echo "<script>alert('Password harus lebih dari 8 huruf'); window.location='../view/VListAkun';</script>";exit;
    }
    else if ($jml_char_pw >15){
        echo "<script>alert('Password harus kurang dari 15 huruf'); window.location='../view/VListAkun';</script>";exit;
    }
    else if ($password1 !== $password2){
        echo "<script>alert('Password baru tidak cocok'); window.location='../view/VListAkun';</script>";exit;
    }

      $password = password_hash($password1, PASSWORD_DEFAULT);
      $query = mysqli_query ($koneksi,"INSERT INTO account VALUES('$nama','$username','$password','$jabatan','default.jpg')");
      
      if ($query!= "") {
      echo "<script>
              alert('ANDA TELAH MEMBUAT AKUN BARU !'); window.location='../view/VListAkun';</script>";
      }

  ?>