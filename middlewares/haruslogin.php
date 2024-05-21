<?php
class haruslogin extends Middleware{
    function run(){
        if(!is_login())
            redirect('auth');
    }
}