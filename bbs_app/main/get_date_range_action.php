<?php

require_once("../../configuration/server_access_code.php");
require_once("../../configuration/form_details.php");

// Create connection
$conn = new mysqli($servername, $sever_user, $sever_password, $dbname);

$date1 = htmlspecialchars($_POST["date1"]);
$date2 = htmlspecialchars($_POST["date2"]);

if ($conn->connect_error){
  echo "4";
}

$table_queried = "entry";
$column_queried = "date";

$header = '<div id="search_div"><table data-role="table" data-mode="columntoggle" class="ui-responsive' . 
'id="search_table"><thead>'.
'<tr><th>Date</th>'.
'<th>Observer</th>'.
'<th>Task</th>'.
'<th>Risks</th>'.
'<th data-priority="2">Type</th>'.
'<th data-priority="3">Location</th>'.
'<th data-priority="4">Crew</th>'.
'</tr></thead><tbody>';

$footer = '</tbody></table></div>';

$stmt = "SELECT * FROM " . $table_queried . " WHERE " . $column_queried .
" between '" . $date1 . "' and '" . $date2 . "'";

$results = $conn->query($stmt);
$entry_results = array();

if ($results->num_rows > 0){
  while($row = $results->fetch_assoc()){
    $details[] = array("username" => $row["name"], "date" => $row["date"], "task" => $row["task"], "risk_stmt" => $row["risk_stmt"]);
    $html = "<tr><td>". $row["date"] . "</td><td>" . 
    $row["username"] . "</td><td>" . 
    $row["task_type"] . "</td><td>" . 
    $row["risk_stmt"] . "</td><td>" . 
    $row["type"] . "</td><td>" . 
    $row["location"] . "</td><td>" . 
    $row["crew"] .
    "</td></tr>";
    $entry_results[] = array("details" => $details, "html" => $html);
  }
}

// OBTAIN ADMIN'S GROUP
$stmt = 'SELECT user_group FROM user_base1 WHERE name="'.$_SESSION["username"].'"';
$results = $conn->query($stmt);
if ($results->num_rows > 0){
  while($row = $results->fetch_assoc()){
		$admin_group = $row["user_group"];
  }
}

// Retrieve distinct list of users who have submitted reports within time frame
// Limited to those within the admin group
$stmt = 'SELECT DISTINCT entry.username FROM entry INNER JOIN user_base1 '.
'ON user_base1.name=entry.username '.
'WHERE user_base1.user_group="'.$admin_group.'" AND '.
'entry.date BETWEEN "'.$date1.'" and "'. $date2 .'"';

$results = $conn->query($stmt);
$user_submitted = array();

if ($results->num_rows > 0){
	while($row = $results->fetch_assoc()){
		$user_submitted[] = $row["username"];
	}
}

// Retrieve list of users who have at least one adhoc submission
$stmt = 'SELECT DISTINCT entry.username FROM entry INNER JOIN user_base1 '.
'ON user_base1.name=entry.username '.
'WHERE user_base1.user_group="'.$admin_group.'" AND '.
'entry.type="adhoc" AND '.
'entry.date BETWEEN "'.$date1.'" and "'. $date2 .'"';

$results = $conn->query($stmt);
$user_adhoc_submitted = array();

if ($results->num_rows > 0){
	while($row = $results->fetch_assoc()){
		$user_adhoc_submitted[] = $row["username"];
	}
}

// Retrieve distinct list of users within admin group
$stmt = 'SELECT DISTINCT name FROM user_base1 ' .
'WHERE user_group="'.$admin_group.'" AND user_type="regular"';

$results = $conn->query($stmt);
$user_list = array();

if ($results->num_rows > 0){
	while($row = $results->fetch_assoc()){
		$user_list[] = $row["name"];
	}
}

$user_no_submit = array();
$array_diff_result = array_diff($user_list, $user_submitted);
foreach ($array_diff_result as $key => $value) {
 $user_no_submit[] = $array_diff_result[$key];
}

$user_no_adhoc = array();
$array_diff_result = array_diff($user_submitted, $user_adhoc_submitted);
foreach ($array_diff_result as $key => $value) {
 $user_no_adhoc[] = $array_diff_result[$key];
}


// Retrieve a count of all risks
$risk_count = array();
$risk_labels = array();
for($i=0; $i<count($section_element_info); $i++){
	for($j=0; $j<count($section_element_info[$i]); $j++){
		$stmt = 'SELECT * FROM entry WHERE ' . $section_element_info_no_space[$i][$j] . '="risk" AND' . ' date BETWEEN "'.$date1.'" and "'. $date2 .'"';
		$results = $conn->query($stmt);
		if ($results->num_rows > 0){
			$risk_labels[] = $section_element_info[$i][$j];
			$risk_count[] = $results->num_rows;
		}
	}
}

// Retrieve a count of risk classification types
$stmt = 'SELECT 1 FROM entry WHERE risk_classification="risk" AND' . ' date BETWEEN "'.$date1.'" and "'. $date2 .'"';
$results = $conn->query($stmt);
$risk_report_count = $results->num_rows;
$stmt = 'SELECT 1 FROM entry WHERE risk_classification="safe" AND' . ' date BETWEEN "'.$date1.'" and "'. $date2 .'"';
$results = $conn->query($stmt);
$safe_report_count = $results->num_rows;

$response = array("header" => $header, "footer" => $footer, "data" => $entry_results, 
"user_submitted" => $user_submitted, "user_no_submit" => $user_no_submit, 
"user_adhoc_submitted" => $user_adhoc_submitted, "user_no_adhoc" => $user_no_adhoc,
"risk_count" => $risk_count, "risk_labels" => $risk_labels,
"risk_report_count" => $risk_report_count, "safe_report_count" => $safe_report_count,
"test" => $test);

echo json_encode($response);

$conn->close();

?>
