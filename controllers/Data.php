<?php

class Data extends Controller
{
    function bidan(){
        $tabelBidan = $this->addViews('components/datatables.responsive', array(
            'dtTitle' => 'Data Bidan',
            'dtid' => 'dt-bidan',
            'head' => array(
                '', 'Username', 'Nama', 'Alamat', 'Nomor Hp', 'Email'
            ),
            'skrip' => 'dtconfig/dt_bidan', //wajib
            'skrip_data' => array('id' => 'dt-bidan'),
            'toolbarSkrip' => 'toolbar/user',
            'toolbarVar' => array(
                'jenis' => 'bidan',
                'urlexport' => 'report/bidan'
            ),
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
                'path' => '',
                'nama' => 'Form Bidan',
                'skrip' => 'forms/form_bidan',
                'formGenerate' => array(
                    [
                        'type' => 'hidden', 'name' => '_http_method', 'id' => 'method',
                    ],
                    [
                        'type' => 'hidden', 'name' => 'role', 'id' => 'role', 'value' => 'bidan',
                    ],
                    [
                        'type' => 'hidden', 'name' => 'kelamin', 'id' => 'kelamin', 'value' => 'P',
                    ],
                    [
                        "label" => 'Username', "placeholder" => 'Username',
                        "type" => 'text', "name" => 'username', "id" => 'username', 'attr' => 'data-rule-required="true"'
                    ],
                    [
                        "label" => 'Nama Lengkap', "placeholder" => 'Nama Lengkap',
                        "type" => 'text', "name" => 'nama_lengkap', "id" => 'nama_lengkap', 'attr' => 'data-rule-required="true"'
                    ],
                    [
                        "label" => 'Nomor Hp', 'placeholder' => 'Masukkan Nomor Hp', 'type' => 'text', 'name' => 'no_hp', 'id' => 'no_hp', 'attr' => 'data-rule-required = "true" data-rule-digits="true" max-length="13"'
                    ],
                    [
                        "label" => 'Email', 'placeholder' => 'Masukkan Email', 'type' => 'email', 'name' => 'email', 'id' => 'email', 'attr' => 'data-rule-required = "true"'
                    ],
                    [
                        "label" => 'Alamat', 'placeholder' => 'Masukkan Alamat', 'type' => 'textarea', 'name' => 'alamat', 'id' => 'alamat', 'attr' => 'data-rule-required = "true"'
                    ],
                    [
                        "label" => 'Password', 'placeholder' => 'Masukkan Password', 'type' => 'password', 'name' => 'password', 'id' => 'password', 'attr' => 'data-rule-required = "true"'
                    ],
                ),
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
                            array('tipe' => 'link', 'href' => '#', 'title' => 'Update', 'icon' => 'icon-plus simple-icon-pencil', 'class' => 'btn-outline-warning tool-edit tetap satu'),
                            array('tipe' => 'link', 'href' => '#', 'title' => 'Hapus', 'icon' => 'icon-delete simple-icon-trash', 'class' => 'btn-outline-danger tool-delete tetap multi'),
                            array('tipe' => 'link', 'href' => '#', 'title' => 'Export', 'icon' => 'simple-icon-printer', 'class' => 'btn-outline-secondary tool-custom-export tetap'),
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

        $this->setPageTitle('Data Bidan Desa Senyiur');

        $this->addBodyAttributes(['class' => 'menu-default show-spinner']);
        $this->addResourceGroup('main', 'dore', 'datatables', 'form');
        $this->addViews('templates/dore', $data);

        $this->render();
    }

    function kader(){
        $tabel = $this->addViews('components/datatables.responsive', array(
            'dtTitle' => 'Data Kader',
            'dtid' => 'dt-kader',
            'head' => array(
                '', 'Username', 'Nama', 'Jenis Kelamin', 'Alamat', 'Nomor Hp', 'Email'
            ),
            'skrip' => 'dtconfig/dt_kader', //wajib
            'skrip_data' => array('id' => 'dt-kader', 'role' => myRole()),
            'toolbarSkrip' => 'toolbar/user',
            'toolbarVar' => array(
                'jenis' => 'kader',
                'urlexport' => 'report/kader'
            ),
            'options' => array(
                'source' => 'kader/list',
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
                'id' => 'form-kader',
                'path' => '',
                'nama' => 'Form Kader',
                'skrip' => 'forms/form_kader',
                'formGenerate' => array(
                    [
                        'type' => 'hidden', 'name' => '_http_method', 'id' => 'method',
                    ],
                    [
                        'type' => 'hidden', 'name' => 'role', 'id' => 'role', 'value' => 'kader',
                    ],
                    [
                        "label" => 'Username', "placeholder" => 'Username',
                        "type" => 'text', "name" => 'username', "id" => 'username', 'attr' => 'data-rule-required="true"'
                    ],
                    [
                        "label" => 'Nama Lengkap', "placeholder" => 'Nama Lengkap',
                        "type" => 'text', "name" => 'nama_lengkap', "id" => 'nama_lengkap', 'attr' => 'data-rule-required="true"'
                    ],
                    [
                        "label" => 'Nomor Hp', 'placeholder' => 'Masukkan Nomor Hp', 'type' => 'text', 'name' => 'no_hp', 'id' => 'no_hp', 'attr' => 'data-rule-required = "true" data-rule-digits="true" max-length="13"'
                    ],
                    [
                        "label" => 'Email', 'placeholder' => 'Masukkan Email', 'type' => 'email', 'name' => 'email', 'id' => 'email', 'attr' => 'data-rule-required = "true"'
                    ],
                    [
                        "label" => 'Alamat', 'placeholder' => 'Masukkan Alamat', 'type' => 'textarea', 'name' => 'alamat', 'id' => 'alamat', 'attr' => 'data-rule-required = "true"'
                    ],
                    [
                        'type' => 'custom', 
                        'text' => '<div class="col-12"><label>Jenis Kelamin</label></div>
                        <div class="mb-4">
                            <div class="custom-control custom-radio col-12">
                                <input type="radio" id="kelamin-l" value="L" name="kelamin" class="custom-control-input">
                                <label class="custom-control-label" for="kelamin-l">Laki-laki</label>
                            </div>
                            <div class="custom-control custom-radio col-12">
                                <input value="P" type="radio" id="kelamin-p" name="kelamin" class="custom-control-input">
                                <label class="custom-control-label" for="kelamin-p">Perempuan</label>
                            </div>
                        </div>'
                    ],
                    [
                        "label" => 'Password', 'placeholder' => 'Masukkan Password', 'type' => 'password', 'name' => 'password', 'id' => 'password', 'attr' => 'data-rule-required = "true"'
                    ],
                ),
                'posturl' => 'kader/save',
                'deleteurl' => 'kader/delete',
                'buttons' => array(
                    [ "type" => 'reset', "data" => 'data-dismiss="modal"', "text" => 'Batal', "id" => "batal", "class" => "btn btn btn-warning" ],
                    [ "type" => 'submit', "text" => 'Simpan', "id" => "simpan", "class" => "btn btn btn-primary" ]
                )
            ),
            'data_panel' => array(
                'nama' => 'dt-kader',
                'perpage' => 10,
                'pages' => array(1, 2, 10),
                'hilangkan_display_length' => true,
                'toolbar' => array(
                    array(
                        'tipe' => 'buttonset',
                        'tombol' => array(
                            array('tipe' => 'link', 'href' => '#', 'title' => 'Tambah', 'icon' => 'icon-plus simple-icon-paper-plane', 'class' => 'btn-outline-primary tool-add tetap'),
                            array('tipe' => 'link', 'href' => '#', 'title' => 'Update', 'icon' => 'icon-plus simple-icon-pencil', 'class' => 'btn-outline-warning tool-edit tetap satu'),
                            array('tipe' => 'link', 'href' => '#', 'title' => 'Hapus', 'icon' => 'icon-delete simple-icon-trash', 'class' => 'btn-outline-danger tool-delete tetap multi'),
                            array('tipe' => 'link', 'href' => '#', 'title' => 'Export', 'icon' => 'simple-icon-printer', 'class' => 'btn-outline-secondary tool-custom-export tetap'),
                        )
                    ),
                ),
            )
        ), true);

        $data = [
            'contentHtml' => array($tabel),
            'sidebar' => 'components/sidebar.dore',
            'navbar' => 'components/navbar.dore',
            'sidebarConf' => config_sidebar(myRole(), 2)
        ];

        $this->setPageTitle('Data Kader');

        $this->addBodyAttributes(['class' => 'menu-default show-spinner']);
        $this->addResourceGroup('main', 'dore', 'datatables', 'form');
        $this->addViews('templates/dore', $data);

        $this->render();
    }

    function bumil(){
        $tabel = $this->addViews('components/datatables.responsive', array(
            'dtTitle' => 'Data Ibu Hamil',
            'dtid' => 'dt-bumil',
            'head' => array(
                '','Nama Pencatat', 'Nama', 'Nama Suami', 'Tanggal Lahir', 'Alamat Domisili', 'Alamat', 'Pendidikan', 'Pekerjaan', 'Agama'
            ),
            'toolbarSkrip' => 'toolbar/bumil',
            'toolbarVar' => array(
                'role' => myRole()
            ),
            'skrip' => 'dtconfig/dt_bumil', //wajib
            'skrip_data' => array('id' => 'dt-bumil', 'role' => myRole()),
            'options' => array(
                'source' => 'bumil/list',
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
                'size' => 'modal-lg',
            ),
            'data_panel' => array(
                'nama' => 'dt-bumil',
                'perpage' => 10,
                'pages' => array(1, 2, 10),
                'hilangkan_display_length' => true,
                'toolbar' => array(
                    array(
                        'tipe' => 'buttonset',
                        'tombol' => array(
                            array('tipe' => 'link', 'href' => '#', 'title' => 'Detail Pemeriksaan', 'icon' => 'simple-icon-magnifier', 'class' => 'btn-info tool-custom-detail tetap satu'),
                            array('tipe' => 'link', 'href' => '#', 'title' => 'Export', 'icon' => 'simple-icon-printer', 'class' => 'btn-outline-secondary tool-custom-export tetap'),
                        )
                    ),
                ),
            )
        ), true);

        $data = [
            'contentHtml' => array($tabel, '<div class="mt-4" id="detail-riwayat"></div>'),
            'sidebar' => 'components/sidebar.dore',
            'navbar' => 'components/navbar.dore',
            'sidebarConf' => config_sidebar(myRole(), 3)
        ];

        $this->setPageTitle('Data Ibu Hamil');

        $this->addBodyAttributes(['class' => 'menu-default show-spinner']);
        $this->addResourceGroup('main', 'dore', 'datatables', 'form');
        $this->addViews('templates/dore', $data);

        $this->render();
    }

    function lansia(){
        require_once ROOT . '/controllers/Kader.php';
        $kader = new Kader();
        $kader->lansia();
    }

    function bayi($umur = 'semua'){
        require_once ROOT . '/controllers/Kader.php';
        $kader = new Kader();
        $kader->bayi($umur);
    }
}
