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
    $table = mysqli_query($koneksi, "SELECT * FROM laporan_keuangan WHERE tanggal = '$tanggal_awal' AND akun_keuangan = 'Iuran DPC' GROUP BY iuran_anggota");
} else {

    $table = mysqli_query($koneksi, "SELECT * FROM laporan_keuangan WHERE tanggal BETWEEN '$tanggal_awal' AND '$tanggal_akhir' AND akun_keuangan = 'Iuran DPC' GROUP BY iuran_anggota");
}
    

        $html .= '

        <h3 class="panel-title" align="Center" style = "margin-bottom: 1px; margin-top: 1px;"><img style=" max-height: 70px; width: 100%; text-align:center; " > Logo Hiswana dan KOP  </h3>
        <hr style = "margin-bottom: 1px; margin-top: 1px;">
        <h2 class="panel-title" align="Center" style = "margin-bottom: 1px; margin-top: 1px;"><u><strong>Laporan Iuran DPC</strong></u></h2>
        <pre class="panel-title" align="center"  style="font-size: 12px; margin-bottom: 10px; margin-top: 1px;">'. $tanggal_awal .' - '. $tanggal_akhir .'</pre>
        
        <table align="center"  style="width:100%"   border="1" cellspacing="0">
        <thead>
            <tr>
                <th style="font-size: clamp(12px, 1vw, 12px); color: black;">No</th>
                <th style="font-size: clamp(12px, 1vw, 12px); color: black;">Anggota</th>
                <th style="font-size: clamp(12px, 1vw, 12px); color: black;">Total Iuran</th>
            </tr>
        </thead>
        <tbody>';

    
        $no_urut = 0;
        $total_iuran = 0;
        function formatuang($angka)
        {
            $uang = "Rp " . number_format($angka, 2, ',', '.');
            return $uang;
        }

        while ($data = mysqli_fetch_array($table)) {
            $no_laporan = $data['no_laporan'];
            $iuran_Anggota = $data['iuran_anggota'];
            $jumlah = $data['jumlah'];
            $total_iuran = $total_iuran + $jumlah;
           
            $no_urut++;

                $html .= ' <tr>
                <td style="font-size: clamp(12px, 1vw, 12px); color: black;" align="center" >'. $no_urut .'</td>
                <td style="font-size: clamp(12px, 1vw, 12px); color: black;" >'. $iuran_Anggota .'</td>
                <td style="font-size: clamp(12px, 1vw, 15px); color: black;" >'. formatuang($total_iuran) .'</td>
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