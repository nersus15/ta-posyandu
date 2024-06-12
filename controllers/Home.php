<?php
class Home extends Controller
{
    function index()
    {

        $tahunIni = date('Y');
        $bulanIni = waktu(null, MYSQL_DATE_FORMAT);

        if (is_login('kader')) {
            $dataBumil =  $this->db/* ->where('pencatat', myRole()) */->from('bumil')->results();
            $dataPemeriksaan = $this->db->from('kunjungan_bumil')->where('pencatat', myRole())->results();
            $dataAnak = $this->db->from('bayi')->where('pencatat', myRole())->num_rows();
            $periksaAnak = $this->db->from('kunjungan_anak')->where('pencatat', myRole())->num_rows();
            $dataLansia = $this->db->from('lansia')->where('pencatat', myRole())->num_rows();
            $periksaLansia = $this->db->from('periksa_lansia')->where('pencatat', myRole())->num_rows();


            $dataDashboard = [
                'jmlbumil' => count($dataBumil),
                'periksa' => count($dataPemeriksaan),
                'jml_bayi' => $dataAnak,
                'periksa_anak' => $periksaAnak,
                'jml_lansia' => $dataLansia,
                'periksa_lansia' => $periksaLansia
            ];
        } elseif (is_login('bidan')) {
            $dataBumil =  $this->db/* ->where('pencatat', myRole()) */->from('bumil')->results();
            $dataPemeriksaan = $this->db->from('kunjungan_bumil')->where('pencatat', myRole())->results();
            $dataDashboard = [
                'jmlbumil' => count($dataBumil),
                'periksa' => count($dataPemeriksaan)
            ];
        } elseif (is_login('admin')) {
            $tmp = $this->db->from('users')->select('role, COUNT(*) jml')->group_by('role')->results('object');
            $dataUser = [
                'admin' => 0,
                'bidan' => 0,
                'kader' => 0
            ];
            foreach ($tmp as $v) {
                $dataUser[$v->role] += $v->jml;
            }
            $dataDashboard = [
                'bidan' => $dataUser['bidan'],
                'kader' => $dataUser['kader'],
            ];
        }

        $this->addResourceGroup('main', 'dore');

        $this->addBodyAttributes(['class' => 'menu-hidden show-spinner']);
        $data = array(
            'content' => array('pages/dashboard/' . myRole()),
            'data_content' => $dataDashboard,
            'sidebar' => 'components/sidebar.dore',
            'navbar' => 'components/navbar.dore',
            'sidebarConf' => config_sidebar(myRole())
        );

        $this->setPageTitle('Dashboard');
        $this->addViews('templates/dore', $data);
        $this->render();
    }

    function profile()
    {
        $this->addResourceGroup('main', 'form', 'dore');

        $this->addBodyAttributes(['class' => 'menu-hidden show-spinner']);
        $data = array(
            'content' => array('pages/profile'),
            'data_content' => sessiondata(),
            'sidebar' => 'components/sidebar.dore',
            'navbar' => 'components/navbar.dore',
            'sidebarConf' => config_sidebar(myRole())
        );

        $this->add_stylesheet(
            [
                'src' => 'css/pages/profile.css',
                'type' => 'file',
                'pos' => 'head'
            ],
        );

        $this->setPageTitle('Profile');
        $this->addViews('templates/dore', $data);
        $this->render();
    }
}
