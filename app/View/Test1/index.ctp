<?php
    $this->start('head');
?>

<style type='text/css'>
#message{
	margin:3em auto 10em;
	font-size:1.2em;
	text-align:center;
	border:1px solid #999;
	color:#050;
    background-color:#dfd;
    width:16em;
    padding:0.5em;
    font-weight:bold;
    
}

</style>

<?php
    $this->end();
?>

<div id="message">
<?php echo $msg; ?>
</div>