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
$nama = $data1['nama'];
$foto_profile = $data1['foto_profile'];
$username = $data1['username'];
if ($jabatan_valid == 'Admin') {
} else {
    header("Location: logout.php");
    exit;
}



if (isset($_GET['tanggal1'])) {
    $tanggal_awal = $_GET['tanggal1'];
    $tanggal_akhir = $_GET['tanggal2'];
} elseif (isset($_POST['tanggal1'])) {
    $tanggal_awal = $_POST['tanggal1'];
    $tanggal_akhir = $_POST['tanggal2'];
} else {
    $tanggal_awal = date('Y-m-1');
    $tanggal_akhir = date('Y-m-31');
}

if ($tanggal_awal == $tanggal_akhir) {
    $table = mysqli_query($koneksi, "SELECT * FROM laporan_keuangan WHERE tanggal = '$tanggal_awal'");
} else {

    $table = mysqli_query($koneksi, "SELECT * FROM laporan_keuangan WHERE tanggal BETWEEN '$tanggal_awal' AND '$tanggal_akhir'");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Rekap Keuangan</title>

    <!-- Custom fonts for this template-->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="/css/sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/bootstrap-select/dist/css/bootstrap-select.css">
    <link rel="stylesheet" href="/css/dataTables.bootstrap4.min.css">



</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="">
                <div class="sidebar-brand-icon rotate-n-15">

                </div>
                <div class="sidebar-brand-text mx-3">Logo Hiswana</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="DsAdmin">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span style="font-size: 17px;">Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Nav Item - Menu Keuangan -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fa-solid fa-cash-register"></i>
                    <span>Keuangan</span>
                </a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="VRekapKeuangan">Rekap Keuangan</a>
                        <a class="collapse-item" href="VRekapIuranDPC">Rekap Iuran DPC</a>
                    </div>
                </div>
            </li>

            <!-- Nav Item - Menu Anggota -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities" aria-expanded="true" aria-controls="collapseUtilities">
                    <i class="fa-solid fa-people-group"></i>
                    <span>Anggota</span>
                </a>
                <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="VListAnggota">List Anggota</a>
                    </div>
                </div>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">


            <!-- Nav Item - Menu Pengaturan Akun -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages" aria-expanded="true" aria-controls="collapsePages">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>Pengaturan Akun</span>
                </a>
                <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="VListAkun">List Akun</a>
                    </div>
                </div>
            </li>


            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>


        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - Informasi Akun -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo "$nama"; ?></span><!-- link nama profile -->
                                <img class="img-profile rounded-circle" src="/img/foto_profile/<?= $foto_profile; ?>"><!-- link foto profile -->
                            </a>
                            <!-- Dropdown - Informasi Akun -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="VProfile">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="logout" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">



                    <!-- Tabel List Akun -->



                    <!-- Posisi Halaman -->
                    <small class="m-0 font-weight-thin text-primary"><a href="DsAdmin">Dashboard</a> <i style="color: grey;" class="fa fa-caret-right" aria-hidden="true"></i> <a style="color: grey;">Rekap Keuangan</a> </small>
                    <br>
                    <br>

                    <div class="card shadow mb-4">
                        <!-- Card Header - Dropdown -->
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h5 style="color: grey;">Rekap Keuangan</h5>
                        </div>
                        <!-- Card Body -->
                        <div style="height: 700px;" class="card-body">
                            <div class="chart-area">

                                <!-- Form Tanggal Akses Data -->
                                <?php echo "<form  method='POST' action='VRekapKeuangan' style='margin-bottom: 15px;'>" ?>
                                <div>
                                    <div align="left" style="margin-left: 20px;">
                                        <input type="date" id="tanggal1" style="font-size: 14px" name="tanggal1">
                                        <span>-</span>
                                        <input type="date" id="tanggal2" style="font-size: 14px" name="tanggal2">
                                        <button type="submit" name="submmit" style="font-size: 12px; margin-left: 10px; margin-bottom: 2px;" class="btn1 btn btn-outline-primary btn-sm">Lihat</button>
                                    </div>
                                </div>
                                </form>

                                <!-- Form Input -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <?php echo " <a style='font-size: 12px'> Data yang tampil  $tanggal_awal  sampai  $tanggal_akhir</a>" ?>
                                    </div>
                                    <div class="col-md-6">
                
                                        <!-- Button Input Data keuangan dan print  -->
                                        <div align="right">
                                       
                                       <?php echo "<a href='VCetakKuangan?tanggal1=$tanggal_awal&tanggal2=$tanggal_akhir' target='_blank'><button style='color:white;
                                             '  type='submit' class=' btn btn-secondary' >  <i class='fa-solid fa-print'></i> Cetak Rekap Keuangan</button></a>"; ?>

                                            <button style="font-size: clamp(7px, 3vw, 15px); " type="button" class="btn btn-primary" data-toggle="modal" data-target="#input"> <i class="fas fa-plus-square mr-2"></i>Catat Keuangan</button> <br> <br>
                                        </div>

                                        <!-- Form Modal  -->
                                        <div class="modal fade bd-example-modal-lg" id="input" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title"> Form Keuangan</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>

                                                    <!-- Form Input Data -->
                                                    <div class="modal-body" align="left">
                                                        <?php echo "<form action='../proses/IRekapKeuangan?tanggal1=$tanggal_awal&tanggal2=$tanggal_akhir' enctype='multipart/form-data' method='POST'>";  ?>

                                                        <br>

                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <label>Tanggal</label>
                                                                <input class="form-control " type="date" name="tanggal" required="">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label>Akun Keuangan</label>
                                                                <select name="akun_keuangan" class="form-control" required="">
                                                                    <option>Iuran DPC</option>
                                                                    <option>Pemasukan Lainnya</option>
                                                                    <option>Biaya Acara</option>
                                                                    <option>Biaya Lainnya</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <br>

                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <label>Iuran Anggota</label>
                                                                <select id="tokens" class="selectpicker form-control" name="iuran_anggota" multiple data-live-search="true">
                                                                    <?php
                                                                    include 'koneksi.php';

                                                                    $result = mysqli_query($koneksi, "SELECT nama_anggota FROM anggota");

                                                                    while ($data2 = mysqli_fetch_array($result)) {
                                                                        $nama_anggota = $data2['nama_anggota'];

                                                                        echo "<option> $nama_anggota </option> ";
                                                                    }
                                                                    ?>
                                                                </select>
                                                                <small>Di pilih jika akun keuangan Iuran DPC</small>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label>Jumlah</label>
                                                                <input class="form-control form-control-sm" type="text" name="jumlah" required="">
                                                            </div>
                                                        </div>

                                                        <br>

                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <label>Keterangan</label>
                                                                <textarea class="form-control form-control-sm" name="keterangan"></textarea>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label>Upload File</label>
                                                                <input type="file" name="file">
                                                            </div>
                                                        </div>

                                                        <br>

                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-primary"> BUAT</button>
                                                            <button type="reset" class="btn btn-danger"> RESET</button>
                                                        </div>
                                                        </form>


                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tabel -->
                                <div style="overflow-x: auto" ;>
                                    <table align="center" id="example" class="table-sm table-striped table-bordered  nowrap" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th style="font-size: clamp(12px, 1vw, 12px); color: black;">No</th>
                                                <th style="font-size: clamp(12px, 1vw, 12px); color: black;">Tanggal</th>
                                                <th style="font-size: clamp(12px, 1vw, 12px); color: black;">Akun Keuangan</th>
                                                <th style="font-size: clamp(12px, 1vw, 12px); color: black;">Iuran Anggota</th>
                                                <th style="font-size: clamp(12px, 1vw, 12px); color: black;">Debit</th>
                                                <th style="font-size: clamp(12px, 1vw, 12px); color: black;">Kredit</th>
                                                <th style="font-size: clamp(12px, 1vw, 12px); color: black;">Total</th>
                                                <th style="font-size: clamp(12px, 1vw, 12px); color: black;">Keterangan</th>
                                                <th style="font-size: clamp(12px, 1vw, 12px); color: black;">File</th>
                                                <th style="font-size: clamp(12px, 1vw, 12px); color: black;">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php
                                            $total_kredit = 0;
                                            $no_urut = 0;
                                            $total_debit = 0;
                                            function formatuang($angka)
                                            {
                                                $uang = "Rp " . number_format($angka, 2, ',', '.');
                                                return $uang;
                                            }

                                            while ($data = mysqli_fetch_array($table)) {
                                                $no_laporan = $data['no_laporan'];
                                                $tanggal = $data['tanggal'];
                                                $akun_keuangan = $data['akun_keuangan'];
                                                $iuran_Anggota = $data['iuran_anggota'];
                                                $status_keuangan = $data['status_keuangan'];
                                                $jumlah = $data['jumlah'];

                                                if ($status_keuangan == 'Masuk') {
                                                    $total_debit = $total_debit + $jumlah;
                                                } elseif ($status_keuangan == 'Keluar') {
                                                    $total_kredit = $total_kredit + $jumlah;
                                                }
                                                $keterangan = $data['keterangan'];
                                                $file_bukti = $data['file_bukti'];
                                                $no_urut++;

                                                echo "<tr>
                                                <td style='font-size: clamp(12px, 1vw, 12px); color: black;' >$no_urut</td>
                                                <td style='font-size: clamp(12px, 1vw, 12px); color: black;' >$tanggal</td>
                                                <td style='font-size: clamp(12px, 1vw, 12px); color: black;' >$akun_keuangan</td>
                                                <td style='font-size: clamp(12px, 1vw, 12px); color: black;' >$iuran_Anggota</td>";

                                                if ($status_keuangan == 'Masuk') {
                                                    echo "
                                                        <td style='font-size: 14px'>" ?> <?= formatuang($jumlah); ?> <?php echo "</td>";
                                                                                                                } else {
                                                                                                                    echo "
                                                        <td style='font-size: 14px'>" ?> <?php echo "</td>";
                                                                                                                }

                                                                                                                if ($status_keuangan == 'Keluar') {
                                                                                                                    echo "
                                                        <td style='font-size: 14px'>" ?> <?= formatuang($jumlah); ?> <?php echo "</td>";
                                                                                                                } else {
                                                                                                                    echo "
                                                        <td style='font-size: 14px'>" ?> <?php echo "</td>";
                                                                                                                }
                                                                                        ?>
                                                <?php echo "
                                                
                                                <td style='font-size: clamp(12px, 1vw, 15px); color: black;' >"; ?> <?= formatuang($total_debit - $total_kredit); ?> <?php echo "</td>
                                                <td style='font-size: clamp(12px, 1vw, 12px); color: black;' >$keterangan</td>
                                                <td style='font-size: clamp(12px, 1vw, 15px);'>"; ?> <a download="" href="/HiswanaDPD/Admin/file_admin/<?= $file_bukti ?>"> <?php echo "$file_bukti </a> </td>
                                                "; ?>
                                                    <?php echo "<td style='font-size: clamp(12px, 1vw, 15px);'>"; ?>

                                                    <button style=" font-size: clamp(7px, 1vw, 10px); color:black; " href="#" type="submit" class=" btn bg-warning mr-2 rounded" data-toggle="modal" data-target="#formedit<?php echo $data['no_laporan']; ?>" data-toggle='tooltip' title='Edit Keuangan'>
                                                        <i class="fa-regular fa-pen-to-square"></i></button>
                                                    <!-- Form EDIT DATA -->

                                                    <div class="modal fade" id="formedit<?php echo $data['no_laporan']; ?>" role="dialog" arialabelledby="modalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-lg" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title"> Edit Keuangan </h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="close">
                                                                        <span aria-hidden="true"> &times; </span>
                                                                    </button>
                                                                </div>

                                                                <!-- Form Edit Data -->
                                                                <div class="modal-body">
                                                                    <form action="../proses/ERekapKeaungan" enctype="multipart/form-data" method="POST">

                                                                        <input type="hidden" name="no_laporan" value="<?= $no_laporan; ?>">
                                                                        <input type="hidden" name="tanggal1" value="<?php echo $tanggal_awal; ?>">
                                                                        <input type="hidden" name="tanggal2" value="<?php echo $tanggal_akhir; ?>">
                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                                <label>Tanggal</label>
                                                                                <input class="form-control " type="date" name="tanggal" value="<?= $tanggal; ?>" required="">
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <label>Akun Keuangan</label>
                                                                                <select name="akun_keuangan" class="form-control">
                                                                                    <?php $dataSelect = $data['akun_keuangan']; ?>
                                                                                    <option <?php echo ($dataSelect == 'Iuran DPC') ? "selected" : "" ?>>Iuran DPC</option>
                                                                                    <option <?php echo ($dataSelect == 'Pemasukan Lainnya') ? "selected" : "" ?>>Pemasukan Lainnya</option>
                                                                                    <option <?php echo ($dataSelect == 'Biaya Acara') ? "selected" : "" ?>>Biaya Acara</option>
                                                                                    <option <?php echo ($dataSelect == 'Biaya Lainnya') ? "selected" : "" ?>>Biaya Lainnya</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>

                                                                        <br>

                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                                <div>
                                                                                    <label>Iuran Anggota</label>
                                                                                </div>
                                                                                <select id="tokens" class="selectpicker form-control" name="iuran_anggota" multiple data-live-search="true">


                                                                                    <?php
                                                                                    $dataSelect = $data['iuran_anggota']; ?>
                                                                                    <option <?php echo ($dataSelect == '') ? "selected" : "" ?>></option> <?php
                                                                                                                                                            $result = mysqli_query($koneksi, "SELECT nama_anggota FROM anggota ");
                                                                                                                                                            while ($data2 = mysqli_fetch_array($result)) {
                                                                                                                                                                $nama_anggota = $data2['nama_anggota'];


                                                                                                                                                                echo "<option" ?> <?php echo ($dataSelect == $nama_anggota) ? "selected" : "" ?>> <?php echo $nama_anggota; ?> <?php echo "</option>";
                                                                                                                                                                                                        }

                                                                                                                                                                                                            ?>
                                                                                </select>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <label>Jumlah</label>
                                                                                <input class="form-control form-control-sm" type="text" name="jumlah" value="<?= $jumlah; ?>" required="">
                                                                            </div>
                                                                        </div>

                                                                        <br>

                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                                <label>Keterangan</label>
                                                                                <textarea class="form-control form-control-sm" name="keterangan"><?= $keterangan; ?></textarea>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <label>Upload File</label>
                                                                                <input type="file" name="file">
                                                                            </div>
                                                                        </div>
                                                                        <br>

                                                                        <div class="modal-footer">
                                                                            <button type="submit" class="btn btn-primary"> Ubah </button>
                                                                            <button type="reset" class="btn btn-danger"> RESET</button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Button Hapus -->
                                                    <button style=" font-size: clamp(7px, 1vw, 10px); color:black;" href="#" type="submit" class=" btn btn-danger" data-toggle="modal" data-target="#PopUpHapus<?php echo $data['no_laporan']; ?>" data-toggle='tooltip' title='Hapus Keuangan'>
                                                        <i style="font-size: clamp(7px, 1vw, 10px); color: black;" class="fa-solid fa-trash"></i></button>
                                                    <div class="modal fade" id="PopUpHapus<?php echo $data['no_laporan']; ?>" role="dialog" arialabelledby="modalLabel" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h4 class="modal-title"> <b> Hapus Keuangan </b> </h4>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="close">
                                                                        <span aria-hidden="true"> &times; </span>
                                                                    </button>
                                                                </div>

                                                                <div class="modal-body">
                                                                    <form action="../proses/DRekapKeuangan" method="POST">
                                                                        <input type="hidden" name="no_laporan" value="<?php echo $no_laporan; ?>">
                                                                        <input type="hidden" name="tanggal1" value="<?php echo $tanggal_awal; ?>">
                                                                        <input type="hidden" name="tanggal2" value="<?php echo $tanggal_akhir; ?>">
                                                                        <div class="form-group">
                                                                            <h6> Yakin Ingin Hapus Keuangan ini ? </h6>
                                                                        </div>

                                                                        <div class="modal-footer">
                                                                            <button type="submit" class="btn btn-primary"> Hapus </button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                <?php echo  " </td> </tr>";
                                            }
                                                ?>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.container-fluid -->
            </div>


            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Balcom Solution 2023</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Mau Keluar?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Klik Ya jika ingin keluar.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Tidak</button>
                    <a class="btn btn-primary" href="/index">Ya</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="/vendor_sb/jquery/jquery.min.js"></script>
    <script src="/vendor_sb/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="/js/bootstrap.bundle.min.js"></script>
    <script src="/vendor_sb/bootstrap/js/bootstrap.min.js"></script>
    <!-- Core plugin JavaScript-->
    <script src="/vendor_sb/jquery-easing/jquery.easing.min.js"></script>
    <script src="/bootstrap-select/dist/js/bootstrap-select.js"></script>
    <!-- Custom scripts for all pages-->
    <script src="/js/sb-admin-2.min.js"></script>
    <script src="/js/jquery.dataTables.min.js"></script>
    <script src="/js/dataTables.bootstrap4.min.js"></script>
    <script src="/js/dataTables.buttons.min.js"></script>
    <script src="/js/buttons.bootstrap4.min.js"></script>
    <script src="/js/jszip.min.js"></script>
    <script src="/js/buttons.html5.min.js"></script>
    <!-- Fontawasome-->
    <script src="/js/6bcb3870ca.js" crossorigin="anonymous"></script>




    <script>
        $(document).ready(function() {
            var table = $('#example').DataTable({
                lengthChange: false,
                buttons: ['excel']
            });

            table.buttons().container()
                .appendTo('#example_wrapper .col-md-6:eq(0)');
        });
    </script>

    <script>
        function createOptions(number) {
            var options = [],
                _options;

            for (var i = 0; i < number; i++) {
                var option = '<option value="' + i + '">Option ' + i + '</option>';
                options.push(option);
            }

            _options = options.join('');

            $('#number')[0].innerHTML = _options;
            $('#number-multiple')[0].innerHTML = _options;

            $('#number2')[0].innerHTML = _options;
            $('#number2-multiple')[0].innerHTML = _options;
        }

        var mySelect = $('#first-disabled2');

        createOptions(4000);

        $('#special').on('click', function() {
            mySelect.find('option:selected').prop('disabled', true);
            mySelect.selectpicker('refresh');
        });

        $('#special2').on('click', function() {
            mySelect.find('option:disabled').prop('disabled', false);
            mySelect.selectpicker('refresh');
        });

        $('#basic2').selectpicker({
            liveSearch: true,
            maxOptions: 1
        });
    </script>


</body>

</html>