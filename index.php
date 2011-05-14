<?php
	require_once("backend/mysqli.php");
	$user = "";
	$userid = 0;
	$userlist = "";
	
	if (isset($_GET['user'])) {
		$_GET['user'] = urldecode($_GET['user']);
		$user = strip_tags($_GET['user']);
		try {
			$db = new dbWrapper();
			$userid = $db->q("SELECT id FROM users WHERE name=?","s",$user);
			
			if (sizeof($userid)==0) {
				$db->q("INSERT INTO users SET name=?","s",$user);
				$userid = $db->handle()->insert_id;
				//echo "Created User $userid";
			} else {
				$userid = $userid[0]['id'];
			}
		} catch (Exception $e) {
			echo "Sorry, there was an error [1].";
			exit();
		}
	} else if (isset($_COOKIE['userid'])) {
		$userid=intval(strip_tags($_COOKIE['userid']));
		try {
			$db = new dbWrapper();
			$user = $db->q("SELECT name FROM users WHERE id=?","i",$userid);
			$user = $user[0]['name'];
		} catch (Exception $e) {
			echo "Sorry, there was an error [2].";
			exit();
		}
	}
?>
<DOCTYPE html>
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
			$.post("./submitcomment.php","comment="+escape(comment)+"&debtid="+id);//.success(function(a,b,c){alert(a+" "+b+" "+c);});
			
			$(form).find(".feed").append("<div class='comment'>"
				+ "<span class='author'><?php echo $_COOKIE['user']; ?></span>"
				+ "<comment>"+comment+"</comment>"
				+ "</div>");
		}
	</script>
</head>
<body>
<header><a href='./'>St Leo's College IOU System</a><span class='viewall'><!-- <a href='./viewall.php'>View All Debts About Me</a> |  --><a href='./login.php'>Login</a> | <a href='./account.php'>Account</a></span></header>
<form method="GET" target="_self"><input class="user" name="user" placeholder="View As User..."></input></form>
<?php
	try {
		$db = new dbWrapper();
		$userlist = $db->q("SELECT name FROM users");
		echo "<span class='userlist'>";
		$i = 0;
		foreach ($userlist as $u) {
			if (!$i) {
				echo "<a href='./?user=".urlencode(stripcslashes($u['name']))."'>".stripcslashes($u['name'])."</a>";
				$i = 1;
			} else {
				echo " | <a href='./?user=".urlencode(stripcslashes($u['name']))."'>".stripcslashes($u['name'])."</a>";
			}
		}
		$userlisthtml = "<datalist id='userlist'>";
		foreach ($userlist as $u) {
			$userlisthtml .= "<option value=\"".urlencode($u['name'])."\">";
		}
		$userlisthtml .= "</datalist>";
		echo "</span>";
	} catch (Exception $e) {
		echo "Sorry, there was an error. [3]";
		exit();
	}
	if (!$userid) {
		echo "<div class='centerpage'>";
		echo "<form method='POST' target='_self' action='setcookie.php'>";
		echo "<input class='textinput' type='text' name='user' list='userlist' placeholder='What is your name?'></input>";
		echo "$userlisthtml";
		echo "</form></div>";
		exit();
	}
