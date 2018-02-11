<?php session_start();

require_once("../../configuration/server_access_code.php");
 
// Create connection
$conn = new mysqli($servername, $sever_user, $sever_password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo "4";
} 

$column_queried = "id";
$table_queried = "notif_base1";
$notif_index = htmlspecialchars($_REQUEST["notif_no"]);

$stmt = "DELETE FROM " . $table_queried . " WHERE ". $column_queried ."=". $notif_index	;
echo $stmt;

if ($conn->query($stmt) === TRUE){
	echo "Deleted";
}else{
	echo "Unable to Delete Record";
}

$conn->close();

?>
