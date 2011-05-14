<?php
	require_once("backend/mysqli.php");
	try {
		$db = new dbWrapper();
		$date = date("Y-m-d");
		$db->q("UPDATE debts SET paid=? WHERE id=?","si",$date,intval(strip_tags($_POST['itemid'])));
	} catch (Exception $e) {
		echo "Sorry, there was an error.";
		exit();
	}
?>