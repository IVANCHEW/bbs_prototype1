<?php session_start();
$_SESSION["authentication"] = "false";
unset($_SESSION["type"]);
unset($_SESSION["username"]);
unset($_SESSION["user_status"]);
?>
