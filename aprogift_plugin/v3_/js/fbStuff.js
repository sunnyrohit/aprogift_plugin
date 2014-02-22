var clickedOnFacebook = false;
var fbAccessToken = 0;

$(document).ready(function() {
	
	$(".facebook-login-signup").on("click", function() {
		z_fblogin();
		clickedOnFacebook = true;
	});
	
	
	$("#fb-button").fSelector({
		closeOnSubmit:true,
		max:1,
		onSubmit: function(response){			
			selected_friends = response;
			if (!response[0])
				return;
			fbId = response[0];
			FB.api('/'+fbId+'?fields=id,name,username', function(response) {
				fillRecipientDetailsFb(response.id, response.name, getFacebookEmail(response));
			});
		}
	});
	
});


var card2Displayed = false;

function goToCard2() {
	if (!card2Displayed) {
		displayCard($("#card2-fb"));
		getUserInfo();
		getFriendsBirthdayInfo();
		
		card2Displayed = true;
	}
}


function fillRecipientDetailsFb(recipientFbId, recipientName, recipientEmail, recipientAge) {
	$("#upcoming-birthdays-container>div").removeClass("selected");
	
	$("#recipient-fb-image").attr("src", getFbImgSrc(recipientFbId, "square"));
	$("#recipient-fb-image").attr("src", getFbImgSrc(recipientFbId, "large"));
	
	
	$("#card2-fb input[name='recipient-name']").val(recipientName).prop("disabled", true);
	$("#card2-fb input[name='recipient-fb-id']").val(recipientFbId);
	$("#card2-fb input[name='recipient-email']").val(recipientEmail).prop("disabled", true);
	
}

function getFbImgSrc(fbId, image_type) {
	if (image_type == "large")
		return "https://graph.facebook.com/"+fbId+"/picture?type=large";
	else
		return "https://graph.facebook.com/"+fbId+"/picture";
}



function z_fblogin() {
	FB.login(function(response){}, {scope: 'email,friends_birthday,friends_interests,friends_likes'});
}

var userInfo;

function getUserInfo() {
	FB.api('/me?fields=id,username,name,email',
		function(response) {
			userInfo = response;
			$("#card2-fb input[name='organizer-email']").val(userInfo.email);
			$("#card2-fb input[name='organizer-fb-id']").val(userInfo.id);
		}
	);
}


var tmp = new Date();
tmp.setHours(0);tmp.setMinutes(0);tmp.setSeconds(0);
var dummyYear = tmp.getFullYear();
function getBirthDateFromStr(birthdate_str) {
	var month = parseInt(birthdate_str.substr(0, 2));
	var day = parseInt(birthdate_str.substr(3, 2));
	
	date = new Date(dummyYear, month-1, day, 0, 0, 1);
	if (date < tmp)
		date.setFullYear(date.getFullYear() + 1);
	
	return date;
}

var future = new Date();
future.setDate(future.getDate() + 60);
function within60Days(date) {
	return (date < future);
}

var friendsBirthdayInfo = [];

function getFriendsBirthdayInfo() {
	FB.api('/me/friends?fields=id,username,name,birthday',
		function(response) {
			$.each(response.data, function(index, el) {
				if (el.birthday) {
					var birthdate = getBirthDateFromStr(el.birthday);
					if (!within60Days(birthdate))
						return true;
					var ob = {};
					ob['id'] = el.id;
					ob['email'] = getFacebookEmail(el);
					ob['name'] = el.name;
					ob['birthday'] = birthdate;
					friendsBirthdayInfo.push(ob);
				}
			});
			friendsBirthdayInfo.sort(compareFriendObjects);
			
			$container = $("#upcoming-birthdays-container")
			
			$.each(friendsBirthdayInfo, function(index, fr) {
				$container.append("<div data-array-index="+index+"><div class=fb-image><img src='"+getFbImgSrc(fr.id, "small")+"' /></div><div class=name>"+fr.name+"</div><div class=date>"+formatBirthday(fr.birthday)+"</div>"
					+"<div class='clear'></div>"
					+"</div>"
				);
			});
			
			$("#upcoming-birthdays-container>div").on("click", function() {
				var arrayIndex = parseInt($(this).attr("data-array-index"));
				var el = friendsBirthdayInfo[arrayIndex];
				fillRecipientDetailsFb(el.id, el.name, el.email);
				$(this).addClass("selected");
				
			});
		}
	);
	
}

function getFacebookEmail(person) {
	return ((person.username && person.username!="") ? (person.username + "@facebook.com") : (person.id + "@facebook.com"));
}


var monthName = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
function formatBirthday(birthdate) {
	return birthdate.getDate() + " " + monthName[birthdate.getMonth()];
}

function compareFriendObjects(f1, f2) {
	d1 = f1.birthday;
	d2 = f2.birthday;

	if (d1 < d2)
		return -1;
	else if (d1 > d2)
		return +1;
	else return 0;
	
}

// Processing Server Side by passing fb access token
function getRecommendations() {
	var fbId = $("#card2-fb input[name='recipient-fb-id']").val();
	if (fbId == "")
		return;

	$.ajax({
		url:"recommendations.php",
		type:"POST",
		data:{"fbAccessToken":fbAccessToken, "fbId":fbId}
	});
}

/*
// old function 
// Processing client side

function getRecommendations() {
	var fbId = $("#card2-fb input[name='recipient-fb-id']").val();
	if (fbId == "")
		return;
	
	var likes;
	var interests;

	FB.api('/'+fbId+'/likes?limit=5000', function(response) {
		//console.log(response);
		var likes = combineAccordingToCategory(response.data);
		FB.api('/'+fbId+'/interests?limit=5000', function(response) {
			//console.log(response);
			interests = combineAccordingToCategory(response.data);
			
			var dataToSend = {"likes":likes, "interests":interests};
			console.log(dataToSend);
			
			// send AJAX request
			$.ajax({
				url:"recommendations.php",
				type:"POST",
				data:dataToSend
			});
			
		});
	});



	return false;
}


function combineAccordingToCategory(data) {
	// data is an array
	var finalOb = {};
	
	$.each(data, function(index, el) {
		if (!el.category)
			return true;
		
		var category = el.category.replace(/[^a-zA-Z0-9]/g,' ').replace(/ +(?= )/g,'');;
		
		if (!finalOb[category])
			finalOb[category] = [];
		
		var ob = {};
		ob.name = el.name.replace(/[^a-zA-Z0-9]/g,' ').replace(/ +(?= )/g,'');;
		ob.createdDate = (el.created_time) ? el.created_time.substr(0, 10) : 0;
		
		finalOb[category].push(ob);

	}); 
	
	return finalOb;
}


*/