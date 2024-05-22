<?php
class haruslogin extends Middleware{
    function run($args){
        $role = isset($args['role']) ? $args['role'] : null;
        $redirect = isset($args['redirect']) ? $args['redirect'] : null;
        
        if(!is_login($role))
            redirect($redirect);
    }
}