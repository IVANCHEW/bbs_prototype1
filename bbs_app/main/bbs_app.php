<?php session_start();

require_once("../../configuration/general_values.php");

?>

<!DOCTYPE html>
<html>
<head>
<script>
console.log("Reading Headers");
</script>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="stylesheet" href="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css">
<script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
<script src="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>

<style>
.grids div { /* all blocks */
  vertical-align: middle;
  text-align: center;
}
p {
    word-wrap: break-word;
    white-space:normal
}
#basic_form .ui-controlgroup-label{
    float: none;
    display: block;
    text-align: center;
    width: 100%;
}
#basic_form .ui-controlgroup-label legend{ 
    width: 100%;
    margin-top: 10px;
    margin-bottom: 10px;
}
#basic_form .ui-controlgroup-controls {
    float: none; 
    display: block;
    width: 100%;
}
#basic_form .ui-radio{
    width: 50%;
}
#basic_form .ui-radio label{
    text-align: center;
    white-space: nowrap;
}

#basic_form2 .ui-controlgroup-label{
    float: none;
    display: block;
    text-align: center;
    width: 100%;
}
#basic_form2 .ui-controlgroup-label legend{ 
    width: 100%;
    margin-top: 10px;
    margin-bottom: 10px;
}
#basic_form2 .ui-controlgroup-controls {
    float: none; 
    display: block;
    width: 100%;
}
#basic_form2 .ui-radio{
    width: 16.66%;
}
#basic_form2 .ui-radio label{
    text-align: center;
    white-space: nowrap;
    font-size: 10px;
}

#checklist .ui-radio label{
    text-align: center;
    white-space: nowrap;
    font-size: 13px;
}

.custom_images {
	text-align: center;
}

.ui-page {
    background: transparent;
}
.ui-content{
    background: transparent;
}

#login_btn{
	background-color: rgba(255, 255, 255, 0.3);
}

.ui-btn{
	background: transparent;
}

#page_one{
	background-image: url("images/background.jpg");
	background-repeat: no-repeat;
	background-size: 100% 100%;
}
</style>

