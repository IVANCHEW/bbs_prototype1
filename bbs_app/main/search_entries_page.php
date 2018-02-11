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
<!-- Include Chart JS library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.js"></script>
<!-- Include Piece Label Plugin -->
<script src="https://cdn.rawgit.com/emn178/Chart.PieceLabel.js/master/build/Chart.PieceLabel.min.js"></script>

<script>

function create_chart(user_submitted, user_no_submit, user_adhoc_submitted, user_no_adhoc){	
	var ctx = document.getElementById("myChart").getContext('2d');
	var myChart = new Chart(ctx, {
		type: 'pie',
		data: {
			//~ labels : labels_names,
			labels : ["At least one Adhoc submission", "No submission", "Formal submission only"],
			// Start of first data set
			datasets: [{				
				label: 'DATA1',					
				data: [user_adhoc_submitted.length, user_no_submit.length, user_no_adhoc.length],
					backgroundColor: [
						'rgba(54, 162, 235, 0.2)',
						'rgba(255, 99, 132, 0.2)',
						'rgba(255, 206, 86, 0.2)',
					],
					borderColor: [
						'rgba(54, 162, 235, 1)',
						'rgba(255,99,132,1)',
						'rgba(255, 206, 86, 1)',
					],	
			}, 
			]
		},
		options: {
			pieceLabel: {
				// mode 'label', 'value' or 'percentage', default is 'percentage'
				mode: 'value',

				// precision for percentage, default is 0
				precision: 0,
				
				//identifies whether or not labels of value 0 are displayed, default is false
				showZero: true,

				// font size, default is defaultFontSize
				fontSize: 12,

				// font color, default is '#fff'
				fontColor: '#000',

				// font style, default is defaultFontStyle
				fontStyle: 'normal',

				// font family, default is defaultFontFamily
				fontFamily: "'Helvetica Neue', 'Helvetica', 'Arial', sans-serif",

				// draw label in arc, default is false
				arc: false,

				// position to draw label, available value is 'default', 'border' and 'outside'
				// default is 'default'
				position: 'default',

				// format text, work when mode is 'value'
				format: function (value) { 
					return value;
				}
			}
		}
	});
}

function create_report_type_chart(risk_count, safe_count){
	var ctx = document.getElementById("report_type_chart").getContext('2d');
	var myChart = new Chart(ctx, {
		type: 'pie',
		data: {
			//~ labels : labels_names,
			labels : ["Safe", "Risk"],
			// Start of first data set
			datasets: [{				
				label: 'DATA1',					
				data: [safe_count, risk_count],
					backgroundColor: [
						'rgba(54, 162, 235, 0.2)',
						'rgba(255, 99, 132, 0.2)',
					],
					borderColor: [
						'rgba(54, 162, 235, 1)',
						'rgba(255,99,132,1)',
					],	
			}, 
			]
		},
		options: {
			pieceLabel: {
				// mode 'label', 'value' or 'percentage', default is 'percentage'
				mode: 'value',

				// precision for percentage, default is 0
				precision: 0,
				
				//identifies whether or not labels of value 0 are displayed, default is false
				showZero: true,

				// font size, default is defaultFontSize
				fontSize: 12,

				// font color, default is '#fff'
				fontColor: '#000',

				// font style, default is defaultFontStyle
				fontStyle: 'normal',

				// font family, default is defaultFontFamily
				fontFamily: "'Helvetica Neue', 'Helvetica', 'Arial', sans-serif",

				// draw label in arc, default is false
				arc: false,

				// position to draw label, available value is 'default', 'border' and 'outside'
				// default is 'default'
				position: 'default',

				// format text, work when mode is 'value'
				format: function (value) { 
					return value;
				}
			}
		}
	});
}

function create_risk_chart(risk_labels, risk_count){
	var background_color = 'rgba(54, 162, 235, 0.2)';
	var border_color = 'rgba(54, 162, 235, 1)';
	
	console.log("C: Risk labels: " + risk_labels);
	console.log("C: Risk counts: " + risk_count);
	
	for (n=0; n<risk_labels.length; n++){
		for (m=0; m<risk_labels.length - 1; m++){
			if ((risk_count[m] < risk_count[m + 1])) {
				swapInt = risk_count[m];
				risk_count[m] = risk_count[m + 1];
				risk_count[m + 1] = swapInt;
				swapRisk = risk_labels[m];
				risk_labels[m] = risk_labels[m + 1];
				risk_labels[m + 1] = swapRisk;
			}
		}
	}
	
	console.log("C: Sorted array");
	console.log("C: Risk labels: " + risk_labels);
	console.log("C: Risk counts: " + risk_count);
	
	var risk_ctx = document.getElementById("risk_chart").getContext('2d');
	
	var risk_data = {
		type: 'horizontalBar',
		data: {
			labels : risk_labels,
			datasets: [{					
				label: "Risk Distribution",				
				data: risk_count,
				borderWidth: 1,
					backgroundColor: background_color,
					borderColor: border_color,	
			}]
		},
		options: {
			scales: {
				xAxes: [{
					ticks: {
						min: 0
					}
				}],
				yAxes: [{
						barThickness: 10,
				}]
			}
		}
	}

	var risk_chart = new Chart(risk_ctx, risk_data);
	
}

