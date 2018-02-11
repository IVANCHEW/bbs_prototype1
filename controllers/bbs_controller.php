<?php session_start();

function render_page($page_name = string, $data = array()){
	
	if($page_name=="not_logged_in"){
		
		//~ require("../views/not_logged_in.php");
		require("../views/test_view.php");
		
	}elseif($page_name=="profile_management"){
		
		require("../../views/profile_management.php");
		
	}
	
}

function include_jm_headers(){
	
	require("../../views/jm_headers.php");
	
}

?>
