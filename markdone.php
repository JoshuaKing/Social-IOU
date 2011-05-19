<?php
session_start();
require_once("backend/mysqli.php");

try {
	$db = new dbWrapper();
	
	if (isset($_SESSION['token']) && isset($_POST['id'])) {
		$debtid = intval($_POST['id']);
		$debt = $db->q("SELECT * FROM debts WHERE id=? AND (from_id=? OR to_id=?)","iss",$_POST['id'],$_SESSION['userid'],$_SESSION['userid']);
		if (sizeof($debt)) {
			$date = date("Y-m-d");
			$db->q("UPDATE debts SET paid=? WHERE id=?","si",$date,$_POST['id']);
			echo $date;
		} else {
			header("HTTP/1.0 401 Unauthorised");
		}
	}
} catch (Exception $e) {
	echo "Sorry, there was an error.";
	exit();
}

?>