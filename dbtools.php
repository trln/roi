<?php
	//Info for roi_user test account
	//TODO: Switch to env variables
	$dbhost = "localhost";
	$dbuser = "roi_user";
	$dbpass = "FlyingBottleStickyNote";
	$dbname = "roi";
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
?>