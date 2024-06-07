<?php
require_once 'vendor/autoload.php';

use MatthiasMullie\Minify\JS;


/** CONSTANT */
define('MYSQL_TIMESTAMP_FORMAT', 'Y-m-d H:i:s');
define('MYSQL_DATE_FORMAT', 'Y-m-d');





function redirect($path = null)
{
    header('Location:' .  base_url($path));
}

function staticUrl($path = null)
{
    return base_url('static/' . (empty($path) ? '' : $path));
}
function parseUrl($segment = true)
{
    $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

    $url = str_replace(BASEURL, '', $url);
    // readQueryParams($url);
    $offsetQueryParams = strpos($url, '?');
    if($offsetQueryParams !== false && strpos($url, '=') != false){
        $url = substr($url, 0, $offsetQueryParams);
    }

    if ($segment)
        return empty($url) ? $url : explode('/', $url);
    else
        return $url;
}

function readQueryParams(&$url){
    $offsetQueryParams = strpos($url, '?');
    if($offsetQueryParams !== false && strpos($url, '=') != false){
        $tmp = $url;
        $url = substr($url, 0, $offsetQueryParams);

        // ASSIGN QUERY PARAMS TO $_GET VARIABLE
        $qParamsString = substr($tmp, $offsetQueryParams + 1);
        $qParamsString = urldecode($qParamsString);
        unset($tmp);
        $_qparams = explode('&', $qParamsString);
        foreach($_qparams as $key_value){
            $__ = explode('=', $key_value);
            $_GET[$__[0]] = $__[1];
        }
    }
}

function sessiondata($index = 'login', $kolom = null)
{
    $data = isset($_SESSION[$index]) ?  $_SESSION[$index] : [];
    return empty($kolom) ? $data : (isset($data[$kolom]) ? $data[$kolom] : null);
}
function namaBulan($key, $reverse = false){
    $mapBulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    $returnValue = null;
    if($reverse){
        foreach($mapBulan as $k => $bulan){
            if(strtolower($key) == strtolower($bulan)){
                $returnValue = $k + 1;
                break;
            }
        }
    }else{
        $key = intval($key) - 1;
        $returnValue = isset($mapBulan[$key]) ? $mapBulan[$key] : null;
    }

    return $returnValue;
}
function response($message = '', $code = 200, $type = 'succes', $format = 'json')
{
    http_response_code($code);
    $responsse = array();
    if (!in_array($code, [200, 201]))
        $type = 'Error';

    if (is_object($message))
        $message = (array) $message;
    if (is_string($message) || is_int($message) || is_bool($message))
        $responsse['message'] = $message;
    else
        $responsse = $message;

    if (!isset($message['type']))
        $responsse['type'] = $type;
    else
        $responsse['type'] = $message['type'];

    if ($code != 200 && $format == 'json')
        header("message: " . json_encode($responsse));

    if ($format == 'json') {
        header('Content-Type: application/json');
        echo json_encode($responsse);
    } elseif ($format == 'html') {
        echo '<script> var path = "' . base_url() . '"</script>';
        echo $responsse['message'];
    }
    exit();
}
function base_url($path = null)
{
    return empty($path) ? BASEURL : BASEURL . $path;
}
function assets_url($path = null)
{
    return ROOT . '/assets/' . (empty($path) ? '' : $path);
}
function include_view($path, $data = null)
{
    if (is_array($data))
        extract($data);
    // var_dump(APP_PATH . 'views/' . $path . '.php');die;
    include ROOT . '/views/' . $path . '.php';
}
function random($length = 5, $type = 'string')
{
    $characters = $type == 'string' ? '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ' : '0123456789';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $type == 'string' ? $randomString : boolval($randomString);
}

function ambilAuthorizationiHeader()
{
    $header = null;

    if (isset($_SERVER['Authorization'])) {
        $header = trim($_SERVER['Authorization']);
    } elseif (isset($_SERVER['HTTP_AUTHORIZATION'])) {
        $header = trim($_SERVER['HTTP_AUTHORIZATION']);
    } elseif (function_exists('apache_request_headers')) {
        $requestHeader = apache_request_headers();
        $requestHeader = array_combine(array_map('ucwords', array_keys($requestHeader)), array_values($requestHeader));
        $header = $requestHeader['Authorization'];
    }
    return $header;
}

function is_login($role = null, $user = null, $callback = null)
{
    $userdata = sessiondata('login');
    if (!empty($callback) && is_callable($callback))
        return $callback($role, $user, $userdata);

    if (empty($role) && empty($user)) {
        return !empty($userdata);
    } elseif (!empty($userdata) && !empty($role) && empty($user)) {
        return $userdata['role'] == $role;
    } elseif (!empty($userdata) && empty($role) && !empty($user)) {
        return $userdata['username'] == $user;
    } elseif (!empty($userdata) && !empty($role) && !empty($user)) {
        return $userdata['username'] == $user && $userdata['role'] == $role;
    }
}

