<?php session_start();
require_once("../../configuration/general_values.php");
?>
<!DOCTYPE html>
<html>
<head>

<!-- Include meta tag to ensure proper rendering and touch zooming -->
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- Include jQuery Mobile stylesheets -->
<link rel="stylesheet" href="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css">
<!-- Include the jQuery library -->
<script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
<!-- Include the jQuery Mobile library -->
<script src="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>
<style>
p {
    word-wrap: break-word;
    white-space:normal
}
</style>
<script>

var current_name;
var current_index;
var notif_count = 1;

/* 	
		DOCUMENTATION: notif_button_click Function
		Instance: Function is called when the notification cards on the dash board receives an onTouch Event
		Process:
		(1) Receives the HTML's element i.d and the index associated with it
		(2) An AJAX request to notif_removal_action.php is prepared
		(3) The AJAX request creates an SQL query to delete the appropriate entry in the notif_base1 database
		(4) The AJAX response with a success/fail message
		(5) Independent of the AJAX response, the function proceeds to hide the HTML element

*/
function notif_button_click(button_name, i){
	console.log("Registered click event, id: " + button_name + " index: " + i);
	console.log("Entry Removal function called");
	current_name = button_name;
	current_index = i;
	$("#p").popup("open");
}

function remove_notif(){
	var param = "notif_no=" + current_index;
	var xhttp_notif_remove = new XMLHttpRequest();
	xhttp_notif_remove.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			var notif_removal_response = this.response;
			console.log("notif_removal_action.php returned value: " + notif_removal_response);			
		}
	};
	xhttp_notif_remove.open("POST","notif_removal_action.php", true);
	xhttp_notif_remove.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhttp_notif_remove.send(param);
	console.log("Entry Removal function end");
	$("#" + current_name).hide();
	$("#p").popup("close");
}

function close_popup(){
	$("#p").popup("close");
}

function get_notif(){
	
	console.log("N: Get notification function called");
	console.log("N: Session varible, username: <?php print($_SESSION["username"]); ?>");
	
	var xhttp_notif = new XMLHttpRequest();
	var params = "username=<?php print($_SESSION["username"]); ?>";
	
	xhttp_notif.onreadystatechange = function() {
		
		if (this.readyState == 4 && this.status == 200) {
			
			console.log("N: notif_retrieve_action.php returned value");
			
			var raw_response = this.response;		
						
			if (notif_response == "NIL"){
				console.log("N: No Notification for display");
				document.getElementById("notif").insertAdjacentHTML("No Notification for display");				
			}
			else if (raw_response == ""){
				console.log("N: Cannot receive query results");
				document.getElementById("debugger2").innerHTML = "Cannot Receive Query Results";				
			}else{			
				console.log("N: Valid entry query session variable");
				var notif_response = JSON.parse(raw_response);
				var notif_total = notif_response.length;
				console.log("N: entry_total: " + notif_total);
				
				for(i=0; i<notif_total; i++){
					display_notifs(notif_response[i][1], notif_response[i][2], notif_response[i][3], notif_response[i][4], notif_response[i][5], notif_response[i][0]);
				}
				
			}		
			
		}
	};
	xhttp_notif.open("POST","notif_retrieve_action.php", true);
	xhttp_notif.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhttp_notif.send(params);
	console.log("N: Get notification function end");
}

function display_notifs(username_input, task_input, risk_stmt, index, date_input, entry_id){
	console.log("Display notification function called, for index: " + index);
	console.log("Element IDs: notif" + notif_count);
	//~ document.getElementById("notif").insertAdjacentHTML('beforeend',
	//~ '<button class="ui-btn" id=notif' + notif_count + 
	//~ ' onclick="notif_button_click(' + "'" + 'notif' + notif_count + "'," + index + ')">' +
	//~ '<p>Notification no: ' + notif_count + '</p>' + 
	//~ '<p>Observer: ' + username_input + '</p>' +
	//~ '<p>Task: ' + task_input + ', Date: ' + date_input + '</p>' +
	//~ '<p>' + risk_stmt + '</p>' + 
	//~ '</button>');
	
	$("#notif").append('<a data-role="button" id=notif' + notif_count + 
	' onclick="notif_button_click(' + "'" + 'notif' + notif_count + "'," + index + ')">' +
	'<p>Entry no: ' + entry_id + '</p>' + 
	'<p>Observer: ' + username_input + '</p>' +
	'<p>Task: ' + task_input + ', Date: ' + date_input + '</p>' +
	'<p>' + risk_stmt + '</p>' + 
	'</a>').trigger("create");
	
	notif_count = notif_count + 1;
}

function logout_function(){
	console.log("L: Logout function executed");	
	<?php
		$_SESSION["authentication"] = "false";
		unset($_SESSION["type"]);
	?>
	window.location.href = "bbs_app.php";
}

function user_management_page(){
	console.log("U: Move to user management");
	window.location.href = "user_management.php";
}

function search_page(){
	console.log("S: Move to search function");
	window.location.href = "search_entries_page.php";
}

</script>

</head>

<body>

<div data-role="page" id="page_one">

	<div data-role="header">
		<div data-role="navbar">
			<ul>
				<li><a href="#anylink" data-icon="user" onclick="user_management_page();">Users</a></li>
				<li><a href="#anylink" data-icon="search" onclick="search_page();">Search</a></li>
				<li><a href="#anylink" data-icon="delete" onclick="logout_function();">Logout</a></li>
			</ul>
		</div>
	</div>
	
	<div data-role="header">
		<h1>Admin Dashboard</h1>
		<p id="user_logged_in"></p>
	</div>
  
  <div data-role="main" class="ui-content">
	  <div id="notif"></div>
  </div>
  
  <div data-role="footer">
		<h1><?php print($prototype_version); ?></h1>
	</div>

	<div data-role="popup" id="p" data-position-to="window" data-transition="turn">
		<p>Delete notification?</p>
		<button class="ui-btn" onclick="remove_notif()">Yes</button>
		<button class="ui-btn" onclick="close_popup()">No</button>
	</div>
	
	<script>
		console.log("S: Calling get_notif function");
		get_notif();
	</script>
	
	<script>
		document.getElementById("user_logged_in").innerHTML = "User Logged In: <?php print($_SESSION["username"]); ?>";
	</script>

</div>



</body>

</html>
