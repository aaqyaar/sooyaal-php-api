<?php

header("Content-Type: application/json");

include '../conn.php';

function getUsers($conn) {
    $sql = "SELECT * FROM USERS";
    $result = $conn->query($sql);
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
    echo json_encode($data);
}

// if(isset($_POST['action'])){
//     $action = $_POST['action'];
//     // execute the fundtion that we requested
//     $action($conn);
// }else{
//     echo json_encode(array("status" => false, "data" => "Action Required...",  "post"=>$_POST));
// }
getUsers($conn);


