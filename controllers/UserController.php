<?php


class UserController {
    public function __construct(private UserGateway $gateway) {
        $this->gateway = $gateway;
    }
    public function processRequest($requestMethod, $userId = null) {
        switch ($requestMethod) {
            case 'GET':
                if ($userId) {
                    $response = $this->getUserById($userId);
                } else {
                   $response = $this->getAllUsers();
                };
                break;

            case 'POST':
                $response = $this->loginUserFromRequest();
                break;
           
        }
        header($response['status_code_header']);
        if ($response['body']) {
            echo json_encode($response['body']);
        }
    }
    private function getAllUsers() {
        $result = $this->gateway->findAll();
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = $result;
        return $response;
    }
    private function getUserById($id) {
        $result = $this->gateway->findById($id);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = $result;
        return $response;
    }

    private function createUserFromRequest() {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (! $this->gateway->validateUser($input)) {
            return false;
        }
        $result = $this->gateway->register($input);
        $response['status_code_header'] = 'HTTP/1.1 201 Created';
        $response['body'] = $result;
        return $response;
    }
    private function loginUserFromRequest() {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
       $email = $input['email'];
         $password = $input['password'];
         if (! isset($email) || ! isset($password)) {
             return false;
         }
        $result = $this->gateway->login($email, $password);
        $response['status_code_header'] = 'HTTP/1.1 200 Success';
        $response['body'] = $result;
        return $response;
    }
}