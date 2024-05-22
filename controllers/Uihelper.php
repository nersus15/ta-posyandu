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

        if (!file_exists(ROOT . '/views/' . $form . '.php'))
            response(['message' => 'Form ' . $form . ' Tidak ditemukan'], 404);
        else {
            $ambil_data = function($key, $default = null, $return = false) use($data) {
                $cached = (array) $data['ed'];
                $value = $default;
                
                if(isset($cached[$key]))
                    $value = $cached[$key];

                if($return) return $value;

                echo $value;
            };

            $html =  $this->addViews($form, array_merge((array) $data['sv'], ['data' => (array) $data['ed'], 'ambil_data' => $ambil_data]), true);
            $_script = '';
            if(!empty($skrip)){
                $skrip = load_script($skrip, [
                    'form_cache' => json_encode($data['ed']),
                    'form_data' => json_encode($data['sv'])
                ], true); 
                $_script = "<script " . (isset($data['sv']->skripid) ? 'id="' . $data['sv']->skripid . '"' : '') . ">" . $skrip . '</script>';  
            }

            response([
                'html' => $html . $_script
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
            $_script = load_script($skrip, [
                'form_cache' => json_encode($data['ed']),
                'form_data' => json_encode($data['sv'])
            ], true);
            
            response([
                'skrip' => '<script '. (isset($data['sv']->skripid) ? 'id="'. $data['sv']->skripid .'"'  : ''). '>' . $_script . "</script>"
            ]);
        }
    }

    function notifcenter(){
        response("OK");
    }
}