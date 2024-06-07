<?php
class Permission extends Middleware{
    function run($args){
        $role = isset($args['role']) ? $args['role'] : null;
        $redirect = isset($args['redirect']) ? $args['redirect'] : null;
        $exit = isset($args['exit']) && $args['exit'];

        if(is_string($role)) $role = [$role];

        if(is_null($role)){
            if(!is_login()){
                if($exit){
                    showError('Forbidden', 'Anda tidak boleh mengakses halaman ini', 403);
                }
                redirect($redirect);
            }
        }elseif(!in_array(myRole(), $role)){
            if($exit){
                showError('Forbidden', 'Anda tidak boleh mengakses halaman ini', 403);
            }
            redirect($redirect);
        }
    }
}