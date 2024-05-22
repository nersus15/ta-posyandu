<?php
abstract class Middleware{

    private static $instance;
    abstract function run(array $args);
    function stop(){
        exit;
    }

    /**
     * @param $var Array
     */
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
}
