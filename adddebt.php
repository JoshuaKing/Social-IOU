<?php
	session_start();
	require_once("./backend/mysqli.php");
	$friendname = strip_tags($_POST['friendname']);
	$friendid = strip_tags($_POST['friendid']);
	$username = strip_tags($_SESSION['username']);
	$userid = intval($_SESSION['userid']);
	$value = strip_tags($_POST['message']);
	
	/* addiou add's a debt FROM the User, TO the Friend (User owes Friend)
	 * adddebt add's a debt TO the User, FROM the Friend (Friend owes User)
	 */
	try {
		$db = new dbWrapper();
		if ($friendid==0 || $friendid=="0") {
			$possibleuser = $db->q("SELECT IF(`from`=?,`from_id`,IF(`to`=?,`to_id`,?)) AS possibleid FROM debts WHERE `from`=? OR `to`=? LIMIT 1","sssss",$friendname,$friendname,$friendname,$friendname,$friendname);
			if (sizeof($possibleuser)>0) {
				$friendid = $possibleuser[0]['possibleid'];
			} else {
				$friendid = $username;
			}
		}
		
		$value_type = (is_numeric($value)) ? "value_money" : "value_item";
		$bindstr = (is_numeric($value)) ? "dssss" : "sssss";
		
		$db->q("INSERT INTO debts SET `$value_type`=?,`from_id`=?,`from`=?,`to_id`=?,`to`=?",$bindstr,$value,$friendid,$friendname,$userid,$username);
		echo $db->handle()->insert_id;
	} catch (Exception $e) {
		echo "Sorry, there was an error.".$e->getMessage();
		exit();
	}
?>