<?php
	require_once("backend/mysqli.php");
	$from = strip_tags($_POST['from']);
	$fromid = 0;
	try {
		$db = new dbWrapper();
		$fromid = $db->q("SELECT id FROM users WHERE name=?","s",$from);
		if (sizeof($fromid)==0) {
			//print_r($fromid);exit();
			$db->q("INSERT INTO users SET name=?","s",$from);
			$fromid = $db->handle()->insert_id;
			//echo "New ID for $to: $toid";
		} else {
			$fromid = $fromid[0]['id'];
			//echo "ID for $to is $toid";
		}
		$to = intval(strip_tags($_POST['id']));
		$value = strip_tags($_POST['value']);
		$value_type = (is_float($value)) ? "value_money" : "value_item";
		$bindstr = (is_float($value)) ? "dii" : "sii";
		
		$db->q("INSERT INTO debts SET `$value_type`=?,`from`=?,`to`=?",$bindstr,$value,$fromid,$to);
		$insertid = $db->handle()->insert_id;
		
		$email = $db->q("SELECT email,name FROM users WHERE id=?","i",$to);
		if (sizeof($email)!=0) {
			$noticename = $email[0]['name'];
			$email = $email[0]['email'];
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'From: noreply@vierware.com'."\r\n";
			
			$message = "<html><head><title>New Post in IOU</title></head>";
			$message .= "<body>Hello $noticename,<br/>";
			$message .= strip_tags($_COOKIE['user'])." requested you pay a debt.<br/>To view, visit <a href='http://vierware.com/projects/iou/viewcomment.php?view=$insertid'>visit this link</a>.<br/>";
			$message .= "</body></html>";
			
			mail($email, "New IOU Post by ".strip_tags($_COOKIE['user']),$message,$headers);
		}
		
		header("Location: ./?user=".urlencode($_COOKIE['user']));
	} catch (Exception $e) {
		echo "Sorry, there was an error.";
		exit();
	}
?>