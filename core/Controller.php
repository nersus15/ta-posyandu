<?php

class Controller
{
    private $views = [];
    private $title = 'Untitled';
    private $params = [];
    private static $instance;
    private $resourceGroups = [];
    private $skippedResourceGroups = [];
    private $configs = [];
    private $bodyAttributes = '';
    public $db;

    public function __construct()
    {
        self::$instance =& $this;
        if (class_exists('qbuilder'))
            $this->db = new qbuilder();
        else {
            require_once ROOT . "/functions/qbuilder.php";
            $this->db = new qbuilder();
        }
    }

    public static function &_getInstance()
    {
        return self::$instance;
    }

    function validateInput($inputs, $rules){
        foreach($rules as $k => $rule){
            if(!isset($inputs[$k])){
                response("$k Tidak dikirim", 403);
            }
		    $result = $this->_validateInput($inputs[$k], $rule);
            if($result !== true){
                response($result, 403);
            }
		}
    }
    private function _validateInput($input, $rules){
        foreach($rules as $r){
			$message = isset($r['message']) ? $r['message'] : 'Invalid!!';
			$nilai = $input;

			if(isset($r['converterNilai']) && is_callable($r['converterNilai']))
				$nilai = $r['converterNilai']($input);

			if(is_callable($r['rule'])){
				return $r['rule']($input);
			}elseif($r['rule'] == 'required'){
				if(empty($nilai))
					return $message;
			}elseif($r['rule'] == 'minimal'){
				if($nilai < $r['min'])	
					return $message;
			}elseif($r['rule'] == 'maximal'){
				if($nilai > $r['max'])	
					return $message;
			}elseif($r['rule'] == 'regex'){
				if(empty($nilai) || !preg_match($r['pattern'], $input))
					return $message;
			}elseif($r['rule'] == 'number'){
                if(!is_numeric($nilai))
                    return $message;
            }
		}

		return true;
    }
    /**
     * @return Array
     */
    function getFromMiddleware($key = null){
        $data = [];

        if(isset($_SERVER['REQUEST_HEADER'])){
           $data = isset($_SERVER['REQUEST_HEADER']['data']) ? (!empty($key) ?  $_SERVER['REQUEST_HEADER']['data'][$key] :  $_SERVER['REQUEST_HEADER']['data']) : [];
        }

        return $data;
    }

    function addRawHtml($html){
        $this->views[] = $html;
    }

    function setPageTitle($title){
        $this->title = $title;
    }

    /**
     * @param $attributes Array or String
     */
    function addBodyAttributes($attributes){
        if(is_array($attributes)){
            foreach($attributes as $key => $val){
                $this->bodyAttributes .= $key . '=' . '"' . $val . '"';
            }
        }else{
            $this->bodyAttributes = $attributes;
        }

    }

    function addViews($path, $data = null, $return = false)
    {
        if (!file_exists(ROOT . "/views/$path.php"))
            throw new Error("File " . ROOT . "/views/$path.php tidak ditemukan");

        $view = null;
        ob_start();
        if (!empty($data))
            extract($data);

        include_once ROOT . "/views/$path.php";
        $view = ob_get_contents();
        ob_end_clean();

        if ($return) return $view;

        $this->views[] = $view;
    }

    function add_javascript($js)
    {
        if (isset($js['pos'])) {
            $this->params['extra_js'][] = $js;
        } else {
            foreach ($js as $j) {
                $this->params['extra_js'][] = $j;
            }
        }
    }

    function add_cachedJavascript($js, $type = 'file', $pos = "body:end", $data = array())
    {
        try {
            if ($type == 'file') {
                ob_start();
                if (!empty($data))
                    extract($data);


                include_once ROOT . '/assets/js/' . $js . '.js';
            }

            $this->params['extra_js'][] = array(
                'script' => $type == 'file' ? ob_get_contents() : $js,
                'type' => 'inline',
                'pos' => 'body:end'
            );
            if ($type == 'file')
                ob_end_clean();
        } catch (\Throwable $th) {
            print_r($th);
        }
    }
    function add_cachedStylesheet($css, $type = 'file', $pos = 'head', $data = array())
    {
        if (!empty($data)) {
            foreach ($data as $k => $v) {
                $this->params['var'][$k] = $v;
            }
        }
        if ($type == 'file') {
            ob_start();
            if (!empty($data))
                extract($data);
            try {
                include_once ROOT . '/assets/css/' . $css . '.css';
            } catch (\Throwable $th) {
                print_r($th);
            }
        }

        $this->params['extra_css'][] = array(
            'style' => $type == 'file' ? ob_get_contents() : $css,
            'type' => 'inline',
            'pos' => $pos
        );
        if ($type == 'file')
            ob_end_clean();
    }
    function add_stylesheet($css)
    {
        if (isset($css['pos'])) {
            $this->params['extra_css'][] = $css;
        } else {
            foreach ($css as $c) {
                $this->params['extra_css'][] = $c;
            }
        }
    }

    function addResourceGroup(...$groups)
    {
        if (is_array($groups)) {
            $this->resourceGroups = array_merge($this->resourceGroups, $groups);
        } else {
            $this->resourceGroups[] = $groups;
        }
    }

