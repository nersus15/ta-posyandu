<?php
error_reporting(E_ALL);
ini_set("error_log", "./logs/log-" . date('y-m-d') . '.php');

include_once './config/config.php';
include_once './config/middleware.php';
require_once './core/Controller.php';
function &get_instance()
{
    return Controller::_getInstance();
}

include_once './functions/utils.php';
include_once './core/Middleware.php';
define('ROOT', __DIR__);

if(ENV == 'development') ini_set('display_errors', '1');


// START SESSION
session_start();

// Global Functions
// function  _getInstance(){
//     return new Controller();
// }

// Get Controller and Route from Url
$urls = parseUrl();
if(empty($urls)){
    $class = DEFAULT_CONTROLLER;
    $method = empty(DEFAULT_METHOD) ? 'index' : DEFAULT_METHOD;

    __runController($class, $method);
}

// Static Files
if(!empty($urls) && $urls[0] == 'static'){
    $path = str_replace('static', '', join('/', $urls));
    // var_dump($path);die;
    $querySign = strpos($path, '?');
    if($querySign !== false){
        $path = substr($path, 0, $querySign);
    }

    $filename = basename($path);
    $file_extension = strtolower(substr(strrchr($filename,"."),1));
    switch( $file_extension ) {
        case "gif": $ctype="image/gif"; break;
        case "png": $ctype="image/png"; break;
        case "jpeg": $ctype="image/jpeg"; break;
        case "jpg": $ctype="image/jpeg"; break;
        case "svg": $ctype="image/svg+xml"; break;
        case "js": $ctype = "text/javascript; charset=utf-8"; break;
        case "css": $ctype= "text/css; charset=utf-8"; break;
        default: $ctype = 'text/html; charset=utf-8';
    }

    header('Content-type: ' . $ctype);
    if(!file_exists(ROOT . '/assets' . $path)) exit;

    echo file_get_contents(ROOT . '/assets' . $path);
    exit;
}
// Run Route
if(!empty($urls)){
    // Cek Controller File
    $controllers = parseUrl();
    $class = ucfirst($controllers[0]);
    $method = count($controllers) >= 2 ? $controllers[1] : 'index';
    $batas = strpos($method, '?');
    if($batas !== false){
        $m = substr($method, 0, $batas);
        $get = explode('&', substr($method, $batas + 1));
        $method = $m;

        foreach($get as $v){
            $arr = explode('=', $v);
            $key = $arr[0];
            $value = null;
            if(count($arr) == 2)
                $value = $arr[1];

            $_GET[$key] = $value;
        }
    }
    if(count($controllers) > 2) {
        unset($controllers[0], $controllers[1]);
        $params = (array) $controllers;
    }else{
        $params = [];
    }
    if(!file_exists("./controllers/$class.php")){
        echo "Controller file <b><i>controllers/$class.php</i></b> not found";
        exit;
    }
   
    __runController($class, $method, $params); 
}

function __runController($class, $method, $params = []){
    global $config;

    // Load Middlewares
    $_configMiddleware = $config['middleware'];
    $midlewareGlobal = array_filter($_configMiddleware, function($c) {
        return $c['controller'] == '*';
    });
    $midlewareClass = array_filter($_configMiddleware, function($c) use($class){
        $class = strtolower($class);
        return strtolower($c['controller']) == $class && !isset($c['method']);
    });


    $midlewareMethod =  array_filter($_configMiddleware, function($c) use($class, $method){
        if(empty($method)) 
            $method = 'index';

        return strtolower($c['controller']) == strtolower($class) && isset($c['method']) && ((empty($c['method']) && $method == 'index') ||  $c['method'] == $method);
    });

    
    if(!file_exists("./controllers/$class.php")){
        echo "Controller file <b><i>controllers/$class.php</i></b> not found";
        exit;
    }
    include_once "./controllers/$class.php";
    $clz = new $class();

    if($clz instanceof Controller){
        if(!method_exists($clz, $method)){
            echo "Method <b><i> $method </b></i>not found in <b><i>controllers/$class.php</i></b>";
            exit;
        }
        // Ambil POST application/x-www-form-urlencoded
        $req = file_get_contents("php://input");
        
        $_POST = array_merge($_POST, (array)json_decode($req));
    
        // Call before Middleware
        __runMiddleware($midlewareGlobal);
         __runMiddleware($midlewareClass);
        __runMiddleware($midlewareMethod);

        call_user_func_array(array($clz, $method), $params);

        // Call after Middleware
        __runMiddleware($midlewareGlobal, 'after');
        __runMiddleware($midlewareClass, 'after');
        __runMiddleware($midlewareMethod, 'after');

        exit;
    }else{
        echo "Class <b><i>". ucfirst($class) ." </i></b> Not a Controller";
        exit;
    }
}

function __runMiddleware($conf, $event = 'before'){
    if(!empty($conf)){
        foreach($conf as $mid){
            if($mid['event'] != $event) continue;
            if(is_callable($mid['handler'])){
                $mid['handler']();
            }else{
                if(file_exists(ROOT . '/middlewares/' . $mid['handler'] . '.php')){
                    require_once ROOT . '/middlewares/' . $mid['handler'] . '.php';
                    $midleware = new $mid['handler']();
                    $params = isset($mid['params']) ? $mid['params'] : [];
                    
                    if($midleware instanceof Middleware){
                        $midleware->run($params);
                    }
                }
            }
        }
    }
}