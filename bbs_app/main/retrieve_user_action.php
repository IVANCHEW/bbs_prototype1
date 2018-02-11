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
$username = $_SESSION["username"];

$results_array = array();
$stmt = "SELECT * FROM " . $table_queried . " WHERE ". $column_queried ."='". $username ."'";
$query_results = $conn->query($stmt);

if ($query_results->num_rows > 0) {
	$valid_query = TRUE;
	while($row = $query_results->fetch_assoc()) {
		$user_group = $row["user_group"];
	}
}

// Query for all users within the group
$user_list = array();
$stmt2 = "SELECT * FROM user_base1 WHERE user_type='regular' AND user_group='" . $user_group . "'";
$query_results2 = $conn->query($stmt2);
if ($query_results2->num_rows > 0) {
	while($row = $query_results2->fetch_assoc()) {
		if($row["user_status"]=="suspend"){
			$html = '<div class="ui-block-a" style="width:20%;"><p><font color="red">' . $row["name"] . '</font></p></div>'.
			'<div class="ui-block-b" style="width:80%">';
		}else if($row["user_status"]=="new"){
			$html = '<div class="ui-block-a" style="width:20%;"><p><font color="green">' . $row["name"] . '</font></p></div>'.
			'<div class="ui-block-b" style="width:80%">';
		}else{
			$html = '<div class="ui-block-a" style="width:20%;"><p>' . $row["name"] . '</p></div>'.
			'<div class="ui-block-b" style="width:80%">';
		}
		//Format without suspend button
		$html = $html . "<a data-role='button' data-inline='true' data-icon='refresh' style='font-size : 12px;' onclick='update_user(". '"' ."reset". '"' .", ". '"' . $row["name"] . '"' .");'>Reset</a>".
		"<a data-role='button' data-inline='true' data-icon='delete' style='font-size : 12px;' onclick='delete_user(". '"' . $row["name"] . '"' .");'>Delete</a></div>";
		
		//~ $html = $html . "<a data-role='button' data-inline='true' data-icon='minus' style='font-size : 12px;' onclick='update_user(". '"' ."suspend". '"' .", ". '"' . $row["name"] . '"' .");'>Suspend</a>".
		//~ "<a data-role='button' data-inline='true' data-icon='refresh' style='font-size : 12px;' onclick='update_user(". '"' ."reset". '"' .", ". '"' . $row["name"] . '"' .");'>Reset</a>".
		//~ "<a data-role='button' data-inline='true' data-icon='delete' style='font-size : 12px;' onclick='delete_user(". '"' . $row["name"] . '"' .");'>Delete</a></div>";
		
		$user_list[] = array("username" => $row["name"], "row_html" => $html);
	}
}

$header = '<div id="retrieved_user_list" class="ui-grid-a grids">';
$footer = '</div>';

$response_array = array("header" => $header, "footer" => $footer, "user_list" => $user_list);

echo json_encode($response_array);

?>
