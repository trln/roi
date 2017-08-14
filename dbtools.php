<?php
	/*
		TRLN ROI Database Reporting Project, Database Report Helper
		Include For 'simplereport_new.php'
		Author: Joseph Leonardi
	*/

	//Info for roi_user test account
	$dbhost = $_SERVER['ROI_DB_HOST'];
	$dbuser = $_SERVER['ROI_DB_USER'];;
	$dbpass = $_SERVER['ROI_DB_PASS'];;
	$dbname = $_SERVER['ROI_DB_NAME'];;
	$db = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
	if($db->connect_error) {
		$error = $db->connect_error;
	}
	
	//Function for generating select menus
	function create_menu(&$items, $category, $default) {
		$selector = "";
		foreach($items as $i) {
			if(!$_GET) {
				$j = $default;
			} else {
				$j = $_GET[$category];
			}
			if($i == $j) {
				$selector = 'selected';
			} else {
				$selector = "";
			}
			echo "<option value=\"$i\"$selector>$i</option>";
		}
	}
	
	//Function for sanitizing inputs
	function check($input, $numeric, $db) {
		if(is_numeric($input)) {
			return $input;
		} else {
			if($numeric) {
				if($input != '') {
					die("Invalid input. Non-number entered for a numeric field"); 
				}
			}
			$str = $db->real_escape_string($input);
			return $str;
		}
	}
?>