<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once($_SERVER['DOCUMENT_ROOT'] . '/messageapp/vendor/autoload.php');

# ========== MUDA DIRECTORIO DE SESSOES ======================= #
if (realpath(ini_get('session.save_path')) != realpath($_SERVER['DOCUMENT_ROOT'] . '/messageapp/sessions')) {
    session_save_path($_SERVER['DOCUMENT_ROOT'] . '/messageapp/sessions');
}
# ========== MUDA DIRECTORIO DE SESSOES ======================= #

# ========== CARREGAMENTO DO ARQUIVO .ENV ======================= #
    use Dotenv\Dotenv;

    $dotenv = Dotenv::createImmutable(__DIR__ . '/config');
    $dotenv->load();
# ========== CARREGAMENTO DO ARQUIVO .ENV ======================= #

include_once($_SERVER['DOCUMENT_ROOT'] . '/messageapp/core/init.php');

# =============== Endpoints ======================= #
$router = new Router();

$router->get('/messageapp/', function() {
    include('./public/login.php');
});

$router->get('/messageapp/logout', function() {
    include('./public/login.php');
});

$router->get('/messageapp/login', function() {
    include('./public/login.php');
});

$router->post('/messageapp/login', function() {
    (new UserController())->login();
});

if (!Session::exists(Config::get('/session/session_name')) && 
    $_SERVER['REQUEST_URI'] != '/messageapp/login'
    ) {
    Redirect::to('/messageapp/login');
    exit();
}

# ROTAS DO USUARIO
# [dados]
    $router->post('/messageapp/user/register', function() { 
        (new UserController())->registerUser(); 
    });

    $router->get('/messageapp/users', function() {
        (new UserController())->listUsers();
    });

    $router->get('/messageapp/user/{user_id}', function($user_id) {
        (new UserController())->getUserById($user_id);
    });

# [views]
    $router->get('/messageapp/signup', function() {
        include('./public/signin.php');
    });

    $router->get('/messageapp/user/{user_id}/profile', function() {
        include('./app/views/Profile.php');
    });

    $router->get('/messageapp/usersession', function() {
        (new UserController())->getUserSession();
    });

# ROTAS DE CONTACTOS
# [views]
    $router->get('/messageapp/user/contacts/saved', function() {
        opcache_reset();
        include('./app/views/Contacts.php');
    });
# [dados]
    $router->post('/messageapp/user/contact/{contact_id}/add', function($contact_id) {
        (new UserContactController())->addContact($contact_id);    
    });

    $router->get('/messageapp/contacts', function() {
        (new UserContactController())->listContacts();
    });

# ROTAS DE MENSAGENS
    $router->get('/messageapp/chat/{receiver_id}', function($receiver_id) {
        (new MessageController())->getChatMessages($receiver_id);
    });

    $router->post('/messageapp/message/send', function() {
        (new MessageController())->sendMessage();
    });

#ROTAS DE GRUPOS
    $router->post('/messageapp/group/create', function() {
        (new UserGroupController())->createGroup();
    });

    $router->get('/messageapp/user/{user_id}/groups', function($user_id) {
        (new UserGroupController())->getAllGroupsByUser($user_id);
    });

    $router->get('/messageapp/groups', function() {
        (new UserGroupController())->getGroupList();
    });

    $router->get('/messageapp/group/{group_id}', function($group_id) {
        (new UserGroupController())->getGroupById($group_id);
    });

    $router->get('/messageapp/group/{group_id}/messages', function($group_id) {
        (new MessageController())->getGroupChatMessages($group_id);
    });

# HOME PAGE
    $router->get('/messageapp/home', function() {
        include('./app/views/Home.php');
    });

$router->run();

?>

