<?php

class Bidan extends Controller{
    /** @var Datatables */
    var $datatables;

    function list(){
        $this->load('Datatables', 'datatables');

        $query = $this->db->select('*')->from('users')->where('role', 'bidan');
        $header = array(
            'username' => array('searchable' => true),
            'nama' => array('searchable' => true, 'field' => 'nama_lengkap'),
            'alamat' => array('searchable' => true),
            'email' => array('searchable' => true),
            'hp' => array('searchable' => true, 'field' => 'no_hp'),
        );
        $this->datatables->setHeader($header);
        $this->datatables->setQuery($query);

        response($this->datatables->getData());
    }

    function save(){
        if(!httpmethod('post'))
            response("Ilegal Method", 403);

        $ruleValidator = [
            'username' => array(
                [
                    'rule' => 'required',
                    'message' => 'Username harus diisi!'
                ],
            ),
            'password' => array(
                [
                    'rule' => 'required',
                    'message' => 'Password harus diisi!'
                ],
            ),
            'nama_lengkap' => array(
                [
                    'rule' => 'required',
                    'message' => 'Harus menyertakan Nama Lengkap'
                ]
            )
        ];
        $input = $_POST;

        $isEdit = $this->getFromMiddleware('isEdit');
        if($isEdit){
            unset($ruleValidator['password']);
            $this->validateInput($input, $ruleValidator);

            if(!empty($input['password']))
                $input['password'] = password_hash($input['password'], PASSWORD_DEFAULT);
            else
                unset($input['password']);
            
            $username = $input['username'];
            unset($input['username']);

            $this->db->where('username', $username)->update($input, 'users');
            response("Berhasil Update data Bidan " . $input['nama_lengkap'] . ' dengan username @' . $username);
        }else{
            $this->validateInput($input, $ruleValidator);
            $this->db->insert($input, 'users');
            response("Berhasil mendaftarkan Bidan " . $input['nama_lengkap'] . ' dengan username @' . $input['username']);
        }


    }
}