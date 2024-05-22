<?php

class Kader extends Controller{
    /** @var Datatables */
    var $datatables;

    function list(){
        $this->load('Datatables', 'datatables');

        $query = $this->db->from('users')->where('role', 'kader');
        $header = array(
            'id' => array('searchable' => false, 'field' => 'username'),
            'nama' => array('searchable' => true, 'field' => 'nama_lengkap'),
            'kelamin' => array('searchable' => true, 'field' => 'kelamin'),
            'alamat' => array('searchable' => true),
            'email' => array('searchable' => true),
            'hp' => array('searchable' => true, 'field' => 'no_hp'),
        );
        $this->datatables->setHeader($header);
        $this->datatables->setQuery($query);

        response($this->datatables->getData());
    }

    function save(){
        if(!httpmethod('post') && !httpmethod('update'))
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
            response("Berhasil Update data Kader " . $input['nama_lengkap'] . ' dengan username @' . $username, 201);
        }else{
            $input['password'] = password_hash($input['password'], PASSWORD_DEFAULT);
            $this->validateInput($input, $ruleValidator);
            $this->db->insert($input, 'users');
            response("Berhasil mendaftarkan Kader " . $input['nama_lengkap'] . ' dengan username @' . $input['username'], 201);
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