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
.grids div { /* all blocks */
  vertical-align: middle;
  text-align: center;
}

#user_display {
	font-size: 12px;
}
</style>

<script>
	
function get_user_list(){
	//Clear old list
	$("#user_display").fadeOut();
	$("#retrieved_user_list").remove();
	
	console.log("U: Get User List Function Called");
	console.log("U: Username Logged in: <?php print($_SESSION["username"]); ?>");
	var xhttp_get_user = new XMLHttpRequest();
	xhttp_get_user.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			var $div_element = $("#user_display");
			var ajax_response = JSON.parse(this.response);
			console.log("U: Response: " + ajax_response);			
			var user_table_html = ajax_response.header;
			for (i=0; i<ajax_response.user_list.length; i++){
				user_table_html = user_table_html + ajax_response.user_list[i].row_html;
			}
			user_table_html = user_table_html + ajax_response.footer;
			console.log(user_table_html);
			$div_element.append(user_table_html).trigger("create");
			$("#user_display").fadeIn();
		}
	};
	xhttp_get_user.open("POST","retrieve_user_action.php", true);
	xhttp_get_user.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhttp_get_user.send();
	console.log("U: Get User function end");
}

function return_to_dashboard(){
	window.location.href = "admin_dashboard.php";
}

function add_user(){
	console.log("A: Add user interface called");
	$("#add_user_window").show();
}

function randomPassword(length) {
    var chars = "abcdefghijklmnopqrstuvwxyz!@#$%^&*()-+<>ABCDEFGHIJKLMNOP1234567890";
    var pass = "";
    for (var x = 0; x < length; x++) {
        var i = Math.floor(Math.random() * chars.length);
        pass += chars.charAt(i);
    }
    return pass;
}

function update_user(mode = string, username = string){
	console.log("U: Update user called, mode: " + mode + ", username: " + username);
	$("#generated_password").remove();
	
	//Request for user password
	if (mode=="reset"){
		password_generated = randomPassword(7);
		console.log("U: Password generated: " + password_generated);
		$("#update_successful_popup").append("<p id='generated_password' style='text-align: center;'>Password: " + password_generated + "</p>");
	}
	
	$.ajax({type: "POST", data: {'update_type' : mode, 'username' : username, 'password' : password_generated}, url: "admin_user_update_action.php", success: function(result){
		console.log("U: Admin update result received");
		console.log(result);
		if (result=="success"){
			console.log("U: Successful user update");
			$("#update_successful_popup").popup("open");
		}else{
			console.log("U: User update failed");
			$("#update_fail_popup").popup("open");
		}
		get_user_list();
	}});
}

function delete_user(username = string){
	console.log("U: Delete user called, username: " + username);
	$.ajax({type: "POST", data: {'username' : username}, url: "delete_user_action.php", success: function(result){
		console.log("U: User delete action results");
		console.log(result);
		if (result=="success"){
			console.log("U: Successful deleted user");
			$("delete_successful_popup").popup("open");
		}else{
			console.log("U: User deletion failed");
			$("#delete_fail_popup").popup("open");
		}
		get_user_list();
	}});
}

function submit_new_user(){
	console.log("S: Submit new user function called");
	var form_username = document.getElementById("username").value
	var form_password = document.getElementById("password").value
	var form_password_verify = document.getElementById("password_verify").value
	if (form_password == form_password_verify){
		console.log("S: Password verified");
		var xhttp_register_user = new XMLHttpRequest();
		var params = "username=" + form_username + "&password=" + form_password;
		xhttp_register_user.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				var response = this.response
				console.log("S: Response from ajax: " + response);
				$("#add_user_window").hide();
				if (response=="Added"){
					alert("User successfully added");
					get_user_list();
				}
			}
		};
		xhttp_register_user.open("POST","register_new_user_action.php", true);
		xhttp_register_user.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xhttp_register_user.send(params);
		console.log("S: Submit new user function end");		
	}
}

function logout(){
	window.location.href = "bbs_app.php";
}

</script>
</head>
<body>
	


<div data-role="page" id="page_one">
	<div data-role="header">
		<h1>User Management for admin: </h1>
		<p id="user_logged_in"></p>
		<div data-role="navbar">
			<ul>
				<li><a href="#anylink" data-icon="home" onclick="return_to_dashboard();">Home</a></li>
				<li><a href="#anylink" data-icon="plus" onclick="add_user();">Add User</a></li>
				<li><a href="#anylink" data-icon="delete" onclick="logout();">Logout</a></li>
			</ul>
		</div>
	</div>

	<div data-role="main" class="ui-content">
		<div id="add_user_window">
			<p>Create a new user:</p>
			<label for="username">Username:</label>
			<input type="text" id="username" name="username" value="user6" required>
			<label for="password">Password:</label>
			<input type="password" id="password" name="password" value="Password6" required>
			<label for="password">Re-enter Passowrd:</label>
			<input type="password" id="password_verify" name="password" value="Password6" required>
			<button class="ui-btn" onclick="submit_new_user()">Submit</button>
		</div>
	</div>

	<div id="user_display"></div>

	<div data-role="footer">
		<h1><?php print($prototype_version); ?></h1>
	</div>

	<div data-role="popup" id="update_successful_popup" data-position-to="window" data-transition="turn">
		<p>User Updated Successfully</p>
	</div>
	
	<div data-role="popup" id="update_fail_popup" data-position-to="window" data-transition="turn">
		<p>User Update Fail</p>
	</div>

	<div data-role="popup" id="delete_successful_popup" data-position-to="window" data-transition="turn">
		<p>User Deleted Successfully</p>
	</div>
	
	<div data-role="popup" id="delete_fail_popup" data-position-to="window" data-transition="turn">
		<p>User Deletion Fail</p>
	</div>
	
</div>

<script>
get_user_list();
</script>

<script>
console.log("S: Hide add user window"); 
$("#add_user_window").hide();
</script>

</div>





</body>
</html>
