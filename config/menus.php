<?php
$config['menus'] = array(
    'admin' => array(
        'menus' => array(
            array('text' => 'Dashboard', 'icon' => 'iconsmind-Home', 'link' => base_url('dashboard')),
            array('text' => 'Bidan', 'icon' => 'iconsmind-Nurse', 'link' => base_url('data/bidan')),
            array('text' => 'Kader', 'icon' => 'simple-icon-user', 'link' => base_url('data/kader')),
        )
    ),

    'bidan' => array(
        'menus' => array(
            array('text' => 'Dashboard', 'icon' => 'iconsmind-Home', 'link' => base_url('dashboard')),
            array('text' => 'Ibu Hamil', 'icon' => 'simple-icon-user-female', 'link' => base_url('kader/bumil')),
        )
    ),
    'kader' => array(
        'menus' => array(
            array('text' => 'Dashboard', 'icon' => 'iconsmind-Home', 'link' => base_url('dashboard')),
            array('text' => 'Ibu Hamil', 'icon' => 'simple-icon-user-female', 'link' => base_url('kader/bumil')),
            array('text' => 'Bayi/Balita', 'link' => '#bayi', 'icon' => 'iconsmind-Baby', 'sub' => array(
                array('text' => 'Semua', 'link' => base_url('kader/bayi')),
                array('text' => 'Umur 0-5 Bulan', 'link' => base_url('kader/bayi/05')),
                array('text' => 'Umur 6-11 Bulan', 'link' => base_url('kader/bayi/611')),
                array('text' => 'Umur 12-23 Bulan', 'link' => base_url('kader/bayi/1223')),
                array('text' => 'Umur 24-59 Bulan', 'link' => base_url('kader/bayi/2459')),
            )),
            array('text' => 'Lansia', 'icon' => 'iconsmind-Female', 'link' => base_url('kader/lansia')),
        )
    )
);