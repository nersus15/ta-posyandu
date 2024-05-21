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
                'posturl' => 'bidan/save',
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
}
