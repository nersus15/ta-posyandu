<?php
class Bayi extends Controller{
    /** @var Datatables */
    var $datatables;

    function list($usia = 'semua'){
        $this->load('Datatables', 'datatables');
        $username = sessiondata('login', 'username');

        $query = $this->db->from('bayi');
        if(is_login('kader'))
            $query->where('pencatat', $username);

        $mapUmur = array(
            '05' => '0-5',
            '611' => '6-11',
            '1223' => '12-23',
            '2459' => '24-59',
        );
        if(!in_array($usia, array_keys($mapUmur))) $usia = 'semua';

        if($usia != 'semua'){
            $startEnd = explode('-', $mapUmur[$usia]);
            $mulai = $startEnd[0];
            $ahir = $startEnd[1];
            // Ambil batas atas
            $index = 0;
            foreach(array_keys($mapUmur) as $i => $k){
                if($k == $usia){
                    $index = $i;
                    break;
                }
            }

            if($index > 0){
                $mulai = explode('-', $mapUmur[array_keys($mapUmur)[$index - 1]])[1];
            }
            $query->between('umur', ($mulai * 30), ($ahir * 30));
        }

        $header = array(
            'id' => array('searchable' => false),
            'nama' => array('searchable' => true),
            'alamat' => array('searchable' => true),
            'ayah' => array('searchable' => true),
            'ibu' => array('searchable' => true),
            'ttl' => array('searchable' => false, 'field' => 'tanggal_lahir'),
            'ttl_estimasi' => array('searchable' => false),
            'pencatat' => array('searchable' => true),
            'bbl' => array('searchable' => false),
            'akb' => array('searchable' => false),
            'createdAt' => array('searchable' => true),
            'umur' => array('searchable' => true),
        );


        $this->datatables->setHeader($header);
        $this->datatables->setQuery($query);

        response($this->datatables->getData());
    }

    function save(){
        if(!httpmethod('post') && !httpmethod('update'))
            response("Ilegal Method", 403);

        $ruleValidator = [
            'tanggal_lahir' => array(
                [
                    'rule' => 'required',
                    'message' => 'Harus menyertakan Tanggal Lahir'
                ],
                [
                    'rule' => 'regex',
                    'pattern' => '/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/',
                    'message' => 'Format tanggal lahir harus yyy-mm-dd'
                ]
            ),
            'umur' => array(
                [
                    'rule' => 'number',
                    'message' => 'Umur harus berupa angka'
                ],
                [
                    'rule' => 'maximal',
                    'max' => 59 * 30,
                    'message' => 'Maksimal anak berusia ' . (59 * 30) . ' Hari'
                ]
            ),
            'ibu' => array(
                [
                    'rule' => 'required',
                    'message' => 'Harus ada nama ibu!'
                ]
            )

        ];
        $input = $_POST;

        $input['ttl_estimasi'] = '0';

        if ($input['ingat_ttl'] == 0) {
            $hariIni = time();
            $umur = intval($input['umur']) * (60 * 60 * 24);
            $input['tanggal_lahir'] = waktu($hariIni - $umur, MYSQL_DATE_FORMAT);
            $input['ttl_estimasi'] = '1';
        }else{
            $hariIni = date_create();
            $ttl = date_create($input['tanggal_lahir']);


            $diff = $hariIni->diff($ttl);

            $hari = $diff->format('%a');

            $input['umur'] = intval($hari);
        }
        unset($input['ingat_ttl']);
        $isEdit = $this->getFromMiddleware('isEdit');
        $this->validateInput($input, $ruleValidator);
        if($isEdit){
            $id = $input['id'];
            $this->db->where('id', $id)->update($input, 'bayi');
            response("Berhasil Update data Bayi " . $input['nama'] . ' dengan id ' . $id, 201);
        }else{
            $input['id'] = random(8);
            $input['pencatat'] = sessiondata('login', 'username');

            $this->db->insert($input, 'bayi');
            response("Berhasil mendaftarkan Bayi " . $input['nama'] . ' dengan id id' . $input['id'], 201);
        }
    }

    function delete(){
        $ids = $_POST['ids'];
        if(!httpmethod('delete')) response("Invalid HTTP Method", 403);
        if(empty($ids)) response("Request Invalid", 403);

        try {
            $this->db->where_in('id', $ids)->delete('bayi');
            response("Berhasil menghapus data Anak (" . join(', ', $ids) . ')');
        } catch (\Throwable $th) {
            response(['message' => 'Gagal menghapus data', 'reason' => $th->getMessage()], 500);
        }
    }
    