<script>
	var logged_in = false;
	var debugger_count = 0;
	var ajax_response;
	var ajax_response_popup;
	var form_element_names = ["observee", "type", "location", "task", "task_type", "reference"];
	var section_name_no_space = [];
	
	function set_loading_widget(){
		console.log("S: Setting up loading widget");
		$( document ).on( "mobileinit", function() {
			$.mobile.loader.prototype.options.text = "loading";
			$.mobile.loader.prototype.options.textVisible = true;
			$.mobile.loader.prototype.options.theme = "z";
			$.mobile.loader.prototype.options.html = "";
		});
	}
	
	function set_popup(){
		console.log("P: Populate popup called");
		var xhtto_request = new XMLHttpRequest();
		xhtto_request.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				ajax_response_popup = JSON.parse(this.response);
				console.log("P: Response from ajax: " + ajax_response_popup);		
				var $div_element = $("#popup_div");
				var html_prepare = "";				
				for(j=0; j<ajax_response_popup.length; j++){
					var section_elements = ajax_response_popup[j].section_element_array;
					
					for(i=0; i<section_elements.length; i++){
						html_prepare = html_prepare + section_elements[i].html_output;
					}
				  //~ console.log("P: " + html_prepare);
					$div_element.append(html_prepare);
				}
			}
		};
		xhtto_request.open("POST","generate_popup_action.php", true);
		xhtto_request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xhtto_request.send();
		console.log("P: Request completed");	
	}
	
	function set_checklist(){
		console.log("P: Populate checklist called");
		var xhtto_request = new XMLHttpRequest();
		xhtto_request.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				ajax_response = JSON.parse(this.response);
				console.log("P: Response from ajax: " + ajax_response);		
				var $div_element = $("#checklist");
								
				for(j=0; j<ajax_response.length; j++){
					var html_prepare = ajax_response[j].section_header_html;
					section_name_no_space.push(ajax_response[j].section_name_no_space);
					var section_elements = ajax_response[j].section_element_array;
					
					for(i=0; i<section_elements.length; i++){
						html_prepare = html_prepare + section_elements[i].html_output;
					}
					html_prepare = html_prepare + ajax_response[j].section_footer_html;
				  //~ console.log("P: " + html_prepare);
					$div_element.append(html_prepare).trigger("create");
				}
			}
		};
		xhtto_request.open("POST","generate_form_action.php", true);
		xhtto_request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xhtto_request.send();
		console.log("P: Request completed");
	}
	
	function handle_click(label_name = string, class_name = string, color_name = string){
		$("." + class_name).css("background-color","#EDEDED");
		if (color_name=="red"){
			$("#" + label_name).css("background-color","#DC143C");
		}else if (color_name=="blue"){
			$("#" + label_name).css("background-color","#3388CC");
		}else if (color_name=="green"){
			$("#" + label_name).css("background-color","#00FF00");
		}
	}
	
	function open_popup_info(popup_name = string){
		console.log("P: Popup open, " + popup_name);
		$("#" + popup_name).popup("open");
	}
	
	function show_survey_page(){
  	
    //Auto Fill the Date
		var now = new Date();
		var day = ("0" + now.getDate()).slice(-2);
		var month = ("0" + (now.getMonth() + 1)).slice(-2);
		var today = now.getFullYear()+"-"+(month)+"-"+(day) ;
		$("#date").val(today);
		
		//Auto Fill the Time
		var time = '' + ('0' + now.getHours()).slice(-2) + ':' + ('0' + now.getMinutes()).slice(-2) + ':' + ('0' + now.getSeconds()).slice(-2);

		console.log("S: Auto fill time: " + time);
		$("#time").val(time);
		 //~ "18:01:07" ('0' + deg).slice(-2)
		
		//Set the on tap event listener
		$("input[name='task']").on("change", function() {
			if ($("input[name='task']:checked").val() == "ground"){
				//~ document.getElementById("survey_debugger").innerHTML = "Ground Task Selected";
				$("#kingfisher_section").hide();
				$("#ground_section").show();
			}
			else{
				//~ document.getElementById("survey_debugger").innerHTML = "Kingfisher Task Selected";
				$("#kingfisher_section").show();
				$("#ground_section").hide();
			}
    });
    
    $("input[name='type']").on("change", function(){
			console.log("T: Toggled type field change");
			if ($("input[name='type']:checked").val() == "adhoc"){
				for(i=0; i<section_name_no_space.length; i++){
					console.log("T: i : " + i + "element: " + section_name_no_space[i]);
					$("#" + section_name_no_space[i]).hide();
				}
			}else if ($("input[name='type']:checked").val() == "formal"){
				for(i=0; i<section_name_no_space.length; i++){
					$("#" + section_name_no_space[i]).show();
				}
			}
		});

		$(':mobile-pagecontainer').pagecontainer('change', '#page_two', {
        transition: 'fade',
    });
	}
	
	function show_admin_dashboard(){
		window.location.href = "admin_dashboard.php";
	}
	
	function show_end_page(){
		$(':mobile-pagecontainer').pagecontainer('change', '#page_three', {
				transition: 'fade',
		});
	}
	
	function show_new_user_page(){
		window.location.href = "change_password_page.php";
	}

	function update_login_attempt(update_type = string){
		console.log("L: Login attempt function called, " + update_type);
		$.ajax({type: "POST", data: {'update_type' : update_type}, url: "update_login_attempt_action.php", success: function(result){
			console.log("L: Login attempt result received");
			console.log(result);
		}});
	}

	function log_in(form_username, form_password) {
		$.mobile.loading("show");
		var params = "form_username=" + form_username + "&form_password=" + form_password;
		var xhttp_login = new XMLHttpRequest();
		xhttp_login.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				
				var login_response =JSON.parse(this.response);
				console.log("L: Response values, authentication: " + login_response.authentication + ", username: " + login_response.username + ", type: " + login_response.type + ", status: " + login_response.user_status);	
				console.log("L: Session values, authentication: <?php print($_SESSION["authentication"]); ?>, username: <?php print($_SESSION["username"]); ?>, type: <?php print($_SESSION["type"]); ?>");	
				
				if (login_response.user_status == "suspend"){
					console.log("L: User suspended, cannot log in");
					$("#suspended_account").popup("open");					
				}else if (login_response.authentication == "true"){		
					update_login_attempt("reset");
					if (login_response.user_status == "normal"){
						if (login_response.type == "regular"){
							show_survey_page();
						}else if (login_response.type =="admin"){
							console.log("L: Administrator log in");
							show_admin_dashboard();
						}
					}else if (login_response.user_status =="new"){
						console.log("L: New user detected");
						show_new_user_page();
					}
				}else if (login_response.authentication == "false"){
					console.log("L: Login Error, " + login_response.error);
					if(login_response.error=="Invalid password"){
						update_login_attempt("count");
						$("#invalid_password").popup("open");
					}
				}
				$.mobile.loading("hide");
			}
		};
		xhttp_login.open("POST","login_action.php", true);
		xhttp_login.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xhttp_login.send(params);
	}
	
	function login_button_click(){
		$("#kingfisher_section").show();
		$("#ground_section").hide();
		
		//Step 1: Retrieve form value
		var form_username = document.getElementById("username").value
		var form_password = document.getElementById("password").value
		console.log("Click: " + form_username);
		console.log("Click: " + form_password);
		//Step 2: Call login function to verify user details with SQL database
		var login_response2 = log_in(form_username, form_password);
	}
	
	function form_verification(){
		console.log($("input:radio[name='task_type']:checked").val());
		//Test 1: Check that task is filled in
		if($("input:radio[name='task_type']:checked").val()==undefined){
			//Task not selected
			document.getElementById("form_verification_popup_text").innerHTML = "Warning: Task not selected";
			return false;
		}
		
		//Test 2: If adhoc mode selected, ensure that all checklist items have been selected
		//Design Decision: Test 2 was coded into the survey submit button function, to check the code and
		//extract the desired value at the same time.
		
		return true;
	}
		
	function logout_function(){
		console.log("L: Logout function executed");	
		$.ajax({type: "POST", data: {}, url: "logout_action.php", success: function(result){
			console.log("L: Logout action completed");
		}});		
	}
	
	function logout_button_click(){
		logout_function();
		window.location.href = "bbs_app.php";
	}
	
	function return_home_button_click(){
		window.location.href = "bbs_app.php";
	}
		
	function survey_submit(){
		var return_value;
		var checklist_results=[];
		var param_name=[];
		
		form_verified = form_verification();
		console.log("S: Form verification result: " + form_verified);
		
		if(form_verified==false){
			$("#form_verification_prompt").popup("open");
			return false;
		}
		
		//Collect Form Details - Date
		return_value = $("#date").val();
		param_name.push("date");
		checklist_results.push(return_value);
		
		//Collect Form Details - Time		
		param_name.push("time");
		checklist_results.push($("#time").val());
		console.log("S: time submitted: " + $("#time").val());
		
		//Collect Form Details - Crew
		param_name.push("crew");
		checklist_results.push($("#crew").val());
		
		console.log("S: Crew number: " + $("#crew").val());
		
		for (i=0; i<form_element_names.length; i++){
			return_value = $("input:radio[name='" + form_element_names[i] + "']:checked").val();
			checklist_results.push(return_value);
			param_name.push(form_element_names[i]);
		}
		
		console.log(checklist_results);
		console.log(param_name);
		
		//Collect ChecklistData
		if($("input:radio[name='type']:checked").val()=="formal"){
			for (i=0; i<ajax_response.length; i++){
				for (j=0; j<ajax_response[i].section_element_array.length; j++){
					return_value = $("input:radio[name='" + ajax_response[i].section_element_array[j].element_name + "']:checked").val();
					if (return_value==undefined){
						document.getElementById("form_verification_popup_text").innerHTML = "Warning: Formal submission requires a user input for every checklist item";
						$("#form_verification_prompt").popup("open");
						return false;
					}else{
						checklist_results.push(return_value);
						param_name.push(ajax_response[i].section_element_array[j].element_name);
					}
				}
			}
		}else if($("input:radio[name='type']:checked").val()=="adhoc"){
			for (i=0; i<ajax_response.length; i++){
				for (j=0; j<ajax_response[i].section_element_array.length; j++){
					return_value = $("input:radio[name='" + ajax_response[i].section_element_array[j].element_name + "']:checked").val();
					checklist_results.push(return_value);
					param_name.push(ajax_response[i].section_element_array[j].element_name);
				}
			}
		}
		var store_data = [param_name, checklist_results];
		console.log(store_data);
		
		console.log("S: Test value");
		console.log(param_name);
		
		//AJEX Request to perform form submission
		console.log("S: Begin AJEX form submission");
		var jsonString = JSON.stringify(store_data);
		//~ //var jsonString = JSON.stringify(param_name);
		var xhttp_submission = new XMLHttpRequest();
		xhttp_submission.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				var submission_response = this.response;
				console.log("S: Response: ");
				console.log(submission_response);
				if(submission_response=="Entry Insert fail"){
					$("#submission_error_prompt").popup("open");
				}else if(submission_response=="Entry Insert success"){
					logout_function();
					show_end_page();
				}
			}
		};
		xhttp_submission.open("POST","submit_form_action.php", true);
		xhttp_submission.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xhttp_submission.send(jsonString);
	}
	
	function survey_submit_button_click(){
		
		//Login verification
		$.ajax({type: "POST", data: {}, url: "login_verification_action.php", success: function(result){
			console.log("V: Verification results: " + result);
			if (result=="true"){
				survey_submit();
				return true;
			}else{
				console.log("Survey submission failed: user not logged in correctly");
				document.getElementById("submission_error_prompt_text").innerHTML = "Form Submission Failed - User is not logged in correctly";
				$("#submission_error_prompt").popup("open");
				return false;
			}
		}});	

	}
	
	function toggle_checklist_section(element_name){
		console.log("T: Button toggled, " + element_name);
		var el = $("#" + element_name);
		if(el.is(":visible")){
			console.log("T: Element is visible");
			el.hide();	
		}else{
			console.log("T: Element is hidden");
			el.show();
		}
	}
	
	function toggle_type(type){
		console.log("T: Toggled: " + type);
	}
		
	function manage_profile(){
		console.log("M: Manage user profile called");
		//~ window.location.href = "personal_profile_management_page.php";
		window.location.href = "change_password_page.php";
	}

