<?php

header("Content-Type: application/json");

include '../conn.php';

function getPosts($conn) {
    $sql = "SELECT * FROM POSTS";
    $result = $conn->query($sql);
    $posts = array();
    $data = array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $posts[] = $row;
        }
    }
    if ($result) {
        $data = array("status" => true, "data" => $posts);
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
getPosts($conn);


