
<?php
	require_once("backend/mysqli.php");
	$userid = 0;
	if (isset($_COOKIE['userid'])) {
		$userid=intval(strip_tags($_COOKIE['userid']));
	} else if (isset($_GET['user'])) {
		$user = strip_tags($_GET['user']);
		try {
			$db = new dbWrapper();
			$userid = $db->q("SELECT id FROM users WHERE name=?","s",$user);
			$userid = $userid[0]['id'];
		} catch (Exception $e) {
			echo "Sorry, there was an error.";
			exit();
		}
	}
	try {
		$db = new dbWrapper();
		$userlist = $db->q("SELECT id,name FROM users WHERE id!=?","i",$userid);
		foreach ($userlist as $u) {
			$sumiou = $db->q("SELECT SUM(value_money) AS v FROM debts WHERE `from`=? AND `to`=?","ii",$userid,$u['id']);
			$sumdebt = $db->q("SELECT SUM(value_money) AS v FROM debts WHERE `to`=? AND `from`=?","ii",$userid,$u['id']);
			echo $u['name']." owes you: ".$sumdebt[0]['v']."<br/>";
			echo "You owe ".$u['name'].": ".$sumiou[0]['v']."<br/><br/>";
		}
	} catch (Exception $e) {
		echo "Sorry, there was an error.";
		exit();
	}
?>