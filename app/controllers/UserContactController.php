<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/messageapp/core/init.php');

class UserContactController {

    public function addContact($contact_id) {
        $userContact    = new UserContact(); 
        $logged_user_id = Session::get(Config::get('session/session_user_id'));

        $userContact->setContactId($contact_id);
        $userContact->setUserId($logged_user_id);
        $userContact->setStatus('saved');

        if ($userContact->insertContact()) {
            echo json_encode(['status' => 'saved']);
        }
    }

    public function listContacts() {
        echo json_encode((new UserContact())->getContacts()); 
    }

}

?>
