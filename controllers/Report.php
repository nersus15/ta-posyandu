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
        $dataBayi = $query->results();
        $data = [];

        foreach ($dataBayi as $r) {
            $idanak = $r['id'];
            if (!isset($data[$idanak])) {
                $data[$idanak] = $r;
                $data[$idanak]['kunjungan'] = [];
                $tmp = $this->db->select('YEAR(tgl_periksa) tahun, MONTH(tgl_periksa) bulan, tgl_periksa, id, nama_pemeriksa, berat, tinggi')
                    ->where('anak', $idanak)    
                    ->from('kunjungan_anak')
                    ->results();
    
                foreach($tmp as $row){
                    if(!isset($data[$idanak]['kunjungan'][$row['tahun']])){
                        $data[$idanak]['kunjungan'][$row['tahun']] = array();
                    }
        
                    $data[$idanak]['kunjungan'][$row['tahun']][] = array(
                        'tahun' => $row['tahun'],
                        'bulan' => $row['bulan'],
                        'berat' => $row['berat'],
                        'tinggi' => $row['tinggi'],
                        'nama_pemeriksa' => $row['nama_pemeriksa'],
                        'id' => $row['id'],
                        'tgl_periksa' => $row['tgl_periksa']
                    );
                }
            }
        }
        $maps = [
            'Tanggal Catat' => 'createdAt',
            'Nama' => function($row){
                return empty($row['nama']) ? 'Belum punya nama' : $row['nama'];
            },
            'Umur' => 'umur',
            'Jenis Kelamin' => function($row){
                return $row['kelamin'] == 'L' ? 'Laki-laki':'Perempuan';
            },
            'AKB' => 'akb',
            'BBL' => 'bbl',
            'Ibu' => 'ibu',
            'Ayah' => 'ayah',
            'Tanggal Lahir' => function($row){
                $row['tanggal_lahir'] . ($row['ttl_estimasi'] == 1 ? ' <span class ="badge badge-pill badge-sm bg-info">Estimasi</span>' : '');
            },
            'Alamat' => 'alamat',
        ];

        $headerHtmlKunjungan = '<tr>'.
            '<th class="middle center" rowspan="2">Tahun</th>'.
            '<th class="middle center" colspan="12">Hasil Penimbangan BB/TB (gr/cm)</th>'.
            '</tr>'.
            '<tr>'.
            '<th class="center">Jan</th>'.
            '<th class="center">Feb</th>'.
            '<th class="center">Mar</th>'.
            '<th class="center">Apr</th>'.
            '<th class="center">Mei</th>'.
            '<th class="center">Jun</th>'.
            '<th class="center">Jul</th>'.
            '<th class="center">Agu</th>'.
            '<th class="center">Sep</th>'.
            '<th class="center">Okt</th>'.
            '<th class="center">Nov</th>'.
            '<th class="center">Des</th>'.
            '</tr>';

        $maps_kunjungan = [
            'tahun' => function($lansia, $kunjungan){
                return $kunjungan[0]['tahun'];
            },
        ];

        for ($i=1; $i <= 12 ; $i++) { 
            $maps_kunjungan[$i] = function($lansia, $kunjungan) use($i){
                $kunjunganBulanIni = null;

                foreach($kunjungan as $v){
                    if($i == $v['bulan']){
                        $kunjunganBulanIni = $v;
                        break;
                    }
                }
                $berat = !empty($kunjunganBulanIni) ?  $kunjunganBulanIni['berat'] : '-';
                $tinggi = !empty($kunjunganBulanIni) ?  $kunjunganBulanIni['tinggi'] : '-';

                return "$berat/$tinggi";
            };
        }

        $teks = isset($mapUmur[$usia]) ? "(umur $mapUmur[$usia])" : '';
        $dataHtml = [
            'title' => 'Data Bayi/Balita ' . $teks, 
            'maps' => $maps, 
            'data' => $data,
            'maps_kunjungan' => $maps_kunjungan,
            'headerHtmlKunjungan' => $headerHtmlKunjungan
        ];

        $html = $this->addViews('reports/all', $dataHtml, true);
        $this->buatPdf($html);
    }
    function bumil()
    {
        $tahun = $_POST['tahun'];
        $dataBumil = $this->db
            ->select('bumil.*, users.nama_lengkap nama_pencatat')
            ->from('bumil')
            ->join('users', 'users.username = bumil.pencatat')
            ->like('bumil.createdAt', $tahun, 'after')->results();
        $data = [];

        foreach ($dataBumil as $r) {
            if (!isset($data[$r['id']])) {
                $data[$r['id']] = $r;
                $data[$r['id']]['kunjungan'] = $this->db->select('YEAR(tgl_periksa) tahun, MONTH(tgl_periksa) bulan, kunjungan_bumil.*')->from('kunjungan_bumil')->where('ibu', $r['id'])->results();
            }
        }
        $maps = [
            'Nama Pencatat' => 'nama_pencatat',
            'Nama' => 'nama',
            'Nama Suami' => 'nama_suami',
            'Tanggal Lahir' => function($row){
                return $row['tanggal_lahir'] . ($row['ttl_estimasi'] == 1 ? '<span class ="badge badge-pill badge-sm bg-info">Estimasi</span>' : '');
            },
            'Alamat Domisili' => 'domisili',
            'Alamat'=> 'alamat',
            'Pendidikan'=> 'pendidikan',
            'Pekerjaan'=> 'pekerjaan',
            'Agama' => 'agama'
        ];

        $maps_kunjungan = [
            'Tanggal Periksa' => 'tgl_periksa',
            'Tahun' => 'tahun',
            'Bulan' => function($ibu, $kunjungan){
                return namaBulan($kunjungan['bulan']);
            },
            'Nama Pemeriksa' => 'nama_pemeriksa',
            'Usia Kehamilan' => 'usia_kehamilan',
            'Hamil Ke' => 'gravida',
            'BJ' => 'bj',
            'BB' => 'bb',
            'TB' => 'tb',
            'Tinggi Fundus' => 'fundus',
            'Lingkar Lengan Atas' => 'lila',
            'HB' => 'hb',
        ];

        if(myRole() == 'bidan'){
            $maps_kunjungan = [
                'Tanggal Periksa' => 'tgl_periksa',
                'Tahun' => 'tahun',
                'Bulan' => function($ibu, $kunjungan){
                    return namaBulan($kunjungan['bulan']);
                },
                'Nama Pemeriksa' => 'nama_pemeriksa',
                'Obstetrik' => function($ibu, $row){
                    $obstetrik = 'Gravida: ' . $row['gravida'] . '<br> Partus: ' . $row['paritas'] . ' <br>Abortus: ' . $row['abortus'] . ' <br>Hidup: ' . $row['hidup'];
                    return $obstetrik;
                },
                'HPHT' => 'hpht',
                'Taksiran <br> Persalinan' => 'hpl',
                'Persalinan <br> Sebelumnya' => 'persalinan_sebelumnya',
                'BB' => 'bb',
                'TB' => 'tb',
                'Buku KIA' => function($ibu, $row){
                    return $row['buku_kia'] == 1 ? 'Memiliki' : 'Tidak Memiliki';
                },
            ];
        }
        $dataHtml = [
            'title' => 'Data Ibu Hamil', 
            'data' => $data, 
            'maps' => $maps,
            'maps_kunjungan' => $maps_kunjungan
        ];
        $html = $this->addViews('reports/all', $dataHtml, true);
        $this->buatPdf($html);
    }

    function lansia()
    {
        $tahun = $_POST['tahun'];
        $dataLansia = $this->db
            ->from('lansia')
            ->like('lansia.createdAt', $tahun, 'after')->results();
        $data = [];

        foreach ($dataLansia as $r) {
            $idlansia = $r['id'];
            if (!isset($data[$idlansia])) {
                $data[$idlansia] = $r;
                $data[$idlansia]['kunjungan'] = [];
                $tmp = $this->db->select('YEAR(tgl_periksa) tahun, MONTH(tgl_periksa) bulan, tgl_periksa, id, pemeriksa, berat')
                    ->where('lansia', $idlansia)    
                    ->from('periksa_lansia')
                    ->results();
    
                foreach($tmp as $row){
                    if(!isset($data[$idlansia]['kunjungan'][$row['tahun']])){
                        $data[$idlansia]['kunjungan'][$row['tahun']] = array();
                    }
        
                    $data[$idlansia]['kunjungan'][$row['tahun']][] = array(
                        'tahun' => $row['tahun'],
                        'bulan' => $row['bulan'],
                        'berat' => $row['berat'],
                        'nama_pemeriksa' => $row['pemeriksa'],
                        'id' => $row['id'],
                        'tgl_periksa' => $row['tgl_periksa']
                    );
                }
            }
        }
        $maps = [
            'Nama' => 'nama',
            'Alamat' => 'alamat',
            'Tanggal Lahir' => function($row){
                return $row['tanggal_lahir'] . ($row['estimasi_ttl'] == 1 ? '<span class ="badge badge-pill badge-sm bg-info">Estimasi</span>' : '');
            },
            'NIK' => 'nik',
        ];

        $headerHtmlKunjungan = '<tr>'.
            '<th class="middle center" rowspan="2">Tahun</th>'.
            '<th class="middle center" colspan="12">Hasil Penimbangan BB (gr)</th>'.
            '</tr>'.
            '<tr>'.
            '<th class="center">Jan</th>'.
            '<th class="center">Feb</th>'.
            '<th class="center">Mar</th>'.
            '<th class="center">Apr</th>'.
            '<th class="center">Mei</th>'.
            '<th class="center">Jun</th>'.
            '<th class="center">Jul</th>'.
            '<th class="center">Agu</th>'.
            '<th class="center">Sep</th>'.
            '<th class="center">Okt</th>'.
            '<th class="center">Nov</th>'.
            '<th class="center">Des</th>'.
            '</tr>';

        $maps_kunjungan = [
            'tahun' => function($lansia, $kunjungan){
                return $kunjungan[0]['tahun'];
            },
        ];

        for ($i=1; $i <= 12 ; $i++) { 
            $maps_kunjungan[$i] = function($lansia, $kunjungan) use($i){
                $kunjunganBulanIni = null;

                foreach($kunjungan as $v){
                    if($i == $v['bulan']){
                        $kunjunganBulanIni = $v;
                        break;
                    }
                }

                return !empty($kunjunganBulanIni) ?  $kunjunganBulanIni['berat'] : '-';
            };
        }

        $dataHtml = [
            'title' => 'Data Lansia', 
            'data' => $data, 
            'maps' => $maps, 
            'maps_kunjungan' => $maps_kunjungan,
            'headerHtmlKunjungan' => $headerHtmlKunjungan
        ];
        $html = $this->addViews('reports/all', $dataHtml, true);
        $this->buatPdf($html);
    }

    function tes(){
        $_POST['tahun'] = 2024;
        $_POST['umur'] = 'semua';
        $this->bumil();
    }

    private function buatPdf($html)
    {
        $fname = random(8);
        $fpath = ROOT . '/assets/docs/pdf/' . $fname . '.pdf';

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        $pdf = $dompdf->output();
        file_put_contents($fpath, $pdf);

        response(['message' => 'Berhasil membuat laporan', 'data' => $fname]);
    }
}
