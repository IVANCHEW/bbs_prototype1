<?php

require_once("../../configuration/form_details.php");

$section_element_html = array(); 

$ajax_response = array();
header('Content-Type: application/json');

for ($i=0; $i<count($section_element_info); $i++){
	
	for ($j=0; $j<count($section_element_info[$i]); $j++){
		
		$output_string = '<div data-role="popup" id="'.$section_element_info_no_space[$i][$j].'_popup" data-position-to="window" data-transition="turn">' .
		'<p>' . $section_element_content[$i][$j] .
		'</p></div>';
		$section_element_output[] = array("element_name" => $section_element_info[$i][$j], "html_output" => $output_string);
	}
	
	$ajax_response[] = array("section_element_array" => $section_element_output);
	
}
echo json_encode($ajax_response);

?>
