<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/messageapp/core/init.php');

class ChatGroup {
    private $id;
    private $group_name;
    private $group_description;
    private $admin_id;
    private $create_at;

    private $_data;

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getGroupName() {
        return $this->group_name;
    }

    public function getGroupDescription() {
        return $this->group_description;
    }

    public function getAdminId() {
        return $this->admin_id;
    }

    public function getCreateAt() {
        return $this->create_at;
    }

    public function getData() {
        return $this->_data;
    }

    // Setters
    public function setGroupName($group_name) {
        $this->group_name = $group_name;
    }

    public function setGroupDescription($group_description) {
        $this->group_description = $group_description;
    }

    public function setAdminId($admin_id) {
        $this->admin_id = $admin_id;
    }

    public function setCreateAt($create_at) {
        $this->create_at = $create_at;
    }

    // CRUD methods
    public function createGroup() {
        return DBConnection::getInstance()->insert('chat_group', [
            'group_name'        => $this->getGroupName(),
            'group_description' => $this->getGroupDescription(),
            'admin_id'          => $this->getAdminId(),
            'created_at'        => $this->getCreateAt()
        ]);
    }

    public function getGroupList() {
        return DBConnection::getInstance()->query("SELECT * FROM chat_group")->results();
    }

    public function getGroupById($id) {
        $group = DBConnection::getInstance()->query("SELECT * FROM chat_group WHERE id = ?", [$id]);
        return $group->getFirst();
    }

    public function getGroupsByAdmin($admin_id) {
        return DBConnection::getInstance()->query("SELECT * FROM chat_group WHERE admin_id = ?", [$admin_id])->results();
    }

    public function getLastCreatedUserGroupByAdminId($admin_id) {
        $group = DBConnection::getInstance()->query('
            SELECT * FROM chat_group 
            WHERE admin_id = ?
            ORDER BY created_at DESC
            LIMIT 1',
            [$admin_id]
        );

        if ($group->results()) {
            return $group->getFirst();
        }

        return null;
    }

    public function updateGroup($id, $fields = []) {
        return DBConnection::getInstance()->update('chat_group', $id, $fields);
    }

    public function deleteGroup($id) {
        return DBConnection::getInstance()->delete('chat_group', ['id', '=', $id]);
    }
}

?>

