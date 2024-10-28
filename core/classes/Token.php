<?php

class Token {
	public static function generate() {
		$token = Session::put(
			Config::get('session/token_name'), 
			md5(uniqid())
		);

		return $token;
	}

	public static function exists($token) {
		$token_name = Config::get('session/token_name');

		if (Session::exists($token_name) && $token === Session::get($token_name)) {
			return true;
		}

		return false;
	}

	public static function check($token) {
		$token_name = Config::get('session/token_name');

		if (Session::exists($token_name) && $token === Session::get($token_name)) {
			Session::delete($token_name);
			return true;
		}

		return false;
	}
}

?>
