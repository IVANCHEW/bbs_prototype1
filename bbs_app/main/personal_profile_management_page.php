<?php session_start();

require_once("../../controllers/bbs_controller.php");

if ($_SESSION["authentication"]=="false"){
	
	render_page("not_logged_in");
	
}elseif ($_SESSION["authentication"]=="true"){
	
	render_page("profile_management");
	
} 

?>

