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

$nama_anggota = htmlspecialchars($_POST['nama_anggota']);
$no_hp = htmlspecialchars($_POST['no_hp']);
$alamat = htmlspecialchars($_POST['alamat']);

    $no_kode = 1;

        $kode = 'DPC';

        $sql_data = mysqli_query($koneksi, "SELECT * FROM anggota ");
        
        if(mysqli_num_rows($sql_data) === 0 ){
       
            $kode_new = $kode.$no_kode;
            $query = mysqli_query($koneksi,"INSERT INTO anggota VALUES('$kode_new','$nama_anggota','$no_hp','$alamat')");
       
                echo "<script>alert('Data Anggota Berhasil di input'); window.location='../view/VListAnggota';</script>";exit;
    
        }
        while($data_karyawan = mysqli_fetch_array($sql_data)){
            $no_kode = $no_kode + 1;

            $kode_new = $kode.$no_kode;
            $sql_cek = mysqli_query($koneksi, "SELECT * FROM anggota WHERE kode_anggota = '$kode_new' ");
       
            if(mysqli_num_rows($sql_cek) === 0 ){
              
                $query = mysqli_query($koneksi,"INSERT INTO anggota VALUES('$kode_new','$nama_anggota','$no_hp','$alamat')");
           
                    echo "<script>alert('Data Anggota Berhasil di input'); window.location='../view/VListAnggota';</script>";exit;
        
            }
           

        }
    
   
        






  ?>