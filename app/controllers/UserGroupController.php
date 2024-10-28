<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/messageapp/core/init.php');

class UserGroupController {
    public function createGroup() {
        header('Content-Type: application/json');           

        $group       = new ChatGroup();
        $data        = json_decode(file_get_contents('php://input'), true);
        $userSession = (new User())->getUserSession();

        $group->setAdminId($userSession['user_id']);
        $group->setGroupName($data['group_name']);
        $group->setGroupDescription($data['group_description']);
        $group->setCreateAt(date('Y-m-d H:i:s'));

        if (!$group->createGroup()) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Group not created!'
            ]); 
            return;
        }

        $loggedUserLastCreatedGroup = (new ChatGroup())->getLastCreatedUserGroupByAdminId($userSession['user_id']);
        $notAddedUsers = [];

        if ($loggedUserLastCreatedGroup) {
            foreach ($data['users'] as $user) {
                $user_added = $this->addUserInGroup($user, $loggedUserLastCreatedGroup->id); 

                if (!$user_added) {
                    $notAddedUsers[] = $user;
                }
            } 
        }

        echo json_encode([
            'status' => 'success',
            'message' => "Group created with success!",
            'body' => $notAddedUsers
        ]);
    }

    public function addUserInGroup($user_id, $group_id) {
        $userGroup = new UserGroup();

        $userGroup->setChatGroupId($group_id);
        $userGroup->setUserId($user_id);
        $userGroup->setStatus('in');
        $userGroup->setJoinedAt(date('Y-m-d H:i:s'));

        if ($userGroup->insertUserInGroup()) {
            return true;
        }

        return false;
    }

    public function getAllGroupsByUser($user_id) {
        echo json_encode((new UserGroup())->getAllGroupsByUser($user_id));
    }

    public function getGroupById($group_id) {
        echo json_encode((new ChatGroup())->getGroupById($group_id));
    }

    public function getGroupList() {
        echo json_encode((new ChatGroup())->getGroupList());
    }

}

?>
