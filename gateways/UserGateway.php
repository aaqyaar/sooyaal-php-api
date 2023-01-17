<?php

class UserGateway {

    public function __construct(private $conn) {
        $this->conn = $conn;
    }
    public function findAll() {
        $sql = "SELECT * FROM users";
       try {
        $result = $this->conn->query($sql);
        $users = array();
        $data = array();
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
        }
        if ($result) {
            $data = array("status" => true, "data" => $users);
        } else {
            $data = array("status" => false, "data" => "No Data Found");
        }
        return $data;
       } catch (Exception $e) {
           echo $e->getMessage();
       }

    }
    public function findById($id) {
        $sql = "SELECT * FROM users WHERE id = $id";
        try {
            //code...
            $result = $this->conn->query($sql);
            $users = array();
            $data = array();
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $users[] = $row;
                }
            }
            if ($result) {
                $data = array("status" => true, "data" => $users);
            } else {
                $data = array("status" => false, "data" => "No Data Found");
            }
            return $data;
        } catch (Exception $e) {
            //throw $e;
            echo $e->getMessage();
        }
    }
  
    public function validateUser($input)
    {
        if (! isset($input['name'])) {
            return false;
        }
        if (! isset($input['email'])) {
            return false;
        }
        if (! isset($input['password'])) {
            return false;
        }
        if (! isset($input['phone'])) {
            return false;
        }
        return true;
    }

    public function register($data) {
        // validate the data   
        if (! $this->validateUser($data)) {
            return array("status" => false, "message" => "Invalid Data");
        }
        // check if the email already exists
        $isExist = $this->findUserByEmail($data['email']);
       // data is not empty
        if (count($isExist['data']) > 0) {
            return array("status" => false, "message" => "Email Already Exists");
        }

        //  hash the password
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
        // insert the data
        $query = "INSERT INTO users (name, email, password, phone) VALUES ('".$data['name']."', '".$data['email']."', '".$hashedPassword."', '".$data['phone']."')";
      
        try {
            //code...
            $result = $this->conn->query($query);
            $users = array();
            $data = array();
            if ($result) {
                $data = array("status" => true, "message" => "User Created Successfully");
            } else {
                $data = array("status" => false, "message" => "User Creation Failed");
            }
            return $data;
        } catch (Exception $e) {
            //throw $e;
            echo $e->getMessage();
        }
    }

    public function login($email, $password) {
        if (! isset($email) || ! isset($password)) {
            return array("status" => false, "message" => "Invalid Data");
        }
        
        // check if the email already exists
        $isExist = $this->findUserByEmail($email);
        // data is not empty
        if (count($isExist['data']) == 0) {
            return array("status" => false, "message" => "Email Does Not Exist");
        }
        // check if the password is correct
        $hashedPassword = $isExist['data'][0]['password'];
        if (! password_verify($password, $hashedPassword)) {
            return array("status" => false, "message" => "Invalid Password");
        }
        // return the user
        return array("status" => true, "data" => $isExist['data'][0]);

    }

    private function findUserByEmail($email) {
        $query = "SELECT * FROM USERS WHERE email = '$email'";
        try {
            //code...
            $result = $this->conn->query($query);
            $user = array();
            $data = array();
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $user[] = $row;
                }
            }
            if ($result) {
                $data = array("status" => true, "data" => $user);
            } else {
                $data = array("status" => false, "data" => "No Data Found");
            }
            return $data;
        } catch (Exception $e) {
            //throw $e;
            echo $e->getMessage();
        }
    }
}

