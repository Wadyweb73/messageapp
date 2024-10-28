<?php

class UserContact {
    private $user_id;
    private $contact_id;
    private $status;

    private $_data;

    // Getters
    public function getUserId() {
        return $this->user_id;
    }

    public function getContactId() {
        return $this->contact_id;
    }

    public function getStatus() {
        return $this->status;
    }

    public function getData() {
        return $this->_data;
    }

    // Setters
    public function setUserId($user_id) {
        $this->user_id = $user_id;
    }

    public function setContactId($contact_id) {
        $this->contact_id = $contact_id;
    }

    public function setStatus($status) {
        $allowed = ['saved', 'not saved', 'deleted'];

        if (in_array($status, $allowed)) {
            $this->status = $status;
        }
    }

    // CRUD methods
    public function insertContact() {
        return DBConnection::getInstance()->insert('user_contacts', [
            'user_id'    => $this->getUserId(),
            'contact_id' => $this->getContactId(),
            'status'     => $this->getStatus()
        ]);
    }

    public function getContacts() {
        $logged_user_id = Session::get(Config::get('session/session_user_id'));

        $contacts = DBConnection::getInstance()->query('SELECT * FROM user_contacts WHERE user_id = ?', [
            $logged_user_id
        ]);  

        if ($contacts->count()) {
            return $contacts->results();
        }

        return false;
    }

    public function getContactsByUser($user_id) {
        $contacts = DBConnection::getInstance()->query("SELECT * FROM user_contacts WHERE user_id = ?", [$user_id]);
        return $contacts->results();
    }

    public function getContactEntry($user_id, $contact_id) {
        $entry = DBConnection::getInstance()->query(
            "SELECT * FROM user_contacts WHERE user_id = ? AND contact_id = ?",
            [$user_id, $contact_id]
        );
        return $entry->getFirst();
    }

    public function updateStatus($user_id, $contact_id, $status) {
        return DBConnection::getInstance()->update('user_contacts', ['user_id', '=', $user_id, 'AND', 'contact_id', '=', $contact_id], [
            'status' => $status
        ]);
    }
}
?>

