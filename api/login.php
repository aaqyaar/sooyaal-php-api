<?php

include '../conn.php';
include '../gateways/UserGateway.php';
include '../controllers/UserController.php';


header("Content-Type: application/json; charset=UTF-8");

 function login($email, $password, $conn) {
    if (! isset($email) || ! isset($password)) {
        return array("status" => false, "message" => "Invalid Data");
    }
    
    // check if the email already exists
    $isExist = findUserByEmail($email, $conn);
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

 function loginUserFromRequest($conn) {
    $input = (array) json_decode(file_get_contents('php://input'), TRUE);
   $email = $input['email'];
     $password = $input['password'];
     if (! isset($email) || ! isset($password)) {
         return false;
     }
    $result = login($email, $password, $conn);
    $response['status_code_header'] = 'HTTP/1.1 200 Success';
    $response['body'] = $result;
    echo json_encode($response['body']);
}
 function findUserByEmail($email, $conn) {
    $query = "SELECT * FROM USERS WHERE email = '$email'";
    try {
        //code...
        $result = $conn->query($query);
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


loginUserFromRequest($conn);