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

    function updateprofile(){
        if(!httpmethod() || !is_login(null, $_POST['username']))
            response("Ilegal", 403);

        $input = [];
        if(!empty($_FILES)){
            // Upload Profile
            $config = [];
            if(sessiondata('login', 'photo') != 'default.jpg')
                $config['sebelum'] = sessiondata('login', 'photo');
            
            $input['photo'] = upload_image($_FILES['photo'], ROOT . '/assets/img/profile', $config);
        }

        if(isset($_POST['password']) && !empty($_POST['password']))
            $input['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);

        foreach (sessiondata() as $key => $value) {
            if(!in_array($key, ['photo', 'password', 'username'])){
                if(isset($_POST[$key]) && $_POST[$key] != $value)
                    $input[$key] = $_POST[$key];
            }
        }

        if(!empty($input)){
            try {
                $this->db->where('username', sessiondata('login', 'username'))->update($input, 'users');
            } catch (\Throwable $th) {
                response($th->getMessage(), 500);
            }

            // Update sessiondata
            foreach($input as $k => $v){
                if(!in_array($k, ['password', 'username']))
                    $_SESSION['login'][$k] = $v;
            }
            
        }
        response("Berhasil memperbarui profile", 201);
    }

    function logout(){
        unset($_SESSION['login']);
        response("Loged Out");
    }
}
