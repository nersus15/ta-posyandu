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

    function bumil(){
        $tabelBidan = $this->addViews('components/datatables.responsive', array(
            'dtTitle' => 'Data Ibu Hamil',
            'dtid' => 'dt-bidan',
            'head' => array(
                '', 'Username', 'Nama', 'Alamat', 'Nomor Hp', 'Email'
            ),
            'skrip' => 'dtconfig/dt_bidan', //wajib
            'skrip_data' => array('id' => 'dt-bidan'),
            'options' => array(
                'source' => 'bidan/list',
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
                'id' => 'form-bidan',
                'path' => 'forms/tambah_bumil',
                'nama' => 'Form Bidan',
                'skrip' => 'forms/form_bidan',
                'posturl' => 'bidan/save',
                'deleteurl' => 'bidan/delete',
                'buttons' => array(
                    [ "type" => 'reset', "data" => 'data-dismiss="modal"', "text" => 'Batal', "id" => "batal", "class" => "btn btn btn-warning" ],
                    [ "type" => 'submit', "text" => 'Simpan', "id" => "simpan", "class" => "btn btn btn-primary" ]
                )
            ),
            'data_panel' => array(
                'nama' => 'dt-bidan',
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
            'contentHtml' => array($tabelBidan),
            'sidebar' => 'components/sidebar.dore',
            'navbar' => 'components/navbar.dore',
            'sidebarConf' => config_sidebar(myRole(), 1)
        ];

        $this->setPageTitle('Data Bidan');

        $this->addBodyAttributes(['class' => 'menu-default show-spinner']);
        $this->addResourceGroup('main', 'dore', 'datatables', 'form');
        $this->addViews('templates/dore', $data);

        $this->render();
    }

    function bayi($umur = 'semua'){
        $mapUmur = [
            'semua' => '',
            '05' => '0-5 Bulan',
            '611' => '6-11 Bulan',
            '1223' => '12-23 Bulan',
            '2459' => '24-59 Bulan' 
        ];
        $index = 0;
        foreach(array_keys($mapUmur) as $i => $k){
            if($umur == $k){
                $index = $i;
                break;
            }
        }
        $tabel = $this->addViews('components/datatables.responsive', array(
            'dtTitle' => 'Data Bayi' . ($umur != 'semua' ? ' (' .$mapUmur[$umur] . ')' : ''),
            'dtid' => 'dt-bayi',
            'head' => array(
                '', 'Nama', 'Umur', 'Jenis Kelamin', 'AKB', 'BBL', 'Ibu', 'Ayah', 'Tanggal Lahir', 'Alamat'
            ),
            'skrip' => 'dtconfig/dt_bayi', //wajib
            'toolbarSkrip' => 'toolbar/anak',
            'skrip_data' => array('id' => 'dt-bayi'),
            'options' => array(
                'source' => 'bayi/list/'. $umur,
                'search' => 'false',
                'select' => 'multi', //false, true, multi
                'checkbox' => 'true',
                'change' => 'false',
                'dom' => 'rtip',
                'responsive' => 'true',
                'auto-refresh' => 'false',
                'deselect-on-refresh' => 'true',
            ),
            'modal' => array(
                'size' => 'modal-lg'
            ),
            'form' => array(
                'id' => 'form-bayi',
                'path' => 'forms/tambah_anak',
                'nama' => 'Form Anak',
                'skrip' => 'forms/form_bayi',
                'posturl' => 'bayi/save',
                'deleteurl' => 'bayi/delete',
                'buttons' => array(
                    [ "type" => 'reset', "data" => 'data-dismiss="modal"', "text" => 'Batal', "id" => "batal", "class" => "btn btn btn-warning" ],
                    [ "type" => 'submit', "text" => 'Simpan', "id" => "simpan", "class" => "btn btn btn-primary" ]
                )
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
                            array('tipe' => 'link', 'href' => '#', 'title' => 'Detail Pemeriksaan', 'icon' => 'simple-icon-magnifier', 'class' => 'btn-info tool-custom-detail tetap'),
                            array('tipe' => 'link', 'href' => '#', 'title' => 'Tambah', 'icon' => 'icon-plus simple-icon-paper-plane', 'class' => 'btn-outline-primary tool-add tetap'),
                            array('tipe' => 'link', 'href' => '#', 'title' => 'Update', 'icon' => 'icon-plus simple-icon-pencil', 'class' => 'btn-outline-warning tool-edit tetap'),
                            array('tipe' => 'link', 'href' => '#', 'title' => 'Hapus', 'icon' => 'icon-delete simple-icon-trash', 'class' => 'btn-outline-danger tool-delete tetap'),
                        )
                    ),
                ),
            )
        ), true);

        $data = [
            'contentHtml' => array($tabel, '<div class="mt-4" id="detail-riwayat"></div>'),
            'sidebar' => 'components/sidebar.dore',
            'navbar' => 'components/navbar.dore',
            'sidebarConf' => config_sidebar(myRole(), 2, $index)
        ];

        $this->setPageTitle('Data Bayi'. $mapUmur[$umur]);

        $this->addBodyAttributes(['class' => 'menu-default show-spinner']);
        $this->addResourceGroup('main', 'dore', 'datatables', 'form');
        $this->addViews('templates/dore', $data);

        $this->render();
    }

    function lansia(){}
}