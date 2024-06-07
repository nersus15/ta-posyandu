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
    ['controller' => 'home', 'handler' => 'permission', 'params' => ['redirect' => 'auth'],  'event' => 'before'],
    ['controller' => 'data', 'handler' => 'permission', 'params' => ['role' => 'admin'], 'event' => 'before'],
    ['controller' => 'bumil', 'method' => 'kunjungan', 'handler' => 'permission', 'params' => ['role' => 'bidan'], 'event' => 'before'],
    ['controller' => 'report', 'method' => 'bumil', 'handler' => 'permission', 'params' => ['role' => ['bidan', 'kader']], 'event' => 'before'],
    ['controller' => 'report', 'method' => 'bayi', 'handler' => 'permission', 'params' => ['role' => ['kader']], 'event' => 'before'],
    ['controller' => 'report', 'method' => 'lansia', 'handler' => 'permission', 'params' => ['role' => ['kader'], 'exit' => true], 'event' => 'before'],

    ['controller' => 'auth', 'method' => '', 'handler' => function(){
        if(is_login()) redirect();
    }, 'event' => 'before'],
);