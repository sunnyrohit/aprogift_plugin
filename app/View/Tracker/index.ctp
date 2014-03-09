<?php
    $this->start('head');
?>

    <link href="bootstrap/datepicker/css/datepicker.css" rel="stylesheet" />
    <script type="text/javascript" src="bootstrap/datepicker/js/bootstrap-datepicker.js"> </script>

    
<script type="text/javascript" src="/www/js/jquery.min.js"></script>

<script>

var fb_access_token;

window.fbAsyncInit = function() {
	    FB.init({
	      appId : '<?php echo $fbAppId; ?>',
		  status : true, cookie : true, xfbml : true
	    });
	
			FB.Event.subscribe('auth.authResponseChange', function(response) {
				if (response.status === 'connected') {
                    fb_access_token = response.authResponse.accessToken;
                    var accessToken = fb_access_token;
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
   
    function test_publish_stream(accessToken) {
        FB.api('/me/permissions', function(response) {
            permissions = response.data[0];
            if ('publish_stream' in permissions) {
                $("#hiddeninputs").append(
                    "<input type='hidden' name='accessToken' value='"+accessToken+"'>"
                );
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
	}
   
   
   
   
</script>

<script type='text/javascript'>
    
    $(document).ready(function(){
	<?php
		if (isset($flashMessage)) {
		echo "var message = '{$flashMessage}';";
		?>
		customPopupMessage(message);	
	
	<?php } ?>	
		
   	$("#message-invitees").on("click", function(){
    		$("#messaged-invitees-box").html("<img id='close-button' src='img/close.gif'><br /><br /><div style='text-align:center'><img src='img/ajax-loader.gif' /><br /><br />Messaging your friends ...</div><br /><br />");
    		
    		$("#close-button").on("click", function(){
	    		$("#overlay").fadeOut(250);
	    		$("#messaged-invitees-box").slideUp(200);
    		});
    		
    		$("#overlay").fadeIn(200);
    		$("#messaged-invitees-box").slideDown(350);
    		
    		
    		/*
            var url = document.URL 
            var path = url.substring(0,url.lastIndexOf("/")+1);
    		var t = $(this).attr('data-target');
    		var targeturl = path + t;
            */
    		var selfTarget = window.location.href;
    		$.ajax({
    			url:selfTarget,
    			method:"POST",
                data:{"command":"message-invitees", "accessToken":fb_access_token},
    			success:function(response){
	    			$("#messaged-invitees-box").fadeOut(100, function(){
	    				$box = $("#messaged-invitees-box");
	    				
	    				$box.html("<img id='close-button' src='img/close.gif'><br />");
	    				$("#close-button").on("click", function(){
				    		$("#overlay").fadeOut(250);
				    		$("#messaged-invitees-box").slideUp(200);
	    				});
                        if (response != "error") {
                            $box.append("Following people were reminded about the group gift<br /><br />");
                            
                            $box.append(response).append("<br /><br />");
                        }
                        else {
                            $box.append("There was a problem sending message");
                        }
	    				$box.append("<div id='okay-container'><div id='okay' class='btn btn-primary' style='margin:0 auto'>Okay</div></div>");
	    				$("#okay").on("click", function(){
	    					$("#close-button").click();
	    				});
	    				$("#message-invitees").append(" - <u>Sent</u>");
	    				$("#overlay").fadeIn();
		    			$("#messaged-invitees-box").fadeIn(70);
	    			
	    			});
    				    				
    			}
    		});
    		return false;
    	}); 
    	    	
    	
    	// Deadline editing stuff
		var nowTemp = new Date();
		var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
		$('#edit-deadline').datepicker({
			onRender: function(date) {
			return date.valueOf() < now.valueOf() ? 'disabled' : '';
			}
		}).on('changeDate', function(ev) {
			$("#edit-deadline").datepicker('place');
			var newDeadline = $("#edit-deadline input").val();
			var selfTarget = window.location.href;
			var data = {"command":"update-deadline",
						"newDeadline":newDeadline
						}
			$.ajax({
				url:selfTarget,
				method:"POST",
				data:data,
				success:function(response) {
					//console.log(response)
					window.location.reload();
				}
			});
			
			//$('#edit-deadline').datepicker('hide');
		});
		
		
        $("#convert-to-voucher").on("click", function(){
			test_publish_stream(fb_access_token);
            var message = "The amount that has been collected would be converted into an equivalent voucher value for gifting. This would complete the group-gift event. Would you like to proceed?";
			$('body').append("<div id=flash-message style='width:60%;left:19%;top:23%'><br>"+message+"<br><br><div id='convert-to-voucher-container'><div id='convert-to-voucher-button-yes' class='btn btn-success' style='float:left'>Yes</div><div id='convert-to-voucher-button-no' class='btn btn-danger' style='float:right'>No</div><div style='clear:both'></div></div>");
			$("#overlay").fadeIn(200);
			$("#flash-message").slideDown(350);
			
			$("#convert-to-voucher-button-no").on("click", function() {
				$("#overlay").fadeOut(250);
				$("#flash-message").slideUp(200, function() {
					$("#flash-message").remove();
				});
				
			});
			
			$("#convert-to-voucher-button-yes").on("click", function() {
				
                $("#convert-to-voucher-container").html("<div style='text-align:center'><img src='img/ajax-loader.gif' /></div><br /><br />");
                var url = document.URL 
				var path = url.substring(0,url.lastIndexOf("/")+1);
	    		var t = $("#convert-to-voucher").attr('data-target');
	    		var targeturl = path + t;
                
                $.ajax({
                    url:targeturl,
                    method:"POST",
                    data:{"accessToken":fb_access_token},
                    success:function(response){
                        response = JSON.parse(response);
                        if (response.code == 0) {
                            // redirect to url in response
                            var redirecturl = response['data']['redirectURL'];
                            setTimeout(function(){
                                window.location = redirecturl;
                            }, 500);
                        }
                        else {
                            alert("There was a problem converting the group gift to voucher");
                            window.location.reload();
                        }
                    }
                });
                
                
			});
			
    		return false;
    	});
        
		
    	$("#cancel-gift").on("click", function(){
			var message = "You wouldn't be able to access or track this group gift anymore.<br />Are you sure you want to cancel this group gift?";
			$('body').append("<div id=flash-message style='text-align:center;width:40%;left:36%:top:23%'><br>"+message+"<br><br><div id='cancel-container'><div id='cancel-gift-button-no' class='btn btn-success' style='float:left'>Not Now</div><div id='cancel-gift-button-yes' class='btn btn-danger' style='float:right'>Cancel Gift</div><div style='clear:both'></div></div>");
			$("#overlay").fadeIn(200);
			$("#flash-message").slideDown(350);
			
			$("#cancel-gift-button-no").on("click", function() {
				$("#overlay").fadeOut(250);
				$("#flash-message").slideUp(200, function() {
					$("#flash-message").remove();
				});
				
			});
			
			$("#cancel-gift-button-yes").on("click", function() {
				var url = document.URL 
				var path = url.substring(0,url.lastIndexOf("/")+1);
	    		var t = $("#cancel-gift").attr('data-target');
	    		var targeturl = path + t;
	    		setTimeout(function(){
	    			window.location = targeturl;
	    		}, 500);
			});
			
    		return false;
    	});
    	
    });
    
    </script>
    <style type="text/css">        
      
    #messaged-invitees-box, #flash-message{
     display:none;
	position:fixed;
	z-index:1002;
	left:32%;
	top:15%;
	min-width:250px;
	width:34%;
	
	max-height:80%;
	background-color:#f9f9f9;
	font-size:1.2em;
	padding:0.2em 0.7em  0.7em;

	overflow:auto;
	border:2px solid #333;
	-webkit-border-radius:7px;
	border-radius:7px;	
      
      }

      #messaged-invitees-box #close-button{
	float:right;
	cursor:pointer;
	margin-bottom:0.5em;
	margin-right:-0.3em;
	box-shadow:1px 1px 1px 1px #a0a0a0;
	-webkit-box-shadow:1px 1px 1px 1px #a0a0a0;
	}
	#messaged-invitees-box #close-button:hover{
		box-shadow:1px 1px 1px 1px #777;
		-webkit-box-shadow:1px 1px 1px 1px #777;
	}
	#messaged-invitees-names{
		text-align:left;
		font-weight:bold;		
	}
      #okay-container,  #okay-container-2 {
      	margin:0 auto;
      	width:5em;
      }
    
        .bordered-box {
            border: solid 1px #eee;
            border-radius:25px;
            -moz-border-radius:25px; /* Old Firefox */
        }
        
        .recipient-input, .occasion-select {
            width: 200px;
            padding: 0;
            margin: 0;
            height: 20px;
            -moz-box-sizing: border-box;
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
        }
    
        /* For modern browsers */
        .left-right:before,
        .left-right:after {
            content:"";
            display:table;
        }
        .left-right:after {
            clear:both;
        }
        /* For IE 6/7 (trigger hasLayout) */
        .left-right {
            zoom:1;
        }
           .user-thumb{
        	border:1px solid #888;
        	padding:1px;
        	height:34px;
        	box-shadow:1px 1px 1px #aaa;        	
        }
        h5.big-links{
            margin-bottom:0.1em;
            margin-top:1.5em;
        }
        
        .clear{
            clear:both;
        }
    </style>
    
<?php
    $this->end();
    /*
    function getImgSrc($fbID){
    	return ($fbID === NULL || $fbID == 0) ? "img/user-thumb.png" : "https://graph.facebook.com/{$fbID}/picture";
    }
    */
?>

    <div class="row">
        <div class="span12" style="padding-left:10px">
             <h4> Manage <?php echo ucwords($recipientDetails['name']); ?>'s Group Gift </h4>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="span7">
            <div style="padding-left: 10px">
                <table class="">
                    <tbody>
                        <tr>
                            <td>
                                <img src="<?php echo $cartDetails['imgSrc']; ?>" class="img-polaroid" style="width:120px" />
                            </td>
                            <td style='padding-left:1.5em'>
                                 <?php echo $cartDetails['name'], $cartDetails['quantity_str']; ?> <br /><br />
                                 <?php echo "INR ",$cartDetails['price']; ?>
                                 
                            </td>
                        </tr>
						<tr>
						<td>
                        
                        <div style='text-align:center;font-weight:bold'><?php
                        $editOrView = ($organizerDetails['transaction_completed_on'] == NULL) ? 'Edit Gift' : 'View Gift' ;
                        echo "<a style='text-decoration:none' href='EditCart?".$organizerDetails['URL_arg']."'><i class=icon-gift></i> {$editOrView}</a>"; 
                        ?>
                        
                        </div>
                        
                        </td>
						<td></td>
						</tr>
                    </tbody>
                </table>
            </div>
            <br />
            <div class="span1" style="padding-left: 10px">
            </div>
            <div>
                <?php 
                if ($organizerDetails['transaction_completed_on'] == NULL && isset($organizerDetails['payment_URL_buyout']))
                    echo "<a href='{$organizerDetails['payment_URL_buyout']}' class='btn btn-danger' class='masterTooltip' style='margin-left:-70px' title='You can pay the complete remaining amount right now and then collect the money later from invitees on your own'>Buyout in advance</a>";
                ?>
				
			
                
                <br />
                <br />
                <div style="padding-left: 20px">
                    
                    <?php if ($organizerDetails['transaction_completed_on'] == NULL) { ?>                    
                        <h5 class=big-links> <?php echo "<a href='EditInvitees?".$organizerDetails['URL_arg']."'>"; ?><i class=icon-retweet></i> INVITE MORE FRIENDS </a> </h5>
                        Invite more friends to contribute via Facebook or Email
                   
                        <?php
                        if ($organizerDetails['group_cardinality'] != '1') {
                            echo "<h5 class=big-links><a href='#' id='message-invitees' data-target='MessageInvitees?".$organizerDetails['URL_arg']."'><i class=icon-share-alt></i>  MESSAGE INVITEES </a> </h5>
                        Remind friends to chip in before gift deadline";
                        }
                        ?>
                        
                        <?php
                        if (intval($totalPaid) != 0) {
                        echo "<h5 class=big-links><a href='#' id='convert-to-voucher' data-target='ConvertToVoucher?".$organizerDetails['URL_arg']."'><i class=icon-random></i> CONVERT TO VOUCHER </a> </h5>
                        Complete the group gift by gifting voucher equal to the collected amount";
                        }
                        ?>
                        
                        <h5 class=big-links> <?php echo "<a href='#' id='cancel-gift' data-target='CancelGift?".$organizerDetails['URL_arg']."'>"; ?><i class=icon-ban-circle></i> CANCEL GIFT </a> </h5>
                        Cancel this group gift and refund contributors
                    <?php } ?>

                   
                </div>
            </div>
            <br />
            <div style="padding-left: 10px">
                <span>
                    <?php 
                    if ($organizerDetails['transaction_completed_on'] == NULL) 
                        echo "INR {$remaining} needed before {$target_date}"; 
                    ?>
                </span>
                <span style="float:right">
                    Gift Goal: INR <?php echo $cartDetails['price']; ?>
                </span>
                <div class=clear></div>
            </div>
            <div style="padding-left: 10px">
                <div class="progress">
                    <div class="bar bar-success" style="width: <?php echo $progress; ?>%"><?php echo $totalPaid; ?></div>
                </div>                        
            </div>
            <br />
  
           <div style="padding-left: 10px">
            <?php 
            	if ($organizerDetails['transaction_completed_on'] == NULL && !$organizerPaid) {
            	            ?>
                <form class="form-inline" action="<?php echo "EditInvitees?".$organizerDetails['URL_arg']; ?>" method="POST">
                    Change your contribution : INR 
                    <input style="padding: 0px 0px 0px 5px" name='organizerShare' type="text" class="input-small" value="<?php echo $organizerDetails['share']; ?>" />
                    <input type='submit' class="btn btn-primary btn-small" value="Apply">
                </form>
                <?php } ?>
            </div>
 </div>
   

  <div class="span5">
            <div style="margin-right:10px" class="bordered-box">
                
                <div class="left-right">
                    <div style="float:left;margin-top:10px; margin-left:10px">
                        <h5> Gift Deadline Date </h5>
                    </div>
				</div>
                <div style="margin-left:10px; text-align: left;">
                    <?php if ($organizerDetails['transaction_completed_on'] == NULL) { ?>
                    <div id="edit-deadline" data-date-format="yyyy-mm-dd" data-date='<?php echo $organizerDetails['deadline']; ?>' class="input-append date" style="display:inline">
						<input name='deadline' class="span2p3" style="height: 28px" placeholder="Set Deadline for group"  type="hidden">
						</input>
						<span class="add-on" style='margin-right:5px;border:0;background-color:#fff;margin-top:-2px'>
							<i class="icon-calendar" title='Edit Deadline'>
							</i>
						</span>
                    </div> 
					<?php 
                    }
                    echo "{$target_date} [{$days_left}]"; ?>
                </div>
                
                
                <hr style="margin-bottom:10px" />
                <div class="left-right" style="margin-bottom:10px">
                    <div style="float:left; margin-left:10px">
                        <i class="icon-chevron-down"> </i>
                        ALREADY PAID
                    </div>
                    <div style="float:right; margin-right:10px"> <strong> <?php echo count($peoplePaid); ?> </strong> </div>
                </div>
                <table class="table table-striped" align="center" width="100%">
                    <tbody>
                        <?php
                        $count = 1;
                        foreach ($peoplePaid as $user) {
                        ?>
                            <tr>
                                <td style='padding:0'>
                                    <?php echo "<img src='".$user['imgSrc']."' class='user-thumb'>"; ?>
                                </td>
                                <td>
                                    <?php echo $user['name']; ?>
                                </td>
                                <td style="text-align:right">
                                    INR <?php echo $user['share']; ?>
                                </td>
                            </tr>
                        <?php
                            $count++;
                        }
                        ?>
                    </tbody>
                </table>
                
                <div class="left-right" style="margin-bottom:10px">
                    <div style="float:left; margin-left:10px">
                        <i class="icon-chevron-down"> </i>
                        AWAITING REPLY
                    </div>
                    <div style="float:right; margin-right:10px"> <strong> <?php echo count($peopleLeft); ?> </strong> </div>
                </div>
                <table class="table table-striped" align="center" width="100%">
                    <tbody>
                        <?php
                        $count = 1;
                        foreach ($peopleLeft as $user) {
                        ?>
                            <tr>
                                <td style='padding:0'>
                                    <?php echo "<img src='".$user['imgSrc']."' class='user-thumb'>"; ?>
                                </td>
                                <td>
                                    <?php echo $user['name']; ?>
                                </td>
                                <td style="text-align:right">
                                    INR <?php echo $user['share']; ?>
                                </td>
                            </tr>
                        <?php
                            $count++;
                        }
                        ?>
                    </tbody>
                </table>
            </div>
       <div style="float:right;margin-top:5px; margin-right:10px">
	   		<?php if ($organizerDetails['transaction_completed_on'] == NULL && isset($organizerDetails['payment_URL'])) {
					echo "<a href='{$organizerDetails['payment_URL']}' class='btn btn-link'>
					<img src='img/Contribute-share.png' /></a>";
				}
			?>

	   
                                 </div> </div>
    </div>
    
 
  
 <div id='overlay'></div>
    <div id='messaged-invitees-box'></div> 