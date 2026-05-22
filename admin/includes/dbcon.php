<?php
$db_host       =   "localhost";
$db_user        =   "root";
$db_password    =   "";
$db_name        =   "jobYaari";

$conn = new mysqli($db_host, $db_user,$db_password,$db_name);

if ($conn->connect_error) {
    header('Location: ../');
    exit();
}

$conn->set_charset("utf8mb4");

?>