function highlight_non_submission(user_no_submit){
	console.log("H: Highlight non submission called");
	var list = "<b>Non-Submission Users</b>: ";
	for(i=0; i<user_no_submit.length; i++){
		if (i==(user_no_submit.length-1)){
			list = list + user_no_submit[i];
		}else{
			list = list + user_no_submit[i] + ", ";
		}
	}
	console.log("H: String: " + list);
	document.getElementById("user_highlight_para").innerHTML = list;
}

function search_button_click(){
	console.log("S: Search button clicked");
	
	var date1 = $("#date1").val();
	var date2 = $("#date2").val();
	
	$(".search_result_class").fadeOut();
	
	$.ajax({type: "POST", data: {'date1' : date1, 'date2' : date2}, url: "get_date_range_action.php", success: function(result){
		console.log("S: Removing initial values");
		$("#search_div").remove();
		var search_response = JSON.parse(result)
		console.log("S: Search response: " + search_response);
		console.log("S: Test value: " + search_response.test);
		
		console.log("S: Creating Chart");
		$("#risk_chart_holder").remove();
		$("#risk_chart_div").append('<div id="risk_chart_holder"><canvas id="risk_chart" width="200" height="200"></canvas></div>');
		
		create_chart(search_response.user_submitted, search_response.user_no_submit,
		search_response.user_adhoc_submitted, search_response.user_no_adhoc);
		//~ console.log("S: Risk labels: " + search_response.risk_labels);
		//~ console.log("S: Risk count: " + search_response.risk_count);
		create_risk_chart(search_response.risk_labels, search_response.risk_count);
		console.log("S: Risk report count: " + search_response.risk_report_count + " Safe report count: " + search_response.safe_report_count);
		create_report_type_chart(search_response.risk_report_count, search_response.safe_report_count);
		//~ highlight_non_submission(search_response.user_no_submit);
		var html_input = search_response.header;
		for(i=0; i<search_response.data.length; i++){
			html_input = html_input + search_response.data[i].html;
		}
		html_input = html_input + search_response.footer;
		//~ console.log("S: Input: " + html_input);
		$("#search_results").append(html_input).trigger("create");
		$(".search_result_class").fadeIn();
	}});
	
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

function home_page(){
	console.log("H: Move to home page");
	window.location.href = "admin_dashboard.php";
}

</script>

</head>
<body>
<div data-role="page" id="page_one">
	
	<div data-role="header">
		<h1>Search Past Entries</h1>
		<div data-role="navbar">
			<ul>
				<li><a href="#anylink" data-icon="home" onclick="home_page();">Home</a></li>
				<li><a href="#anylink" data-icon="user" onclick="user_management_page();">Users</a></li>				
				<li><a href="#anylink" data-icon="delete" onclick="logout_function();">Logout</a></li>
			</ul>
		</div>
	</div>
  
  <div data-role="main" class="ui-content">
		<label for="date1">From Date:</label>
		<input type="Date" name="date1" id="date1" placeholder="From Date">
-->
<!--
		<input type="Date" name="date1" id="date1" placeholder="From Date" value="2017-06-01">
-->
		<label for="date2">To Date:</label>
		<input type="Date" name="date2" id="date2" placeholder="To Date">
<!--
		<input type="Date" name="date2" id="date2" placeholder="To Date" value="2017-07-01">
-->
		<button class="ui-btn" onclick="search_button_click()">Search</button>
		<p id="user_highlight_para"></p>
  </div>
  
  <div id="risk_chart_div" class="search_result_class">
		<div id="risk_chart_holder"><canvas id="risk_chart" width="200" height="200"></canvas></div>
  </div>
  
  <h2 style="text-align: center;">Participation Rate</h2>
  <div id="pie_chart_div" class="search_result_class">
		<canvas id="myChart" width="200" height="100"></canvas>
	</div>
	
	<h2 style="text-align: center;">BBS Safety Overview</h2>
  <div id="pie_chart_div2" class="search_result_class">
		<canvas id="report_type_chart" width="200" height="100"></canvas>
	</div>
	
	<div id="search_results" class="search_result_class">
	</div>
	
  <div data-role="footer" class="search_result_class">
		<h1><?php print($prototype_version); ?></h1>
	</div>
</div>
</body>
</html>
