<?php 
    class Home extends Controller{
        function index(){
            $this->addResourceGroup('main', 'dore');

            $this->addBodyAttributes(['class' => 'menu-hidden show-spinner']);
            $data = array(
                'sidebar' => 'components/sidebar.dore',
                'navbar' => 'components/navbar.dore',
                'sidebarConf' => config_sidebar(myRole())
            );
            $this->setPageTitle('Dashboard');
            $this->addViews('templates/dore', $data);
            $this->render();
        }
    }