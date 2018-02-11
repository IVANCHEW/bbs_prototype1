<?php session_start();

require_once("../../configuration/server_access_code.php");

// Create connection
$conn = new mysqli($servername, $sever_user, $sever_password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo "4";
} 

// Query for group associated to user
$column_queried = "name";
$table_queried = "user_base1";
$username_user = $_SESSION["username"];

$results_array = array();
$stmt = "SELECT * FROM " . $table_queried . " WHERE ". $column_queried ."='". $username_user ."'";
$query_results = $conn->query($stmt);

if ($query_results->num_rows > 0) {
	$valid_query = TRUE;
	while($row = $query_results->fetch_assoc()) {
		$user_group = $row["user_group"];
	}
}

$username = htmlspecialchars($_REQUEST["username"]);
$password = htmlspecialchars($_REQUEST["password"]);

if ($valid_query == TRUE){
	$sql_insertion = "INSERT INTO `user_base1` (`name`, `password`, `user_type`, `user_group`, `user_status`) VALUES ('" . $username . "', '". $password . "', 'regular', '". $user_group . "', 'new');";
	echo $sql_insertion;
	if ($conn->query($sql_insertion) === TRUE){
		echo "Added";
	}else{
		echo "Unable to Add Record";
	}
}else{
	echo "Could not find user group";
}

?>
