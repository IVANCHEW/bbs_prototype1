<?php session_start();

require_once("../../configuration/server_access_code.php");

// Create connection
$conn = new mysqli($servername, $sever_user, $sever_password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo "4";
} 

// Query Database
$form_username = htmlspecialchars($_REQUEST["form_username"]);
$form_password = htmlspecialchars($_REQUEST["form_password"]);


$column_queried = "name";
$table_queried = "user_base1";

// Default Values
$auth = "false";
$utype = "";
$error = "";
$ustatus = "";

$stmt = "SELECT * FROM " . $table_queried . " WHERE " . $column_queried . "='". $form_username ."'";
$query_results = $conn->query($stmt);

if ($query_results->num_rows > 0) {
	while($row = $query_results->fetch_assoc()) {
		
		if ($form_password==$row["password"]){
			$ustatus = $row["user_status"];
			$utype = $row["user_type"];
			if ($row["user_status"]=="normal"){
				$auth = "true";
			}elseif ($row["user_status"]=="new"){
				$auth = "true";				
			}else{
				$error = "Suspended User";
			}
		}else{
			$ustatus = $row["user_status"];
			$utype = $row["user_type"];
			$error = "Invalid password";
		}
	}	
	
}elseif ($query_results->num_rows == 0) {
	$_SESSION["authentication"] = "false";
	$reply = array("authentication" => "false", "error"=>"Invalid user");
}

$_SESSION["username"] = $form_username;
$_SESSION["type"] = $utype;
$_SESSION["authentication"] = $auth;
$_SESSION["user_status"] = $utype;
$reply = array("authentication" => $auth, "type" => $utype, "username" => $form_username, "user_status"=>$ustatus, "error" => $error);

header('Content-Type: application/json');
echo json_encode($reply);

$conn->close();

?>
