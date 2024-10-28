<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/messageapp/core/init.php');

class UserController {
    function UserController() {
         
    }

    public function getFormData() {
        $user = new User();

        $user->setFullName(Input::get('name'));
        $user->setEmail(Input::get('email'));
        $user->setPhoneNumber(Input::get('phonenumber'));
        $user->setPassword(Input::get('password'));
        $user->setJoinedAt(date('Y-m-d H:i:s'));

        return $user;
    }

    public function validate() {
        $validation = (new Validate())->check($_POST, [
            'name' => [
                'required' => true,
                'min'      => 2
            ],
            'email' => [
                'required' => true,
                'unique'   => 'user',
                'email'    => true
            ],
            'phonenumber' => [
                'required' => true,
                'min'      => 9
            ],
            'password' => [
                'required' => true,
                'min'      => 3
            ],
            'passwordagain' => [
                'matches'  => 'password',
                'required' => true
            ]
        ]); 

        return $validation;
    }

    public function registerUser() {
        if (!Input::exists('post') || !Token::check(Input::get('token'))) {
            Redirect::to('/messageapp/signup');  
            return;
        }

        $validation = $this->validate();

        if (!$validation->passed()) {
            foreach($validation->errors() as $e) {
                self::printErrorDiv($e);
            }

            self::button();
            exit;
        }

        $user = $this->getFormData();

        if ($user->insertUser()) {
            Redirect::to('/messageapp/login');
        }
    }

    public function listUsers() {
        echo json_encode((new User())->readUsers());
    }

    public function getUserById($user_id) {
        echo json_encode((new User())->getUserById($user_id)); 
    }

    public function getUserSession() {
        echo json_encode((new User())->getUserSession());
    }

    public function getUserSessionV2() {
        echo json_encode($_SESSION);
    }

    public function login() {
		if (Input::exists('post')) {
			if (Token::check(Input::get('token'))) {
				$validate = new Validate();	
				$validation = $validate->check($_POST, [
					'email' => ['required' => true],
					'password' => ['required' => true]
				]);

				if ($validation->passed()) {
					$user = new User();

                    $user->setEmail(Input::get('email'));
                    $user->setPassword(Input::get('password'));

                    if (session_status() === PHP_SESSION_NONE) {
                        session_start();
                    }

					if ($user->login()) {
                        Redirect::to('/messageapp/home');
					}
					else {
						self::logout();
						exit();
					}
				}
				else {
					print_r($validation->errors());
				}
			}
			else {
				echo "Token errado: " . Input::get('token') . '. Actualize a pagina e faca o login novamente.';
			}
		}
	}

	public static function logout() {
		if (Session::exists(Config::get('session/session_user_id'))) {
			session_destroy();
		}
		
		Redirect::to('/messageapp/login');
    }	

    public static function printErrorDiv($e) {
        echo '
            <div style="
                color: red; 
                background-color: #f2e29f;
                font-size: 15px;
                width: inherited;
                border: 1px solid #123456;
                padding: 10px;
                margin-bottom: 1px;
                border-radius: 4px;
            ">'.
                $e    
            .'</div>    
        '; 
    }

    # Renderiza o botao de voltar para a pagina anterior
    private static function button() {
        echo 
            '<button onclick="
                window.history.back()
            " style="
                color: #fff;
                background-color: red;
                padding: 10;
                margin-top: 10;
                font-weight: 600;
                border: none;
                border-radius: 4px;
                cursor: pointer;
            ">
                Voltar e Corrigir Dados
            </button>'
        ;
    }
}

?>
