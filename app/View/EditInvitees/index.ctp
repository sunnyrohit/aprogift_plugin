<?php
    $this->start('head');
?>
	    <link type="text/css" href="js/friend-selector/jquery.friend.selector-1.2.css" rel="stylesheet" />
    <script type="text/javascript" src="js/validation.js"> </script>
    <script type="text/javascript" src="js/friend-selector/jquery.friend.selector-1.2.min.js"></script>
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
					z_fblogin()
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
    
	function z_bindFriendList() {
		
		$button = $("#fb-button");
		$button.off("click");

		$button.fSelector({
		  closeOnSubmit:true,
		  onSubmit: function(response){			
			  selected_friends = response;
			  $friendsDiv = $("#selected-friends");
			 
			  $.each(selected_friends, function(index, id) {
				FB.api('/' + id, function (response) {
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
  
	function z_fblogin(){
		loggedInJustNow = true;
		FB.login(function(response){}, {scope: 'email,publish_stream'});
		// add more in scope to get more permissions
	}
  
   function getUserInfo() {
    FB.api('/me', function(response) {
	   user_info = response;
    });
  }
   
	$(document).ready(function() {
		$("#fb-button").on("click",z_fblogin);
	});
   
    // END  facebook stuff added by shikhar//////////    
        
   
        var w = 200;
        var h = 200;
        var left = Number((screen.width/2)-(w/2));
        var tops = Number((screen.height/2)-(h/2));
        
        $(document).ready(function() {
        });
        
        people = 1;
        cost = <?php echo $giftcostleft; ?>;
        
        function updateHiddenParams(name, email, fbID) {
            $("#numpeople").val(people);
            $("#hiddeninputs").append(
                "<input type=hidden name=person" + people + " value=\"" + name + "\" />" +
                "<input type=hidden name=personemail" + people + " value=\"" + email + "\" />" +
                "<input type=hidden name=personfbID" + people + " value=\"" + fbID + "\" />"
            );
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
            $("#peopletable").append(
                "<tr id=\"newrow" + people + "\" >" +
                    "<td> " + name + " </td>" +
                    "<td> " + email + " </td>" +
                    "<td> " +
                    "<input class=\"shareInput\" type=\"text\" id=\"newshare" + people + "\" name=\"newshare" + people + "\" value=\"0\" />" +
                    "</td>" +
                    "<td> No </td>" +
                    "<td> " +
                    "<input id=\"deletenew" + people + "\" type=\"checkbox\" name=\"deletenew" + people + "\" onchange=\"javascript: darkenNewRow(" + people + ")\" />" +
                    "</td>" +
                "</tr>"
            );
            updateHiddenParams(name, email, fbID);
            $("#personname").val("");
            $("#personemail").val("");
        }

        function darkenNewRow(id) {
            $("#newrow" + id).toggleClass("darken");
            if ($("#deletenew" + id).is(':checked')) {
                $("#newshare" + id).attr("disabled", "true");
                $("#newshare" + id).val("0");
            }
            else {
                $("#newshare" + id).removeAttr("disabled");
            }
        }

        function darkenRow(id) {
            $("#row" + id).toggleClass("darken");
            if ($("#delete" + id).is(':checked')) {
                $("#share" + id).attr("disabled", "true");
                $("#share" + id).val("0");
            }
            else {
                $("#share" + id).removeAttr("disabled");
            }
        }
        
    </script>
    
    <style type="text/css">
        .centered tr td, .centered tr th {
            text-align: center;
        }
        
        .darken {
            color: #CCCCCC;
        }
        
        .shareInput {
            width: 75px;
            text-align: right;
        }
        
        .hidden {
            visibility: hidden;
        }
        
        .noborder {
            border-top: 0px !important;
        }
        
    </style>

<?php
    $this->end();
?>

    <form action="" method=POST onsubmit="javascript: return validateShares(<?php echo $cartDetails['price']; ?>);">
        <div id="hiddeninputs">
            <input type="hidden" id="numpeople" name="numpeople" value="1" />
        </div>

        <div class="row pagination-centered">
            <div class="span2"> </div>
            <div class="span8">

                <div class="alert alert-info">
                    Cost of the gift is <?php echo $cartDetails['price']; ?> <br />
                </div>
                <?php    
                    $count = 0;
                    $countPaid = 0;
                    $countUnpaid = 0;
                ?>
                <table id="peopletable" class="table table-bordered table-striped" align="center" width=100%>
                    <tbody class="centered">
                        <tr>
                            <th width="25%"> Name </th>
                            <th width="45%"> Email </th>
                            <th width="15%"> Share </th>
                            <th width="5%"> Paid? </th>
                            <th width="10%"> Remove? </th>
                        </tr>
                        <tr>
                            <td> <?php echo $organizerDetails['name']; ?> </td>
                            <td> <?php echo $organizerDetails['email']; ?> </td>
                            <td>
                                <?php
                                $count++;
                                if ($organizerDetails['paid'] == 1) {
                                    $countPaid++;
                                ?>
                                <input class="shareInput" type="text" id="organizershare" name="organizershare" value="<?php echo $organizerDetails['share']; ?>" disabled />
                                <?php
                                }
                                else {
                                    $countUnpaid++;
                                ?>
                                <input class="shareInput" type="text" id="organizershare" name="organizershare" value="<?php
                                if (isset($organizerShare))
                                	echo $organizerShare;
                                else
                                	echo $organizerDetails['share']; 
                                ?>" />
                                <?php
                                }
                                ?>
                            </td>
                            <td> <?php echo ($organizerDetails['paid'] == 1 ? 'Yes' : 'No'); ?> </td>
                            <td> </td>
                        </tr>
                    <?php
                    foreach ($people as $person) {
                        ?>
                        <tr id="row<?php echo $person['id']; ?>">
                            <td> <?php echo $person['name']; ?> </td>
                            <td> <?php echo $person['email']; ?> </td>
                            <td>
                                <?php
                                if ($person['paid'] == 1) {
                                ?>
                                <input class="shareInput" type="text" id="share<?php echo $person['id']; ?>" name="id<?php echo $person['id']; ?>share" value="<?php echo $person['share']; ?>" disabled />
                                <?php
                                }
                                else {
                                ?>
                                <input class="shareInput" type="text" id="share<?php echo $person['id']; ?>" name="id<?php echo $person['id']; ?>share" value="<?php echo $person['share']; ?>" />
                                <?php
                                }
                                ?>
                            </td>
                            <td> <?php echo ($person['paid'] == 1 ? 'Yes' : 'No'); ?> </td>
                            <td>
                            <?php
                                $count++;
                                if ($person['paid'] == 1) {
                                    $countPaid++;
                                }
                                else {
                                    $countUnpaid++;
                                    ?>
                                    <input id="delete<?php echo $person['id']; ?>" type="checkbox" name="delete<?php echo $person['id']; ?>" onchange="javascript: darkenRow(<?php echo $person['id'];?>)" />
                                    <?php
                                }
                            ?>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>
                <table class="table" align="center" width=100%>
                    <tbody class="centered">
                    <tr>
                        <td class="noborder">
                            <div class="alert alert-notice" style="margin-bottom: 0px;">
                                Click the button to make the share for each person equal
                            </div>
                        </td>
                        <td class="noborder">
                            <a onclick="javascript: equalizeSplit();" class="btn"> Split Equally </a>
                        </td>
                    </tr>
                    </tbody>
                </table>

                <table class="table table-bordered table-striped" align="center" width=100%>
                    <tbody class="centered">
                    <tr>
                        <td>
                            <img src='img/facebook_icon_small.jpg' id='fb-button' style='float:left;height:2em;width:2em;cursor:pointer'/> <input class="span2" style="height: 22px; margin-bottom:0px" placeholder="Friend's Name" type="text" id="personname">
                        </td>
                        <td>
                             <input class="span2p3" style="height: 22px; margin-bottom:0px" placeholder="Friend's email address" type="text" id="personemail">
                        </td>
                        <td>
                            <a onclick="javascript:addPerson()" class="btn btn-small">
                                <i class="icon-plus"> </i> Add
                            </a>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <table class="table table-bordered table-striped" align="center" width=100%>
                    <tbody class="centered">
                    <tr>
                        <td width="100%">
                            <input class="btn" type=submit value="Done" />
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="span2"> </div>
        </div>
        <input type="hidden" name="submitted" value="1" />
    </form>
    <script type="text/javascript">
        count = <?php echo $count; ?>;
        countPaid = <?php echo $countPaid; ?>;
        countUnpaid = <?php echo $countUnpaid; ?>;
        
        function equalizeSplit() {
            var shares = $(".shareInput");
            var validShares = 0;
            for (var i = 0; i < shares.length; i++) {
                if ($(shares[i]).prop('disabled')) {
                    
                }
                else {
                    validShares++;
                }
            }
            var newShare = cost / validShares;
            var newShareFirst = newShare;
            // now round off shares!
            if (cost % validShares != 0) {
                newCost = cost - (cost % validShares);
                newShare = newCost / validShares;
                newShareFirst = newShare + (cost % validShares);
            }
            for (var i = 0; i < shares.length; i++) {
                if ($(shares[i]).prop('disabled')) {
                    
                }
                else {
                    $(shares[i]).val(newShare);
                }
            }
            if ($("#organizershare").prop('disabled')) {
                $(shares[1]).val(newShareFirst);
            }
            else {
                $("#organizershare").val(newShareFirst);
            }
        }
    </script>