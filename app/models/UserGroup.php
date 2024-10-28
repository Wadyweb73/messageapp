<?php

class UserGroup {
    private $user_id;
    private $chat_group_id;
    private $status;
    private $joined_at;

    private $_data;

    // Getters
    public function getUserId() {
        return $this->user_id;
    }

    public function getChatGroupId() {
        return $this->chat_group_id;
    }

    public function getStatus() {
        return $this->status;
    }

    public function getJoinedAt() {
        return $this->joined_at;
    }

    public function getData() {
        return $this->_data;
    }

    // Setters
    public function setUserId($user_id) {
        $this->user_id = $user_id;
    }

    public function setChatGroupId($chat_group_id) {
        $this->chat_group_id = $chat_group_id;
    }

    public function setJoinedAt($date) {
        $this->joined_at = $date;
    }

    public function setStatus($status) {
        $allowed = ['in', 'out'];

        if (in_array($status, $allowed)) {
            $this->status = $status;
        } else {
            throw new InvalidArgumentException("Invalid status. Use 'in' or 'out'.");
        }
    }

    // CRUD methods
    public function insertUserInGroup() {
        return DBConnection::getInstance()->insert('user_groups', [
            'user_id'       => $this->getUserId(),
            'chat_group_id' => $this->getChatGroupId(),
            'status'        => $this->getStatus(),
            'joined_at'     => $this->getJoinedAt() 
        ]);
    }

    public function getUserGroupEntry($user_id, $chat_group_id) {
        $entry = DBConnection::getInstance()->query(
            "SELECT * FROM user_groups WHERE user_id = ? AND chat_group_id = ?",
            [$user_id, $chat_group_id]
        );

        return $entry->getFirst();
    }

    public function getAllGroupsByUser($user_id) {
        $groups = DBConnection::getInstance()->query(
            "SELECT * FROM user_groups WHERE user_id = ?",
            [$user_id]
        );

        return $groups->results();
    }

    public function updateUserGroupStatus($user_id, $chat_group_id, $status) {
        return DBConnection::getInstance()->update('user_groups', ['user_id', '=', $user_id, 'AND', 'chat_group_id', '=', $chat_group_id], [
            'status' => $status
        ]);
    }
}

?>

