<?php 

require_once($_SERVER['DOCUMENT_ROOT'] . '/messageapp/config/UrlConfig.php');

$userSessions   = (new User())->getUserSession();
$logged_user_id = $userSessions['user_id'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script type="module"   src="<?= BASE_URL ?>/assets/js/Contacts.js"></script>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/Home.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/Header.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/Contacts.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Contactos</title>
</head>
<body>
    <div class="container">
        <!-- Menu -->
        <?php include($_SERVER['DOCUMENT_ROOT'] . '/messageapp/app/views/partials/Menu.php') ?>

        <div class="right-section">
            <!-- Header -->
            <?php $page_title="Lista de Contactos"; include($_SERVER['DOCUMENT_ROOT'] . '/messageapp/app/views/partials/Header.php') ?>

            <div class="main-content">
                <div class="top-main-content action-buttons-container">
                    <div class="search-input-container left-side-input-container">
                        <input placeholder="pesquise por email ou telefone" class="search-contact-input" type="text"> 
                        <button class="search-action-button">Procurar</button>
                    </div>
                    <div class="right-side-input-container">
                        <button class="add-new-contact-button js-add-new-contact-button">+ Novo Contacto</button>
                    </div>
                </div>

                <div class="table-container js-table-container">
                    <div class="user-info-row">
                        <div class="left-side-info">
                        </div>
                        
                        <div class="email-container middle-info">
                            <p>Os seus contactos aparecerão aquí.</p>
                        </div>

                        <div class="action-buttons-container right-side-info">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-add-contact js-modal-add-contact">
        <div style="margin: 0.5em;" class="modal-title-container">
            <h1 style="color: #DD5745;">Encontrar Novos Contactos</h1>
        </div>
        <div class="modal-top-content">
            <input class="modal-contact-input" type="text">
            <button class="modal-find-contact-button">
                <i class="fa-solid fa-search"></i>
                Encontrar
            </button>
        </div>

        <div class="modal-bottom-content contact-suggestions">
        </div>
    </div>
</body>
</html>
