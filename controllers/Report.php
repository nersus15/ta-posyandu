<?php

use Dompdf\Dompdf;

class Report extends Controller
{
    function bayi()
    {
        $tahun = $_POST['tahun'];
        $usia = $_POST['umur'];
        $query = $this->db->from('bayi')
            ->like('createdAt', $tahun, 'after')
            ->where('pencatat', sessiondata('login', 'username'));

        $mapUmur = array(
            '05' => '0-5',
            '611' => '6-11',
            '1223' => '12-23',
            '2459' => '24-59',
        );
        if (!in_array($usia, array_keys($mapUmur))) $usia = 'semua';

        if ($usia != 'semua') {
            $startEnd = explode('-', $mapUmur[$usia]);
            $mulai = $startEnd[0];
            $ahir = $startEnd[1];
            // Ambil batas atas
            $index = 0;
            foreach (array_keys($mapUmur) as $i => $k) {
                if ($k == $usia) {
                    $index = $i;
                    break;
                }
            }

            if ($index > 0) {
                $mulai = explode('-', $mapUmur[array_keys($mapUmur)[$index - 1]])[1];
            }
            $query->between('umur', ($mulai * 30), ($ahir * 30));
        }
        $fname = random(8);
        $fpath = ROOT . '/assets/docs/pdf/' . $fname . '.pdf';

        // reference the Dompdf namespace

       try {
         // include autoloader
        //  require_once ROOT . '/vendor/dompdf/autoload.inc.php';
         // instantiate and use the dompdf class
         $dompdf = new Dompdf();
         $dompdf->loadHtml('hello world');
 
         // (Optional) Setup the paper size and orientation
         $dompdf->setPaper('A4', 'landscape');
 
         // Render the HTML as PDF
         $dompdf->render();
 
         // Output the generated PDF to Browser
         $pdf = $dompdf->output();
         file_put_contents($fpath, $pdf);
 
         response(['message' => 'Berhasil membuat laporan', 'data' => $fname]);
       } catch (\Throwable $th) {
        //throw $th;
        response($th->getMessage());
       }
    }
    function bumil()
    {

        $tabsContent = [];
    }

    function lansia()
    {
    }
}
