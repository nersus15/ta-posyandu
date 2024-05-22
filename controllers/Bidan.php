<?php

class Bidan extends Controller{
    /** @var Datatables */
    var $datatables;

    function list(){
        $this->load('Datatables', 'datatables');

        $query = $this->db->from('users')->where('role', 'bidan');
        $header = array(
            'id' => array('searchable' => false, 'field' => 'username'),
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
            response("Berhasil Update data Bidan " . $input['nama_lengkap'] . ' dengan username @' . $username, 201);
        }else{
            $input['password'] = password_hash($input['password'], PASSWORD_DEFAULT);
            $this->validateInput($input, $ruleValidator);
            $this->db->insert($input, 'users');
            response("Berhasil mendaftarkan Bidan " . $input['nama_lengkap'] . ' dengan username @' . $input['username'], 201);
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


    function bayi($umur = 'semua'){
        $mapUmur = array(
            'semua' => 'Semua',
            '05' => 'Umur 0-5 Bulan',
            '611' => 'Umur 6-11 Bulan',
            '1223' => 'Umur 12-23 Bulan',
            '2459' => 'Umur 24-59 Bulan',
        );
        $tabel = $this->addViews('components/datatables.responsive', array(
            'dtTitle' => 'Data Bayi (<small>' . $mapUmur[$umur] . '</small>)',
            'dtid' => 'dt-bayi',
            'head' => array(
                '', 'Nama', 'Umur', 'L/P', 'BBL', 'AKB', 'Ibu', 'Ayah', 'Tanggal Lahir', 'Alamat'
            ),
            'modal' => [
                'size' => 'modal-lg',
            ],
            'skrip' => 'dtconfig/dt_bayi', //wajib
            'skrip_data' => array('id' => 'dt-bayi'),
            'options' => array(
                'source' => 'bayi/list/' . $umur,
                'search' => 'false',
                'select' => 'multi', //false, true, multi
                'checkbox' => 'true',
                'change' => 'false',
                'dom' => 'rtip',
                'responsive' => 'true',
                'auto-refresh' => 'false',
                'deselect-on-refresh' => 'true',
            ),
            'form' => array(
                'id' => 'form-bayi',
                'path' => 'forms/tambah_anak',
                'nama' => 'Form Bayi',
                'skrip' => 'forms/form_bayi',
                'posturl' => 'bayi/save',
                'deleteurl' => 'bayi/delete',
            ),
            'data_panel' => array(
                'nama' => 'dt-bayi',
                'perpage' => 10,
                'pages' => array(1, 2, 10),
                'hilangkan_display_length' => true,
                'toolbar' => array(
                    array(
                        'tipe' => 'buttonset',
                        'tombol' => array(
                            array('tipe' => 'link', 'href' => '#', 'title' => 'Tambah', 'icon' => 'icon-plus simple-icon-paper-plane', 'class' => 'btn-outline-primary tool-add tetap'),
                            array('tipe' => 'link', 'href' => '#', 'title' => 'Update', 'icon' => 'icon-plus simple-icon-pencil', 'class' => 'btn-outline-warning tool-edit tetap'),
                            array('tipe' => 'link', 'href' => '#', 'title' => 'Hapus', 'icon' => 'icon-delete simple-icon-trash', 'class' => 'btn-outline-danger tool-delete tetap'),
                        )
                    ),
                ),
            )
        ), true);

        $data = [
            'contentHtml' => array($tabel),
            'sidebar' => 'components/sidebar.dore',
            'navbar' => 'components/navbar.dore',
            'sidebarConf' => config_sidebar(myRole(), 1)
        ];

        $this->setPageTitle('Data Bayi - ' . $mapUmur[$umur]);

        $this->addBodyAttributes(['class' => 'menu-default show-spinner']);
        $this->addResourceGroup('main', 'dore', 'datatables', 'form');
        $this->addViews('templates/dore', $data);

        $this->render();
    }
}