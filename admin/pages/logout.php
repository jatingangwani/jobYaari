<?php 

session_start();

$_SESSION['auser'] = "";

session_unset();
session_destroy();

header("Location: ../"); 

 ?>