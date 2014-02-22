<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
	</title>

	<link href="bootstrap/css/bootstrap.css" rel="stylesheet" />

	<style type="text/css">
            .container {
                margin-top:50px;
                border: solid 1px #eee;
                border-radius:25px;
                -moz-border-radius:25px; /* Old Firefox */
            }
	</style>

    <script type="text/javascript" src="js/jquery-1.9.0.min.js"> </script>
    <script type="text/javascript" src="bootstrap/js/bootstrap.min.js"> </script>

    <?php echo $this->fetch('head'); ?>

</head>
<body>
    
    <?php echo $this->fetch('body'); ?>
    
	<div class="container">
		<div class="row">
			<div class="span9">
				<img src="img/Aprogift L.png" />
			</div>
			<div class="span2">
				<table>
				    <tbody>
					<tr>
					    <td>
						&nbsp;
					    </td>
					</tr>
					<tr>
					    <td>
						<a href="javascript:window.open('img/logo_white.jpg', '', 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+tops+', left='+left);">How it works</a>
					    </td>
					</tr>
				    </tbody>
				</table>
			</div>
			<div class="span1">
			    <table>
				<tbody>
				    <tr>
					<td>
					    &nbsp;
					</td>
				    </tr>
				    <tr>
					<td>
					    FAQs
					</td>
				    </tr>
				</tbody>
			    </table>
			</div>
		</div>
	
		<?php echo $this->fetch('content'); ?>
	
	</div>
	
	<?php echo $this->element('sql_dump'); ?>
</body>
</html>
