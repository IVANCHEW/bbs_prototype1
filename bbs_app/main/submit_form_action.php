<?php session_start();

require_once("../../configuration/server_access_code.php");

$table_queried = "entry";

// Create connection
$conn = new mysqli($servername, $sever_user, $sever_password, $dbname);

// Receive data from AJEX request
$data = file_get_contents( "php://input" );
$data = json_decode( $data );
$risk_classification = "safe";

// Prepare Risk Statement
$risk_stmt = "";
for($i=0; $i<count($data[0]); $i++){
	if ($data[1][$i] == "risk"){
		if ($risk_stmt==""){
			$risk_stmt = $data[0][$i];
			$risk_classification = "risk";
		}else{
			$risk_stmt = $risk_stmt . ", " . $data[0][$i];
		}
	}
}

// Remove white spaces
for($i=0; $i<count($data[0]); $i++){
	$data[0][$i] = str_replace(' ', '', $data[0][$i]);
}

// Prepare SQL query statement
$param_input = "(`username`, `risk_stmt`, `risk_classification`, ";
$value_input = "('" . $_SESSION["username"] . "', '" . $risk_stmt . "', '" . $risk_classification . "', ";

for($i=0; $i<count($data[0]); $i++){
	if(data[0][$i]=="date"){
		$param_input = $param_input . "`" . $data[0][$i] . "`";
		$value_input = $value_input . "STR_TO_DATE('" . $data[1][$i] . "', '%d/%m/%Y')";
		//~ $value_input = $value_input . '"' . $data[1][$i] . '"';
	}else{
		$param_input = $param_input . "`" . $data[0][$i] . "`";
		$value_input = $value_input . '"' . $data[1][$i] . '"';
	}
	if ($i < count($data[0]) - 1){
		$param_input = $param_input . ",";
		$value_input = $value_input . ",";
	}
}

$param_input = $param_input . ")";
$value_input = $value_input . ")";

$sql_query = "INSERT INTO `" . $table_queried . "` " . $param_input . " VALUES " . $value_input . ";" ;
//~ echo $sql_query;

// Send Response to Javascript
if ($conn->query($sql_query) === TRUE){
	echo "Entry Insert success";
	$entry_inserted = TRUE;
}else{
	echo "Entry Insert fail";
	$entry_inserted = FALSE;
}

if ($entry_inserted==TRUE){

	// Query for user's group
	$inserted_id = $conn->insert_id;
	$selected_group = "NIL";
	$table_queried2 = "user_base1";
	$group_query = "SELECT `user_group` FROM `user_base1` WHERE name='" . $_SESSION["username"] . "';";
	$group_query_results = $conn->query($group_query);
	if ($group_query_results->num_rows > 0) {
		while($row = $group_query_results->fetch_assoc()) {
			$selected_group = $row["user_group"];
		}
	}

	// Query for users within group
	$users_found = array();
	$user_query = "SELECT `name` FROM `user_base1` WHERE user_group='" . $selected_group . "';";
	$user_query_results = $conn->query($user_query);
	if ($user_query_results->num_rows > 0) {
		while($row = $user_query_results->fetch_assoc()) {
			$users_found[] = $row["name"];
		}
	}

	//Create entry for each user within group
	$number_of_inserts = 0;
	for ($i=0; $i<count($users_found); $i++){
		$insert_query = "INSERT INTO `notif_base1` (`id`, `admin`, `entry_index`) VALUES (NULL , '" . $users_found[$i] . "', '" . $inserted_id . "');";
		if ($conn->query($insert_query) === TRUE){
			$number_of_inserts++;
		}	
	}
	//~ echo "Number of notif insertions: " . $number_of_inserts;
	
}

$conn->close();
?>
