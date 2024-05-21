<?php

$config['middleware'] = array(
    ['controller' => '*', 'event' => 'before', 'handler' => function(){
        if(isset($_POST['_http_method'])){
            if($_POST['_http_method'] == 'update'){
                sentToController(['isEdit' => true]);
            }elseif($_POST['_http_method'] == 'POST'){
                sentToController(['isEdit' => false]);
            }

            unset($_POST['_http_method']);
        
        }
    
    }],
    ['controller' => 'home', 'handler' => 'haruslogin', 'event' => 'before'],
    ['controller' => 'data', 'handler' => function(){
        if(!is_login('admin')) redirect();
    }, 'event' => 'before'],
    ['controller' => 'auth', 'method' => '', 'handler' => function(){
        if(is_login()) redirect();
    }, 'event' => 'before'],
);