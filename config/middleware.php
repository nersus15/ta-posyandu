<?php

$config['middleware'] = array(
    ['controller' => '*', 'event' => 'before', 'handler' => function(){
        if(isset($_POST['_http_method'])){
            if($_POST['_http_method'] == 'update'){
                sentToController(['isEdit' => true]);
            }elseif($_POST['_http_method'] == 'POST'){
                sentToController(['isEdit' => false]);
            }elseif($_POST['_http_method'] == 'delete'){
                sentToController(['isEdit' => false]);
            }

            $_SERVER['REQUEST_METHOD'] = strtoupper($_POST['_http_method']);
            unset($_POST['_http_method']);
        
        }
    
    }],
    ['controller' => 'home', 'handler' => 'haruslogin', 'params' => ['redirect' => 'auth'],  'event' => 'before'],
    ['controller' => 'data', 'handler' => 'haruslogin', 'params' => ['role' => 'admin'], 'event' => 'before'],

    ['controller' => 'auth', 'method' => '', 'handler' => function(){
        if(is_login()) redirect();
    }, 'event' => 'before'],
);