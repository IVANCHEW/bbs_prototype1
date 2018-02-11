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
	$password = $_POST['password'];
}else{
	echo "Error, no data";
}

if ($update_type=="suspend"){
	$column_queried = "name";
	$table_queried = "user_base1";
	$stmt = "SELECT * FROM " . $table_queried . " WHERE " . $column_queried . "='". $username ."'";
	$query_results = $conn->query($stmt);

	if ($query_results->num_rows > 0) {
		while($row = $query_results->fetch_assoc()) {
			$stmt = "UPDATE `" . $table_queried . "` SET `user_status`='suspend' WHERE "  . $column_queried . "='". $username ."'";
			if ($conn->query($stmt)==true){
				echo "success";
			}else{
				echo "Error in updating login attempt";
			}
		}
	}	
}elseif($update_type=="reset"){
	$column_queried = "name";
	$table_queried = "user_base1";
	$stmt = "UPDATE `" . $table_queried . "` SET `login_count`=0, `user_status`='new', `password`='" . $password . "' WHERE "  . $column_queried . "='". $username ."'";
	if ($conn->query($stmt)==true){
		echo "success";
	}else{
		echo "Error in updating login attempt";
	}
}

$conn->close();

?>
