<?php
session_start();
require_once("backend/mysqli.php");

try {
	$db = new dbWrapper();
	
	if (isset($_SESSION['token']) && isset($_POST['id'])) {
		$debt = $db->q("SELECT * FROM debts WHERE id=? AND (from_id=? OR to_id=?)","iss",$_POST['id'],$_SESSION['userid'],$_SESSION['userid']);
		if (sizeof($debt)) {
			$db->q("UPDATE debts SET paid='0000-00-00' WHERE id=?","i",$_POST['id']);
			echo "done";
		} else {
			header("HTTP/1.0 401 Unauthorised");
		}
	}
} catch (Exception $e) {
	echo "Sorry, there was an error.";
	exit();
}

?>