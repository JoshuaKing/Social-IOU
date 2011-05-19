<?php
	require_once("backend/mysqli.php");
	
	try {
		$db = new dbWrapper();
		$page = isset($_GET['p']) ? $_GET['p'] : "Unknown";
		$db->q("INSERT INTO visitorlog SET ip=?,xforwarded=?,page=?, useragent=?", "ssss", $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_X_FORWARDED_FOR'], $page, $_SERVER['HTTP_USER_AGENT']);
	} catch(Exception $e) {
		//meh
	}
?>