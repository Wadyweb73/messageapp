<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/messageapp/core/init.php');

class User {
    private $id;
    private $full_name;
    private $email;
    private $password;
    private $joined_at;
    private $phone_number;

    private $_data;

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getFullName() {
        return $this->full_name;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getJoinedAt() {
        return $this->joined_at;
    }

    public function getPhoneNumber() {
        return $this->phone_number;
    }

    public function getData() {
        return $this->_data;
    }

    // Setters
    public function setId($id) {
        $this->id = $id;
    }

    public function setFullName($full_name) {
        $this->full_name = $full_name;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function setJoinedAt($joined_at) {
        $this->joined_at = $joined_at;
    }

    public function setPhoneNumber($phone_number) {
        $this->phone_number = $phone_number;
    }

    public function insertUser() {
        return DBConnection::getInstance()->insert('user', [
            'full_name'    => $this->getFullName(),
            'email'        => $this->getEmail(),
            'password'     => $this->getPassword(),
            'joined_at'    => $this->getJoinedAt(),
            'phone_number' => $this->getPhoneNumber()
        ]);
    }

    public function readUsers() {
        $users = DBConnection::getInstance()->query("SELECT * FROM user");
        return $users->results();
    }

    public function getUserById($id) {
        $user = DBConnection::getInstance()->get('user', ['id', '=', $id]);

        if ($user->count()) {
            return $user->getFirst();
        }

        return false;
    }

    public function getUserByEmail($email) {
        $user = DBConnection::getInstance()->get('user', ['email', '=', $email]);
        if ($user->count()) {
            $this->_data = $user;
            return $user;
        }
        return false;
    }

    public function login() {
        $user = $this->getUserByEmail($this->getEmail());

        if ($user != false) {
            $user_data = $user->getFirst();

            if ($this->getPassword() === $user_data->password) {
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }

                Session::put(Config::get('session/session_name'), $user_data->email);
                Session::put(Config::get('session/session_user_id'), $user_data->id);

                return true;
            }
        }

        return false;
    }

    public function getUserSession() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (Session::exists(Config::get('session/session_name'))) {
            $username = Session::get((Config::get('session/session_name')));
            $user_id  = Session::get((Config::get('session/session_user_id')));

            return [
                'username' => $username,
                'user_id'  => $user_id
            ];
        }

        return [
            'username' => null,
            'user_id'  => null
        ];
    }
}
?>

