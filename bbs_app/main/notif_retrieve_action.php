<?php session_start();

require_once("../../configuration/server_access_code.php");

// Create connection
$conn = new mysqli($servername, $sever_user, $sever_password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo "4";
} 

// Query for notification tagged to admin name 
$column_queried = "admin";
$table_queried = "notif_base1";
$username = htmlspecialchars($_REQUEST["username"]);

$results_array = array();
$stmt = "SELECT * FROM " . $table_queried . " WHERE ". $column_queried ."='". $username ."'";
$query_results = $conn->query($stmt);
if ($query_results->num_rows > 0) {
	$valid_query = TRUE;
	while($row = $query_results->fetch_assoc()) {
		$results_array[] = array($row["id"], $row["admin"], $row["entry_index"]);
	}
}else{
	$valid_query = FALSE;
	exit("NIL");
}

// Query for specific entries tagged to entry_index
if ($valid_query==TRUE){
	$column_queried = "id";
	$table_queried = "entry";

	$entry_array = array();
	$row_count = 0;
	$total_entry = count($results_array);
	$index = 0;

	while($index < ($total_entry)){
		$stmt = "SELECT * FROM " . $table_queried . " WHERE ". $column_queried ."='". $results_array[$index][2] ."'";
		$query_results = $conn->query($stmt);
		if ($query_results->num_rows > 0) {
			while($row = $query_results->fetch_assoc()) {
				$entry_array[] = array($row["id"], $row["username"], $row["task_type"], $row["risk_stmt"], $results_array[$index][0], $row["date"]);
			}
		}
		++$index;
	}

	if (count($entry_array) > 0){
		echo json_encode($entry_array);
	}
}
$conn->close();

?>
