<?php
	require_once("backend/mysqli.php");
	$comment = strip_tags($_POST['comment']);
	$userid = intval(strip_tags($_COOKIE['userid']));
	$debtid = intval(strip_tags($_POST['debtid']));
	$user = strip_tags($_COOKIE['user']);
	try {
		$db = new dbWrapper();
		$comments = $db->q("INSERT INTO comments SET debtid=?, author=?, comment=?","iis",$debtid,$userid,$comment);
		
		$userstoupdate = $db->q("SELECT users.email,users.name,comments.*,debts.* FROM users,debts,comments WHERE comments.debtid=? AND users.id!=? AND users.email!='' AND (users.id=comments.author OR users.id=debts.from OR users.id=debts.to) GROUP BY users.email","ii",$debtid,$userid);
		
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'From: noreply@vierware.com'."\r\n";
		
		//echo "mailing...\n";
		foreach($userstoupdate as $user) {
			$message = "<html><head><title>New Post in IOU</title></head>";
			$message .= "<body>Hello ".$user['name'].",<br/>";
			$message .= strip_tags($_COOKIE['user'])." commented on a debt you registered to.<br/>To view, visit <a href='http://vierware.com/projects/iou/viewcomment.php?view=$debtid'>visit this link</a>.<br/>";
			$message .= "</body></html>";
			
			mail($user['email'], "New IOU Post by $user",$message,$headers);
		}
	} catch (Exception $e) {
		echo "Sorry, there was an error.".$e->getMessage();
		exit();
	}
?>