<?php
class haruslogin extends Middleware{
    function run($args){
        $role = isset($args['role']) ? $args['role'] : null;
        $redirect = isset($args['redirect']) ? $args['redirect'] : null;
        if(is_string($role)) $role = [$role];

        if(is_null($role)){
            if(!is_login()){
                redirect($redirect);
            }
        }elseif(!in_array(myRole(), $role)){
            redirect($redirect);
        }
    }
}