</script>
</head>

<body>

<?php $_SESSION["test_value"] = "Session On"; ?>
<script>console.log("S: Session type variable: <?php print($_SESSION["type"]); ?>");</script>
<script>set_checklist(); set_popup(); set_loading_widget();</script>
		
<div data-role="page" id="page_one" data-theme="a">
	
	<p></p>
	
	<div id="header_bar" class="custom_images" style="height: 80px; width = 100%">
		<img src="images/header.png" style="height: 70px;" align="middle"/>
	</div>
	
  <div data-role="main" class="ui-content" data-theme="a">
	
		<table style="width:100%">
		<tr>
			<td><img src="images/user_icon.png" style="height: 25px;" align="middle"/></td>
			<td><input type="text" id="username" name="username" required></td>
		</tr>
		<tr>
			<td><img src="images/password_icon.png" style="height: 30px;" align="middle"/></td>
			<td><input type="password" id="password" name="password" required></td>
		</tr>
		</table>
		
		<button id="login_btn" class="ui-btn" onclick="login_button_click()">Login</button>
  </div>
  
	<div data-role="popup" id="invalid_password" data-position-to="window" data-transition="turn">
		<p>Incorrect Username and Password</p>
	</div>

	<div data-role="popup" id="suspended_account" data-position-to="window" data-transition="turn">
		<p>User Suspended, please contact administrator</p>
	</div>
	
