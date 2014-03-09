<?php
    $this->start('head');
?>

<meta http-equiv="refresh" content="10;url=<?php echo $redirectTarget; ?>">
<style type='text/css'>
#message-cancel-gift{
	margin:1em auto 2em;
	font-size:1.2em;
	text-align:center;
	border:1px solid #999;
	background-color:#efe;
	width:60%;
	border-radius:5px;
	-webkit-border-radius:5px;
}

</style>

<?php
    $this->end();
?>

<div id="message-cancel-gift">
<?php echo $message; ?> <br /><a href='<?php echo $redirectTarget; ?>'>Click here if your browser doesnt redirect you in 10 seconds</a>
</div>