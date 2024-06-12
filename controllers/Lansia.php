<?php
class Lansia extends Controller{
    /** @var Datatables */
    var $datatables;

    function list($usia = 'semua'){
        $this->load('Datatables', 'datatables');
        $username = sessiondata('login', 'username');

        $query = $this->db->from('lansia');
        if(is_login('kader'))
            $query->where('pencatat', $username);

        $header = array(
            'id' => array('searchable' => false),
            'nama' => array('searchable' => true),
            'alamat' => array('searchable' => true),
            'nik' => array('searchable' => true),
            'ttl' => array('searchable' => false, 'field' => 'tanggal_lahir'),
            'estimasi_ttl' => array('searchable' => false)
        );

        $this->datatables->set_resultHandler(function($data, $_ ){
            $tmp = $data;

            foreach($data as $k => $row){
                $hariIni = date_create();
                $tglLahir = date_create($row['ttl']);

                $diff = date_diff($tglLahir, $hariIni);
                $tahun = $diff->format('%y');
                $tmp[$k]['umur'] = intval($tahun);
            }

            return $tmp;
        });

        $this->datatables->setHeader($header);
        $this->datatables->setQuery($query);

        response($this->datatables->getData());
    }

    function save(){
        if(!httpmethod('post') && !httpmethod('update'))
            response("Ilegal Method", 403);

        $ruleValidator = [

        ];
        $input = $_POST;

        $input['estimasi_ttl'] = '0';

        if ($input['ingat_ttl'] == 0) {
            $hariIni = time();
            $umur = intval($input['umur']) * (60 * 60 * 24 * 365);
            $input['tanggal_lahir'] = waktu($hariIni - $umur, MYSQL_DATE_FORMAT);
            $input['estimasi_ttl'] = '1';
        }
        unset($input['ingat_ttl'], $input['umur']);
        $isEdit = $this->getFromMiddleware('isEdit');
        $this->validateInput($input, $ruleValidator);
        if($isEdit){
            $id = $input['id'];
            $this->db->where('id', $id)->update($input, 'lansia');
            response("Berhasil Update data lansia " . $input['nama'] . ' dengan id ' . $id, 201);
        }else{
            $input['id'] = random(8);
            $input['pencatat'] = sessiondata('login', 'username');

            $this->db->insert($input, 'lansia');
            response("Berhasil mendaftarkan lansia " . $input['nama'] . ' dengan id id' . $input['id'], 201);
        }
    }

    function delete(){
        $ids = $_POST['ids'];
        if(!httpmethod('delete')) response("Invalid HTTP Method", 403);
        if(empty($ids)) response("Request Invalid", 403);

        try {
            $this->db->where_in('id', $ids)->delete('lansia');
            response("Berhasil menghapus data Lansia (" . join(', ', $ids) . ')');
        } catch (\Throwable $th) {
            response(['message' => 'Gagal menghapus data', 'reason' => $th->getMessage()], 500);
        }
    }
    
    private function _getlist($idlansia){
        $tmp = $this->db->select('YEAR(tgl_periksa) tahun, MONTH(tgl_periksa) bulan, tgl_periksa, id, pemeriksa, berat')
            ->where('lansia', $idlansia)    
            ->from('periksa_lansia')
            ->results();

        $data = [
            'lansia' => [],
            'pemeriksaan' => []
        ];

        foreach($tmp as $row){
            if(empty($data['lansia'])){
                $data['lansia']['id'] = $idlansia;
            }
            
            if(!isset($data['pemeriksaan'][$row['tahun']])){
                $data['pemeriksaan'][$row['tahun']] = array();
            }

            $data['pemeriksaan'][$row['tahun']][$row['bulan']] = array(
                'berat' => $row['berat'],
                'nama_pemeriksa' => $row['pemeriksa'],
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

        ];
        $input = $_POST;

        $input['tgl_periksa'] = $input['tahun'] . '-' . ($input['bulan'] < 10 ? '0' . $input['bulan'] : $input['bulan']) . '-01';
      
        $isEdit = $this->getFromMiddleware('isEdit');
        $this->validateInput($input, $ruleValidator);

        unset($input['bulan'], $input['tahun']);
        if(empty($input['pemeriksa']))
            $input['pemeriksa'] = sessiondata('login', 'nama_lengkap');

        if($isEdit){
            $id = $input['id'];
            $this->db->where('id', $id)->update($input, 'periksa_lansia');

            $pemeriksaan = $this->_getlist($input['lansia']);
            response(["data" => $pemeriksaan, "message" => "Berhasil Update data pemeriksaan lansia"], 201);
        }else{
            // cek bulan
            $bulanIni = substr($input['tgl_periksa'], 0, 7);
            $ada = $this->db->select('*')->from('periksa_lansia')->where('lansia', $input['lansia'])->like('tgl_periksa', $bulanIni, 'after')->results();
            if(!empty($ada))
                response(['message' => "Pemeriksaan pada bulan " . namaBulan($input['tgl_periksa']) . ' sudah dilakukan'], 403);

            unset($input['id']);
            $input['pencatat'] = sessiondata('login', 'username');

            $this->db->insert($input, 'periksa_lansia');


            $pemeriksaan = $this->_getlist($input['lansia']);
            response(['data' => $pemeriksaan, 'message' => "Berhasil mencatat pemeriksaan lansia"], 201);
        }
    }

    function deletepemeriksaan(){
        $id = $_POST['id'];
        $idlansia = $_POST['idlansia'];
        if(!httpmethod('delete')) response("Invalid HTTP Method", 403);
        if(empty($id)) response("Request Invalid", 403);

        try {
            $this->db->where('id', $id)->delete('periksa_lansia');

            $pemeriksaan = $this->_getlist($idlansia);
            response(["data" => $pemeriksaan, "message" => "Berhasil menghapus data pemeriksaan Lansia"]);
        } catch (\Throwable $th) {
            response(['message' => 'Gagal menghapus data', 'reason' => $th->getMessage()], 500);
        }
    }
}