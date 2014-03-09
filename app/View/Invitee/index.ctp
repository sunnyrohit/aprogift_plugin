<?php
    $this->start('head');
?>

    <link href="bootstrap/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />
    <script type="text/javascript" src="bootstrap/js/bootstrap-datetimepicker.min.js"> </script>
   
   <script type='text/javascript'>
   $(document).ready(function() {
   
        	<?php
		if (isset($flashMessage)) {
		echo "var message = '{$flashMessage}';";
		?>
		
		$('body').append("<div id=flash-message style='text-align:center;width:25%;left:40%:top:19%'><br>"+message+"<br><br><div id='okay-container-2'><div id='okay-2' class='btn btn-primary' style='margin:0 auto'>Okay</div></div></div>");
		$("#okay-2").on("click", function() {
			$("#overlay").fadeOut(250);
			$("#flash-message").slideUp(200, function() {
				$("#flash-message").remove();
			});
			
		});
			$("#overlay").fadeIn(200);
			$("#flash-message").slideDown(350);
	
	
	<? } ?>	
            
		$("#message-from-invitee-button").height($("#message-from-invitee").height());
			
		$("#message-from-invitee-button").on("click", function() {
			var message = $("#message-from-invitee").val();
			var selfTarget = window.location.href;
			var data =
				{
					"command":"update-message-from-invitee",
					"message":message
				}
			
			$.ajax({
				url:selfTarget,
				method:"POST",
				data:data,
				success:function(response) {
					if (response == "success") {
						var oldColor = $("#message-from-invitee").css("background-color");
						$("#message-from-invitee").css("background-color", "#dfd");
						setTimeout(function() {
									$("#message-from-invitee").css("background-color", oldColor);
								} 
							,5000);
					} else {
						alert("There was a problem updating your message. Please refresh the page and try again.")
					}
				}
			});
		});
		
	});
   </script>
   
    <style type="text/css">        
    
    
      #overlay {
	display:none;
	position: fixed;
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
	background-color: #000;
	filter:alpha(opacity=40);
	-moz-opacity:0.4;
	-khtml-opacity: 0.4;
	opacity: 0.5;
	z-index: 1000;
      }
      
      #confirmation-box{
      	display:none;
	position:fixed;
	z-index:1002;
	left:29%;
	top:19%;
	min-width:250px;
	width:40%;
	
	max-height:80%;
	background-color:#f9f9f9;
	font-size:1.2em;
	padding:0.5em 0.8em 0.8em;

	overflow:auto;
	border:2px solid #333;
	-webkit-border-radius:7px;
	border-radius:7px;	
      
      }
    
    #flash-message{
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
        .bigger{
        	font-size:1.1em;
        }
        .user-thumb{
        	border:1px solid #888;
        	padding:1px;
        	height:34px;
        	box-shadow:1px 1px 1px #aaa;
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
	function getFirstName($fullName) {
		return ucfirst(strstr($fullName.' ', ' ', true));
	}
?>

    <div class="row">
        <div class="span12" style="padding-left:10px">
             <h5 style='width:70%;float:left;font-size:1.1em;'><span class='bigger'>Hi <?php echo getFirstName($thisGroupUser['name']); ?></span>, <span class='bigger'><?php echo ucwords($organizerDetails['name']); ?></span> has found a gift for <span class='bigger'><?php echo ucwords($recipientDetails['name']); ?></span> and you're invited to contribute to <?php echo ucfirst($recipientDetails['name']); ?>'s group gift </h5>
             
        </div>
    </div>
    <hr style="margin-top: 0px;" />

    <div class="row">
        <div class="span7">
            <div style="margin-left: 10px" class="bordered-box">
                <table align="center">
                    <tbody>
                        <tr>
                            <td width=30% style="padding-top:10px; padding-bottom:10px;">
                                <img src="<?php echo $cartDetails['imgSrc']; ?>" class="img-polaroid" style="width:120px" />
                            </td>
                            <td width=30% style="padding-left:1em;padding-right:1em">
                                
                                    <?php echo $cartDetails['name'], $cartDetails['quantity_str']; ?><br /><br />
                                    <?php echo $cartDetails['price']; ?>
  
                            </td>
                            <td width=40% style="border-left:solid 1px #eee;padding-left:1em;padding-right:1em">                           
                                <u> Contribution Deadline </u><br /><br />
                                
                                    <?php
                                    echo $target_date;
                                    echo "<br />";
                                    echo "[{$days_left}]";
                                     ?>                              
                            </td>
                        </tr>
                        <tr>
						<td><div style='text-align:center;font-weight:bold'><?php echo "<a style='text-decoration:none' href='ViewCart?".$thisGroupUser['URL_arg']."'><i class=icon-gift></i> View Gift</a>"; ?></div></td>
						<td></td>
						<td></td>
						</tr>
                        
                        
                    </tbody>
                </table>
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
            <div style="padding-left: 10px">
                <?php 
                if ($organizerDetails['permission_invite_more']) { ?>
                <a class='btn btn-primary' href='InviteMore?<?php echo $_SERVER['QUERY_STRING'] ?>'>You Can Invite <?php echo $thisGroupUser['canInviteCount']; ?> More Friends</a>
                <br />
                <br />
                <br />
                <?php } ?>
            
            
				<div class='input-append'>
				<?php echo "<textarea id='message-from-invitee' rows=4 style='width:90%' placeholder='Type personal message to recipient'>{$thisGroupUser['message']}</textarea>";
				?>
				<button class='btn' title='Save' id='message-from-invitee-button' style='border-radius:0 14px 14px 0'><i class='icon-pencil'> </i></button>
				</div>
            </div>
        </div>
        <div class="span5" style="margin-bottom:10px">
            <div style="margin-right:10px" class="bordered-box">
                <!--
                <div class="left-right">
                    <div style="float:left;margin-top:10px; margin-left:10px">
                        <h5> Gift Deadline Date </h5>
                    </div>

                </div>
                <div style="margin-left:10px; text-align: left;">
                    <?php //echo "{$target_date} [{$days_left}]"; ?>
                </div>
               
                <hr style="margin-bottom:10px" />
                -->
                <div class="left-right" style="margin-bottom:10px;margin-top:14px">
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
                        </tr>
                    </tbody>
                </table>
                <div class="left-right" style="margin-bottom:10px">
                    <div style="float:left; margin-left: 10px; margin-right:10px; margin-top:5px;">
                        Your share: INR  <?php echo $thisGroupUser['share']; ?>
                    </div>
                    <div>
					<?php if (isset($thisGroupUser['payment_URL'])) {
						echo "<a href='{$thisGroupUser['payment_URL']}' class='btn btn-link'><img src='img/Contribute-share.png' /></a>";
					}
					?>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id='overlay'></div>