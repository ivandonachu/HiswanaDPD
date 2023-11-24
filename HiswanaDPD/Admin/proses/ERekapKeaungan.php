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
$no_laporan = htmlspecialchars($_POST['no_laporan']);
$tanggal = htmlspecialchars($_POST['tanggal']);
if (!isset($_POST['iuran_anggota'])) {
    $iuran_anggota = "";
} else {
    $iuran_anggota = $_POST['iuran_anggota'];
}

$akun_keuangan = htmlspecialchars($_POST['akun_keuangan']);
if ($akun_keuangan == 'Iuran DPC' && $iuran_anggota == "") {
    echo "<script>alert('Iuran DPC Harus di Isi'); window.location='../view/VRekapKeuangan?tanggal1=$tanggal_awal&tanggal2=$tanggal_akhir';</script>";
    exit;
}

if ($akun_keuangan == 'Iuran DPC' || $akun_keuangan == 'Pemasukan Lainnya') {
    $status_keuangan = 'Masuk';
} else {
    $status_keuangan = 'Keluar';
}



$jumlah = htmlspecialchars($_POST['jumlah']);
$keterangan = htmlspecialchars($_POST['keterangan']);
$nama_file = $_FILES['file']['name'];

if ($nama_file == "") {
    $file = "";
} else if ($nama_file != "") {

    function upload()
    {
		$nama_file = $_FILES['file']['name'];
		$ukuran_file = $_FILES['file']['size'];
		$error = $_FILES['file']['error'];
		$tmp_name = $_FILES['file']['tmp_name'];

		$ekstensi_valid = ['jpg','jpeg','pdf','doc','docs','xls','xlsx','docx','txt','png'];
		$ekstensi_file = explode(".", $nama_file);
		$ekstensi_file = strtolower(end($ekstensi_file));


		move_uploaded_file($tmp_name, '../file_admin/' . $nama_file   );

		return $nama_file; 
    }

    $file = upload();
    if (!$file) {
        return false;
    }
}


if ($file == '') {
    mysqli_query($koneksi, "UPDATE laporan_keuangan SET tanggal = '$tanggal' , akun_keuangan = '$akun_keuangan' , iuran_anggota = '$iuran_anggota' , jumlah = '$jumlah' , status_keuangan = '$status_keuangan' , keterangan = '$keterangan' WHERE no_laporan =  '$no_laporan'");
} else {
    mysqli_query($koneksi, "UPDATE laporan_keuangan SET tanggal = '$tanggal' , akun_keuangan = '$akun_keuangan' , iuran_anggota = '$iuran_anggota' , jumlah = '$jumlah' , status_keuangan = '$status_keuangan' , keterangan = '$keterangan', file_bukti = '$file' WHERE no_laporan =  '$no_laporan'");
}

echo "<script>alert('Data Keuangan Berhasil di Edit'); window.location='../view/VRekapKeuangan?tanggal1=$tanggal_awal&tanggal2=$tanggal_akhir';</script>";
exit;
