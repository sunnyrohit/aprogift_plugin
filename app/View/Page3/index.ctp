<?php
    $this->start('head');
?>

    <link href="bootstrap/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />
    <script type="text/javascript" src="bootstrap/js/bootstrap-datetimepicker.min.js"> </script>
    <script type="text/javascript">
	
    var shippingDetailsSaved = false;
    
    function empty(el) {
        return el == "";
    }
    function validatePhone(mobNum) {
        var re =/^\d*$/;
        return re.test(mobNum);
    }
    
	$(document).ready(function(){
    
        var secret = $("#message-from-organizer").attr('data-secret');
        
        $("#disableShippingAddress").on("click", function(){
            if ( $(this).is(":checked") ) {
                $("#shippingDetails input").prop("disabled", "disabled");
                if (shippingDetailsSaved) {
                    $.ajax({
                        url:"Page3",
                        method:"POST",
                        data:{"command":"delete-shipping-details", "secret":secret},
                        success:function(response) {
                            if (response == "success") {
                                shippingDetailsSaved = false;
                                $("#shippingDetails input[type='text']").val("");
                            }
                            
                        }
                    });

                    
                }
            }
            else {
                $("#shippingDetails input").prop("disabled", "");
            }
        });
        
        $("#saveShippingDetails").on("click", function(){
            
            var name = $("#name").val();
            var address = $("#address").val();
            var city = $("#city").val();
            var state = $("#state").val();
            var country = $("#country").val();
            var postalCode = $("#pincode").val();
            var phone = $("#phone").val();

            if (empty(name) || empty(address) || empty(city) || empty(state) || empty(country) || empty(postalCode) || empty(phone)) {
                customPopupMessage("Please fill in the complete shipping address", 35);
                return;
            }
            if (!validatePhone(phone)) {
                customPopupMessage("Please enter a numerical phone number", 30);
                return;
            }
            
            var data = 
                {
                    "command":"save-shipping-details",
                    "secret":secret,
                    "name":name,
                    "address":address,
                    "city":city,
                    "state":state,
                    "country":country,
                    "postal-code":postalCode,
                    "phone":phone
                };
            
            $.ajax({
                url:"Page3",
                method:"POST",
                data:data,
                success:function(response) {
                    if (response == "success") {
                        shippingDetailsSaved = true;
						$("#shippingDetails input[type='text']").css("background-color", "#dfd");
						setTimeout(function() {
                                $("#shippingDetails input[type='text']").css("background-color", '');
                            } 
                        ,5000);
                    }
                    
                }
            });
        });
    
		$("#message-from-organizer-button").height($("#message-from-organizer").height());
		$("form").submit(function(e) {
			e.preventDefault();
		});		
				
		$("#message-from-organizer-button").on("click", function() {
			var message = $("#message-from-organizer").val();
			
			var data =
				{
					"command":"update-message-from-organizer",
					"message":message,
					"secret":secret
				}
			
			$.ajax({
				url:"Page3",
				method:"POST",
				data:data,
				success:function(response) {
					if (response == "success") {
						var oldColor = $("#message-from-organizer").css("background-color");
						$("#message-from-organizer").css("background-color", "#dfd");
						setTimeout(function() {
									$("#message-from-organizer").css("background-color", oldColor);
								} 
							,5000);
					} else {
						alert("There was a problem updating your message. Please refresh the page and try again.")
					}
				}
			});
		});
        
        $("#disableShippingAddress").click();
		
	});
	
	</script>
    <style type="text/css">        
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
        
        ul.spaced-list li {
            margin-top: 10px;
        }
        #shippingDetails input{
            width:100px;
        }
    </style>
    
<?php
    $this->end();
?>

