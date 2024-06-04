<?php
class Bumil extends Controller{
    /** @var Datatables  */
    var $datatables;

    function list(){
        $role = myRole();

        $this->load('Datatables', 'datatables');
        $username = sessiondata('login', 'username');

        $query = $this->db->from('bumil')->join('users', 'users.username = bumil.pencatat');
        $header = array(
            'id' => array('searchable' => false),
            'no' => array('searchable' => true, 'field' => 'nomor'),
            'nama' => array('searchable' => true),
            'alamat' => array('searchable' => true, 'field' => 'bumil.alamat'),
            'domisili' => array('searchable' => true),
            'suami' => array('searchable' => true, 'field' => 'nama_suami'),
            'ttl' => array('searchable' => false, 'field' => 'tanggal_lahir'),
            'ttl_estimasi' => array('searchable' => false),
            'pencatat' => array('searchable' => true),
            'nama_pencatat' => array('searchable' => true, 'field' => 'users.nama_lengkap'),
            'pendidikan' => array('searchable' => false),
            'pekerjaan' => array('searchable' => false),
            'createdAt' => array('searchable' => true, 'field' => 'bumil.createdAt'),
            'agama' => array('searchable' => false),
            'hp' => array('searchable' => false),
        );

        $this->datatables->set_resultHandler(function($data, $_, $header){
            $_new = $data;
            foreach($data as $k => $row){
                if($row['ttl_estimasi'] == 1){
                    $hariIni = date_create($row['createdAt']);
                    $ttl = date_create($row['ttl']);        
                    $diff = $hariIni->diff($ttl);
                    $tahun = $diff->format('%y');
                    $_new[$k]['umur'] = intval($tahun);
                }else{
                    $_new[$k]['umur'] = '';
                }
            }
            return $_new;
        });

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
                    'message' => 'Harus menyertakan Nama Lengkap'
                ],
                [
                    'rule' => 'regex',
                    'pattern' => '/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/',
                    'message' => 'Format tanggal lahir harus yyy-mm-dd'
                ]
            ),
            'alamat' => array(
                [
                    'rule' => 'required',
                    'message' => 'Alamat harus diisi'
                ],
            ),
            'domisili' => array(
                [
                    'rule' => 'required',
                    'message' => 'Alamat Domisili harus diisi'
                ],
            ),
            'nama' => array(
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
            $umur = intval($input['umur']) * (60 * 60 * 24 * 360);
            $input['tanggal_lahir'] = waktu($hariIni - $umur, MYSQL_DATE_FORMAT);
            $input['ttl_estimasi'] = 1;
        }else{
            $hariIni = date_create();
            $ttl = date_create($input['tanggal_lahir']);
            $diff = $hariIni->diff($ttl);
            $hari = $diff->format('%a');
            $input['umur'] = intval($hari);
        }
        unset($input['ingat_ttl'], $input['umur']);
        $isEdit = $this->getFromMiddleware('isEdit');
        $this->validateInput($input, $ruleValidator);
        if($isEdit){
            $id = $input['id'];
            $this->db->where('id', $id)->update($input, 'bumil');
            response("Berhasil Update data Ibu Hamil " . $input['nama'] . ' dengan id ' . $id, 201);
        }else{
            $input['id'] = random(8);
            $input['pencatat'] = sessiondata('login', 'username');

            $this->db->insert($input, 'bumil');
            response("Berhasil mendaftarkan Ibu Hamil " . $input['nama'] . ' dengan id id' . $input['id'], 201);
        }
    }


    function delete(){
        $ids = $_POST['ids'];
        if(!httpmethod('delete')) response("Invalid HTTP Method", 403);
        if(empty($ids)) response("Request Invalid", 403);

        try {
            $this->db->where_in('id', $ids)->delete('bumil');
            response("Berhasil menghapus data Bumil (" . join(', ', $ids) . ')');
        } catch (\Throwable $th) {
            response(['message' => 'Gagal menghapus data', 'reason' => $th->getMessage()], 500);
        }
    }

    private function _getlist($idbumil){
        $tmp = $this->db->select('YEAR(tgl_periksa) tahun, MONTH(tgl_periksa) bulan, kunjungan_bumil.*')
            ->where('ibu', $idbumil)
            ->from('kunjungan_bumil')
            ->results();

        $data = [
            'bumil' => [],
            'pemeriksaan' => empty($tmp) ? [] : $tmp
        ];

        // foreach($tmp as $row){
            if(empty($data['bumil'])){
                $data['bumil']['id'] = $idbumil;
            }
            
        //     if(!isset($data['pemeriksaan'][$row['tahun']])){
        //         $data['pemeriksaan'][$row['tahun']] = array();
        //     }
        //     foreach(array_keys($row) as $k){
        //         if(!in_array($k, ['tahun', 'bulan'])){
        //             $data['pemeriksaan'][$row['tahun']][$row['bulan']][$k] = $row[$k];
        //         }
        //     }
        // }
        
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

        ];
        $input = $_POST;
      
        $isEdit = $this->getFromMiddleware('isEdit');
        $this->validateInput($input, $ruleValidator);

        unset($input['bulan'], $input['tahun']);

        if($isEdit){
            $id = $input['id'];
            $this->db->where('id', $id)->update($input, 'kunjungan_bumil');

            $pemeriksaan = $this->_getlist($input['ibu']);
            
            response(["data" => $pemeriksaan, "message" => "Berhasil Update data pemeriksaan ibu hamil"], 201);
        }else{
            unset($input['id']);
            $input['pencatat'] = sessiondata('login', 'username');
            if(empty($input['nama_pemeriksa']))
                $input['nama_pemeriksa'] = sessiondata('login', 'nama_lengkap');



                // cek bulan
            $bulanIni = substr($input['tgl_periksa'], 0, 7);
            $ada = $this->db->select('*')->from('kunjungan_bumil')->where('ibu', $input['ibu'])->like('tgl_periksa', $bulanIni, 'after')->results();
            if(!empty($ada))
                response(['message' => "Pemeriksaan pada bulan " . namaBulan(substr($input['tgl_periksa'], 5, 2)) . ' sudah dilakukan'], 403);

            $this->db->insert($input, 'kunjungan_bumil');

            $pemeriksaan = $this->_getlist($input['ibu']);
            response(['data' => $pemeriksaan, 'message' => "Berhasil mencatat pemeriksaan ibu hamil"], 201);
        }
    }

    function deletepemeriksaan(){
        $id = $_POST['id'];
        $idbumil = $_POST['idbumil'];
        if(!httpmethod('delete')) response("Invalid HTTP Method", 403);
        if(empty($id)) response("Request Invalid", 403);

        try {
            $this->db->where('id', $id)->delete('kunjungan_bumil');

            $pemeriksaan = $this->_getlist($idbumil);
            response(["data" => $pemeriksaan, "message" => "Berhasil menghapus data pemeriksaan Anak"]);
        } catch (\Throwable $th) {
            response(['message' => 'Gagal menghapus data', 'reason' => $th->getMessage()], 500);
        }
    }
}