</div>

<div data-role="page" id="page_two" data-theme="a">
	
	<p></p>
	
	<div id="header_bar" class="custom_images" style="height: 50px; width = 100%">
		<img src="images/header.png" style="height: 50px;" align="middle"/>
	</div>
  
  <div data-role="main" class="ui-content">
		
		<div data-role="header">
			<div data-role="navbar">
				<ul>
					<li><a href="#anylink" data-icon="user" onclick="manage_profile();">Change Password</a></li>
					<li><a href="#anylink" data-icon="delete" onclick="logout_button_click();">Logout</a></li>
				</ul>
			</div>
		</div>
		
		<label for="date">Date of Occurence:</label>
		<input type="Date" name="date" id="date">

		<label for="time">Time of Occurence:</label>
		<input type="Time" name="time" id="time">
		
		<div data-role="popup" id="form_verification_prompt" data-position-to="window" data-transition="turn">
			<p id="form_verification_popup_text">Verification of form failed</p>
		</div>

		<div data-role="popup" id="submission_error_prompt" data-position-to="window" data-transition="turn">
			<p id="submission_error_prompt_text">Form Submission Error - Check server connection</p>
		</div>
		
		<label for="crew" class="select">Number of Crew:</label>
		<select name="crew" id="crew">
			<option value="1">1</option>
			<option value="2">2</option>
			<option value="3">3</option>
			<option value="4">4</option>
			<option value="5">5</option>
			<option value="6">6</option>
			<option value="7">7</option>
			<option value="8">8</option>	
			<option value=">8">>8</option>	
		</select>
		
		<!-- Observee Field -->
		<div id="basic_form" data-role="fieldcontain">
			<fieldset data-role="controlgroup" data-type="horizontal">
				<legend>Observee</legend>
				<input type="radio" name="observee" id="observee_b" value="n_nmp" checked="checked">
				<label for="observee_b">CP</label>
				<input type="radio" name="observee" id="observee_a" value="external"> 
				<label for="observee_a">External</label>
			</fieldset>			
			<!-- Type field -->
			<fieldset data-role="controlgroup" data-type="horizontal">
				<legend>Type</legend>
				<input type="radio" name="type" id="type_a" value="formal" checked="checked"> 
				<label for="type_a">Formal</label>
				<input type="radio" name="type" id="type_b" value="adhoc">
				<label for="type_b">Adhoc</label>
			</fieldset>
			<!-- Location Field -->
			<fieldset data-role="controlgroup" data-type="horizontal">
				<legend>Location</legend>
				<input type="radio" name="location" id="location_a" value="T" checked="checked"> 
				<label for="location_a">T</label>
				<input type="radio" name="location" id="location_b" value="M">
				<label for="location_b">M</label>
			</fieldset>
			<!-- Crew Number Field -->
			<!--
			<fieldset data-role="controlgroup" data-type="horizontal">
				<legend>Number of Crew</legend>
				<input type="radio" name="crew" id="crew_a" value="1" checked="checked"> 
				<label for="crew_a">1</label>
				<input type="radio" name="crew" id="crew_b" value="2">
				<label for="crew_b">>= 2</label>
			</fieldset>		
			-->
			<!-- Task Field -->
			<fieldset data-role="controlgroup" data-type="horizontal">
				<legend>Task</legend>
				<input type="radio" name="task" id="task_a" value="kingfisher" checked="checked"> 
				<label for="task_a">Kingfisher Tasks</label>
				<input type="radio" name="task" id="task_b" value="ground">
				<label for="task_b">WorkShop & Ground Tasks</label>
			</fieldset>	
		</div>		
		<div id="basic_form2" data-role="fieldcontain">
			<fieldset data-role="controlgroup" data-type="horizontal">
				<legend>Reference</legend>
				<input type="radio" name="reference" id="reference_a" value="TM" checked="checked">
				<label for="reference_a">TM</label>
				<input type="radio" name="reference" id="reference_b" value="LO">
				<label for="reference_b">LO</label>
				<input type="radio" name="reference" id="reference_c" value="HIRA">
				<label for="reference_c">HIRA</label>
				<input type="radio" name="reference" id="reference_d" value="TSA">
				<label for="reference_d">TSA</label>
				<input type="radio" name="reference" id="reference_e" value="Others">
				<label for="reference_e">Others</label>			
				<input type="radio" name="reference" id="reference_f" value="NA">
				<label for="reference_f">NA</label>							
			</fieldset>	
		</div>

		<!-- King Fisher Section -->
		<div id="kingfisher_section">
			<legend>King Fisher Tasks:</legend>
			<div class="ui-grid-a grids"><div class="ui-block-a" style="width:50%;">
				<input type="radio" name="task_type" id="kf_task_a" value="Serv"> 
				<label for="kf_task_a">Serv</label>
			</div><div class="ui-block-b" style="width:50%">
				<input type="radio" name="task_type" id="kf_task_b" value="Rect">
				<label for="kf_task_b">Rect</label>
			</div><div class="ui-block-a" style="width:50%;">
				<input type="radio" name="task_type" id="kf_task_c" value="L and R"> 
				<label for="kf_task_c">L and R</label>
			</div><div class="ui-block-b" style="width:50%">
				<input type="radio" name="task_type" id="kf_task_d" value="Towing">
				<label for="kf_task_d">Towing</label>
			</div><div class="ui-block-a" style="width:50%;">
				<input type="radio" name="task_type" id="kf_task_e" value="Maint. Run"> 
				<label for="kf_task_e">Grnd Run</label>
			</div><div class="ui-block-b" style="width:50%">
				<input type="radio" name="task_type" id="kf_task_f" value="POL Replenish">
				<label for="kf_task_f">POL Replenish</label>
			</div><div class="ui-block-a" style="width:50%;">
				<input type="radio" name="task_type" id="kf_task_g" value="Others"> 
				<label for="kf_task_g">Others</label>
			</div></div>
		</div>
		
		<!-- Ground Task Secetion -->
		<div id="ground_section">
			<legend>Workshop and Ground Tasks:</legend>
			<div class="ui-grid-a grids"><div class="ui-block-a" style="width:50%;">
				<input type="radio" name="task_type" id="gnd_task_a" value="Office Admin"> 
				<label for="gnd_task_a">Office Admin</label>
			</div><div class="ui-block-b" style="width:50%">
				<input type="radio" name="task_type" id="gnd_task_b" value="Maint and Repair">
				<label for="gnd_task_b">Maint and Repair</label>
			</div><div class="ui-block-a" style="width:50%;">
				<input type="radio" name="task_type" id="gnd_task_c" value="Bench Test"> 
				<label for="gnd_task_c">Bench Test</label>
			</div><div class="ui-block-b" style="width:50%">
				<input type="radio" name="task_type" id="gnd_task_d" value="Eqp. Operation">
				<label for="gnd_task_d">Eqp. Operation</label>
			</div><div class="ui-block-a" style="width:50%;">
				<input type="radio" name="task_type" id="gnd_task_e" value="POL and Chemical"> 
				<label for="gnd_task_e">POL and Chemical</label>
			</div><div class="ui-block-b" style="width:50%">
				<input type="radio" name="task_type" id="gnd_task_f" value="Hoisting">
				<label for="gnd_task_f">Hoisting</label>
			</div><div class="ui-block-a" style="width:50%;">
				<input type="radio" name="task_type" id="gnd_task_g" value="Fork Lifting"> 
				<label for="gnd_task_g">Fork Lifting</label>
			</div><div class="ui-block-b" style="width:50%">
				<input type="radio" name="task_type" id="gnd_task_h" value="Vehicle">
				<label for="gnd_task_h">Vehicle</label>
			</div><div class="ui-block-a" style="width:50%;">
				<input type="radio" name="task_type" id="gnd_task_i" value="Others"> 
				<label for="gnd_task_i">Others</label>
			</div></div>
		</div>
		
		<!-- Checklist Form -->
		<div id="checklist"></div>
		
		<button class="ui-btn" onclick="survey_submit_button_click()">Submit Survey</button>
	
		<div id="popup_div">
		
		</div>
		
  </div>
  
</div>

<div data-role="page" id="page_three">

	<div data-role="header">
		<h1>Survey Completed</h1>
	</div>

	<div data-role="main" class="ui-content">
		<p>Submission Successful! Thank you for completing the Survey. Logged out automatically completed</p>
		<button class="ui-btn" onclick="return_home_button_click()">Return to Log In Page</button>
	</div>
	
  <div data-role="footer">
		<h1><?php print($prototype_version); ?></h1>
	</div>
</div>

<div data-role="page" id="page_error">

	<div data-role="header">
		<h1>Error Page</h1>
	</div>

	<div data-role="main" class="ui-content">
		<p>UNDEFINED ERROR</p>
	</div>
	
</div>

</body>

</html>
