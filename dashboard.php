<?php
	session_start();
	require_once("backend/mysqli.php");
?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="css/green.css">
	<script src="js/jquery.js"></script>
	<script src="js/facebook.js"></script>
</head>

<body onload="dashboardload();">

<?php require_once("minihtml/header.html"); ?>

<div id="dashboard">
<?php
	if (!isset($_SESSION['token'])) {
		echo "<h1>Sorry</h1><h2>You need to log in first!</h2>";
		echo "</div></body></html>";
		exit();
	}
?>

<div id="profile">
	<img id="profilepicture" onerror="alert('Sorry, you need to log in again.');window.location.href = './?expired=true&redirect_to='+escape(window.location.href);" class='profilepicture' src="https://graph.facebook.com/me/picture?access_token=<?php echo $_SESSION['token']; ?>" />
	<h1 class="profileusername">Hi, <?php echo $_SESSION['username']; ?></h1>
</div>

<div id="headers">
	<h3 data-val="iou">Add IOU</h3>
	<h3 data-val="debt">Add Debt</h3>
	<h3 data-val="settings">Settings</h3>
</div><span id="headerarrow" class='arrow-up'></span><br/>
<form id="createiou" onsubmit="onIOUsubmit();return false;">
I owe <input id="message" placeholder="Debt"></input> to my friend 
<input id="to" name="to" placeholder="Friend"></input>
<input id="submit" type="submit" value="Add IOU" onclick="$('#friendsuggestions').html('');"></input>
</form>
<form id="createdebt" onsubmit="onDebtsubmit();return false;">
<input id="message" placeholder="Debt"></input> is owed to me by
<input id="to" name="to" placeholder="Friend"></input>
<input id="submit" type="submit" value="Add Debt" onclick="$('#friendsuggestions').html('');"></input>
</form>
<form id="editsettings" onsubmit="return false;">
<input id="autofbsubmit" class='regularbutton' type='button' value='Prompt for Facebook wall post.' onclick='togglefbsubmit(this);'></input>
<input id="showpaid" class='regularbutton' type='button' value='Show paid IOU's.' onclick='toggleshowpaid(this);'></input>
</form>
</section>

<div id="friendsuggestions"></div>
<div id="friendlist"></div>

<div class="devider"></div>
<section class="stats">
<header id="stats">Stats</header>
<span id="row"><span id="leftcolumn"></span><span id="centercolumn">
<?php
	try {
		$db = new dbWrapper();		
		
		$totalcashowed = $db->q("SELECT SUM(value_money) AS sum FROM debts WHERE value_item='' AND to_id=?","s",$_SESSION['userid']);
		$otherowed = $db->q("SELECT value_item AS value FROM debts WHERE value_item!='' AND to_id=?","s",$_SESSION['userid']);
		
		if (empty($totalcashowed[0]['sum'])) {
			echo "<section class='record'><header>No one owes you any cash.</header>";
		} else {
			echo "<section class='record'><header>You are owed <strong>$".$totalcashowed[0]['sum']."</strong></header>";
		}
		
		if (sizeof($otherowed)==0 && empty($totalcashowed[0]['sum'])) {
			echo "No one owes you any items, either.";
			echo "</section>";
		} else if (sizeof($otherowed)>0) {
			if (empty($totalcashowed[0]['sum'])) {
				echo "But you are owed ";
			} else {
				echo "And you are also owed ";
			}
			$first = true;
			foreach($otherowed as $item) {
				if (!$first) {
					echo ", ".$item['value'];
				} else {
					echo $item['value'];
					$first = false;
				}
			}
			echo "</section>";
		} else {
			echo "But you are owed no items.</section>";
		}
		
		$totalcashiou = $db->q("SELECT SUM(value_money) AS sum FROM debts WHERE value_item='' AND from_id=?","s",$_SESSION['userid']);
		$otherindebted = $db->q("SELECT value_item AS value FROM debts WHERE value_item!='' AND from_id=?","s",$_SESSION['userid']);
		
		if (empty($totalcashiou[0]['sum'])) {
			echo "<section class='record'><header>You owe no one cash.</header>";
		} else {
			echo "<section class='record'><header>You owe <strong>$".$totalcashiou[0]['sum']."</strong></header>";
		}
		
		if (sizeof($otherindebted)==0 && empty($totalcashiou[0]['sum'])) {
			echo "And you owe no items, either.";
			echo "</section>";
		} else if (sizeof($otherindebted)>0) {
			if (empty($totalcashiou[0]['sum'])) {
				echo "But you owe ";
			} else {
				echo "And you also owe ";
			}
			$first = true;
			foreach($otherindebted as $item) {
				if (!$first) {
					echo ", ".$item['value'];
				} else {
					echo $item['value'];
					$first = false;
				}
			}
			echo "</section>";
		} else {
			echo "But you owe no items.</section>";
		}
		
		if (empty($totalcashowed[0]['sum']) && empty($totalcashiou[0]['sum'])
				&& sizeof($otherindebted)==0 && sizeof($otherowed)==0) {
			//echo "<section class='record' style='text-align:center'><header>No stats available!</header></section>";	
		}
	} catch (Exception $e) {
		echo "Sorry, there was an error.<br/>".$e->getMessage();
		exit();
	}
?>
</span><span id="rightcolumn"></span></span></section>
<div class="devider"></div>

<section class="feed">
<header id="feed">Feed</header>
<span id="row"><span id="leftcolumn"></span><span id="centercolumn">
<section>
<?php
	try {
		$db = new dbWrapper();		
		
		$debts = $db->q("SELECT debts.*, IF(value_item='',CONCAT('$',value_money),value_item) AS value FROM debts WHERE from_id=? OR to_id=? ORDER BY made DESC","ss",$_SESSION['userid'],$_SESSION['userid']);
		if (sizeof($debts)==0) {
			echo "<section class='record'><header>You owe no one anything, and no one owes you!</header></section>";
		}
		foreach ($debts as $debt) {
			echo "<section class='record";
			if ($debt['paid']!='0000-00-00') {
				echo " recorddone'>";
			} else {
				echo "'>";
			}
			echo "<header><strong>".stripcslashes(strip_tags($debt['from']))."</strong> owes <strong>".stripcslashes(strip_tags($debt['value']))."</strong> to <strong>".stripcslashes(strip_tags($debt['to']))."</strong>.</header>";
			
			$comments = $db->q("SELECT * FROM comments WHERE debtid=?","i",$debt['id']);
			foreach ($comments as $comment) {
				echo "<article><h1>".strip_tags(stripcslashes($comment['author']))."</h1>";
				echo "<p>".strip_tags(stripcslashes($comment['comment']))."</p></article>";
			}
			echo "<form name='commentbox' onsubmit='dashboardcomment(".$debt['id'].",this); return false;'>";
			echo "<input id='comment' placeholder='Comment'></input>";
			echo "<input id='submit' type='submit' value='Comment'></input>";
			if ($debt['from_id']==$_SESSION['userid'] || $debt['to_id']==$_SESSION['userid']) {
				if ($debt['paid']!='0000-00-00') {
					echo "<input id='markundone' type='button' value='Mark Undone' onclick='markiouundone(".$debt['id'].",this);'></input>";
				} else {
					echo "<input id='markdone' name='".$debt['paid']."' type='button' value='Mark Done' onclick='markioudone(".$debt['id'].",this);'></input>";
				}
			}
			echo "</form></section>";
		}
	} catch (Exception $e) {
		echo "Sorry, there was an error.<br/>".$e->getMessage();
		exit();
	}
?>
</section>
</span><span id="rightcolumn"></span></span>
</div>
</body>
</html>
