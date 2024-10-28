<?php

class Message {
    private $id;
    private $content;
    private $id_sender;
    private $id_receiver;
    private $send_date;
    private $receiver_type;

    private $_data;

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getContent() {
        return $this->content;
    }

    public function getSenderId() {
        return $this->id_sender;
    }

    public function getReceiverId() {
        return $this->id_receiver;
    }

    public function getSendDate() {
        return $this->send_date;
    }

    public function getReceiverType() {
        return $this->receiver_type;
    }

    public function getData() {
        return $this->_data;
    }

    // Setters
    public function setContent($content) {
        $this->content = $content;
    }

    public function setSenderId($id_sender) {
        $this->id_sender = $id_sender;
    }

    public function setReceiverId($id_receiver) {
        $this->id_receiver = $id_receiver;
    }

    public function setSendDate($send_date) {
        $this->send_date = $send_date;
    }

    public function setReceiverType($receiver_type) {
        $allowed = ['user', 'group'];
        if (in_array($receiver_type, $allowed)) {
            $this->receiver_type = $receiver_type;
        } else {
            throw new InvalidArgumentException("Invalid receiver_type. Use 'user' or 'group'.");
        }
    }

    // CRUD methods
    public function sendMessage() {
        return DBConnection::getInstance()->insert('message', [
            'content'       => $this->getContent(),
            'id_sender'     => $this->getSenderId(),
            'id_receiver'   => $this->getReceiverId(),
            'send_date'     => $this->getSendDate(),
            'receiver_type' => $this->getReceiverType()
        ]);
    }

    public function getMessageById($id) {
        $msg = DBConnection::getInstance()->query("SELECT * FROM message WHERE id = ?", [$id]);
        return $msg->getFirst();
    }

    public function getMessagesByUser($user_id) {
        return DBConnection::getInstance()->query("
            SELECT * FROM message 
            WHERE (id_sender = ? AND id_receiver = ?) 
            AND receiver_type = 'user'
            ORDER BY send_date DESC
        ", [$user_id, $user_id])->results();
    }

    public function getMessagesBySenderAndReceiverId($sender_id, $receiver_id) {
        $messages = DBConnection::getInstance()->query("
            SELECT * FROM message
            WHERE (id_sender = ? AND id_receiver = ?) 
               OR (id_sender = ? AND id_receiver = ?)
            ORDER BY send_date ASC
            ", [
                $sender_id, $receiver_id,
                $receiver_id, $sender_id
            ]
        );

        if ($messages->count()) {
            return $messages->results();
        }

        return [ 'data' => null ];
    }

    public function getMessagesByGroup($group_id) {
        return DBConnection::getInstance()->query("
            SELECT * FROM message 
            WHERE id_receiver = ? 
            AND receiver_type = 'group'
            ORDER BY send_date ASC
        ", [$group_id])->results();
    }
}
?>

