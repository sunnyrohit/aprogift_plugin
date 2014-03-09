<?php
    $this->start('head');
?>

<style type='text/css'>
	#form-message{
		background-color:#eee;
		color:#900;
		text-align:center;
		padding:1em;
		margin:0 auto;
	}
	#form-container{
		margin:2em auto;
		padding:0.5em;
		width:40%;
	}

</style>
<?php
    $this->end();
    if (!$validUser) {
    	if (isset($formMessage)) {
    		echo "<div id='form-message'>{$formMessage}</div>";
    	}
    }
?>
<h3 style='text-align:center'>Admin Login</h3>
<div id='form-container'>
<form name='validation' method='post' action=''>
<table border='0'>
<tr>
<td>Username</td>
<td><input type='text' name='username' placeholder='username'></td>
</tr><tr>
<td>Password</td>
<td><input type='password' name='password' placeholder='password'></td>
</tr><tr>
<td></td>
<td><input type='submit' value='Log In' class='btn btn-primary'></td>
</tr>
</table>
<form>
</div>
