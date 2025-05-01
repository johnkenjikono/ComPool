<?php
require_once PROJECT_ROOT_PATH . "/Model/UserModel.php";

class UserController extends BaseController
{
    /**
     * Handle GET requests to /user/list or /user/list?username=x
     * Returns all users or a specific user by username
     */
    public function listAction()
    {
        $strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        $arrQueryStringParams = $this->getQueryStringParams();

        if (strtoupper($requestMethod) == 'GET') {
            try {
                $userModel = new UserModel();

                // If ?username=... is present, fetch specific user
                if (isset($arrQueryStringParams['username'])) {
                    $username = $arrQueryStringParams['username'];
                    $arrUsers = $userModel->getUserByUsername($username);
                } else {
                    // Otherwise return a limited list of users (default limit = 10)
                    $intLimit = 10;
                    if (isset($arrQueryStringParams['limit']) && $arrQueryStringParams['limit']) {
                        $intLimit = (int)$arrQueryStringParams['limit'];
                    }
                    $arrUsers = $userModel->getUsers($intLimit);
                }

                $responseData = json_encode($arrUsers);
            } catch (Error $e) {
                $strErrorDesc = $e->getMessage() . ' Something went wrong! Please contact support.';
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
            }
        } else {
            $strErrorDesc = 'Method not supported';
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }

        // Return success or error response
        if (!$strErrorDesc) {
            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );
        } else {
            $this->sendOutput(json_encode(array('error' => $strErrorDesc)),
                array('Content-Type: application/json', $strErrorHeader)
            );
        }
    }

    /**
     * Handle POST requests to /user/create
     * Creates a new user with username and password
     */
    public function createAction()
    {
        $strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];

        if (strtoupper($requestMethod) == 'POST') {
            try {
                // Get the input JSON data
                $rawInput = file_get_contents("php://input");
                $postData = json_decode($rawInput, true);

                // Extract and sanitize values
                $username = trim($postData['username'] ?? '');
                $password = trim($postData['password'] ?? '');

                // Validate fields
                if ($username === '' || $password === '') {
                    throw new Exception("Username and password are required.");
                }

                // Create the user in the database
                $userModel = new UserModel();
                $result = $userModel->createUser($username, $password);

                $responseData = json_encode([
                    "success" => $result,
                    "message" => $result ? "User created successfully." : "Username already exists."
                ]);
                
            } catch (Exception $e) {
                $strErrorDesc = $e->getMessage();
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
            }
        } else {
            $strErrorDesc = 'Method not supported';
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }

        // Return result
        if (!$strErrorDesc) {
            $this->sendOutput(
                $responseData,
                ['Content-Type: application/json', 'HTTP/1.1 201 Created']
            );
        } else {
            $this->sendOutput(
                json_encode(['error' => $strErrorDesc]),
                ['Content-Type: application/json', $strErrorHeader]
            );
        }
    }

    /**
     * Handle PUT requests to /user/updatePassword?username=x
     * Updates a user's password
     */
    public function updatePasswordAction()
    {
        $strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];

        if (strtoupper($requestMethod) == 'PUT') {
            try {
                // Read and parse JSON body
                $rawInput = file_get_contents("php://input");
                $putData = json_decode($rawInput, true);

                // Extract from query and body
                $username = $_GET['username'] ?? null;
                $newPassword = trim($putData['password'] ?? '');

                // Validate input
                if (!$username || $newPassword === '') {
                    throw new Exception("Username and new password are required.");
                }

                // Call model method to update password
                $userModel = new UserModel();
                $result = $userModel->updateUserPassword($username, $newPassword);

                $responseData = json_encode([
                    "success" => $result,
                    "message" => $result ? "Password updated successfully." : "Update failed or no rows changed."
                ]);
            } catch (Exception $e) {
                $strErrorDesc = $e->getMessage();
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
            }
        } else {
            $strErrorDesc = 'Method not supported';
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }

        // Send result
        if (!$strErrorDesc) {
            $this->sendOutput(
                $responseData,
                ['Content-Type: application/json', 'HTTP/1.1 200 OK']
            );
        } else {
            $this->sendOutput(
                json_encode(['error' => $strErrorDesc]),
                ['Content-Type: application/json', $strErrorHeader]
            );
        }
    }

    /**
     * Handle DELETE requests to /user/delete?username=x
     * Deletes the user with the given username
     */
    public function deleteAction()
    {
        $strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];

        if (in_array(strtoupper($requestMethod), ['DELETE', 'GET'])) {
            try {
                // Extract username from query string
                $username = $_GET['username'] ?? null;

                if (!$username) {
                    throw new Exception("Username must be provided.");
                }

                // Perform delete operation
                $userModel = new UserModel();
                $result = $userModel->deleteUser($username);

                $responseData = json_encode([
                    "success" => $result,
                    "message" => $result ? "User deleted successfully." : "User not found or delete failed."
                ]);
            } catch (Exception $e) {
                $strErrorDesc = $e->getMessage();
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
            }
        } else {
            $strErrorDesc = 'Method not supported';
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }

        // Return delete response
        if (!$strErrorDesc) {
            $this->sendOutput(
                $responseData,
                ['Content-Type: application/json', 'HTTP/1.1 200 OK']
            );
        } else {
            $this->sendOutput(
                json_encode(['error' => $strErrorDesc]),
                ['Content-Type: application/json', $strErrorHeader]
            );
        }
    }

    /**
     * Handle POST requests to /user/login
     * Verifies username and password
     */
    public function loginAction()
    {
        $strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];

        if (strtoupper($requestMethod) == 'POST') {
            try {
                // Read and parse JSON body
                $rawInput = file_get_contents("php://input");
                $postData = json_decode($rawInput, true);

                $username = trim($postData['username'] ?? '');
                $password = trim($postData['password'] ?? '');

                // Validate input
                if ($username === '' || $password === '') {
                    throw new Exception("Username and password are required.");
                }

                $userModel = new UserModel();
                $users = $userModel->getUserByUsername($username); // get all matching users
                $user = $users[0] ?? null; // pick the first one

                if (!$user) {
                    // Username not found
                    $responseData = json_encode(['success' => false, 'message' => 'Invalid username or password.']);
                } else {
                    // Check password
                    if (password_verify($password, $user['password'])) {
                        $responseData = json_encode(['success' => true]);
                    } else {
                        $responseData = json_encode(['success' => false, 'message' => 'Invalid username or password.']);
                    }
                }
            } catch (Exception $e) {
                $strErrorDesc = $e->getMessage();
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
            }
        } else {
            $strErrorDesc = 'Method not supported';
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }

        // Return response
        if (!$strErrorDesc) {
            $this->sendOutput(
                $responseData,
                ['Content-Type: application/json', 'HTTP/1.1 200 OK']
            );
        } else {
            $this->sendOutput(
                json_encode(['error' => $strErrorDesc]),
                ['Content-Type: application/json', $strErrorHeader]
            );
        }
    }

    public function balanceAction()
    {
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        $arrQueryStringParams = $this->getQueryStringParams();

        if (strtoupper($requestMethod) == 'GET') {
            $username = $arrQueryStringParams['username'] ?? null;

            if (!$username) {
                return $this->sendOutput(
                    json_encode(['error' => 'Username is required']),
                    ['Content-Type: application/json', 'HTTP/1.1 400 Bad Request']
                );
            }

            $userModel = new UserModel();
            $balance = $userModel->getUserBalance($username);

            if ($balance === null) {
                return $this->sendOutput(
                    json_encode(['error' => 'User not found']),
                    ['Content-Type: application/json', 'HTTP/1.1 404 Not Found']
                );
            }

            return $this->sendOutput(
                json_encode(['balance' => $balance]),
                ['Content-Type: application/json', 'HTTP/1.1 200 OK']
            );
        }

        return $this->sendOutput(
            json_encode(['error' => 'Method not allowed']),
            ['Content-Type: application/json', 'HTTP/1.1 405 Method Not Allowed']
        );
    }


    public function setBalanceAction()
    {
        if ($_SERVER["REQUEST_METHOD"] !== 'POST') {
            return $this->sendOutput(
                json_encode(['error' => 'Method not allowed']),
                ['Content-Type: application/json', 'HTTP/1.1 405 Method Not Allowed']
            );
        }

        $data = json_decode(file_get_contents("php://input"), true);
        $username = $data['username'] ?? null;
        $amount = floatval($data['amount'] ?? -1);

        if (!$username || $amount < 0) {
            return $this->sendOutput(
                json_encode(['error' => 'Missing or invalid input']),
                ['Content-Type: application/json', 'HTTP/1.1 400 Bad Request']
            );
        }

        $userModel = new UserModel();
        $result = $userModel->updateUserBalance($username, $amount);

        if ($result) {
            return $this->sendOutput(
                json_encode(['success' => true]),
                ['Content-Type: application/json', 'HTTP/1.1 200 OK']
            );
        } else {
            return $this->sendOutput(
                json_encode(['success' => false, 'message' => 'Update failed']),
                ['Content-Type: application/json', 'HTTP/1.1 500 Internal Server Error']
            );
        }
    }

}