<form action="Tracker" method=GET>
    <div class="row pagination-centered">
        <div class="span12" style='margin:-1em auto 2em'>
          Confirmation and Contribution <br />
      <img src="img/s3.png" />
        </div>
    </div>

    <div class="row">
        <div class="span12" style="padding-left:10px">
             <h4>
                <span style="border:1px solid #eee">PIC</span>
                <u> Share <?php echo ucfirst($recipient),"'s {$occasion}"; ?> Gift </u>
             </h4>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="span5">
            <div style="padding-left: 10px">
                <h4> The Gift </h4>
                <table class="">
                    <tbody>
                        <tr>
                            <td>
                                <img src="<? echo $giftDetails['imgSrc']; ?>" class="img-polaroid" style="height:100px; width:100px" />
                            </td>
                            <td style="padding-left:10px">
                                <ul>
                                    <li style="margin-bottom:10px;"> <?php echo $giftDetails['name']; ?> </li>
                                    <li style="margin-bottom:10px;"> <?php echo $giftDetails['currency'], " ",$giftDetails['price']; ?> </li>
                                    <!-- <li> Item No. <?php //echo $giftDetails['product_code']; ?> </li> -->
                                </ul>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <br />
            <div class='input-append'>
				<?php echo "<textarea id='message-from-organizer' rows=4 style='width:85%' data-secret={$organizerDetails['URL_arg']} placeholder='Type personal message to recipient'></textarea>";
				?>
				<button class='btn' title='Save' id='message-from-organizer-button' style='border-radius:0 14px 14px 0'>Save</button>
            </div>
            <br />
            <div style="padding-left:10px">
                <h5>
                    <i class="icon-list"></i>
                    <?php 
                    	echo "<a href='EditInvitees?".$organizerDetails['URL_arg']."'>Edit Friends List</a>";
                    ?>
                </h5>
                
                <h5>
                    <i class="icon-remove-sign"></i>
                    <?php
                    	echo "<a href='EditCart?".$organizerDetails['URL_arg']."'>Edit Gift</a>";
                    ?>
                </h5>
                
            </div>
        </div>
        <div class="span7">
            <div style="padding-left:10px; margin-bottom:10px; border-left:1px solid #eee">
                <ul class="spaced-list">
                    <li>
                        <strong>Your Share : INR <?php echo $myshare; ?></strong>
                        <br />
                        
                    </li>
                    <li>
                        <strong>Your Name : <?php echo $organizer; ?></strong>
                    </li>
                    <li>
                        <strong>Your email : <?php echo $organizerEmail; ?></strong>
                    </li>
                </ul>
                <br />
                
				<div style='top:1em;position:relative;padding-bottom:1em;margin-bottom:1em'>
                <div style="position: absolute;top: -1.2em;left: 2em;z-index: 1000;padding: 0.5em;background-color: #fff;">Shipping Details</div>
                  <div style='border:1px solid #888;padding:0.5em;padding-top:1em'>
                    <input type=checkbox id='disableShippingAddress' style='width:16px;height:16px'> 
                    
                    <label for='disableShippingAddress' style='font-size:0.9em;display:inline'>
                    Shipping Address to be decided by the recipient of the gift
                    </label>
                    <br /> 
                     <span style='font-size:0.8em'>
                        (Recipient will be prompted for the details once the gift amount is collected)
                      </span>
                    
                    <br />
                    <div style='font-size:1.5em;text-align:center;margin:1em 0 1em'>
                        OR
                    </div>
                    
                    <table id='shippingDetails'>
                      <tr>
                        <td>
                            <input type=text placeholder=Name id=name maxlength=63 value='<?php echo $recipient; ?>'>
                        </td>
                        
                        <td>
                            <input type=text placeholder='House Address' maxlength=255 id='address' style='width:218px'>
                        </td>
                      </tr>
                    
                      <tr>
                        <td>
                            <input type=text placeholder=City maxlength=63 id='city'>
                        </td>
                        
                        <td>
                            <table>
                              <tr>
                                <td>
                                    <input type=text placeholder=State maxlength=63 id='state'>
                                </td>
                                
                                <td>
                                    <input type=text placeholder=Country maxlength=63 id='country'>
                                </td>
                              </tr>
                            </table>
                        </td>
                      </tr>
                      
                      <tr>
                        <td>
                            <input type=text placeholder=Pincode maxlength=10 id='pincode'>
                        </td>
                        
                        <td>
                            <table>
                              <tr>
                                <td>
                                    <input type=text placeholder='Phone Number' maxlength=20 id='phone'>
                                </td>
                                
                                <td>
                                    <span style='font-size:0.8em'>(All fields are required)</span>
                                </td>
                              </tr>
                            </table>
                        </td>
                      </tr>
                    
                      <tr>
                      <td></td>
                      <td>
                        <input type=button class='btn btn-success' value='Save' id='saveShippingDetails'>
                      </td>
                      </tr>
                    
                    </table>
                    
                  </div>
            </div>
				
                <br />
                <div style="text-align:right; padding-right:3%;">
                    
                    <a class="btn-link" href='<?php echo $organizerDetails['payment_URL']; ?>'>
                         <img src="img/Contribute-share.png" />
                  </a>
                </div>
            </div>
        </div>
    </div>
</form>
