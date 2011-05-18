<?php
	if (!isset($_GET['accesstoken'])) {
		exit(1);
	}
	
	echo session_start();
	$_SESSION['token'] = urldecode($_GET['accesstoken']);
	$_SESSION['userid'] = $_GET['id'];
	$_SESSION['username'] = $_GET['username'];
	$_SESSION['firstname'] = $_GET['firstname'];
	$_SESSION['lastname'] = $_GET['lastname'];
?>