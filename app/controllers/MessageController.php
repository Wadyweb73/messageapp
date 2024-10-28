<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/messageapp/core/init.php');

class MessageController {
    public function sendMessage() {
        header('Content-Type: application/json');           

        $message     = new Message();
        $inputData   = file_get_contents('php://input');
        $data        = json_decode($inputData, true);
        $userSession = (new User())->getUserSession();

        if ($userSession['user_id'] !== $data['sender']) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Message sent from non-logged user'
            ]); 

            return;
        }

        $message->setSenderId($data['sender']);
        $message->setReceiverId($data['receiver']);
        $message->setReceiverType($data['receiver_type']);
        $message->setContent($data['content']);
        $message->setSendDate(date('Y-m-d H:i:s'));

        if (!$message->sendMessage()) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Send Message method ran with errors!'
            ]); 
            return;
        }

        echo json_encode([
            'status' => 'success',
            'message' => "Message sent from user {$data['sender']} to {$data['receiver']}",
            'body' => $data
        ]);
    } 

    public function getChatMessages($receiver_id) {
        $userSession = (new User())->getUserSession();
        $logged_user_id = $userSession['user_id'];

        echo json_encode((new Message())->getMessagesBySenderAndReceiverId($logged_user_id, $receiver_id));
    }

    public function getGroupChatMessages($group_id) {
        echo json_encode((new Message())->getMessagesByGroup($group_id)); 
    }
}

?>
