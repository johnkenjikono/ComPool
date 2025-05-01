<?php
require_once PROJECT_ROOT_PATH . "/Model/ChatModel.php";

class ChatController extends BaseController
{
    /**
     * GET /chat/list?group_id=X - Fetch messages for a specific group
     */
    public function listAction()
    {
        $groupId = $_GET['group_id'] ?? null;

        if (!$groupId) {
            return $this->sendOutput(
                json_encode(['error' => 'Missing group ID']),
                ['Content-Type: application/json', 'HTTP/1.1 400 Bad Request']
            );
        }

        try {
            $model = new ChatModel();
            $messages = $model->getMessagesByGroupId((int)$groupId);
            $this->sendOutput(
                json_encode($messages),
                ['Content-Type: application/json', 'HTTP/1.1 200 OK']
            );
        } catch (Exception $e) {
            $this->sendOutput(
                json_encode(['error' => $e->getMessage()]),
                ['Content-Type: application/json', 'HTTP/1.1 500 Internal Server Error']
            );
        }
    }

    /**
     * POST /chat/send - Send a new message
     * Body: { group_id, username, content }
     */
    public function sendAction()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->sendOutput(
                json_encode(['error' => 'Method not allowed']),
                ['Content-Type: application/json', 'HTTP/1.1 405 Method Not Allowed']
            );
        }

        $data = json_decode(file_get_contents("php://input"), true);

        if (empty($data['group_id']) || empty($data['username']) || empty($data['content'])) {
            return $this->sendOutput(
                json_encode(['error' => 'Missing required fields']),
                ['Content-Type: application/json', 'HTTP/1.1 400 Bad Request']
            );
        }

        try {
            $model = new ChatModel();
            $result = $model->sendMessage((int)$data['group_id'], $data['username'], $data['content']);
            $this->sendOutput(
                json_encode(['success' => $result]),
                ['Content-Type: application/json', 'HTTP/1.1 201 Created']
            );
        } catch (Exception $e) {
            $this->sendOutput(
                json_encode(['error' => $e->getMessage()]),
                ['Content-Type: application/json', 'HTTP/1.1 500 Internal Server Error']
            );
        }
    }
}
