<?php session_start();

require_once("../../configuration/server_access_code.php");

// Create connection
$conn = new mysqli($servername, $sever_user, $sever_password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo "4";
} 

if ($_POST){
	$update_type = $_POST['update_type'];
	$username = $_POST['username'];
}else{
	echo "Error, no data";
}

$column_queried = "name";
$table_queried = "user_base1";
$stmt = "DELETE FROM " . $table_queried . " WHERE " . $column_queried . "='". $username ."'";

if ($conn->query($stmt)==true) {
	echo "success";
}else{
	echo "Error in updating login attempt";
}


$conn->close();

?>
