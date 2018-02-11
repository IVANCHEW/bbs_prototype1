<?php

$section_info = array("Personal Safety", "Body Position", "Tools and Equipment", "Environment", "Procedures", "Team");
$section_info_no_space = array();

// Remove white spaces
for($i=0; $i<count($section_info); $i++){
	$section_info_no_space[] = str_replace(' ', '', $section_info[$i]);
}

$section_element_info = array(
	array("Personal Readiness", "Head Protection", "Face and Eyes Protection", "Respiratory Protection", "Hand Protection", "Body Protection", "Foot Protection", "Hearing Protection", "Fall Protection"),
	array("Line of Fire", "Eyes on Tasks", "Body Posture"), 
	array("Selection and Condition", "Eqp Ops And Han"),
	array("House Keeping", "Storage and Segregation", "Surface Condition", "Environmental Condition"),
	array("Follow SOP and Procedures", "Hazard Controls"),
	array("Supervision", "Team CRM")
);

// Remove white spaces
$section_element_info_no_space = array();
for($i=0; $i<count($section_element_info); $i++){
	$section_element_info_no_space[$i] = array();
	for($j=0; $j<count($section_element_info[$i]); $j++){
		$section_element_info_no_space[$i][] = str_replace(' ', '', $section_element_info[$i][$j]);
	}
}

$section_element_content = array(
	array("Healthy and not in a fatigue state etc", 
	"Personal Readiness: Use of Safety helmet for protection against falling objects etc", 
	"Head Protection: Use of glasses, face shield or goggles for protection against flying debris or contact with chemical etc", 
	"Respiratory Protection: Use of appropriate masks for protection against inhalation of pollutants and toxic fumes etc", 
	"Hand Protection: Protection against electrical shocks, cuts, moving parts, falling objects or use of gloves when contact with chemical etc", 
	"Body Protection: Use of protective body suit or seat belt for protection against flying debris, electrical shocks, cuts, moving parts, falling objects, contact with chemical or collision etc", 
	"Foot Protection: Use of safety boots for protection against electrical shock, cuts or falling objects etc", 
	"Hearing Protection: Use of ear plug or eat muff in noisy environment etc", 
	"Fall Protection: Use of safety harness, railing, 3-point-contact for protection against slip and fall etc"),
	array("Line of Fire: Keeping a safe distance or body parts clear from flying debris, moving parts, high pressure, hot surface/steam, raised/hoisted object, moving or reversing vehicle etc", 
	"Eyes on Tasks: Attentive to task, clear line of sight, vision on tasks at all time etc", 
	"Body Posture: Body posture not in an awkward position / restrictive for lifting/lowering or accessing or operating an equipment or vehicle etc"), 
	array("Selection and Condition: Use of appropriate and servicable tools/equipment (including ladders, trolleys etc) which are calibrated or in good working conditions (pre-use check conducted, no loose articles, worn-out, dusty, rusty, leak or not restrictive, appropriate safeguards are available etc) to perform the task", 
	"Eqp. Ops. And H.: Qualified and authorised to operate equipment (vehicle, crane, tractor or forklift etc). Tools and equipment is operated/handled in a correct, controlled, secured and in a safe manner etc. Equipment is not left unattended and switched off when not in-use. Tools and equipment are properly accounted and returned after use etc"),
	array("House Keeping: Tidy, neat and clean workplace", 
	"Storage and Segregation: Items are appropriately stored, tools / equipment / components (servicable and unservicable) are properly segregated and labelled and no risk of falling", 
	"Surface Condition: Working area is not slippery, surface is flat and flushed, no risk of trip, slip and fall", 
	"Environmental Condition: Adequate lighting, exposure to excessive heat, lightning risk, proper ventiation, confined spaces etc"),
	array("Follow SOP and Procedures: Use of appropriate and updated manuel or checklist. Adhere to SOP and procedures to perform maintenance task etc. Loose articles are accounted, secured and properly kept to prevent FOD.", 
	"Hazard Controls: Available and use of barricade, warning signage, poster and safety guard to caution others or isolate hazards (e.g. to prevent unessential personnel from entering danger zone or operating defective appliances). Users are aware of potential hazards and are taking precautions necessary to protect themselves"),
	array("Supervision: Presence of supervision or co-worker to cross-check to ensure task is carried out correctly and in a safe manner", 
	"Team CRM: Effective CRM among team members is demonstrated to ensure task is carried out correctly and in a safe manner")
);

?>
