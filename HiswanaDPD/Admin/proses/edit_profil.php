<?php
session_start();
include'koneksi.php';
if(!isset($_SESSION["login"])){
  header("Location: logout.php");
  exit;
}
$username=$_COOKIE['username'];
$result1 = mysqli_query($koneksi, "SELECT * FROM account WHERE username = '$username'");
$data1 = mysqli_fetch_array($result1);
$jabatan_valid = $data1['jabatan']; 
$nama = $data1['nama'];
$username = $data1['username'];
if ($jabatan_valid == 'Admin') { 
}

else{  header("Location: logout.php");
exit;
}

//change password
$username = htmlspecialchars($_POST['username']);
$password_lama = htmlspecialchars($_POST['password_lama']);
$password_baru1 = htmlspecialchars($_POST['password_baru1']);
$password_baru2 = htmlspecialchars($_POST['password_baru2']);

//change profil
$nama = htmlspecialchars($_POST['nama']);


$nama_file = $_FILES['file_profile']['name'];
if ($nama_file == "") {
	$file = "";
}

else if ( $nama_file != "" ) {

	function upload(){
		$nama_file = $_FILES['file_profile']['name'];
		$ukuran_file = $_FILES['file_profile']['size'];
		$error = $_FILES['file_profile']['error'];
		$tmp_name = $_FILES['file_profile']['tmp_name'];

		$ekstensi_valid = ['jpg','jpeg','png'];
		$ekstensi_file = explode(".", $nama_file);
		$ekstensi_file = strtolower(end($ekstensi_file));


		$nama_file_baru = uniqid();
		$nama_file_baru .= ".";
		$nama_file_baru .= $ekstensi_file;

		move_uploaded_file($tmp_name, '../../../img/foto_profile/' . $nama_file_baru   );

		return $nama_file_baru; 

	}

	$file = upload();
	if (!$file) {
		return false;
	}

}
    
    if($password_lama != "" && $password_baru1 != ""){

        $sql_account = mysqli_query($koneksi, "SELECT * FROM account WHERE username = '$username'");
    
            $data_account = mysqli_fetch_assoc($sql_account);
              if(password_verify($password_lama, $data_account["password"]) ){ 

                    $jml_char = strlen($password_baru1);

                    if($jml_char < 8){
                        echo "<script>alert('Password harus lebih dari 8 huruf'); window.location='../view/VProfile';</script>";exit;
                    }
                    else if ($jml_char >15){
                        echo "<script>alert('Password harus kurang dari 15 huruf'); window.location='../view/VProfile';</script>";exit;
                    }
                    else if (count(explode(' ', $password_baru1)) > 1){
                        echo "<script>alert('password tidak boleh ada spasi'); window.location='../view/VProfile';</script>";exit;
                    }
                    else if ($password_baru1 !== $password_baru2){
                        echo "<script>alert('Password baru tidak cocok'); window.location='../view/VProfile';</script>";exit;
                    }
                    
                    $password_baru1 = password_hash($password_baru1, PASSWORD_DEFAULT);
                    $query1 = mysqli_query($koneksi,"UPDATE account SET password = '$password_baru1' WHERE username =  '$username'");
                    echo "<script>alert('Password Berhasil Di Ganti'); window.location='../view/VProfile';</script>";exit;

              }
              else{
                echo "<script>alert('Password lama salah'); window.location='../view/VProfile';</script>";exit;
              }


    }   
    else{
     

			
        if ($file == '') {
            mysqli_query($koneksi,"UPDATE account SET nama = '$nama'  WHERE username =  '$username'");
        }
        else{
            mysqli_query($koneksi,"UPDATE account SET nama = '$nama', foto_profile = '$file' WHERE username =  '$username'");
        }
        
        echo "<script>alert('Profil Berhasil Di Edit'); window.location='../view/VProfile';</script>";exit;

    }




	
