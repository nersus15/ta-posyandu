<?php
class Uihelper extends Controller{
    function form()
    {

        if (httpmethod())
            response(['message' => 'Ilegal akses'], 403);

        if (!isset($_GET['f']))
            response(['message' => 'File (form) kosong'], 404);
        $skrip = '';
        if(isset($_GET['s']) && !empty($_GET['s']))
            $skrip = $_GET['s'];
            
        $form = $_GET['f'];
        $data = array(
            'ed' => [],
            'sv' => []
        );
        if(isset($_GET['ed']))
            $data['ed'] = json_decode($_GET['ed']);
        if(isset($_GET['sv']))
            $data['sv'] = json_decode($_GET['sv']);

        if (!file_exists(ROOT . 'views/' . $form . '.php'))
            response(['message' => 'Form ' . $form . ' Tidak ditemukan'], 404);
        else {
            $html =  $this->addViews($form, $data, true);
            
            if(!empty($skrip)){
                $skrip = load_script($skrip, [
                    'form_cache' => json_encode($data['ed']),
                    'form_data' => json_encode($data['sv'])
                ], true);    
            }
            response([
                'html' => $html . "<script id='" .$data['sv']->skripid. "'>" . $skrip . '</script>'
            ]);
        }
    }
    function skrip()
    {
        if (httpmethod())
            response(['message' => 'Ilegal akses'], 403);
            
        $skrip = '';
        if(isset($_GET['s']) && !empty($_GET['s']))
            $skrip = $_GET['s'];
        if(empty($skrip)) response(['skrip' => '']);
        
        if (!file_exists(ROOT . '/assets/js/' . $skrip . '.js'))
            response(['message' => 'Form ' . $skrip . ' Tidak ditemukan'], 404);
        else {
            $data = array(
                'ed' => [],
                'sv' => []
            );
            if(isset($_GET['ed']))
                $data['ed'] = json_decode($_GET['ed']);
            if(isset($_GET['sv']))
                $data['sv'] = json_decode($_GET['sv']);

            response([
                'skrip' => "<script id='". $data['sv']->skripid ."'>" . load_script($skrip,[
                    'form_cache' => json_encode($data['ed']),
                    'form_data' => json_encode($data['sv'])
                ], true) . "</script>"
            ]);
        }
    }

    function notifcenter(){
        response("OK");
    }
}