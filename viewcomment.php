<?php
	session_start();
	require_once("backend/mysqli.php");
	$viewid = intval($_GET['id']);
?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="css/green.css">
	<link rel="stylesheet" href="css/viewcomment.css">
	<script src="js/jquery.js"></script>
	<script src="js/facebook.js"></script>
	<script>
		function iouundone(id, button, refresh) {
			$(button).attr("disabled","true");
			$(button).attr("value","Working...");
			$.post("./markundone.php","id="+id).success(function() {
				$("#centercolumn").removeClass("recorddone");
				$("#centercolumn").addClass("recordundone");
				$(button).replaceWith("<input id='markdone' type='button' value='Mark Done' onclick='ioudone("+id+",this);'></input>");
				if (refresh)
					window.location.reload(true);
			}).error(function() {
				alert("Sorry, you need to own the IOU to mark it as done.");
			});
		}
		
		function ioudone(id, button, refresh) {
			$(button).attr("disabled","true");
			$(button).attr("value","Working...");
			$.post("./markdone.php","id="+id).success(function() {
				$("#centercolumn").removeClass("recordundone");
				$("#centercolumn").addClass("recorddone");
				$(button).replaceWith("<input id='markundone' type='button' value='Mark Undone' onclick='iouundone("+id+",this);'></input>");
				if (refresh)
					window.location.reload(true);
			}).error(function() {
				alert("Sorry, you need to own the IOU to mark it as undone.");
			});
		}
	</script>
	<?php require_once("minihtml/analytics.html"); ?>
</head>

<body>
<?php require_once("minihtml/header.html"); ?>
<div id="dashboard">
<?php
	if (isset($_SESSION['token']) && !empty($_SESSION['token'])) {
		echo "<div id='profile'>";
		echo "<img class='profilepicture' onerror=\"alert('Sorry, you need to log in again.');window.location.href = './?expired=true&redirect_to='+escape(window.location.href);\" src='https://graph.facebook.com/me/picture?access_token=".$_SESSION['token']."' />";
		echo "<h1 class='profileusername'>Hi, ".$_SESSION['username']."</h1>";
		echo "</div>";
	}
?>

<section><header>
<?php
	$found = true;
	try {
		$db = new dbWrapper();		
		
		$debt = $db->q("SELECT debts.*,IF(value_item='',CONCAT('$',value_money),value_item) AS value FROM debts WHERE id=?","i",$viewid);
		if (sizeof($debt)>0) {
			$debt = $debt[0];
			echo "<strong>".$debt['from']."</strong> owes <strong>".$debt['value']."</strong> to <strong>".$debt['to']."</strong>";
		} else {
			echo "Sorry, we could not find that IOU.";
			$found = false;
		}
	} catch (Exception $e) {
		echo "Sorry, there was an error.<br/>";
		exit();
	}
?>
</header><span id="row"><span id="leftcolumn"></span><span id="centercolumn" class='record
<?php
	if ($debt['paid']!='0000-00-00')
		echo " recorddone'>";
	else
		echo "'>";
	try {
		$comments = $db->q("SELECT * FROM comments WHERE debtid=?","i",$_GET['id']);
		if ($found && sizeof($comments)==0) {
			echo "<article>No comments, be the first!</article>";
		} else if ($found) {
			foreach($comments as $comment) {
				echo "<article><h1>".strip_tags($comment['author'])."</h1>";
				echo "<p>".strip_tags($comment['comment'])."</p></article>";
			}
		}
	} catch (Exception $e) {
		echo "Sorry, there was an error.<br/>";
		exit();
	}
	
	if (!$found) {
		echo "</span><span id='rightcolumn'></span></span>";
		echo "</body></html>";
		exit();
	}
	
	if (!isset($_SESSION['token']) || empty($_SESSION['token'])) {
		echo "</span><span id='rightcolumn'></span></span>";
		echo "<article><span id='fblogin' onclick=\"fb_login('viewcomment.php?id=$viewid');\"><span class='facebookfontgreyed'>facebook</span> Instant Login</span>";
		echo "<span class='dot'></span><span class='dot'></span><span class='dot'></span><span class='dot' style='margin-left:20px;'></span>";
		echo "<h1 style='float:right;padding-top:25px;'>Login to Comment</h1></article>";
		echo "</section></div></body></html>";
		exit();
	}
?>
<form onsubmit="submitcomment(<?php echo $viewid; ?>,this,true); return false;" id="commentarea">
<input id="comment" name="comment" placeholder="Comment here"></input>
<input id="postcomment" type="submit" value="Post"></input>
<?php
	if ($debt['from_id']==$_SESSION['userid'] || $debt['to_id']==$_SESSION['userid']) {
		if ($debt['paid']!='0000-00-00') {
			echo "<input id='markundone' type='button' value='Mark Undone' onclick='iouundone(".$debt['id'].",this);'></input>";
		} else {
			echo "<input id='markdone' name='".$debt['paid']."' type='button' value='Mark Done' onclick='ioudone(".$debt['id'].",this);'></input>";
		}
	}
?>
</form></span>
<span id="rightcolumn"></span>
</span>
</section>
</div>
</body>
</html>
