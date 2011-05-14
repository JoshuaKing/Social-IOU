<?php
	require_once("backend/mysqli.php");
	$user = strip_tags($_COOKIE['user']);
	$userid = intval(strip_tags($_COOKIE['userid']));
	$password = $_COOKIE['password'];
	$loggedin = false;
	$email = "";
	
	
	try {
		$db = new dbWrapper();
		
		if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['password'])) {
			if ($_POST['email']=="Email for updates...")
				$_POST['email'] = "";
				
			if ($_POST['password']=="") {
				$db->q("UPDATE users SET name=?, email=? WHERE id=?","ssi",strip_tags($_POST['name']),strip_tags($_POST['email']),$userid);
			} else {
				$db->q("UPDATE users SET name=?, email=?, password=? WHERE id=?","sssi",strip_tags($_POST['name']),strip_tags($_POST['email']),$password,$userid);
			}
			$email = strip_tags($_POST['email']);
			$loggedin = true;
		} else {
			$details = $db->q("SELECT * FROM users WHERE id=? AND password=?","is",$userid,$password);
			if (sizeof($details)!=0) {	
				$loggedin = true;
				$email = $details[0]['email'];
			}
		}
	} catch (Exception $e) {
		echo "Sorry, there was an error.";
		exit();
	}
?>
<html>
<head>
	<link rel="stylesheet" href="css/main.css">
	<script src="jquery.js"></script>
	<script>
		$(document).ready(function() {
			if ("<?php echo $email; ?>" == "")
				$("#email").attr("value","Email for updates...");
		});
	</script>
</head>
<body>
<header><a href='./'>St Leo's College IOU System</a></header><span class='viewall'><!-- <a href='./viewall.php'>View All Debts About Me</a> |  --><a href='./login.php'>Login</a> | <a href='./account.php'>Account</a></span>
<form method="GET" target="_self"><input class="user" name="user" placeholder="View As User..."></input></form>
<?php
	if (!$loggedin) {
		echo "Please Login first.";
		exit();
	}
?>
<form action="./account.php" method="POST" style="margin-left:150px;">
<input name="name" type="text" class="textinput" style="width:250px;" placeholder="Name" value="<?php echo $user; ?>"></input><br/>
<input  id="email" name="email" type="text" class="textinput" style="width:250px;margin-top:5px;" placeholder="Email address for updates" value="<?php echo $email; ?>"></input><br/>
<input name="password" type="password" class="textinput" style="width:250px;margin-top:5px;" placeholder="Change Password"></input><br/>
<input type="submit" style="width:250px;margin-top:20px;font-weight:bold;" class="button" value="Update"></input>
</form>