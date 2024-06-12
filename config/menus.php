<?php
$config['menus'] = array(
    'admin' => array(
        'menus' => array(
            array('text' => 'Dashboard', 'icon' => 'iconsmind-Home', 'link' => base_url('dashboard')),
            array('text' => 'Bidan', 'icon' => 'iconsmind-Nurse', 'link' => base_url('data/bidan')),
            array('text' => 'Kader', 'icon' => 'simple-icon-user', 'link' => base_url('data/kader')),
            array('text' => 'Ibu Hamil', 'icon' => 'simple-icon-user-female', 'link' => base_url('data/bumil')),
            array('text' => 'Bayi/Balita', 'link' => '#bayi', 'icon' => 'iconsmind-Baby', 'sub' => array(
                array('text' => 'Semua', 'link' => base_url('data/bayi')),
                array('text' => 'Umur 0-5 Bulan', 'link' => base_url('data/bayi/05')),
                array('text' => 'Umur 6-11 Bulan', 'link' => base_url('data/bayi/611')),
                array('text' => 'Umur 12-23 Bulan', 'link' => base_url('data/bayi/1223')),
                array('text' => 'Umur 24-59 Bulan', 'link' => base_url('data/bayi/2459')),
            )),
            array('text' => 'Lansia', 'icon' => 'iconsmind-Female', 'link' => base_url('data/lansia')),
        )
    ),

    'bidan' => array(
        'menus' => array(
            array('text' => 'Dashboard', 'icon' => 'iconsmind-Home', 'link' => base_url('dashboard')),
            array('text' => 'Ibu Hamil', 'icon' => 'simple-icon-user-female', 'link' => base_url('kader/bumil')),
            /* array('text' => 'Laporan', 'icon' => 'iconsmind-File-Pie', 'link' => '#report', 'sub' => array(
                array('text' => 'Ibu Hamil', 'link' => base_url('report/bumil')),
                array('text' => 'Bayi/Balita', 'link' => base_url('report/bayi')),
                array('text' => 'Lansia', 'link' => base_url('report/lansia')),
            ))
 */        )
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
            /* array('text' => 'Laporan', 'icon' => 'iconsmind-File-Pie', 'link' => '#report', 'sub' => array(
                array('text' => 'Ibu Hamil', 'link' => base_url('report/bumil')),
                array('text' => 'Bayi/Balita', 'link' => base_url('report/bayi')),
                array('text' => 'Lansia', 'link' => base_url('report/lansia')),
            )) */
        )
    )
);