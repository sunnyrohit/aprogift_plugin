<?php
    $this->start('head');
?>

<link href="bootstrap/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />
<script type="text/javascript" src="bootstrap/js/bootstrap-datetimepicker.min.js"> </script>

<script type="text/javascript">

var shippingAddressFilled = <?php if ($shippingAddressFilled) echo "true"; else echo "false"; ?>;
var selfTarget = window.location.href;

$(document).ready(function() {

$("form").submit(function(e) {
	e.preventDefault();
});

function getGift() {
    if (shippingAddressFilled) {
        customPopupMessage("Your gift shall be delivered to your address soon", 40);
    } else {
        customPopupMessage("Please enter your address for receiving the gift", 40);
    }
}

$("#get-gift").on("click", getGift);

<?php if (!$shippingAddressFilled) { ?>

function empty(el) {
    return el == "";
}
function validatePhone(mobNum) {
    var re =/^\d*$/;
    return re.test(mobNum);
}


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
            "name":name,
            "address":address,
            "city":city,
            "state":state,
            "country":country,
            "postal-code":postalCode,
            "phone":phone
        };
    
    $.ajax({
        url:selfTarget,
        method:"POST",
        data:data,
        success:function(response) {
            if (response == "success") {
                shippingAddressFilled = true;
                $("#shippingDetails input[type='text']").css("background-color", "#dfd");
                setTimeout(function() {
                        $("#shippingDetails input[type='text']").css("background-color", '');
                    } 
                ,5000);
            }
            
        }
    });
});

<?php } ?>


$("#message-from-recipient-button").height($("#message-from-recipient").height());
	
	$("#message-from-recipient-button").on("click", function() {
		var message = $("#message-from-recipient").val();
		
		var data =
			{
				"command":"thank-group-users",
				"message":message
			}
		
		$.ajax({
			url:selfTarget,
			method:"POST",
			data:data,
			success:function(response) {
            
                if (response == "error") {
                    response = "There was a problem sending your message";
                }
                
				var oldColor = $("#message-from-recipient").css("background-color");
				$("#message-from-recipient").css("background-color", "#dfd");

                $('body').append('<div id=overlay> </div>');				
				$('body').append("<div id=flash-message style='width:25%;left:40%:top:19%'>Following people were thanked for the group gift<br><br>"+response+"<br><br><div id='okay-container-2'><div id='okay-2' class='btn btn-primary' style='margin:0 auto'>Okay</div></div></div>");
				$("#okay-2").on("click", function() {
					$("#overlay").fadeOut(250);
					$("#flash-message").slideUp(200, function() {
						$("#flash-message").remove();
					});
					
				});
					$("#overlay").fadeIn(200);
					$("#flash-message").slideDown(350);
			}
		});
	});

});

</script>

<style type="text/css">
            
	

	#okay-container-2{
		margin:0 auto;
		width:5em;
	}		
	
	.container {
		margin-top:50px;
		border: solid 1px #eee;
		border-radius:25px;
		-moz-border-radius:25px; /* Old Firefox */
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

                <div class="row">
                    <div class="span12" style="padding-left:10px">
                         <h4>
                            <!-- <span style="border:1px solid #eee">PIC</span> -->
                            <u> Hi <?php echo ucwords($recipientDetails['name']); ?>, you have received a gift from a group of your friends! </u>
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
                                            <img src="<?php echo $cartDetails['imgSrc']; ?>" class="img-polaroid" style="width:120px" />
                                        </td>
                                        <td style="padding-left:10px">
                                            <ul>
                                                <li style="margin-bottom:10px;"> <?php echo $cartDetails['name']; ?> </li>

                                            </ul>
                                        </td>
                                    </tr>
                                    <tr>
                                    <td><div style='text-align:center;font-weight:bold'><a href='<?php echo "ViewCartRecipient?{$recipientDetails['URL_arg']}"; ?>'>View Gift</a></div></td>
                                    <td></td>
                                    </tr>
                                </tbody>
                            </table>
                            
                           <div class='input-append'>
							<textarea id='message-from-recipient' rows=4 style='width:85%' placeholder='Send a personal message to your group to say how awesome they are!'></textarea>					
							<button class='btn' title='Save' id='message-from-recipient-button' style='border-radius:0 14px 14px 0;height:6.4em'>Send</button>
						   </div>
                            
                        </div>
                        <br />
                        <!--
                            <div style="padding-left:10px">
                                <h5>
                                    <i class="icon-list"></i>
                                    <a href="">Click here to store it for redeeming at a later date</a>
                                </h5>
                                <h5>
                                    <i class="icon-list"></i>
                                    <a href="">Click to convert it into a voucher</a>
                                </h5>
                            </div>
                        -->
                    </div>
                    <div class="span7">
                        <div style="padding-left:10px; margin-bottom:10px; border-left:1px solid #eee">
                            <ul class="spaced-list">

                                <li>
                                    <strong>Your email : <?php echo $recipientDetails['email']; ?></strong>
                                </li>
                            </ul>
                            <br />
                            
                            <?php if ($shippingAddressFilled) {
                        echo "<div style='top:1em;position:relative;padding-bottom:1em;margin-bottom:1em'>";
                        echo "<div style='position: absolute;top: -1.2em;left: 2em;z-index: 1000;padding: 0.5em;background-color: #fff;'>Shipping Details</div>";
                        echo "<div style='border:1px solid #999; padding:0.3em;padding-top:1em;width:90%'>";
                        
                        echo "{$recipientDetails['shipping_name']}<br/>";
                        echo "{$recipientDetails['address']}<br/>";
                        echo "{$recipientDetails['city_state_country']}<br/>";
                        echo "PIN: {$recipientDetails['postal_code']}<br/>";
                        echo "Phone: {$recipientDetails['phone']}<br/><br/>";
                        
                        echo "</div>";
                        echo "</div>";

                    } else {
                    ?>
                    
                    <table id='shippingDetails'>
                      <tr>
                        <td>
                            <input type=text placeholder=Name id=name maxlength=63 value=''>
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
                    
                    <?php } ?>
                    
							
                            <br />
                            <div style="text-align:right; padding-right:3%;">
                               <button class="btn-link" id='get-gift'>
                                        <img src="img/Get-Gift.png" />
                      </div>
                        </div>
                    </div>
                </div>
            </div>

    