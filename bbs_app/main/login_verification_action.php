<?php session_start();

if($_SESSION["authentication"] == "true"){
	echo "true";
}else{
	echo "false";
}

?>
