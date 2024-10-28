<?php

class Input {
	public static function exists($type='post') {
		switch ($type) {
			case 'post':
				return (!empty($_POST)) ? true : false;
			break;

			case 'get':
				return (!empty($_GET)) ? true : false;
			break;

			default: 
				return false;
			break;
		}
	}

	public static function get($input) {
		if (isset($_POST[$input])) {
			return $_POST[$input];	
		}
		else if (isset($_GET[$input])){
			return $_GET[$input];
		}

		return '';
	}
}

?>