function myRole()
{
    $userdata = sessiondata('login');
    return empty($userdata) || !isset($userdata['role']) ? 'guest' : $userdata['role'];
}
function upload_image($gambar, $tujuan, $conf = null)
{
    $config = array(
        'default' => null,
        'asal' => null,
        'maks' => 6000000,
        'isEnkripsi' => true,
    );
    if (!empty($conf))
        foreach ($conf as $k => $v)
            $config[$k] = $v;


    $nama_file = $gambar['name'];
    $ukuran_file = $gambar['size'];
    $error = $gambar['error'];
    $tmp = $gambar['tmp_name'];
    $format_sesuai = ['jpg', 'jpeg', 'png'];
    $format_file = explode('.', $nama_file);
    $format_file = strtolower(end($format_file));
    if (!in_array($format_file, $format_sesuai)) {
        response(['message' => 'Gagal, Pilih file yang Valid jpg/jpeg/png'], 500);
    } elseif ($ukuran_file > $config['maks']) {
        response(['message' => 'Gagal, size file yang dipilih terlalu besar'], 500);
    } else {
        if ($config['isEnkripsi']) {
            $nama_image = random(10);
            $nama_image .= ".";
            $nama_image .= $format_file;
        } else
            $nama_image =  $config['name'] . '.' . $format_file;

        try {
            move_uploaded_file($tmp, $tujuan . '/' . $nama_image);
        } catch (\Exception $err) {
            response(['message' => 'Gagal upload file', 'err' => $err->getMessage()], 500);
        }
        if (isset($config['sebelum']) && !empty($config['sebelum'])) {
            unlink($tujuan . '/' . $config['sebelum']);
        }
        return $nama_image;
    }
}

function config_sidebar($sidebar, int $activeMenu = 0, int $activeSubMenu = null, $configName = 'menus')
{
    /** @var Controller $controller */
    $controller = get_instance();
    $controller->loadConfig($configName);
    $compConf = $controller->configItem('menus');
    $sidebarConf = $compConf[$sidebar]['menus'];
    if (!is_null($activeMenu))
        $sidebarConf[$activeMenu]['active'] = true;

    if (!is_null($activeSubMenu) && isset($sidebarConf[$activeMenu]['sub'])) {
        $sidebarConf[$activeMenu]['sub'][$activeSubMenu]['active'] = true;
    }

    // Tandai sebagai menu sidebar
    foreach ($sidebarConf as $k => $m) {
        $sidebarConf[$k]['parrent_element'] = 'sidebar';
        $sidebarConf[$k]['id'] = '-';
    }

    // var_dump($sidebarConf);die;

    $menus = [];
    $subMenus = [];

    foreach ($sidebarConf as $v) {
        $menus[] = $v;
        if (isset($v['sub']) && !empty($v['sub'])) {
            $subMenus[] = ['induk' => str_replace('#', '', $v['link']), 'menus' => $v['sub']];
        }
    }

    foreach ($menus as $k => $v) {
        if (isset($v['sub']))
            unset($menus[$k]['sub']);
    }

    if (!empty($subMenus)) {
        foreach ($subMenus as $k => $sb) {
            foreach ($sb['menus'] as $k2 => $v2) {
                $subMenus[$k]['menus'][$k2]['parrent_element'] = 'sidebar';
            }
        }
    }
    return ['menus' => $menus, 'subMenus' => $subMenus];
}
function load_script($script, $data = array(), $return = false)
{
    $minifier = new JS();
    $ext = pathinfo($script, PATHINFO_EXTENSION);
    if (empty($ext)) $script .= '.js';
    if (!file_exists(ROOT . '/assets/js/' . $script)) return null;

    ob_start();
    if (!empty($data))
        extract($data);


    include_once ROOT . '/assets/js/' . $script;
    $_script =  ob_get_contents();
    ob_end_clean();

    $minifier->add($_script);
    $_script = $minifier->minify();
    if ($return)
        return $_script;
    else
        echo $_script;
}

function toolbar_items($toolbar, &$items = array())
{
    if ((isset($toolbar['tipe']) && ($toolbar['tipe'] == 'link' || $toolbar['tipe'] == 'dropdown')) || isset($toolbar['href'])) {
        $items[] = $toolbar;
        return;
    }
    if (!is_array($toolbar))
        return;

    foreach ($toolbar as $t) {
        if (!is_array($t))
            continue;

        foreach ($t as $n) {
            toolbar_items($n, $items);
        }
    }
}

function attribut_ke_str($attribute, $delimiter = ' ', $dg_quote = true)
{
    $str = '';
    if (is_array($attribute)) {
        foreach ($attribute as $key => $value) {
            if ($value !== '0' && empty($value))
                $str .= $key;
            else {
                $str .= $key . '=';
                if (is_array($value))
                    $value = implode(' ', $value);
                $str .= $dg_quote ? '"' . $value . '"' : $value;
            }
            $str .= $delimiter;
        }

        $str = substr($str, 0, strlen($str) - strlen($delimiter));
    }
    return $str;
}
function httpmethod($method = 'POST')
{
    return $_SERVER['REQUEST_METHOD'] == strtoupper($method);
}

function sentToController($var = []){
    if(!is_array($var)) return;
    if(isset($_SERVER['REQUEST_HEADER'])){
        if(isset($_SERVER['REQUEST_HEADER']['data'])){
            $_SERVER['REQUEST_HEADER']['data'] = array_merge($_SERVER['REQUEST_HEADER']['data'], $var);
        }else{
            $_SERVER['REQUEST_HEADER']['data'] = $var;
        }
    }else{
        $_SERVER['REQUEST_HEADER'] = ['data' => $var];
    }
}

function waktu($waktu = null, $format = MYSQL_TIMESTAMP_FORMAT)
{
    $waktu = empty($waktu) ? time() : $waktu;
    return date($format, $waktu);
}

function showError($title = 'Error', $message = 'Invalid Request', $code = 404, $file = 'default'){
    $controller =& get_instance();

    $controller->addResourceGroup('main', 'dore');
    $controller->addViews('errors/' . $file, ['message' => $message, 'code' => $code]);
    $controller->setPageTitle($title);
    $controller->render();
    exit;
}