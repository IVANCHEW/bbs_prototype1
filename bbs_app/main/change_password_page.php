<?php session_start(); 
?>

<!DOCTYPE html>
<html>

<!-- Include meta tag to ensure proper rendering and touch zooming -->
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- Include jQuery Mobile stylesheets -->
<link rel="stylesheet" href="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css">
<!-- Include the jQuery library -->
<script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
<!-- Include the jQuery Mobile library -->
<script src="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>

<script>

function checkPassword(str)
{
	// at least one number, one lowercase and one uppercase letter
	// at least six characters
	var re = /(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}/;
	return re.test(str);
}


function change_password(){
	
	var p1 = $("#password1").val();
	var p2 = $("#password2").val();

	var ptest = checkPassword(p1);
	
	if (ptest != true){
		console.log("C: Unsuitable password detected");
		$("#unsafe_password_popup").popup("open");
		return;
	}
	
	if (p1 != p2){
		console.log("C: Mismatching passwords");
		$("#mismatch_password_popup").popup("open");
		return;
	}

	console.log("C: Change password function called for user: <?php print($_SESSION["username"]); ?>");

	var param = "password=" + p1;
	console.log("C: Parameter sent: " + param);
	var xhttp_cp = new XMLHttpRequest();
	xhttp_cp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			var cp_response = this.response;
			console.log("C: Change Password AJEX request response: " + cp_response);
			if (cp_response == "success"){
				//Redirect back to home page and logout
				$("#success_change_popup").popup("open");
				//~ window.location.href = "bbs_app.php";
			} 
		}
	};
	xhttp_cp.open("POST","change_password_action.php", true);
	xhttp_cp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhttp_cp.send(param);
	console.log("C: Change password AJEX request sent");

}

</script>

<body>

<div data-role="page" id="page_one">

  <div data-role="header">
    <h1>Change Password</h1>
    <p id="notif"></p>
  </div>

  <div data-role="main" class="ui-content">
    <label for="password1">Enter Password:</label>
    <input type="password" id="password1">
    <label for="password2">Re-Enter Password:</label>
    <input type="password" id="password2">
    <button class="ui-btn" onclick="change_password()">Submit</button>
  </div>

	<div data-role="popup" id="unsafe_password_popup" data-position-to="window" data-transition="turn">
		<h3>Unsafe Password Detected</h3>
		<p>1. Password should have at least 6 characters</p>
		<p>2. Password should contain at least one digit</p>
		<p>3. Password should have at least one upper case letter</p>
		<p>4. Password should have at least one lower case letter</p>
	</div>
	<div data-role="popup" id="mismatch_password_popup" data-position-to="window" data-transition="turn">
		<p>Mismatched Password Fields</p>
	</div>
	<div data-role="popup" id="success_change_popup" data-position-to="window" data-transition="turn">
		<p>Password change completed. Please re-login with new password.</p>
	</div>
	
	<script>
	$("#success_change_popup").bind({
		popupafterclose: function(event, ui) {
			console.log("Popup closed event");
			window.location.href = "bbs_app.php";
		}
	});
	</script>
</div>

</body>

</html>
