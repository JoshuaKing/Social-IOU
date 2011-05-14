<?php

require_once("backend/mysqli.php");
$user = strip_tags($_POST['user']);
$userid = 0;
try {
	$db = new dbWrapper();
	$userid = $db->q("SELECT id FROM users WHERE name=?","s",$user);
	$userid = $userid[0]['id'];
} catch (Exception $e) {
	echo "Sorry, there was an error.";
	exit();
}

setcookie("user",$user,time()+3600*72);
setcookie("userid",$userid,time()+3600*72);
header("Location: ./?user=$user");

?>