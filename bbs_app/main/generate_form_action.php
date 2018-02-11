<?php

require_once("../../configuration/form_details.php");

$section_element_html = array(); 

$ajax_response = array();
header('Content-Type: application/json');

for ($i=0; $i<count($section_info); $i++){
	
	$section_header_output = '<button class="ui-btn" onclick="toggle_checklist_section(' . "'" . $section_info_no_space[$i] . "'" . ')">' . $section_info[$i] . '</button><div id="' . $section_info_no_space[$i] . '">';
	$section_header_footer = '</div>';
	$section_element_output = array();
	
	for ($j=0; $j<count($section_element_info[$i]); $j++){
		
		$output_string = '<div class="ui-grid-a grids"><div class="ui-block-a" style="width:35%;"><p><a data-icon="info" data-mini="true" onclick='."'" .
		'open_popup_info("'.$section_element_info_no_space[$i][$j].'_popup")'."'".'>'. $section_element_info[$i][$j] .'</a></p></div>' . 
		'<div class="ui-block-b" style="width:65%"><fieldset data-role="controlgroup" data-type="horizontal">' . 
		'<label for="' . $section_element_info[$i][$j] . '_a" id="'. $section_element_info_no_space[$i][$j] . '_a" class="' . $section_element_info_no_space[$i][$j] .'">Safe</label>' . 
		'<input type="radio" name="' . $section_element_info[$i][$j] . '" id="' . $section_element_info[$i][$j] . '_a" value="safe" onclick='."'".'handle_click("' .  $section_element_info_no_space[$i][$j] .'_a", "' . $section_element_info_no_space[$i][$j] .'", "green");' . "'" . '>' . 
		'<label for="' . $section_element_info[$i][$j] . '_b" id="'. $section_element_info_no_space[$i][$j] . '_b" class="' . $section_element_info_no_space[$i][$j] .'">N/A</label>' . 
		'<input type="radio" name="' . $section_element_info[$i][$j] . '" id="' . $section_element_info[$i][$j] . '_b" value="na" onclick='."'".'handle_click("'. $section_element_info_no_space[$i][$j] .'_b", "' . $section_element_info_no_space[$i][$j] .'", "blue");' . "'" . '>' . 
		'<label for="' . $section_element_info[$i][$j] . '_c" id="'. $section_element_info_no_space[$i][$j] . '_c" class="' . $section_element_info_no_space[$i][$j] .'">Risk</label>' . 
		'<input type="radio" name="' . $section_element_info[$i][$j] . '" id="' . $section_element_info[$i][$j] . '_c" value="risk" onclick='."'".'handle_click("' . $section_element_info_no_space[$i][$j] .'_c", "' . $section_element_info_no_space[$i][$j] .'", "red");' . "'" .'>' . 
		'</fieldset></div></div>';

		$section_element_output[] = array("element_name" => $section_element_info[$i][$j], "html_output" => $output_string);
	}
	
	$ajax_response[] = array("section_name" => $section_info[$i], "section_name_no_space" => $section_info_no_space[$i], "section_header_html" => $section_header_output, "section_footer_html" => $section_header_footer, "section_element_array" => $section_element_output);
	
}
echo json_encode($ajax_response);

?>
