<?php
	require_once("backend/mysqli.php");
	$to = strip_tags($_POST['to']);
	$toid = 0;
	$email = "";
	$noticename = "";
	try {
		$db = new dbWrapper();
		$toid = $db->q("SELECT * FROM users WHERE name=?","s",$to);
		if (sizeof($toid)==0) {
			$db->q("INSERT INTO users SET name=?","s",$to);
			$toid = $db->handle()->insert_id;
			//echo "New ID for $to: $toid";
		} else {
			$email = $toid[0]['email'];
			$noticename =$toid[0]['name'];
			$toid = $toid[0]['id'];
			//echo "ID for $to is $toid";
		}
		$from = intval(strip_tags($_POST['id']));
		$value = strip_tags($_POST['value']);
		$value_type = (is_double($value) || is_int($value)) ? "value_money" : "value_item";
		$bindstr = (is_double($value) || is_int($value)) ? "dii" : "sii";
		
		$db->q("INSERT INTO debts SET `$value_type`=?,`from`=?,`to`=?",$bindstr,$value,$from,$toid);
		
		if ($email && $_COOKIE['userid']!=$from) {
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'From: noreply@vierware.com'."\r\n";
			
			//echo "mailing...\n";
			$message = "<html><head><title>New Post in IOU</title></head>";
			$message .= "<body>Hello $noticename,<br/>";
			$message .= strip_tags($_COOKIE['user'])." has declared to owing you a debt.<br/>To view, visit <a href='http://vierware.com/projects/iou/viewcomment.php?view=".$db->handle()->insert_id."'>visit this link</a>.<br/>";
			$message .= "</body></html>";
			
			mail($email, "New IOU Post by ".strip_tags($_COOKIE['user']),$message,$headers);
		}
		header("Location: ./?user=".urlencode($_COOKIE['user']));
	} catch (Exception $e) {
		echo "Sorry, there was an error.";
		exit();
	}
?>