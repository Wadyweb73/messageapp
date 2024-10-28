<?php

    require_once $_SERVER['DOCUMENT_ROOT'] . '/messageapp/config/UrlConfig.php';

    $url    = $_SERVER['REDIRECT_URL'];
    $tokens = explode('/', $url); 

    $userData = (new User())->getUserById($tokens[3]) ;

    if (!$userData) {
        Redirect::to('/messageapp/user/contacts');
        exit();
    }

    $loggedUser = $userData->id;
    $userSessions   = (new User())->getUserSession();
    $logged_user_id = $userSessions['user_id'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/Home.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/Profile.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/Header.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Chat</title> 
</head>
<body>
    <div class="container">
        <!-- Menu -->
        <?php include($_SERVER['DOCUMENT_ROOT'] . '/messageapp/app/views/partials/Menu.php') ?>

        <div class="right-section">

            <?php $page_title="Informação do Utilizador"; include($_SERVER['DOCUMENT_ROOT'] . '/messageapp/app/views/partials/Header.php') ?>

            <div class="user-profile-details-container main-content">
                <div class="profile-photo-container">
                    <img class="profile-photo" src="<?= BASE_URL ?>/assets/imgs/user-icon.png"> 
                </div>
                
                <div class="profile-details">
                    <div class="left-profile-details-container">
                        <div class="info-row left-info-row">
                            <div class="label-image-container">
                                <i class="fa-solid fa-user icon username-icon"></i>
                                <label class="user-info-label" for="">Nome do Usuário</label>
                            </div>
                            <div class="username"><?= $userData->full_name ?></div>
                        </div>

                        <div class="info-row left-info-row">
                            <div class="label-image-container">
                                <i class="fa-solid fa-envelope icon email-icon"></i>
                                <label class="user-info-label" for="">Email</label> 
                            </div>
                            <div class="user-email"><?= $userData->email ?></div>
                        </div>

                        <div class="info-row left-info-row">

                            <div class="label-image-container">
                                <i class="fa-solid fa-phone-flip icon cellphone-icon"></i>
                                <label class="user-info-label" for="">Telefone</label>
                            </div>
                            <div class="user-phonenumber"><?= $userData->phone_number ?></div>
                        </div>
                    </div>

                    <div class="right-profile-details-container">
                        <div class="info-row right-info-row">
                            <div class="label-image-container">
                                <i class="fa-solid fa-circle-info icon bio-icon"></i>
                                <label class="user-info-label" for="">Sobre (Biografia)</label>
                            </div>
                            <div class="username">Vão-se os anéis e ficam os dedos</div>
                        </div>

                        <div class="info-row right-info-row">
                            <div class="label-image-container">
                                <i class="fa-solid fa-timeline icon time-icon"></i>
                                <label class="user-info-label" for="">Por aquí desde</label> 
                            </div>
                            <div class="user-email"><?= $userData->joined_at ?></div>
                        </div>

                        <div class="info-row right-info-row">
                                <input type="button" value="Contacto Gravado" class="contact-status-action-button">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
