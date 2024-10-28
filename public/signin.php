<?php

    if (Session::exists(Config::get('session/session_user_id')))
        session_destroy();

    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Pragma: no-cache");
    
    require_once($_SERVER['DOCUMENT_ROOT'] . '/messageapp/config/UrlConfig.php');
    require_once($_SERVER['DOCUMENT_ROOT'] . '/messageapp/core/init.php');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create account</title> 
	<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <div class="login-container">
        <div class="logo-container">
            <h1 class="logo">Our Media</h1>
            <i class="fa-solid fa-comments"></i>
        </div>
        <h1 class="login-title">Criar Conta</h1>
        <form action="/messageapp/user/register" class="login-form" method="post">
            <label class="name-label input-label" for="name">Nome e Apelido</label>
            <input class="signup-input" type="text" id="name" name="name">

            <label class="email-label input-label" for="email">Email</label>
            <input class="signup-input" type="email" id="email" name="email">

            <label class="phonenumber-label input-label" for="phonenumber">Telefone</label>
            <input class="signup-input" type="phonenumber" id="phonenumber" name="phonenumber">

            <label class="password-label input-label" for="password">Senha</label>
            <input class="signup-input" type="password" id="password" name="password">

            <label class="password-again-label input-label" for="passwordagain">Confirmar Senha</label>
            <input class="signup-input" type="password" id="passwordagain" name="passwordagain">

			<input type="hidden" name="token" value="<?= Token::generate() ?>">
            <button type="submit" id="submit-button">Entrar</button>

            <div class="login-container-links">
                <a id="login-link" href="/messageapp/login">Fazer Login</a>
            </div>
        </form>
    </div>
</body>
</html>
