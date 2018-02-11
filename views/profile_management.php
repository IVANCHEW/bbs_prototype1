<?php session_start();

require_once("../../controllers/bbs_controller.php");

?>

<!DOCTYPE html>
<html>
<head>
	
<?php include_jm_headers() ?>

<script>

function checkPassword(str)
{
	// at least one number, one lowercase and one uppercase letter
	// at least six characters
	var re = /(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}/;
	return re.test(str);
}

function change_password_page(){
	console.log("C: Change password page function called");
	$(':mobile-pagecontainer').pagecontainer('change', '#change_password_page', {
			transition: 'fade',
	});
}

function change_password(){
	console.log("C: Change password function called");
	var pw1 = $("#password1").val();
	var pw2 = $("#password2").val();
	
	var ptest = checkPassword(pw1);
	
	if (ptest != true){
		console.log("C: Unsuitable password detected");
		$("#unsafe_password_popup").popup("open");
		return;
	}
	
	console.log("C: Password received, 1: " + pw1 + " 2: " + pw2);
	if (pw1!=pw2){
		$("#password_no_match").popup("open");
		return;
	}else if(pw1==pw2){
		console.log("C: Password Matched");
		var param = "password=" + pw1;
		console.log("C: Parameter sent: " + param);
		var xhttp_cp = new XMLHttpRequest();
		xhttp_cp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				var cp_response = this.response;
				console.log("C: Change Password AJEX request response: " + cp_response);
				if (cp_response == "success"){
					//Redirect back to home page and logout					
					$("#password_changed").popup("open");
				} 
			}
		};
		xhttp_cp.open("POST","change_password_action.php", true);
		xhttp_cp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xhttp_cp.send(param);
		console.log("C: Change password AJEX request sent");
	}
}

</script>

</head>

<body>
		
<div data-role="page" id="profile_management">

	<script>
		console.log("Loading Authentication True Page");
	</script>
	
	<div data-role="header">
		<h1>Profile Management Page</h1>
	</div>
  
  <div data-role="main" class="ui-content">
		<button class="ui-btn" onclick="change_password_page()">Change Password</button>
  </div>

  <div data-role="footer">
		<h1>Prototype v0.08</h1>
	</div>

</div>

<div data-role="page" id="change_password_page">

	<div data-role="header">
		<h1>Profile Management Page</h1>
		<h2>Change User Password</h2>
	</div>
  
  <div data-role="main" class="ui-content">
		<label for="password1">Enter Password:</label>
		<input type="password" id="password1" placeholder="Enter Password">
		<label for="password2">Re-Enter Password:</label>
		<input type="password" id="password2" placeholder="Re-enter Password">		
		<button class="ui-btn" onclick="change_password()">Submit</button>
  </div>

  <div data-role="footer">
		<h1>Prototype v0.08</h1>
	</div>

	<div data-role="popup" id="password_no_match" data-position-to="window" data-transition="turn">
		<p>Error: Passwords entered do not match!</p>
	</div>

	<div data-role="popup" id="password_changed" data-position-to="window" data-transition="turn">
		<p>Password Changed, please re-login!</p>
	</div>
	
	<div data-role="popup" id="unsafe_password_popup" data-position-to="window" data-transition="turn">
		<h3>Unsafe Password Detected</h3>
		<p>1. Password should have at least 6 characters</p>
		<p>2. Password should contain at least one digit</p>
		<p>3. Password should have at least one upper case letter</p>
		<p>4. Password should have at least one lower case letter</p>
	</div>

	<script>
	console.log("Binding after popup close event function");
	$("#password_changed").bind({
		popupafterclose: function(event, ui) {
			window.location.href = "bbs_app.php";
			console.log('unload');
		}
	});
	</script>
	
</div>

</body>

</html>
