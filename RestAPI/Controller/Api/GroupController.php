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
     * PUT /group/update?id=1 - Update group size and members
     * Body: { group_size, members[] }
     */
    public function updateAction()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
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
}
