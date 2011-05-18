<?php
session_start();
require_once("backend/mysqli.php");

try {
	$db = new dbWrapper();
	
	if (isset($_SESSION['token']) && isset($_POST['comment']) && isset($_POST['id'])) {
		$author = strip_tags($_SESSION['username']);
		$authorid = strip_tags($_SESSION['userid']);
		$comment = strip_tags($_POST['comment']);
		$debtid = intval($_POST['id']);
		$db->q("INSERT INTO comments SET author=?, author_id=?, comment=?, debtid=?","sssi",$author,$authorid, $comment, $debtid);
	}
} catch (Exception $e) {
	echo "Sorry, there was an error.<br/>";
	exit();
}

?>