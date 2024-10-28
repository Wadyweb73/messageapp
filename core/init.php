<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$GLOBALS['config'] = array(
	'mysql' => array(
		'hostname' => $_ENV['DB_HOST'],
		'username' => $_ENV['DB_USER'],
		'password' => $_ENV['DB_PASSWORD'],
		'database' => $_ENV['DB_NAME']
	),  
	'remember' => array(
		'cookie_name'   => 'hash',
		'cookie_espiry' => 604800
	),
	'session' => array(
		'session_name'    => 'user',
		'token_name'      => 'token',
        'session_user_id' => 'user_id'
	)
);

spl_autoload_register(function($class) {
    $paths = [
        $_SERVER['DOCUMENT_ROOT'] . '/messageapp/core/classes/',
        $_SERVER['DOCUMENT_ROOT'] . '/messageapp/app/controllers/',
        $_SERVER['DOCUMENT_ROOT'] . '/messageapp/app/models/',
		$_SERVER['DOCUMENT_ROOT'] . '/messageapp/app/views/',       
		$_SERVER['DOCUMENT_ROOT'] . '/messageapp/config/'
    ];

    foreach ($paths as $path) {
        $file = $path . $class . '.php';

        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

require_once($_SERVER['DOCUMENT_ROOT'] . '/messageapp/core/functions/sanitize.php');

?>
