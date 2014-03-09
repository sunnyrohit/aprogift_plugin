<?php
    $this->start('head');
?>

    <link href="bootstrap/datepicker/css/datepicker.css" rel="stylesheet" />
    <link type="text/css" href="js/friend-selector/jquery.friend.selector-1.2.css" rel="stylesheet" />
    <script type="text/javascript" src="bootstrap/datepicker/js/bootstrap-datepicker.js"> </script>
    <script type="text/javascript" src="js/validation.js"> </script>
    <script type="text/javascript" src="js/friend-selector/jquery.friend.selector-1.2.min.js"></script>
    <style type="text/css">        
        .recipient-input, .occasion-select {
            width: 200px;
            padding: 0;
            margin: 0;
            height: 20px;
            -moz-box-sizing: border-box;
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
        }
        
        input[type="text"] {
            padding: 0px 0px 0px 5px !important;
        }
        
        .shareInput {
            width: 75px;
            text-align: right;
        }
        
        .darken {
            color: #CCCCCC;
        }
        
        .hidden {
            visibility: hidden;
        }
        
    </style>
    
    <script type="text/javascript">
    
    // facebook stuff added by shikhar//////////
    
      window.fbAsyncInit = function() {
	    FB.init({
	      appId : '<?php echo $fbAppId; ?>',
		  status : true, cookie : true, xfbml : true
	    });
	
			FB.Event.subscribe('auth.authResponseChange', function(response) {
				if (response.status === 'connected') {
                    var accessToken = response.authResponse.accessToken;
                    test_publish_stream(accessToken);			
				}
				else
					z_fblogin();
			});
	};
    
      (function(d){
     var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement('script'); js.id = id; js.async = true;
     js.src = "//connect.facebook.net/en_US/all.js";
     ref.parentNode.insertBefore(js, ref);
   }(document));
   
   var selected_friends;
	var loggedInJustNow = false;
	var user_info;
	var friends_data;
	var recipients = [];
	
	function z_bindFriendList() {
		
		$button = $("#fb-button");
		$button.off("click");
		$button.fSelector({
		  closeOnSubmit:true,
		
		  onSubmit: function(response){			
			  selected_friends = response;
			  $friendsDiv = $("#selected-friends");
			  
			  $.each(selected_friends, function(index, id) {
				FB.api('/' + id+'?fields=id,name,username', function (response) {
					var recipient = {};
					recipient['fbID'] = response.id;
					recipient['name'] = response.name;
					recipient['email'] = (response.username)?response.username+'@facebook.com':response.id+'@facebook.com';
					addPerson(recipient['name'], recipient['email'], recipient['fbID']);
					recipients.push(recipient);
									
				});
			  });
			  
		  }
		});
		
		if (loggedInJustNow)
			setTimeout(function(){$button.click();}, 1000);
	}
  
    function test_publish_stream(accessToken) {
        FB.api('/me/permissions', function(response) {
            permissions = response.data[0];
            if ('publish_stream' in permissions) {
                $("#hiddeninputs").append(
                    "<input type='hidden' name='accessToken' value='"+accessToken+"'>"
                );
                getUserInfo();
				z_bindFriendList();
            }
            else  {
                alert("Posts made by us will only be visible to you and the people you invite");
                z_fblogin();
            }
        });
        
    }
  
	function z_fblogin(){
		loggedInJustNow = true;
		FB.login(function(response){}, {scope: 'email,publish_stream'});
		// add more in scope to get more permissions
	}
  
   function getUserInfo() {
    FB.api('/me', function(response) {
	   user_info = response;
	   $("#hiddeninputs").append(
	   	"<input type='hidden' name='personfbID' value='"+user_info.id+"'>"
	   );
	   $("#organizer").val(user_info.name);
	   $("#myemail").val(user_info.email);
	   changeOrganizerName();
    });
  }
   
	$(document).ready(function() {
		$("#fb-button").on("click",z_fblogin);
	});
   
    // END  facebook stuff added by shikhar//////////    
    
    
    cost = <?php echo $giftDetails['price']; ?>;
    people = 1;
    numpeople = 1;
    
    splitEqual = true;

    function computeShare() {
        return (1.0 * cost) / people;
    }
    
    function updateShare(share) {
        $(".share").val(share);
    }
    
    
    
    function changeOrganizerName(){
        var name = $("#organizer").val();
            if (!validateName(name)) {
                alert("Please enter a proper name.");
                return;
            }
           $("#myname").html(name);
        }
    $(document).ready(function() {
        $("#myemail").change(function() {
            var email = $("#myemail").val();
            if (!validateEmail(email)) {
                alert("Please enter a valid email address.");
                return;
            }
        });

        $("#organizer").change(changeOrganizerName);
        
        
        $("#split").change(function() {
           onSplitChange();
        });
        
        $(document).ready(function() {
            $(window).keydown(function(event){
                if(event.keyCode == 13) {
                    event.preventDefault();
                    return false;
                }
            });
          });
        
    });

    function onSplitChange() {
        equal = false;
        if ($("#split").val() == "equal") {
             equal = true;
        }
        $(".share").attr("disabled", equal);
        if (equal) {
             updateShare(computeShare());
        }
    }

    function updateHiddenParams(name, email, fbID) {
        $("#numpeople").val(numpeople);
        var fbInput = "";
        if (fbID != "")
        	fbInput = "<input type=hidden name=personfbID" + numpeople + " value=\"" + fbID + "\" />";
        
        $("#hiddeninputs").append(
            "<input type=hidden name=person" + numpeople + " value=\"" + name + "\" />" +
            "<input type=hidden name=personemail" + numpeople + " value=\"" + email + "\" />" +
            fbInput
        );
    }
    
    function roundOffShares() {
        shares = new Array();
        shares[0] = $("#myshare").val();
        for (i = 1; i <= people; i++) {
            shares[i] = $("#share" + i).val();
        }
        // now round off shares!
        if (cost % people != 0) {
            newCost = cost - (cost % people);
            newShare = newCost / people;
            shares[0] = newShare + (cost % people);
            for (i = 1; i <= people; i++) {
                shares[i] = newShare;
                $("#share" + (i+1)).val(newShare);
            }
            $("#myshare").val(shares[0]);
        }
    }
    
    function addPerson(name, email, fbID) {
    	if (typeof(fbID)==='undefined')
        	fbID = "";
    	if (typeof(name)==='undefined')
        	name = $("#personname").val();
        if (typeof(email)==='undefined')
        	email = $("#personemail").val();
        if (!validateName(name)) {
            alert("Please enter a proper name.");
            return;
        }
        if (!validateEmail(email)) {
            alert("Please enter a valid email address.");
            return;
        }
        people++;
        numpeople++;
        share = computeShare();
        updateShare(share);
        $("#peopletable").append(
            "<tr id=\"newrow" + numpeople + "\" >" +
                "<td>" +
                "<a href=\"javascript: darkenNewRow(" + numpeople + ")\" ><i class=\"icon-remove\"> </i> </a>" +
                //"<input class=\"hidden\" id=\"deletenew" + numpeople + "\" type=\"checkbox\" name=\"deletenew" + numpeople + "\" />" +
                "</td>" +
                "<td> " + name + " </td>" +
                "<td style=\"text-align:right\"> " +
                "<input class=\"share shareInput\" type=\"text\" id=\"share" + numpeople + "\" name=\"share" + numpeople + "\" value=\"" +
                + share +
                "\" disabled=\"true\" />" +
                "</td>" +
            "</tr>"
        );
        onSplitChange();
        updateHiddenParams(name, email, fbID);
        $("#personname").val("");
        $("#personemail").val("");
        roundOffShares();
    }
    
    function darkenNewRow(id) {
        $("#newrow" + id).remove();
	$("#hiddeninputs input[name='person"+id+"']").remove();
	$("#hiddeninputs input[name='personemail"+id+"']").remove();        
	$("#hiddeninputs input[name='personfbID"+id+"']").remove();
        
        for (var i=id+1; i<=people; i++) {
        	var newIndex = i-1;
        	$("#newrow"+i).attr("id", "newrow"+newIndex);
        	$("#share"+i).attr("id", "share"+newIndex);
        	$("#share"+newIndex).attr("name", "share"+newIndex);
        	$("#newrow"+newIndex).find("a").eq(0).attr("href", "javascript: darkenNewRow("+newIndex+")");
        	
		$("#hiddeninputs input[name='person"+i+"']").attr("name", "person"+newIndex);
		$("#hiddeninputs input[name='personemail"+i+"']").attr("name", "personemail"+newIndex );        
		$("#hiddeninputs input[name='personfbID"+i+"']").attr("name", "personfbID"+newIndex ); 
        
        }
        /////////////////////////////////////////////
        people--;
        numpeople--; // ??
        
        onSplitChange();

        roundOffShares();
        ////////////////////////////////////////////////
        // Important from shikhar
        // Decrease numpeople in hidden inputs
        //
        $("#hiddeninputs input[name='numpeople']").val(people);
        // DONE
        ////////////////////////////////////////////////
        
        
        
        
        
        //$("#newrow" + id).toggleClass("darken");
        /*
        if ($("#deletenew" + id).is(':checked')) {
            $("#share" + id).attr("disabled", "true");
        }
        else {
            $("#share" + id).removeAttr("disabled");
            onSplitChange();
        }
        */
    }
	
	
    function formSubmit() {
    	
        $(".share").removeAttr("disabled");
        var name = $("#organizer").val();
        var email = $("#myemail").val();
        if (!validateName(name)) {
            alert("Please enter a proper name.");
            return false;
        }
        if (!validateEmail(email)) {
            alert("Please enter a valid email address.");
            return false;
        }
        var $date1 = $("#datepicker4>input");

        if ($date1.val()=="") {
        	customPopupMessage("Please enter a target date for the group gift");
        	return false;
        }
        
        return validateShares(cost);
    }
    
    </script>
    
