<?php
	require_once("backend/mysqli.php");
	
	$failed = false;
	
	try {
		$db = new dbWrapper();
		if (isset($_POST['name']) && isset($_POST['password'])) {
			$details = $db->q("SELECT * FROM users WHERE name=? AND password=?","ss",strip_tags($_POST['name']),$_POST['password']);
			if (sizeof($details)!=0) {
				setcookie("user",$details[0]['name'],time()+3600*72);
				setcookie("userid",$details[0]['id'],time()+3600*72);
				setcookie("password",$details[0]['password'],time()+3600*72);
				header('Location: ./');
			} else {
				$details = $db->q("SELECT * FROM users WHERE name=?","s", strip_tags($_POST['name']));
				if (sizeof($details)==0) {
					$db->q("INSERT INTO users SET name=? AND password=?","ss", strip_tags($_POST['name']),$_POST['password']);
					setcookie("userid",$db->handle()->insert_id,time()+3600*72);
				} else if ($details[0]['password']=="") {
					$db->q("UPDATE users SET password=? WHERE id=?","si",$_POST['password'],$details[0]['id']);
					setcookie("userid",$details[0]['id'],time()+3600*72);
				}
				setcookie("user",strip_tags($_POST['name']),time()+3600*72);
				setcookie("password",$_POST['password'],time()+3600*72);
				header('Location: ./');
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
</head>
<body>
<header><a href='./'>St Leo's College IOU System</a></header><span class='viewall'><!-- <a href='./viewall.php'>View All Debts About Me</a> |  --><a href='./login.php'>Login</a> | <a href='./account.php'>Account</a></span>
<form method="GET" target="_self"><input class="user" name="user" placeholder="View As User..."></input></form>

<form action="./login.php" method="POST" style='margin-left:100px;margin-top:50px;'>
<?php
	if ($failed) {
		echo "<text style='color:red;'>Incorrect Details</text><br/>";
	}
?>
<input name="name" type="text" class="textinput" style="width:250px;" placeholder="Name" value="<?php echo $_COOKIE['user']; ?>"></input><br/>
<input name="password" type="password" class="textinput" style="width:250px;margin-top:5px;" placeholder="Password" value=""></input><br/>
<input type="submit" style="width:250px;margin-top:20px;font-weight:bold;" class="button" value="Log In"></input>
</form>