?>
<section>
<h1><?php echo stripcslashes($user); ?> Owes....</h1>
<form class="addform" method="POST" target="_self" action="./adddebt.php">
<input type="hidden" name="id" value="<?php echo $userid; ?>"></input>
<input class='textinput' name="to" type="text" placeholder="Name"></input>
<input class='textinput' name="value" type="text" placeholder="Value"></input>
<input class='button' type="submit" value="Add Debt"></input><br/>
<small>Value may be either a currency (eg. 15.20) or an item (eg. 3 beers)</small>
</form>
<?php
	/* Total & articles of those people you owe */
	try {
		$query = "SELECT debts.id, "
		. "IF(debts.due='0000-00-00','ASAP',DATE_FORMAT(debts.due,'%d %b %Y')) AS due, "
		. "DATE_FORMAT(debts.made,'%d %b %Y') AS made, "
		. "IF(debts.paid='0000-00-00','NOTPAID',DATE_FORMAT(debts.paid,'%d %b %Y')) AS paid, "
		. "(SELECT users.name FROM users WHERE id=debts.from) AS fromuser, "
		. "(SELECT users.name FROM users WHERE id=debts.to) AS touser, "
		. "IF(debts.value_item='',CONCAT('$',debts.value_money),debts.value_item) AS value "
		. "FROM debts WHERE `from`=? AND debts.paid='0000-00-00' "
		. "ORDER BY debts.due ASC";
		
		$owed = $db->q($query,"i",$userid);
		if (sizeof($owed)==0) {
			echo "<div class='green'>You owe nobody anything :)</div>";
		} else {
			$total = $db->q("SELECT SUM(debts.value_money) AS total FROM debts WHERE `from`=?","i",$userid);
			$total = $total[0]['total'];
			echo "<mark>Total: $".$total."<br/></mark>";
		}
		
		foreach ($owed as $row) {
			echo "<article id='".$row['id']."'><input type='button' class='done' value='done' onclick=\"remove(".$row['id'].")\"></input><input type='button' class='edit' value='edit' onclick=\"edit(".$row['id'].")\"></input>$user owes <mark><a href='./?user=".urlencode($row['touser'])."'>".$row['touser']."</a></mark> ".$row['value']."<br/>"
			. "<small>Due: <span>".$row['due']."</span></small><br/>"
			. "<small>Created: <span>".$row['made']."</span></small>"
			. "</article>";
			
			$comments = $db->q("SELECT comments.*, (SELECT name FROM users WHERE users.id=comments.author) AS name FROM comments WHERE debtid=? ORDER BY posted ASC","i",$row['id']);
			
			
			echo "<form onsubmit='postcomment(this,".$row['id']."); return false;'>";	
			
			echo "<div class='feed'>";
			foreach ($comments as $comment) {
				echo "<div class='comment'>";
				echo "<span class='author'>".$comment['name']."</span>";
				echo "<comment>".stripcslashes($comment['comment'])."</comment>";
				echo "</div>";
			}
			echo "</div>";
			echo "<input class='textinput' id='comment' list='userlist' style='width:200px;'></input>";
			echo "$userlisthtml";
			echo "<input class='button' style='position:absolute;' type='submit' value='Post Comment'></input><br/>";
			echo "</form>";
			echo "</article>";
		}
	} catch (Exception $e) {
		echo "Sorry, there was an error in amounts owed to you.";
	}
?>
</section>
<section>
<h1>Owed to <?php echo stripcslashes($user); ?>....</h1>
<form class="addform" method="POST" target="_self" action="./addiou.php">
<input type="hidden" name="id" value="<?php echo $userid; ?>"></input>
<input class='textinput' name="from" type="text" placeholder="Name"></input>
<input class='textinput' name="value" type="text" placeholder="Value"></input>
<input class='button' type="submit" value="Add IOU"></input><br/>
<small>Value may be either a currency (eg. 15.20) or an item (eg. 3 beers)</small>
</form>
<?php
	/* Total & articles of those people who owe you */
	try {
		$query = "SELECT debts.id, "
		. "IF(debts.due='0000-00-00','ASAP',DATE_FORMAT(debts.due,'%d %b %Y')) AS due, "
		. "DATE_FORMAT(debts.made,'%d %b %Y') AS made, "
		. "IF(debts.paid='0000-00-00','NOTPAID',DATE_FORMAT(debts.paid,'%d %b %Y')) AS paid, "
		. "(SELECT users.name FROM users WHERE id=debts.from) AS fromuser, "
		. "(SELECT users.name FROM users WHERE id=debts.to) AS touser, "
		. "IF(debts.value_item='',CONCAT('$',debts.value_money),debts.value_item) AS value "
		. "FROM debts WHERE `to`=? AND debts.paid='0000-00-00' "
		. "ORDER BY debts.due ASC";
		
		$owed = $db->q($query,"i",$userid);
		if (sizeof($owed)==0) {
			echo "<div class='green'>Nobody owes you anything :)</div>";
		} else {
			$total = $db->q("SELECT SUM(debts.value_money) AS total FROM debts WHERE `to`=?","i",$userid);
			$total = $total[0]['total'];
			echo "<mark>Total: $".$total."<br/></mark>";
		}
		
		foreach ($owed as $row) {
			echo "<article id='".$row['id']."'><input type='button' class='done' value='done' onclick=\"remove(".$row['id'].")\"></input><input type='button' class='edit' value='edit' onclick=\"edit(".$row['id'].")\"></input><mark><a href='./?user=".urlencode($row['fromuser'])."'>".$row['fromuser']."</a></mark> owes ".$row['value']."<br/>"
			. "<small>Due: <span>".$row['due']."</span></small><br/>"
			. "<small>Created: <span>".$row['made']."</span></small>";
			
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
			echo "<input class='textinput' id='comment' list='userlist' style='width:200px;'></input>";
			echo "$userlisthtml";
			echo "<input class='button' style='position:absolute;' type='submit' value='Post Comment'></input><br/>";
			echo "</form>";
			echo "</article>";
		}
	} catch (Exception $e) {
		echo "Sorry, there was an error in amounts owed to you.";
	}
?>
</section>
</body>
</html>