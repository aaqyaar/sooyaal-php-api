<?php
// use controllers\UserController;
include '../conn.php';
include '../gateways/UserGateway.php';
include '../controllers/UserController.php';



header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");



$uri = explode( '/', $_SERVER["REQUEST_URI"]);
// // all of our endpoints start with /users
// // everything else results in a 404 Not Found

// if uri[3] not includes users  then return 404

if ($uri[3] != 'users.php') {
    header("HTTP/1.1 404 Not Found");
    http_response_code(404);
    exit();
}


// // the user id is, of course, optional and must be a number:
$userId = null;
if (isset($uri[4])) {
    $userId = (int) $uri[4];
}


$requestMethod = $_SERVER["REQUEST_METHOD"];

// // pass the request method and user ID to the UserController and process the HTTP request:
$gateway = new UserGateway($conn);

$controller = new UserController($gateway);

$controller->processRequest($requestMethod, $userId);

?>

