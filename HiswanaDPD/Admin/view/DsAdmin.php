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

if ($jabatan_valid == 'Admin') {
} else {
    header("Location: logout.php");
    exit;
}



//script format tanggal
function formattanggal($date)
{


    $newDate = date(" d F Y", strtotime($date));
    switch (date("l")) {
        case 'Monday':
            $nmh = "Senin";
            break;
        case 'Tuesday':
            $nmh = "Selasa";
            break;
        case 'Wednesday':
            $nmh = "Rabu";
            break;
        case 'Thursday':
            $nmh = "Kamis";
            break;
        case 'Friday':
            $nmh = "Jum'at";
            break;
        case 'Saturday':
            $nmh = "Sabtu";
            break;
        case 'Sunday':
            $nmh = "minggu";
            break;
    }
    echo " $newDate";
}

function getDay($date)
{
    $datetime = DateTime::createFromFormat('Y-m-d', $date);
    return $datetime->format('l');
}

function getHari($date)
{
    $day = getDay($date);
    switch ($day) {
        case 'Sunday':
            $hari = 'Minggu';
            break;
        case 'Monday':
            $hari = 'Senin';
            break;
        case 'Tuesday':
            $hari = 'Selasa';
            break;
        case 'Wednesday':
            $hari = 'Rabu';
            break;
        case 'Thursday':
            $hari = 'Kamis';
            break;
        case 'Friday':
            $hari = 'Jum\'at';
            break;
        case 'Saturday':
            $hari = 'Sabtu';
            break;
        default:
            $hari = 'Tidak ada';
            break;
    }
    return $hari;
}

function formatuang($angka)
{
    $uang = "Rp " . number_format($angka, 2, ',', '.');
    return $uang;
}

$tanggal_awal = date('Y-m-1');
$tanggal_akhir = date('Y-m-31');


$table = mysqli_query($koneksi, "SELECT sum(jumlah) AS jumlah_pemasukan FROM laporan_keuangan WHERE status_keuangan = 'Masuk' AND tanggal BETWEEN '$tanggal_awal' AND '$tanggal_akhir'");
$data_pemasukan = mysqli_fetch_array($table);
$total_pemasukan = $data_pemasukan['jumlah_pemasukan'];
if (!isset($data_pemasukan['jumlah_pemasukan'])) {
    $total_pemasukan = 0;
}
$table2 = mysqli_query($koneksi, "SELECT sum(jumlah) AS jumlah_pengeluaran FROM laporan_keuangan WHERE status_keuangan = 'Keluar' AND tanggal BETWEEN '$tanggal_awal' AND '$tanggal_akhir'");
$data_pengeluaran = mysqli_fetch_array($table2);
$total_pengeluaran = $data_pengeluaran['jumlah_pengeluaran'];
if (!isset($data_pengeluaran['jumlah_pengeluaran'])) {
    $total_pengeluaran = 0;
}

//data grafik

//data tanggal
$table3 = mysqli_query($koneksi, "SELECT tanggal FROM laporan_keuangan WHERE tanggal BETWEEN '$tanggal_awal' AND '$tanggal_akhir' GROUP BY tanggal ");


while ($data3 = mysqli_fetch_assoc($table3)) {
    $tanggal_grafik = $data3['tanggal'];

    $data_tanggal[] = "$tanggal_grafik";
}

//data pemasukan
$table4 = mysqli_query($koneksi, "SELECT sum(jumlah) AS jumlah_pemasukan_grafik FROM laporan_keuangan WHERE status_keuangan = 'Masuk' AND tanggal BETWEEN '$tanggal_awal' AND '$tanggal_akhir' GROUP BY tanggal   ");

while ($data4 = mysqli_fetch_array($table4)) {
    $jumalah_pemasukan_grafik = $data4['jumlah_pemasukan_grafik'];
    $data_pemasukan_grafik[] = "$jumalah_pemasukan_grafik";
}

//data penggeluaran
$table5 = mysqli_query($koneksi, "SELECT sum(jumlah) AS jumlah_pengeluaran_grafik FROM laporan_keuangan WHERE status_keuangan = 'Keluar' AND tanggal BETWEEN '$tanggal_awal' AND '$tanggal_akhir' GROUP BY tanggal ");

