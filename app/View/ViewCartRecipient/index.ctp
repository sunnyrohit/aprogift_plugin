<?php
    $this->start('head');
?>
    
    <script type="text/javascript">

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
    $recipientName = explode(' ', $recipientDetails['name']);
	$recipientName = $recipientName[0];

    echo "<h4> Hi ".ucfirst($recipientName).". Welcome to your giftBox</h4>";
	echo "<div id='go-back-container'><a class='btn btn-info' href='Recipients?{$_SERVER['QUERY_STRING']}'><i class='icon-arrow-left icon-white'> </i> Go Back</a></div>";
	echo "<table id='giftbox-table' class='table table-striped' align='center' width='100%'><thead><tr style='font-size:1.2em'><th> </th><th>Name</th><th>Price</th><th>Qty</th><th>Total</th></tr></thead>";
	
	if (count($cartDetails['details']) > 0) {
		foreach ($cartDetails['details'] as $product) {
			echo "<tr data-row-type='data-row' data-product-id={$product['id']}>";
			echo "<td><img src='{$product['imgSrc']}' width=122px></td>";
			echo "<td>{$product['name']}</td>";
			echo "<td>{$product['price']}</td>";
			echo "<td>{$product['quantity']}</td>";
			echo "<td class='row-total'>".$product['price']*$product['quantity']."</td>";
			echo "</tr>";
		}
		echo "<tr style='font-size:1.4em; font-weight:bold'><td></td><td></td><td></td><td>Total</td><td id='final-total'>{$cartDetails['price']}</td></tr></table>";
	} else {
		echo "</table><div id='empty-giftbox'>Your gift box is empty</div>";
	}
	
	
?>
<div id="overlay"></div>