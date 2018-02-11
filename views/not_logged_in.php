<?php session_start();

require_once("../../controllers/bbs_rendering.php");

?>

<!DOCTYPE html>
<html>
<head>
	
<?php include_jm_headers() ?>

<script>

function return_to_login(){
	window.location.href = "bbs_app.php";
}

</script>

</head>

<body>
		
<div data-role="page" id="error">
	
	<script>
		console.log("Loading Authentication False Page");
	</script>
	
	<div data-role="header"><h1>Error - not logged in</h1></div>
  
  <div data-role="main" class="ui-content">
		<button class="ui-btn" onclick="return_to_login()">Return to Login</button>
  </div>

  <div data-role="footer">
		<h1>Prototype v0.04</h1>
	</div>

</div>

</body>

</html>