<?php
    $this->end();
?>

    <div class="row pagination-centered">
        <div class="span12" style='margin:-1em auto 2em'>
          Group of Friends
 <br />
      <img src="img/s2.png" />
        </div>
    </div>
    <br />
    <div class="row">
        <div class="span12" style="padding-left: 10px">
            Split the gift cost (<?php echo $giftDetails['currency'], " ",$giftDetails['price']; ?>) among your friends 
        </div>
    </div>
    <br />
    <form action="Page3" method="POST" onsubmit="javascript: return formSubmit();">
        <div id="hiddeninputs">
            <input type="hidden" id="numpeople" name="numpeople" value="1" />

        </div>
        <div class="row">
            <div class="span8">
                <div style="padding-left:10px">
                    <table class="" align="center" style='float:left;width:59%'>
                        <tbody>
                            <tr style="text-align:center; border-bottom: solid 1px #eee;">
                                <!--
                                <td width=30% style="padding-bottom:10px">
                                    <div class="input-prepend input-append" style="margin-bottom:0px;">
                                        <input class="span2p3" style="height: 22px" placeholder="Best way to invite friends" id="appendedPrependedInput" type="text">
                                        <span class="add-on" style="padding: 0px">
                                            <img src="img/facebook_icon_small.jpg" />
                                        </span>
                                    </div>
                                </td>
                                <td width=10%>
                                    or
                                </td>
                                -->
                                <td width=25% style="padding-bottom:10px">
                                    <input class="span2" style="height: 22px; margin-bottom:0px" placeholder="Your name" type="text" name="organizer" id="organizer">
                                </td>
                                <td width=35% style="padding-bottom:10px">
                                    <input class="span2p3" style="height: 22px; margin-bottom:0px" placeholder="Your email address" type="text" name="organizeremail" id="myemail">
                                </td>
                            </tr>
                            <tr style="text-align:center;">
                                <!--
                                <td width=30% style="padding-top:10px">
                                    <div class="input-prepend input-append" style="margin-bottom:0px;">
                                        <input class="span2p3" style="height: 22px" placeholder="Enter friend's name" id="appendedPrependedInput" type="text">
                                        <span class="add-on" style="padding: 0px">
                                            <img src="img/facebook_icon_small.jpg" />
                                        </span>
                                    </div>
                                </td>
                                <td width=10%>
                                    or
                                </td>
                                -->
                                <td width=25% style="padding-top:10px">
                                    <input class="span2" style="height: 22px; margin-bottom:0px" placeholder="Invitee's Name" type="text" id="personname">
                                </td>
                                <td width=35% style="padding-top:10px">
                                    <input class="span2p3" style="height: 22px; margin-bottom:0px" placeholder="Invitee's email address" type="text" id="personemail">
                                </td>
                            </tr>
                            <tr>
                                <td>          
                                </td>
                                <td>
                                </td>
                                <td>
                                </td>
                            </tr>
                        </tbody>
                    </table>
					<div style='float:right;width:25%;margin-right:15%;margin-top:1.9em'>
						<b style='float:left'>OR</b>
						<div id='fb-button' title='The message will be visible only to you and your tagged friend' class='btn' style='float:right;margin-top:-0.5em;font-size:1.1em;padding:0.16em 0.7em;padding-top:0.5em'>
							<i class="icon-user" style='margin-right:0.32em'> </i> Via <img src='img/facebook_icon_medium.png' style='height:20px;margin-top:-0.5em;margin-left:0.32em'/>
						</div>
						<div style='clear:both'></div>
					</div>
					<div style='clear:both'></div>
					
                </div>
                <br />
                <div class="row">
                    <div class="span5" style='margin-top:-16px;'>
                        <div style="padding-left:10px;">
                            <td style="text-align:left; padding-top:0px; padding-left: 0px" >
                                <a onclick="javascript:addPerson()" class="btn btn-small">
                                    <i class="icon-plus"> </i> Add
                                </a>
                            </td>
                        </div>
                        <div class="span3" style='margin-top:23px;margin-left:10px'>
                                <div id="datepicker4" data-date-format="yyyy-mm-dd" data-date='' class="input-append date" style="text-align:right; padding-right: 27px">
                                    <input name='deadline' class="span2p3" style="height: 28px" placeholder="Set Deadline for group"  type="text">
                                    </input>
                                    <span class="add-on">
                                        <i class="icon-calendar">
                                        </i>
                                    </span>
                                </div>
                                <script type="text/javascript">
                                
                                    $(function() {
					var nowTemp = new Date();
					var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
                                        $('#datepicker4').datepicker({
                                            onRender: function(date) {
					    	return date.valueOf() < now.valueOf() ? 'disabled' : '';
					    }
                                        }).
                                        on('changeDate', function(ev) {
                                            $('#datepicker4').datepicker('hide');
                                        })
                                    });
                                </script>
                            </div>
                        
                    </div>
                    
                </div>
                <br />
                
                <div class=row>
				<div class=span3 style='padding-left:10px'>
					<label><input type=checkbox style='vertical-align:top' name='permission_invite_more' value=1> Allow Invitees to invite others<br /></label><br />
					</div>
				</div>
                
                <div class="row">
                    <div style="padding-left:10px" class="span8">
                        Message to Invitees (OPTIONAL)
                        <br />
                        <br />
                        <textarea rows=5 style="width:80%" placeholder="You can enter a message here for your group of invited friends!" name="imessage"></textarea>
                    </div>
                </div>
            </div>
            <div class="span4">
                <div class="alert alert-info" align=center style="padding-left:45px; margin-right:10px">
                        Click <i class="icon-remove"> </i> to remove a person
                </div>
                <div align=right style="padding-right:10px">
                    <select id="split" class="split-select" style="padding: 0px; height: 20px;">  
                        <option value="equal">Split Equally</option>
                        <option value="unequal">Split Unequally</option>
                    </select>  
                </div>
                <div id="contributions" style="padding-right:10px">
                    <table class="table table-bordered table-condensed table-striped" align="center" width="100%" id="peopletable">
                        <tbody>
                            <tr>
                                <td>
                                </td>
                                <td>
                                    TOTAL
                                </td>
                                <td style="text-align:right">
                                    <?php echo $giftDetails['currency'], " ",$giftDetails['price']; ?>
                                </td>
                            </tr>                            
                            <tr>
                                <td width=10%>
                                    
                                </td>
                                <td id="myname" width=55%>
                                    Me
                                </td>
                                <td width=35% style="text-align:right">
                                    <input type="text" class="share shareInput" id="myshare" name="myshare" value="<?php echo $giftDetails['price']; ?>" disabled="true" />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="span8">
            </div>
            <div class="span4" style="text-align:right;">
               <button type="submit" class="btn-link">
                                        <img src="img/Send-invite.png" />
            </div>
        </div>
    </form>