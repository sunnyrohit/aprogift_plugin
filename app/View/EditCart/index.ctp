<?php
    $this->start('head');
?>
    
    <script type="text/javascript">
        
  $(document).ready(function() {

		function blink($el) {
			for (var i=0 ;i<4; i++) {
				$el.fadeOut(70+(i*50));
				$el.fadeIn(70+(i*40));			
			}
		}
		var selfTarget = window.location.href;
        <?php if ($organizerDetails['transaction_completed_on'] == NULL) { ?>
		$(".save-quantity").on("click", function() {		

            var $row = $(this).closest("tr");

			var data = {};
			data['productID'] = $row.attr("data-product-id");
			data['command'] = "update-quantity";
			data['newQuantity'] = $row.find("input").eq(0).val();
			if (isNaN(data['newQuantity'])) {
				alert("Please enter a proper number");
				return;
			}
			if (data['newQuantity'] == $row.find("input").eq(0).attr("data-quantity"))
				return;
			if (data['newQuantity'] == 0) {
				$(this).parent().parent().parent().find("div.remove-product").click();
				return;
			}
			
			$.ajax({
				url:selfTarget,
				method:"POST",
				data:data,
				success:function(response) {
					console.log(response);
					$row.find("input").eq(0).attr("data-quantity", data['newQuantity']);
					if (response != "success")
						window.location.reload();
					calculateCart();
					setTimeout(
						function() {
							blink($("#redistribute-shares"));
						}
						,1000
					);
				}
			});
			
		});
		
		
		$(".remove-product").on("click", function() {
			var $row = $(this).closest("tr");

			var data = {};
			data['productID'] = $row.attr("data-product-id");
			data['command'] = "remove-product";
			
			$row.fadeOut(1000);
			$.ajax({
				url:selfTarget,
				method:"POST",
				data:data,
				success:function(response) {
					console.log(response);
					if (response != "success") 
						window.location.reload();
					$row.remove();
					calculateCart()
					setTimeout(
						function() {
							blink($("#redistribute-shares"));
						}
						,1000
					);
				}
			});
			
		});
        
        <?php } ?>
        
	
	});
	
	
	function calculateCart() {
		$tr = $("table#giftbox-table tr");
		var finalTotal = 0;
		var rowFound = false;
		$.each($tr, function(index, el) {
			$el = $(el);
			if ($el.attr("data-row-type") == "data-row") {
				rowFound = true;
				var $price = $el.find("td.price");
				var price = parseInt($price.html());
 				
				var $quantity = $el.find("td.quantity input");
				var quantity = parseInt($quantity.attr("data-quantity"));
				
				var $rowTotal = $el.find("td.row-total");
				
				var total = price*quantity;
				
				finalTotal += total;
				
				fadeWithValue($rowTotal, total)
				
			}
		});
		
		fadeWithValue($("table#giftbox-table #final-total"), finalTotal)
		
		if (!rowFound)
			window.location.reload();
	}
	
	
	function fadeWithValue($el, val) {
		if ($el.html() != val) {
			$el.fadeOut(160);
			$el.html(val);
			$el.fadeIn(100);
		}
	}
        
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

    
    .remove-product{
		cursor:pointer;
	}
	#empty-giftbox{
		width:40%;
		margin: 2em auto 5em;
		text-align:center;
		padding:0.4em;
		background-color:#fee;
		font-size:1.2em;
		border-radius:1em;
	}
	#add-more-container, #redistribute-shares, #go-back-container{
		font-size:1.1em;
		margin-left:1em;
		display:inline;
	}
    </style>


<?php
    $this->end();


    echo "<h3> Your GiftBox ";
	if ($organizerDetails['transaction_completed_on'] == NULL) { 
        echo "<div id='add-more-container'><a class='btn btn-success' href='AddMoreToGift?{$_SERVER['QUERY_STRING']}'>Add more to the gift</a></div>";
        echo "<div id='redistribute-shares'><a class='btn btn-primary' href='EditInvitees?{$_SERVER['QUERY_STRING']}'>Redistribute Shares Among Friends</a></div>";
    }
    echo "</h3>";
	echo "<div id='go-back-container'><a class='btn btn-info' href='Tracker?{$_SERVER['QUERY_STRING']}'><i class='icon-arrow-left icon-white'> </i> Go Back</a></div>";
	echo "<table id='giftbox-table' class='table table-striped' align='center' width='100%'><thead><tr style='font-size:1.2em'><th> </th><th>Name</th><th>Price</th><th>Qty</th><th>Total</th><th> </th></tr></thead>";
	
	if (count($cartDetails['details']) > 0) {
		foreach ($cartDetails['details'] as $product) {
			echo "<tr data-row-type='data-row' data-product-id={$product['id']}>";
			echo "<td><img src='{$product['imgSrc']}' width=122px></td>";
			echo "<td>{$product['name']}</td>";
			echo "<td class='price'>{$product['price']}</td>";
			
            if ($organizerDetails['transaction_completed_on'] == NULL) {
                echo "<td class='quantity'><div class='input-append'><input type=text data-quantity={$product['quantity']} class='span2' value={$product['quantity']} style='width:20px'><button class='btn save-quantity' type='button'>Save</button></div></td>";
			} else {
                echo "<td class='quantity'>{$product['quantity']}</td>";
            }
            
            
            echo "<td class='row-total'>".$product['price']*$product['quantity']."</td>";
			
            $removeOrNot = ($organizerDetails['transaction_completed_on'] == NULL) ? "<div class='remove-product'><img src='img/close.gif' /></div>" : "";
            echo "<td>{$removeOrNot}</td>";
			
            
            echo "</tr>";
		}
		echo "<tr style='font-size:1.4em; font-weight:bold'><td></td><td></td><td></td><td>Total</td><td id='final-total'>{$cartDetails['price']}</td><td></td></tr></table>";
	} else {
		echo "</table><div id='empty-giftbox'>Your gift box is empty</div>";
	}
	
	
?>
<div id="overlay"></div>