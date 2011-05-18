function fb_login() {
	var url = "https://www.facebook.com/dialog/oauth?display=popup&response_type=token";
	url += "&client_id=221074584585871";
	url += "&redirect_uri=http://vierware.com/projects/iou/dev/checklogin.html";
	var w = window.open(url,"Facebook Login","height=400,width=480,left="+(screen.width-480)/2+",top="+(screen.height-400)/2);
	w.focus();
}

function dashboardload() {	
	if (localStorage["JSON_FriendList"]) {
		populateFriendList();
	} else {	
		var url = "https://graph.facebook.com/me/friends?callback=storeFriendList&access_token="+localStorage["token"];
		var friendlist = $("<script />").attr("id","friendlist").attr("src",url);
		$("head").append(friendlist.get());
	}
	
	$("[name=to]").keyup(function(e) {
		$("#friendsuggestions").html("");
		if (e.keyCode == 27) {
			return;
		}
		var c = String.fromCharCode(e.keyCode);
		var partial = $(e.target).val();
		var list = $("#friendlist [id^=\""+partial.toLowerCase()+"\"]");
		
		for (var i = 0; i<list.length && i<5; i++) {
			if (list.eq(i).find("img").attr("src")==undefined) {
				var url = "https://graph.facebook.com/"+list.eq(i).attr("data-id")+"/picture?access_token="+localStorage["token"];
				list.eq(i).find("img").attr("src",url);
			}
			var clone = list.eq(i).clone();
			$("#friendsuggestions").append(clone);
		}
	});
	
	$("#headers h3").click(function(header) {
		$("#friendsuggestions").html("");
		var val = $(header.target).attr("data-val");
		if (val=="debt") {
			$("#headerarrow").css("margin-left","294px");
			$("#createdebt #to").attr("value","").html("");
			$("#createdebt #message").attr("value","").html("");
			$("#createiou").hide();
			$("#createdebt").show();
			$("#friendsuggestions").css({left:350});
		} else {
			$("#headerarrow").css("margin-left","94px");
			$("#createiou #to").attr("value","").html("");
			$("#createiou #message").attr("value","").html("");
			$("#createiou").show();
			$("#createdebt").hide();
			$("#friendsuggestions").css({left:280});
		}
	});
}

function onIOUsubmit() {
	if ($("#friendsuggestions").children().length>0) {
		var friendname = $("#friendsuggestions").children(0).find("span").html();
		var friendid = $("#friendsuggestions").children(0).attr("data-id");
		var message = $("#createiou #message").val();
		create_iou(friendid,friendname,message);
		return;
	} else {
		var friendname = $("#createiou #to").val();
		var message = $("#createiou #message").val();
		create_iou(0,friendname,message);
	}
}

function onDebtsubmit() {
	if ($("#friendsuggestions").children().length>0) {
		var friendname = $("#friendsuggestions").children(0).find("span").html();
		var friendid = $("#friendsuggestions").children(0).attr("data-id");
		var message = $("#createdebt #message").val();
		create_debt(friendid,friendname,message);
		return;
	} else {
		var friendname = $("#createdebt #to").val();
		var message = $("#createdebt #message").val();
		create_debt(0,friendname,message);
	}
}

function chooseFriend(div) {
	var friendname = $(div).find("span").html();
	var friendid = $(div).attr("data-id");
	$("#friendsuggestions").html("");

	if ($("#createiou:visible").length==0) {
		var message = $("#createdebt #message").val();
		create_debt(friendid,friendname,message);

	} else{
		var message = $("#createiou #message").val();
		create_iou(friendid,friendname,message);
	}
}

function populateFriendList() {
	var json_friends = JSON.parse(localStorage["JSON_FriendList"]);
	
	for (var i=0;i<json_friends.length; i++) {
		var el = json_friends[i];
		$("#friendlist").append("<div onclick='chooseFriend(this);' id='"+el["name"].toLowerCase()+"' data-id='"+el["id"]+"'><img class='smallprofile'></img><span>"+el["name"]+"</span></div>");
	}
}

function storeFriendList(data) {
	localStorage["JSON_FriendList"] = JSON.stringify(data["data"]);
	populateFriendList();
}

function create_iou(friendid, friendname, message) {
	var viewid = -1;
	$.ajax(
	{
		url: "./addiou.php",
		type: "POST",
		async: false,
		data: {
				"friendname":friendname,
				"friendid":friendid,
				"message":message
		},
		success: function(id) {
			// Now that we have inserted the message
			// into the database, we can now create the link
			viewid = id;
		},
		error: function() {
			alert("Sorry, there was an error");
		}
	});
	
	// We have not inserted the message, so we
	// cannot create a link to the post :/
	
	//open_facebook_submission(friendid, 123, message);
	var url = "http://www.facebook.com/dialog/feed?display=popup&";
	url += "app_id=221074584585871&";
	if (friendid!=0) {
		url += "to="+friendid+"&";
		url += "message=I%20owe%20you%20"+escape(message)+"&";
	} else {
		url += "message=I%20owe%20"+escape(message)+"%20"+escape(friendname)+"&";
	}
	url += "link=http://vierware.com/projects/iou/dev/viewcomment.php?id="+viewid+"&";
	url += "name=Social%20IOU&";
	url += "caption=New%20IOU%20for%20you.&";
	url += "description="+escape(localStorage["username"]+" created a new IOU to you. To see what you are owed, view the link! :) (No Spam)")+"&";
	url += "redirect_uri=http://vierware.com/projects/iou/dev/checksubmission.html";
	
	// open popup to get user to confirm
	var w = window.open(url, "IOU Facebook Submission", "height=400,width=480,left="+(screen.width-480)/2+",top="+(screen.height-400)/2);
	
}

function create_debt(friendid, friendname, message) {
	var viewid = -1;
	$.ajax(
	{
		url: "./adddebt.php",
		type: "POST",
		async: false,
		data: {
				"friendname":friendname,
				"friendid":friendid,
				"message":message
		},
		success: function(id) {
			// Now that we have inserted the message
			// into the database, we can now create the link
			viewid = id;
		},
		error: function() {
			alert("Sorry, there was an error");
		}
	});
	
	// We have not inserted the message, so we
	// cannot create a link to the post :/
	
	//open_facebook_submission(friendid, 123, message);
	var url = "http://www.facebook.com/dialog/feed?display=popup&";
	url += "app_id=221074584585871&";
	if (friendid!=0) {
		url += "to="+friendid+"&";
		url += "message=You%20owe%20me%20"+escape(message)+".&";
	} else {
		url += "message="+escape(friendname)+"%20owe's%20me%20"+escape(message)+"&";
	}
	url += "link=http://vierware.com/projects/iou/dev/viewcomment.php?id="+viewid+"&";
	url += "name=Social%20IOU&";
	url += "caption=You%20have%20a%20new%20IOU%20for%20"+escape(localStorage["username"])+".&";
	url += "description="+escape(localStorage["username"]+" says that you owe them something! To see what you are owed, view the link! :) (No Spam)")+"&";
	url += "redirect_uri=http://vierware.com/projects/iou/dev/checksubmission.html";
	
	// open popup to get user to confirm
	var w = window.open(url, "IOU Facebook Submission", "height=400,width=480,left="+(screen.width-480)/2+",top="+(screen.height-400)/2);
	
}

function submitcomment(id, form) {
	var comment = $(form).find("#comment").val();
	$.post("./submitcomment.php","id="+id+"&comment="+comment).complete(function(a) {
		window.location.reload(true);
	});
}