    function removeResourceGroup($groups, $part = null)
    {
        if (is_array($groups)) {
            foreach ($groups as $group) {
                if (!empty($part)) {
                    if (is_array($part) && isset($part[$group])) {
                        $this->skippedResourceGroups[$group] = $part[$group];
                    } elseif (!is_array($part)) {
                        $this->skippedResourceGroups[$group] = [$part];
                    }
                } else {
                    $this->skippedResourceGroups[$group] = '*';
                }
            }
        } else {
            if (!empty($part)) {
                if (is_array($part) && isset($part[$groups])) {
                    $this->skippedResourceGroups[$groups] = $part[$groups];
                } elseif (!is_array($part)) {
                    $this->skippedResourceGroups[$groups] = [$part];
                }
            } else {
                $this->skippedResourceGroups[$groups] = '*';
            }
        }
    }

    function render()
    {
        // Kumpulkan Resource Groups
        $this->_assembleResourcesGroups();

        if(empty($this->bodyAttributes)){
            $this->bodyAttributes = 'class = ""';
        }
        $head = '<!DOCTYPE html>
            <html lang="id">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>' . $this->title . '</title>
            <script>
                var path = "' . BASEURL  . '";
            </script>' . $this->__getResource('head') . '</head>
            <body id="app-container" '. $this->bodyAttributes .' >
            <div class="c-overlay">
                <div class="c-overlay-text">Loading</div>
            </div>';


        $footer = $this->__getResource('body:end') . '<script> $(document).ready(function(){if($.dore) $("body").dore();}); '  . ' </script></body> </html>';
        echo $head;
        foreach ($this->views as $view) {
            echo $view;
        }
        echo $footer;
    }

    private function _assembleResourcesGroups()
    {
        require_once ROOT . '/config/themes.php';
        $_config = $config['themes'];
        $_skipped = $this->skippedResourceGroups;
        if (is_array($this->resourceGroups)) {

            foreach ($this->resourceGroups as $group) {
                if (!isset($_config[$group])) continue;

                if (isset($_skipped[$group]) && $_skipped[$group] == '*') continue;

                foreach ($_config[$group] as $type => $value) {
                    if (!empty($_skipped[$group])) {
                        $adaFile = array_filter($_skipped[$group], function ($arr) {
                            return !in_array($arr, ['js', 'css']);
                        });

                        if (!empty($adaFile)) {
                            foreach ($_skipped[$group] as $_skippedItem) {
                                if (!in_array($_skippedItem, ['js', 'css'])) {
                                    $index = -1;
                                    foreach ($value as $i => $item) {
                                        if ($item['src'] == $_skippedItem) {
                                            $index = $i;
                                            break;
                                        }
                                    }

                                    if ($index != -1) {
                                        unset($value[$index]);
                                    }
                                }
                            }
                        }
                    }

                    if ($type == 'css') {
                        if (isset($_skipped[$group]) && in_array('css', $_skipped[$group])) continue;
                        $this->params['extra_css'] = isset($this->params['extra_css']) ? array_merge($this->params['extra_css'], $value) : $value;
                    } elseif ($type == 'js') {
                        if (isset($_skipped[$group]) && in_array('js', $_skipped[$group])) continue;
                        $this->params['extra_js'] = isset($this->params['extra_js']) ? array_merge($this->params['extra_js'], $value) : $value;
                    }
                }
            }
        }
    }


    private function __getResource($pos = 'head')
    {
        // echo json_encode($this->params)
        $res = '';
        if (isset($this->params['extra_js']) && !empty($this->params['extra_js'])) {
            foreach ($this->params['extra_js'] as $js) {
                if ($js['pos'] == $pos && (!isset($js['type']) || $js['type'] == 'file')) {
                    if (strpos($js['src'], 'http') == false)
                        $js['src'] = BASEURL . ('static/' . $js['src']);
                    $res .= '<script src="' . $js['src'] . '"></script>';
                } elseif ($js['pos'] == $pos && $js['type'] == 'inline') {
                    $res .= '<script>' . $js['script'] . '</script>';
                }
            }
        }

        if (isset($this->params['extra_css']) && !empty($this->params['extra_css'])) {
            foreach ($this->params['extra_css'] as $css) {
                if ($css['pos'] == $pos && (!isset($css['type']) || $css['type'] == 'file')) {
                    if (strpos($css['src'], 'http') == false)
                        $css['src'] = BASEURL .('static/' . $css['src']);
                    $res .= '<link rel="stylesheet" href="' . $css['src'] . '"></link>';
                } elseif ($css['pos'] == $pos && $css['type'] == 'inline') {
                    $res .= '<style>' . $css['style'] . '</style>';
                }
            }
        }

        return $res;
    }

    /**
     * @param $fileName 
     */
    function load($fileName, $objectName = null, $constructArgument = [], $dir = null){

        $_dir = empty($dir) ? '/functions/' : $dir;

        if(file_exists(ROOT . $_dir . $fileName . '.php' )){
            include_once  ROOT . $_dir . $fileName . '.php';

            $clz = empty($objectName) ? strtolower($fileName) : $objectName;

            if(!empty($constructArgument)){

            }
            $controller =& get_instance();
            $fileName = ucfirst($fileName);
            $controller->{$clz} = new $fileName();
        }
    }
    function loadConfig($configFiles){
        if(is_array($configFiles)){
            foreach($configFiles as $file){
                require_once ROOT . '/config/' . $file . '.php';
                $this->configs[$file] = $config;
            }
        }else{
            require_once ROOT . '/config/' . $configFiles . '.php';
            $this->configs[$configFiles] = $config;
        }
    }

    function configItem($item){
        if(empty($this->configs)) return null;

        foreach ($this->configs as $key => $value) {
            if(isset($value[$item])) return $value[$item];
        }
        return null;
    }
    
}
