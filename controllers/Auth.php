<?php

class Auth extends Controller
{
    function index(){
        $this->setPageTitle('Login');
        $this->add_cachedJavascript('pages/login', 'file', 'body:end', array(
            'formid' => '#form-login',
        ));
        $this->addViews('pages/login');
        $this->addResourceGroup('main', 'form', 'login');
        $this->render();
    }

    function login(){
        $ruleValidator = [
			'username' => array(
				[
					'rule' => 'required',
					'message' => 'Username harus diisi!'
				],
			),
            'password' => array(
				[
					'rule' => 'required',
					'message' => 'Password harus diisi!'
				],
			),
        ];


        $input = $_POST;
        $this->validateInput($input, $ruleValidator);

        $user = $this->db->select('*')->where('username', $input['username'])->from('users')->results();
        if(empty($user)){
            response("User " . $input['username'] . ' Tidak ditemukan', 404);
        }
        $user = $user[0];

        if(!password_verify($input['password'], $user['password'])){
            response("Password untuk User " . $input['username'] . ' Salah', 403);
        }

        $_SESSION['login'] = $user;

        response($user);
    }

    function logout(){
        unset($_SESSION['login']);
        response("Loged Out");
    }
}
