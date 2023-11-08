<?php
require_once 'vendor/autoload.php';
include 'koneksi.php';


    $tanggal_awal = $_GET['tanggal1'];
    $tanggal_akhir = $_GET['tanggal2'];
    $tahun = date('Y', strtotime($tanggal_awal));
    $bulan = date('M', strtotime($tanggal_awal)); 


?>
  <style>
   tr{
    border-bottom: 2pt solid;
   }
  </style>

<?php


$mpdf = new \Mpdf\Mpdf([ 'mode' => 'utf-8', 'format' => 'A4-L', 'orientation' => 'L']);
$mpdf->AddPageByArray([
    'margin-left' => 5,
    'margin-right' => 5,
    'margin-top' => 5,
    'margin-bottom' => 5,
]);

$html = '
<html>

<head>


</head>

<body>
<br>
<br>

        

';
if ($tanggal_awal == $tanggal_akhir) {
    $table = mysqli_query($koneksi, "SELECT * FROM laporan_keuangan WHERE tanggal = '$tanggal_awal'");
} else {

    $table = mysqli_query($koneksi, "SELECT * FROM laporan_keuangan WHERE tanggal BETWEEN '$tanggal_awal' AND '$tanggal_akhir'");
}

    

        $html .= '

        <h3 class="panel-title" align="Center" style = "margin-bottom: 1px; margin-top: 1px;"><img style=" max-height: 70px; width: 100%; text-align:center; " > Logo Hiswana dan KOP  </h3>
        <hr style = "margin-bottom: 1px; margin-top: 1px;">
        <h2 class="panel-title" align="Center" style = "margin-bottom: 1px; margin-top: 1px;"><u><strong>Laporan Keuangan</strong></u></h2>
        <pre class="panel-title" align="center"  style="font-size: 12px; margin-bottom: 10px; margin-top: 1px;">'. $tanggal_awal .' - '. $tanggal_akhir .'</pre>
        
        <table align="center"  style="width:100%"   border="1" cellspacing="0">
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
            </tr>
        </thead>
        <tbody>';

    
            $total_kredit = 0;
            $no_urut = 0;
            $total_debit = 0;
            function formatuang($angka)
            {
                $uang = "Rp " . number_format($angka, 2, ',', '.');
                return $uang;
            }

            while ($data = mysqli_fetch_array($table)) {
                $no_laporan = $data["no_laporan"];
                $tanggal = $data["tanggal"];
                $akun_keuangan = $data["akun_keuangan"];
                $iuran_Anggota = $data["iuran_anggota"];
                $status_keuangan = $data["status_keuangan"];
                $jumlah = $data["jumlah"];

                if ($status_keuangan == 'Masuk') {
                    $total_debit = $total_debit + $jumlah;
                } elseif ($status_keuangan == 'Keluar') {
                    $total_kredit = $total_kredit + $jumlah;
                }
                $keterangan = $data['keterangan'];
                $file_bukti = $data['file_bukti'];
                $no_urut++;

                $html .= ' <tr>
                <td style="font-size: clamp(12px, 1vw, 12px); color: black;" align="center" >'. $no_urut .'</td>
                <td style="font-size: clamp(12px, 1vw, 12px); color: black;" >'.$tanggal .'</td>
                <td style="font-size: clamp(12px, 1vw, 12px); color: black;" >'. $akun_keuangan .'</td>
                <td style="font-size: clamp(12px, 1vw, 12px); color: black;" >'. $iuran_Anggota .'</td>" ';

                if ($status_keuangan == 'Masuk') {
                    $html .= '
                        <td style="font-size: 14px"> '. formatuang($jumlah) .' </td> ';
                                                                                } else {
                                                                                    $html .= '
                        <td style="font-size: 14px"> </td>';
                                                                                }

                                                                                if ($status_keuangan == 'Keluar') {
                                                                                    $html .= '
                        <td style="font-size: 14px">'. formatuang($jumlah) .'</td>';
                                                                                } else {
                                                                                    $html .= '
                        <td style="font-size: 14px"></td>';
                                                                                }
                $html .= '
                
                <td style="font-size: clamp(12px, 1vw, 15px); color: black;" >'. formatuang($total_debit - $total_kredit) .'</td>
                <td style="font-size: clamp(12px, 1vw, 12px); color: black;" >'. $keterangan .'</td>
                 </tr>';
            }
              
 $html .= '
        </tbody>
    </table>';

     



        
       

        

    



    $html .= '';

    





 $html .= '</body>

</html>';

$mpdf->setAutoTopMargin = 'stretch';
$mpdf->setAutoBottomMargin = 'stretch';
$mpdf->WriteHTML($html);
$mpdf->Output();
?>