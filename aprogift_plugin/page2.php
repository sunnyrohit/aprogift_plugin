<html>
    <head>
        <title>
            
        </title>
        <link href="bootstrap/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />
        <link href="bootstrap/css/bootstrap.css" rel="stylesheet" />
        <script type="text/javascript" src="js/jquery-1.9.0.min.js"> </script>
        <script type="text/javascript" src="bootstrap/js/bootstrap.min.js"> </script>
        <script type="text/javascript" src="bootstrap/js/bootstrap-datetimepicker.min.js"> </script>
		
		<script type="text/javascript">
				var w = 200;
				var h = 200;
				var left = Number((screen.width/2)-(w/2));
				var tops = Number((screen.height/2)-(h/2));
		</script>
		
        <style type="text/css">
            .container {
                margin-top:50px;
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
        
        </style>
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="span9">
                     <img src="img/logo_white2.jpg" height="48" width="170"/>
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
                                    <a href="javascript:window.open('http://www.aprogift.com/faqs','mywindow','width=1000,height=700')">FAQs</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <hr style="margin-top: 0px" />
            <div class="row pagination-centered">
                <div class="span12">
                     <img src="img/s 2.png" />
                </div>
            </div>
            <br />
            <div class="row">
                <div class="span12" style="padding-left: 10px">
                    Split the gift cost (INR 25000) among your friends
                </div>
            </div>
            <br />
            <form action="page3.php">
                <div class="row">
                    <div class="span8">
                        <div style="padding-left:10px">
                            <table class="" align="center" width=100%>
                                <tbody>
                                    <tr style="text-align:center; border-bottom: solid 1px #eee;">
                                        <td width=30% style="padding-bottom:10px">
                                            <div class="input-prepend input-append" style="margin-bottom:0px;">
                                                <input class="span2p3" style="height: 22px" placeholder="Best way to invite friends" id="appendedPrependedInput" type="text">
                                                <span class="add-on" style="padding: 0px">
                                                    <img src="img/facebook_icon_small.jpg" />
                                                </span>
                                            </div>
                                        </td>
                                        <td width=10%>
                                            or
                                        </td>
                                        <td width=25%>
                                            <input class="span2" style="height: 22px; margin-bottom:0px" placeholder="Your name" type="text">
                                        </td>
                                        <td width=35%>
                                            <input class="span2p3" style="height: 22px; margin-bottom:0px" placeholder="Your email address" type="text">
                                        </td>
                                    </tr>
                                    <tr style="text-align:center;">
                                        <td width=30% style="padding-top:10px">
                                            <div class="input-prepend input-append" style="margin-bottom:0px;">
                                                <input class="span2p3" style="height: 22px" placeholder="Enter friend's name" id="appendedPrependedInput" type="text">
                                                <span class="add-on" style="padding: 0px">
                                                    <img src="img/facebook_icon_small.jpg" />
                                                </span>
                                            </div>
                                        </td>
                                        <td width=10%>
                                            or
                                        </td>
                                        <td width=25%>
                                            <input class="span2" style="height: 22px; margin-bottom:0px" placeholder="Friend's Name" type="text">
                                        </td>
                                        <td width=35%>
                                            <input class="span2p3" style="height: 22px; margin-bottom:0px" placeholder="Friend's email address" type="text">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>          
                                        </td>
                                        <td>
                                        </td>
                                        <td>
                                        </td>
                                        <td style="text-align:right; padding-top:20px; padding-right: 27px" >
                                            <a class="btn btn-small">
                                                <i class="icon-plus"> </i> Add
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <br />
                        <div class="row">
                            <div class="span5">
                                <div style="padding-left:10px; padding-top:5px;">
                                    <label class="checkbox">
                                        <input type="checkbox" />Allow invitees to invite other friends
                                    </label>
                                </div>
                            </div>
                            <div class="span3">
                                <div id="datetimepicker4" class="input-append" style="text-align:right; padding-right: 27px">
                                    <input class="span2p3" style="height: 30px" placeholder="Set Deadline for group contribution" data-format="yyyy-MM-dd" type="text">
                                    </input>
                                    <span class="add-on">
                                        <i data-time-icon="icon-time" data-date-icon="icon-calendar">
                                        </i>
                                    </span>
                                </div>
                                <script type="text/javascript">
                                    $(function() {
                                        var deadline = $('#datetimepicker4').datetimepicker({
                                            pickTime: false
                                        }).
                                        on('changeDate', function(ev) {
                                            $('#datetimepicker4').datetimepicker('hide');
                                        })
                                    });
                                </script>
                            </div>
                        </div>
                        <br />
                        <div class="row">
                            <div style="padding-left:10px" class="span8">
                                Message to Invitees (OPTIONAL)
                                <br />
                                <br />
                                <textarea rows=5 style="width:80%" placeholder="You can enter a message here for your group of friends!"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="span4">
                        <div align=right style="padding-right:10px">
                            <select id="spliy" class="split-select" style="padding: 0px; height: 20px;">  
                                <option>Split Equally</option>
								<option>Split Unequally</option>
                            </select>  
                        </div>
                        <div id="contributions" style="padding-right:10px">
                            <table class="table table-bordered table-condensed table-striped" align="center" width="100%">
                                <tbody>
                                    <tr>
                                        <td width=10%>
                                            PIC
                                        </td>
                                        <td width=55%>
                                            <input id="MyName" class="span2" style="height: 22px; margin-bottom:0px" placeholder="Me" type="text">
                                        </td>
                                        <td width=35% style="text-align:right">
                                            <input id="MyShare" class="input-small" style="height: 22px; margin-bottom:0px" placeholder="My Share" type="text">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            P1
                                        </td>
                                        <td>
                                            <input id="Friend1" class="span2" style="height: 22px; margin-bottom:0px" placeholder="Friend 1" type="text">
                                        </td>
                                        <td style="text-align:right">
                                            <input id="Share1" class="input-small" style="height: 22px; margin-bottom:0px" placeholder="Share 1" type="text">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            P2
                                        </td>
                                        <td>
                                            <input id="Friend2" class="span2" style="height: 22px; margin-bottom:0px" placeholder="Friend 2" type="text">
                                        </td>
                                        <td style="text-align:right">
                                            <input id="Share2" class="input-small" style="height: 22px; margin-bottom:0px" placeholder="Share 2" type="text">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            P3
                                        </td>
                                        <td>
                                            <input id="Friend3" class="span2" style="height: 22px; margin-bottom:0px" placeholder="Friend 3" type="text">
                                        </td>
                                        <td style="text-align:right">
                                            <input id="Share2" class="input-small" style="height: 22px; margin-bottom:0px" placeholder="Share 2" type="text">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            P4
                                        </td>
                                        <td>
                                            <input id="Friend4" class="span2" style="height: 22px; margin-bottom:0px" placeholder="Friend 4" type="text">
                                        </td>
                                        <td style="text-align:right">
                                            <input id="Share3" class="input-small" style="height: 22px; margin-bottom:0px" placeholder="Share 3" type="text">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                        </td>
                                        <td>
                                            TOTAL
                                        </td>
                                        <td style="text-align:right">
                                            INR 25000
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <br />
                <div class="row">
                    <div class="span8">
                    </div>
                    <div class="span4" style="text-align:right;">
                        <button type="submit" class="btn" style="margin-right:10px;">
                            Send Invitations
                            <i class="icon-chevron-right"> </i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
        
    </body>
</html>