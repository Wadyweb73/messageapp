<?php

    require_once $_SERVER['DOCUMENT_ROOT'] . '/messageapp/config/UrlConfig.php';

    $userSession    = (new User())->getUserSession();
    $logged_user_id = $userSession['user_id'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/Home.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script type="module" src="<?= BASE_URL ?>/assets/js/Home.js"></script>
    <title>Chat</title> 
</head>
<body>
<div class="container">
    
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/messageapp/app/views/partials/Menu.php') ?>

    <section class="discussions">
        <div class="discussion search">
            <div class="searchbar">
                <i class="fa fa-search" aria-hidden="true"></i>
                <input type="text" placeholder="Search...">
            </div>
            <button class="add-user-or-group-button user">Adicionar Amigo</button>
        </div>

        <div class="switch-chat-to-user-or-group-chats">
            <button class="switch-discussion-type-button user-discussions js-switch-discussion-type chosen-discussion">Amigos</button>
            <button class="switch-discussion-type-button group-discussions js-switch-discussion-type">Grupos</button>
        </div>

        <div class="discussions-contact-container js-discussion-contact-container">
            <div class="no-chat-warning-container">
                <p class="no-chat-warning"><i>Suas conversas aparecerão aquí!</i></p>
                <button class="start-first-chat-button js-start-first-chat-button">
                    Começar Uma Conversa
                    <i class="fa-regular fa-comment"></i>
                </button>
            </div>
        </div>
    </section>

    <section class="chat">
        <div class="header-chat js-header-chat">
            <div class="header-chat-username-container">
                <i style="color: red !important;" class="icon header-user-icon fa-solid fa-ban"></i>
                <p class="name js-chat-user-name">NENHUM GRUPO OU USUARIO FOI SELECCIONADO</p>
            </div>
            <i class="icon clickable fa fa-ellipsis-h header-chat-moreoptions-icon" aria-hidden="true"></i>
        </div>

        <div class="messages-chat js-chat-messages-container">
            <!-- Mensagem recebida com foto -->
            <div class="message">
                <div class="photo" style="background-image: url(https://images.unsplash.com/photo-1438761681033-6461ffad8d80?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=1050&q=80);">
                    <!--<div class="online"></div>-->
                </div>
                <div class="message-content">
                    <p class="text">Hi, how are you?</p>
                    <p class="time">14h55</p>
                </div>
            </div>

            <!-- Minha mensagem (text only) -->
            <div class="message text-only">
                <div class="message-content my-message">
                    <p class="text">What are you doing tonight? Want to go take a drink?</p>
                    <p class="time">14h58</p>
                </div>
            </div>

            <!-- Resposta (alinhada à direita) -->
            <div class="message text-only">
                <div class="message-content response">
                    <p class="text">Hey Megan! It's been a while 😃</p>
                    <p class="time">15h00</p>
                </div>
            </div>
        </div>

        <div class="footer-chat">
            <i class="icon emoji fa-solid fa-face-smile clickable" style="font-size:30pt;" aria-hidden="true"></i>
            <input type="text" class="write-message js-write-message-input" placeholder="Type your message here">
            <i class="icon send fa-solid fa-paper-plane clickable js-send-message-button" style="font-size: 20pt;" aria-hidden="true"></i>
        </div>
    </section>
</div>

<div class="modal-create-chat-group">
    <div class="modal-header">
        <h1>Criar Novo Grupo</h1>
    </div>

    <div class="modal-new-group-form">
        <div class="modal-new-group-data-line">
            <label class="group-name-label" for="group-name">Nome do Grupo</label>
            <input id="group-name" type="text" required>
        </div>

        <div class="modal-new-group-data-line">
            <label class="group-description-label" for="group-description">Descrição</label>
            <input id="group-description" type="text">
        </div>
    </div>

    <div class="contact-list js-contact-list">
        <div class="contact contact-info">
            <div>
                <input type="checkbox">
                <label>Joao dos Santos</label>
            </div>
            <i class="fa-solid fa-user-check"></i>
        </div>  

        <div class="contact contact-info">
            <div>
                <input type="checkbox">
                <label>Maria Alberto</label>
            </div>
            <i class="fa-solid fa-user-check"></i>
        </div>  
    </div>

    <button class="create-group-button js-create-group-button">Criar Grupo</button>
</div>

</body>
</html>
