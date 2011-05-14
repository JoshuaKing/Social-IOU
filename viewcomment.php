<?php
	require_once("backend/mysqli.php");
	$user = strip_tags($_COOKIE['user']);
	$userid = intval(strip_tags($_COOKIE['userid']));
	$password = $_COOKIE['password'];
	$loggedin = false;
	$email = "";
	
	
	try {
		$db = new dbWrapper();
		
		
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
		function remove(item) {
			$("#"+item).fadeOut("slow");
			$.post("./markdone.php","itemid="+item);
		}
		
		function postcomment(form, id) {
			var comment = $(form).find("#comment").val();
			$(form).find("#comment").attr("value","");
			$.post("./submitcomment.php","comment="+escape(comment)+"&debtid="+id);
			
			$(form).find(".feed").append("<div class='comment'>"
				+ "<span class='author'><?php echo $_COOKIE['user']; ?></span>"
				+ "<comment>"+comment+"</comment>"
				+ "</div>");
		}
	</script>
</head>
<body>
<header><a href='./'>St Leo's College IOU System</a></header><span class='viewall'><!-- <a href='./viewall.php'>View All Debts About Me</a> |  --><a href='./login.php'>Login</a> | <a href='./account.php'>Account</a></span>
<form method="GET" target="_self" action="./"><input class="user" name="user" placeholder="View As User..."></input></form>
<?php
	try {
		$query = "SELECT debts.id, "
		. "IF(debts.due='0000-00-00','ASAP',DATE_FORMAT(debts.due,'%d %b %Y')) AS due, "
		. "DATE_FORMAT(debts.made,'%d %b %Y') AS made, "
		. "IF(debts.paid='0000-00-00','NOTPAID',DATE_FORMAT(debts.paid,'%d %b %Y')) AS paid, "
		. "(SELECT users.name FROM users WHERE id=debts.from) AS fromuser, "
		. "(SELECT users.name FROM users WHERE id=debts.to) AS touser, "
		. "IF(debts.value_item='',CONCAT('$',debts.value_money),debts.value_item) AS value "
		. "FROM debts WHERE `id`=? ";
		
		$owed = $db->q($query,"i",intval($_GET['view']));
		$row = $owed[0];
		echo "<div style='margin-left:50px;'>";
		
		if ($row['paid']!="NOTPAID") {
			echo "<article id='".$row['id']."'><input type='button' class='done' value='done' onclick=\"remove(".$row['id'].")\"></input><input type='button' class='edit' value='edit' onclick=\"edit(".$row['id'].")\"></input><mark><a href='./?user=".urlencode($row['fromuser'])."'>".$row['fromuser']."</a></mark> owes ".$row['value']."<br/>"
			. "<small style='color:green;'>Paid: <span>".$row['paid']."</span></small><br/>"
			. "<small>Due: <span>".$row['due']."</span></small>"
			. "</article>";
		} else {
			echo "<article id='".$row['id']."'><input type='button' class='done' value='done' onclick=\"remove(".$row['id'].")\"></input><input type='button' class='edit' value='edit' onclick=\"edit(".$row['id'].")\"></input><mark><a href='./?user=".urlencode($row['fromuser'])."'>".$row['fromuser']."</a></mark> owes ".$row['value']."<br/>"
			. "<small>Due: <span>".$row['due']."</span></small><br/>"
			. "<small>Created: <span>".$row['made']."</span></small>"
			. "</article>";
		}
		
		$comments = $db->q("SELECT comments.*, (SELECT name FROM users WHERE users.id=comments.author) AS name FROM comments WHERE debtid=? ORDER BY posted ASC","i",$row['id']);

		
		echo "<form onsubmit='postcomment(this,".$row['id']."); return false;'>";	
		
		echo "<div class='feed'>";
		foreach ($comments as $comment) {
			echo "<div class='comment'>";
			echo "<span class='author'>".$comment['name']."</span>";
			echo "<comment>". stripcslashes($comment['comment'])."</comment>";
			echo "</div>";
		}
		echo "</div>";
		echo "<input class='textinput' id='comment' style='width:200px;'></input><input class='button' type='submit' value='Post Comment'></input><br/>";
		echo "</form>";
		echo "</div>";
	} catch (Exception $e) {
		echo "Sorry, there was an error in amounts owed to you.".$e->getMessage();
	}
?>