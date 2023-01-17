<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sooyaal-db";

try {
  $conn = new mysqli($servername, $username, $password, $dbname);
  // echo "Connected successfully";
} catch (Exception $e) {
  echo $e->getMessage();
}