    private function _getlist($idanak){
        $tmp = $this->db->select('YEAR(tgl_periksa) tahun, MONTH(tgl_periksa) bulan, tgl_periksa, id, nama_pemeriksa, berat, tinggi')
            ->where('anak', $idanak)    
            ->from('kunjungan_anak')
            ->results();

        $data = [
            'anak' => [],
            'pemeriksaan' => []
        ];

        foreach($tmp as $row){
            if(empty($data['anak'])){
                $data['anak']['id'] = $idanak;
            }
            
            if(!isset($data['pemeriksaan'][$row['tahun']])){
                $data['pemeriksaan'][$row['tahun']] = array();
            }

            $data['pemeriksaan'][$row['tahun']][$row['bulan']] = array(
                'berat' => $row['berat'],
                'tinggi' => $row['tinggi'],
                'nama_pemeriksa' => $row['nama_pemeriksa'],
                'id' => $row['id'],
                'tgl_periksa' => $row['tgl_periksa']
            );
        }
        
        return $data;
    }
    function periksalist(){
        if(!httpmethod()) response("Ilegal Method", 403);
        $id = $_POST['id'];
        response(['data' => $this->_getlist($id)]);
    }

    function periksa(){
        if(!httpmethod('post') && !httpmethod('update'))
            response("Ilegal Method", 403);

        $ruleValidator = [
            'tgl_periksa' => array(
                [
                    'rule' => 'required',
                    'message' => 'Harus mengisi tanggal periksa'
                ],
                [
                    'rule' => 'regex',
                    'pattern' => '/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/',
                    'message' => 'Format tanggal periksa harus yyy-mm-dd'
                ]
            ),
            'bulan' => array(
                [
                    'rule' => 'required',
                    'message' => 'Harus mengisi bulan periksa'
                ],
                [
                    'rule' => 'minimal',
                    'min' => 1,
                    'message' => 'Nilai tidak valid'
                ],
                [
                    'rule' => 'maximal',
                    'max' => 12,
                    'message' => 'Nilai tidak valid'
                ]
            ),
            
            'berat' => array(
                [
                    'rule' => 'required',
                    'message' => 'Harus mengisi berat badan'
                ],
                [
                    'rule' => 'number',
                    'message' => 'Berat badan harus berupa angka'
                ],
            ),
            'berat' => array(
                [
                    'rule' => 'required',
                    'message' => 'Harus mengisi berat badan'
                ],
                [
                    'rule' => 'number',
                    'message' => 'Berat badan harus berupa angka'
                ],
            ),
            'tinggi' => array(
                [
                    'rule' => 'required',
                    'message' => 'Harus mengisi tinggi badan!'
                ],
                [
                    'rule' => 'number',
                    'message' => 'Tinggi badan harus berupa angka'
                ],
            )

        ];
        $input = $_POST;

        $input['tgl_periksa'] = $input['tahun'] . '-' . ($input['bulan'] < 10 ? '0' . $input['bulan'] : $input['bulan']) . '-01';
      
        $isEdit = $this->getFromMiddleware('isEdit');
        $this->validateInput($input, $ruleValidator);

        unset($input['bulan'], $input['tahun']);

        if(empty($input['nama_pemeriksa']))
            $input['nama_pemeriksa'] = sessiondata('login', 'nama_lengkap');

        if($isEdit){
            $id = $input['id'];
            $this->db->where('id', $id)->update($input, 'kunjungan_anak');

            $pemeriksaan = $this->_getlist($input['anak']);
            
            response(["data" => $pemeriksaan, "message" => "Berhasil Update data pemeriksaan anak"], 201);
        }else{
            // cek bulan
            $bulanIni = substr($input['tgl_periksa'], 0, 7);
            $ada = $this->db->select('*')->from('kunjungan_anak')->where('anak', $input['anak'])->like('tgl_periksa', $bulanIni, 'after')->results();
            if(!empty($ada))
                response(['message' => "Pemeriksaan pada bulan " . namaBulan($input['tgl_periksa']) . ' sudah dilakukan'], 403);

            unset($input['id']);
            $input['pencatat'] = sessiondata('login', 'username');

            $this->db->insert($input, 'kunjungan_anak');

            $pemeriksaan = $this->_getlist($input['anak']);
            response(['data' => $pemeriksaan, 'message' => "Berhasil mencatat pemeriksaan anak"], 201);
        }
    }

    function deletepemeriksaan(){
        $id = $_POST['id'];
        $idanak = $_POST['idanak'];
        if(!httpmethod('delete')) response("Invalid HTTP Method", 403);
        if(empty($id)) response("Request Invalid", 403);

        try {
            $this->db->where('id', $id)->delete('kunjungan_anak');

            $pemeriksaan = $this->_getlist($idanak);
            response(["data" => $pemeriksaan, "message" => "Berhasil menghapus data pemeriksaan Anak"]);
        } catch (\Throwable $th) {
            response(['message' => 'Gagal menghapus data', 'reason' => $th->getMessage()], 500);
        }
    }
}