while ($data5 = mysqli_fetch_array($table5)) {
    $jumalah_pengeluaran_grafik = $data5['jumlah_pengeluaran_grafik'];
    $data_pengeluaran_grafik[] = "$jumalah_pengeluaran_grafik";
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

    <title>Dasboard</title>


    <!-- Custom fonts for this template-->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="/css/sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/bootstrap-select/dist/css/bootstrap-select.css">
    <link rel="stylesheet" href="/css/dataTables.bootstrap4.min.css">
    <script type="text/javascript">
        window.setTimeout("waktu()", 1000);

        function waktu() {
            var tanggal = new Date();
            setTimeout("waktu()", 1000);
            document.getElementById("jam").innerHTML = tanggal.getHours();
            document.getElementById("menit").innerHTML = tanggal.getMinutes();
            document.getElementById("detik").innerHTML = tanggal.getSeconds();
        }
    </script>

    <style>
        #jam-digital {
            overflow: hidden
        }

        #hours {
            float: left;
            width: 50px;
            height: 30px;
            background-color: darkgrey;
            margin-right: 25px
        }

        #minute {
            float: left;
            width: 50px;
            height: 30px;
            background-color: darkgrey;
            margin-right: 25px
        }

        #second {
            float: left;
            width: 50px;
            height: 30px;
            background-color: darkgrey;
        }

        #jam-digital p {
            color: #FFF;
            font-size: 22px;
            text-align: center
        }
    </style>



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
            <li class="nav-item">
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

                    <!-- Jam tanggal -->
                    <div class="row">
                        <div class="col-sm-9">
                        </div>
                        <div class="col-sm-3" style="color: black; font-size: 18px;">
                            <script type='text/javascript'>
                                var months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                                var myDays = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jum&#39;at', 'Sabtu'];
                                var date = new Date();
                                var day = date.getDate();
                                var month = date.getMonth();
                                var thisDay = date.getDay(),
                                    thisDay = myDays[thisDay];
                                var yy = date.getYear();
                                var year = (yy < 1000) ? yy + 1900 : yy;
                                document.write(thisDay + ', ' + day + ' ' + months[month] + ' ' + year);
                            </script>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-9">
                        </div>
                        <div class="col-sm-3">
                            <div id="jam-digital">
                                <div id="hours">
                                    <p id="jam"></p>
                                </div>
                                <div id="minute">
                                    <p id="menit"> </p>
                                </div>
                                <div id="second">
                                    <p id="detik"> </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <br>
                    <br>


                    <!-- Kotak pemasukan pengeluaran -->
                    <div class="row">
                        <!-- Pemasukan -->
                        <div class="col-xl-6 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Pemasukan</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= formatuang($total_pemasukan) ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pengeluaran -->
                        <div class="col-xl-6 col-md-6 mb-4">
                            <div class="card border-left-danger shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                                Pengeluaran</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= formatuang($total_pengeluaran) ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content Grafik -->

                    <div class="row">

                        <!-- Area Chart -->
                        <div class="col-xl-12 col-lg-7">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">

                                </div>
                                <!-- Card Body -->
                                <div style="height: 450px;" class="card-body">
                                    <div class="chart-area">
                                        <div id="chart_keuangan">

                                        </div>
                                    </div>
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
    <!-- grafik-->
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script>
        Highcharts.chart('chart_keuangan', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Grafik Keuangan'
            },

            xAxis: {
                categories: [
                    <?php

                    foreach ($data_tanggal as $a) {
                    ?> ' <?php print_r($a);

                            ?> '
                    <?php echo ",";
                    } ?>






                ],
                crosshair: true
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Keuangan (Rp)'
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>Rp {point.y:.2f}</b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            series: [{
                name: 'Pendapatan',
                data: [<?php foreach ($data_pemasukan_grafik as $x) {
                            print_r($x);
                            echo ",";
                        } ?>]

            }, {
                name: 'Pengeluaran',
                data: [<?php foreach ($data_pengeluaran_grafik as $n) {
                            print_r($n);
                            echo ",";
                        } ?>]

            }]
        });
    </script>


</body>

</html>