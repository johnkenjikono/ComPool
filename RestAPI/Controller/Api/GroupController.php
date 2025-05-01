<?php
require_once PROJECT_ROOT_PATH . "/Model/GroupModel.php";

class GroupController extends BaseController
{
    /**
     * GET /group/list - Returns all groups
     */
    public function listAction()
    {
        try {
            $model = new GroupModel();
            $groups = $model->getGroups();

            $this->sendOutput(
                json_encode($groups),
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
     * GET /group/view?id=1 - Get a specific group by ID
     */
    public function viewAction()
    {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            return $this->sendOutput(
                json_encode(['error' => 'Missing group ID']),
                ['Content-Type: application/json', 'HTTP/1.1 400 Bad Request']
            );
        }

        try {
            $model = new GroupModel();
            $group = $model->getGroupById((int)$id);

            $this->sendOutput(
                json_encode($group),
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
     * POST /group/create - Create a new group
     * Body: { group_name, username, group_size, members[] }
     */
    public function createAction()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->sendOutput(
                json_encode(['error' => 'Method not allowed']),
                ['Content-Type: application/json', 'HTTP/1.1 405 Method Not Allowed']
            );
        }

        $data = json_decode(file_get_contents("php://input"), true);

        // Validate fields
        if (empty($data['group_name']) || empty($data['username']) || empty($data['group_size']) || empty($data['members'])) {
            return $this->sendOutput(
                json_encode(['error' => 'Missing required fields']),
                ['Content-Type: application/json', 'HTTP/1.1 400 Bad Request']
            );
        }

        try {
            $model = new GroupModel();
            $result = $model->createGroup(
                $data['group_name'],
                $data['username'],
                (int)$data['group_size'],
                $data['members']
            );

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

    /**
     * PUT or POST /group/update?id=1 - Update group size and members
     * Body: { group_size, members[] }
     */
    public function updateAction()
    {
        if (!in_array($_SERVER['REQUEST_METHOD'], ['PUT', 'POST'])) {
            return $this->sendOutput(
                json_encode(['error' => 'Method not allowed']),
                ['Content-Type: application/json', 'HTTP/1.1 405 Method Not Allowed']
            );
        }

        $id = $_GET['id'] ?? null;
        $data = json_decode(file_get_contents("php://input"), true);

        if (!$id || empty($data['group_size']) || empty($data['members'])) {
            return $this->sendOutput(
                json_encode(['error' => 'Missing required fields']),
                ['Content-Type: application/json', 'HTTP/1.1 400 Bad Request']
            );
        }

        try {
            $model = new GroupModel();
            $result = $model->updateGroup((int)$id, (int)$data['group_size'], $data['members']);

            $this->sendOutput(
                json_encode(['success' => $result]),
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
     * DELETE /group/delete?id=1 - Delete a group by ID
     */
    public function deleteAction()
    {
        if (!in_array($_SERVER['REQUEST_METHOD'], ['DELETE', 'GET'])) {
            return $this->sendOutput(
                json_encode(['error' => 'Method not allowed']),
                ['Content-Type: application/json', 'HTTP/1.1 405 Method Not Allowed']
            );
        }

        $id = $_GET['id'] ?? null;

        if (!$id) {
            return $this->sendOutput(
                json_encode(['error' => 'Missing group ID']),
                ['Content-Type: application/json', 'HTTP/1.1 400 Bad Request']
            );
        }

        try {
            $model = new GroupModel();
            $result = $model->deleteGroup((int)$id);

            $this->sendOutput(
                json_encode(['success' => $result]),
                ['Content-Type: application/json', 'HTTP/1.1 200 OK']
            );
        } catch (Exception $e) {
            $this->sendOutput(
                json_encode(['error' => $e->getMessage()]),
                ['Content-Type: application/json', 'HTTP/1.1 500 Internal Server Error']
            );
        }
    }

    public function payInAction()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->sendOutput(
                json_encode(['error' => 'Method not allowed']),
                ['Content-Type: application/json', 'HTTP/1.1 405 Method Not Allowed']
            );
        }

        $data = json_decode(file_get_contents("php://input"), true);
        $groupId = $data['group_id'] ?? null;
        $username = $data['username'] ?? null;
        $amount = floatval($data['amount'] ?? 0);
        error_log("PAYIN: group_id=$groupId, username=$username, amount=$amount");

        if (!$groupId || !$username || $amount <= 0) {
            return $this->sendOutput(
                json_encode(['error' => 'Missing or invalid input']),
                ['Content-Type: application/json', 'HTTP/1.1 400 Bad Request']
            );
        }

        try {
            $userModel = new UserModel();
            $groupModel = new GroupModel();
        
            error_log("PAYIN: username=$username, group_id=$groupId, amount=$amount");
        
            // Get user balance
            $balance = $userModel->getUserBalance($username);
            error_log("User balance before: $balance");
        
            // Check balance
            if ($balance === null || $balance < $amount) {
                return $this->sendOutput(
                    json_encode(['error' => 'Insufficient funds']),
                    ['Content-Type: application/json', 'HTTP/1.1 400 Bad Request']
                );
            }
        
            // Deduct and add
            $userResult = $userModel->updateUserBalance($username, $balance - $amount);
            $groupResult = $groupModel->addFundsToGroup($groupId, $amount);
        
            error_log("Deduct success: " . var_export($userResult, true));
            error_log("Add-to-group success: " . var_export($groupResult, true));
        
            return $this->sendOutput(
                json_encode([
                    'success' => $userResult && $groupResult,
                    'user_balance_after' => $balance - $amount
                ]),
                ['Content-Type: application/json', 'HTTP/1.1 200 OK']
            );
        } catch (Exception $e) {
            return $this->sendOutput(
                json_encode(['error' => $e->getMessage()]),
                ['Content-Type: application/json', 'HTTP/1.1 500 Internal Server Error']
            );
        }
        
    }

    
    public function payOutAction()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->sendOutput(
                json_encode(['error' => 'Method not allowed']),
                ['Content-Type: application/json', 'HTTP/1.1 405 Method Not Allowed']
            );
        }

        $data = json_decode(file_get_contents("php://input"), true);
        $groupId = $data['group_id'] ?? null;
        $fromUsername = $data['from_username'] ?? null; // must be group leader
        $toUsername = $data['to_username'] ?? null;
        $amount = floatval($data['amount'] ?? 0);

        if (!$groupId || !$fromUsername || !$toUsername || $amount <= 0) {
            return $this->sendOutput(
                json_encode(['error' => 'Missing or invalid input']),
                ['Content-Type: application/json', 'HTTP/1.1 400 Bad Request']
            );
        }

        try {
            $groupModel = new GroupModel();
            $userModel = new UserModel();

            $groupArr = $groupModel->getGroupById($groupId);
            $group = $groupArr[0] ?? null;
            if (!$group || $group['username'] !== $fromUsername) {
                return $this->sendOutput(
                    json_encode(['error' => 'Only the group leader can pay out']),
                    ['Content-Type: application/json', 'HTTP/1.1 403 Forbidden']
                );
            }

            if ($group['funds'] < $amount) {
                return $this->sendOutput(
                    json_encode(['error' => 'Group does not have enough funds']),
                    ['Content-Type: application/json', 'HTTP/1.1 400 Bad Request']
                );
            }

            // Deduct from group and credit user
            $groupModel->deductFundsFromGroup($groupId, $amount);
            $userModel->addFundsToUser($toUsername, $amount);

            return $this->sendOutput(
                json_encode(['success' => true]),
                ['Content-Type: application/json', 'HTTP/1.1 200 OK']
            );
        } catch (Exception $e) {
            return $this->sendOutput(
                json_encode(['error' => $e->getMessage()]),
                ['Content-Type: application/json', 'HTTP/1.1 500 Internal Server Error']
            );
        }
    }




}
