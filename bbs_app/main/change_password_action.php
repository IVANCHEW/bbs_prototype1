<?php session_start();

require_once("../../configuration/server_access_code.php");
$conn = new mysqli($servername, $sever_user, $sever_password, $dbname);

if ($conn->connect_error){
  echo "4";
}

$form_password = htmlspecialchars($_REQUEST["password"]);

$table_name = "user_base1";
$column_matched = "name";

$stmt = "UPDATE " . $table_name . " SET user_status='normal', password='" . $form_password . "' WHERE " . $column_matched . "='" . $_SESSION["username"] ."'";
//~ echo $stmt;
if ($conn->query($stmt) === TRUE){
  echo "success";
}else{
  echo "fail";
}

$conn->close();

?>
