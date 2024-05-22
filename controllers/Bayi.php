<?php
class Bayi extends Controller{
    /** @var Datatables */
    var $datatables;

    function list(){
        $this->load('Datatables', 'datatables');
        $username = sessiondata('login', 'username');

        $query = $this->db->from('bayi')->where('pencatat', $username);
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
                    'message' => 'Harus menyertakan Nama Lengkap'
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

        $input['ttl_estimasi'] = 0;

        if ($input['ingat_ttl'] == 0) {
            $hariIni = time();
            $umur = intval($input['umur']) * (60 * 60 * 24);
            $input['tanggal_lahir'] = waktu($hariIni - $umur, MYSQL_DATE_FORMAT);
            $input['ttl_estimasi'] = '1';
        }else{
            $hariIni = date_create();
            $ttl = date_create($input['tanggal_lahir']);

            $diff = $hariIni->diff($ttl, $hariIni);
            $hari = $diff->format('%a');

            $input['umur'] = intval($hari);
        }
        unset($input['ingat_ttl']);

        $isEdit = $this->getFromMiddleware('isEdit');
        $this->validateInput($input, $ruleValidator);
        if($isEdit){
            $id = $input['id'];
            $this->db->where('username', $id)->update($input, 'bayi');
            response("Berhasil Update data Bidan " . $input['nama'] . ' dengan id ' . $id, 201);
        }else{
            $input['id'] = random(8);
            $input['pencatat'] = sessiondata('login', 'username');

            $this->db->insert($input, 'bayi');
            response("Berhasil mendaftarkan Bidan " . $input['nama'] . ' dengan id id' . $input['id'], 201);
        }
    }

    function delete(){
        $ids = $_POST['ids'];
        if(!httpmethod('delete')) response("Invalid HTTP Method", 403);
        if(empty($ids)) response("Request Invalid", 403);

        try {
            $this->db->wherein('username', $ids)->delete('users');
        } catch (\Throwable $th) {
            response(['message' => 'Gagal menghapus data', 'reason' => $th->getMessage()], 500);
        }
    }
}