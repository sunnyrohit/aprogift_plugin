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
                border-radius:0 25px 25px 25px;
                -moz-border-radius:0 25px 25px 25px; /* Old Firefox */
            }
            
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

	#okay-container{
		margin:0 auto;
		width:5em;
	}
            
	</style>

    <script type="text/javascript" src="js/jquery-1.9.0.min.js"> </script>
    <script type="text/javascript" src="bootstrap/js/bootstrap.min.js"> </script>
    <script type="text/javascript">
        var newwindow;
        function openPopup(url, name, width, height) {
            newwindow=window.open(url, name,'width='+width+',height='+height);
            if (window.focus) {newwindow.focus()}
        }
        
        function customPopupMessage(message, width, okayText) {
        
            okayText = typeof okayText !== 'undefined' ? okayText : "Okay";
            width = typeof width !== 'undefined' ? width : 50;
        
            $('body').append("<div id='overlay'></div>");
            var leftDist = (100-width)/2;
            
            $('body').append("<div id=flash-message style='text-align:center;width:"+width+"%;left:"+leftDist+"%;'><br>"+message+"<br><br><div id='okay-container'><div id='okay' class='btn btn-primary' style='margin:0 auto'>"+okayText+"</div></div></div>");
            
            $('#okay').on("click", function(){
                $("#overlay").fadeOut(250, function(){
                    $("#overlay").remove();
                });
                $("#flash-message").slideUp(200, function() {
                    $("#flash-message").remove();
                });
            });
            
            $("#overlay").fadeIn(200);
            $("#flash-message").slideDown(350);

            return true;
        }
        
    </script>
    
    <?php echo $this->fetch('head'); ?>

</head>
<body>
    
    <?php echo $this->fetch('body'); ?>
    
	<div class="container">
		<div class="row">
			<div class="span9">
				<img src="<?php echo $siteLogo; ?>"  style='padding:0.2em 0 0.4em 0.7em;height:3.9em'></img>	 		</div>
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
						<a href="javascript:openPopup('HowItWorks', '_blank', 600, 500);">How it works</a>					    </td>
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
					    <a href="javascript:openPopup('FAQ','_blank', 600, 700)">FAQs</a>					</td>
				    </tr>
				</tbody>
			    </table>
			</div>
		</div>
        <hr style="margin-top: 0px" />
    
		<?php echo $this->fetch('content'); ?>
	
	</div>
	
	<?php echo $this->element('sql_dump'); ?>
</body>
</html>
