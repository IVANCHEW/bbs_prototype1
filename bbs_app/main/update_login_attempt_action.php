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
}else{
	echo "Error, no data";
}

if ($update_type=="count"){
	$column_queried = "name";
	$table_queried = "user_base1";
	$stmt = "SELECT * FROM " . $table_queried . " WHERE " . $column_queried . "='". $_SESSION["username"] ."'";
	$query_results = $conn->query($stmt);

	if ($query_results->num_rows > 0) {
		while($row = $query_results->fetch_assoc()) {
			$new_count = $row["login_count"] + 1;
		}
	}

	if ($new_count < 3){
		$stmt = "UPDATE `" . $table_queried . "` SET `login_count`=" . $new_count . " WHERE "  . $column_queried . "='". $_SESSION["username"] ."'";
		if ($conn->query($stmt)==true){
			echo "Updated count: " . $new_count;
		}else{
			echo "Error in updating login attempt";
		}
	}else if ($new_count == 3){
		$stmt = "UPDATE `" . $table_queried . "` SET `login_count`=" . $new_count . ", `user_status`='suspend' WHERE "  . $column_queried . "='". $_SESSION["username"] ."'";
		if ($conn->query($stmt)==true){
			echo "User Suspended";
		}else{
			echo "Error in updating login attempt";
		}
	}
}elseif($update_type=="reset"){

	$column_queried = "name";
	$table_queried = "user_base1";
	$stmt = "UPDATE `" . $table_queried . "` SET `login_count`=0 WHERE "  . $column_queried . "='". $_SESSION["username"] ."'";
	if ($conn->query($stmt)==true){
		echo "Count Reset";
	}else{
		echo "Error in updating login attempt";
	}
}

$conn->close();

?>
