<html>
    <head>
        <title>
            
        </title>
        <link href="bootstrap/css/bootstrap.css" rel="stylesheet" />
        <link href="bootstrap/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />
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
        </style>
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="span9">
                    <a href="invitee.php">
                        <img src="img/Aprogift L.png" />
                    </a>
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
                                    <a href="javascript:window.open('http://www.aprogift.com/faqs','mywindow','width=1200,height=900')">FAQs</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <hr style="margin-top: 0px" />
            <div class="row">
                <div class="span12" style="padding-left:10px">
                     <h4> Manage Mike's Group Gift </h4>
                </div>
            </div>
            <br />
            <div class="row">
                <div class="span7">
                    <div style="padding-left: 10px">
                        <table class="">
                            <tbody>
                                <tr>
                                    <td>
                                        <img src="img/item.jpg" class="img-polaroid" style="height:100px; width:100px" />
                                    </td>
                                    <td>
                                        <ul>
                                            <li style="margin-bottom:10px;"> Dell Inspiron </li>
                                            <li style="margin-bottom:10px;"> INR 25000 </li>
                                            <li> Item No. 123245 </li>
                                        </ul>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <br />
                    <div class="span1" style="padding-left: 10px">
                    </div>
                    <div>
                        <div class="span3" style="padding-left:20px">
                            <button class="btn btn-danger">
                                Purchase Now, Collect Later
                            </button>
                        </div>
                        <div class="span2 pagination-centered">
                            <a href="">
                                Edit gift
                            </a>
                        </div>
                        <br />
                        <br />
                        <div style="padding-left: 120px">
                            <ul>
                                <li>
                                    <h5> <a href=""> INVITE MORE FRIENDS </a> </h5>
                                    Invite more friends to contribute via Facebook or Gmail
                                </li>
                                <li>
                                    <h5> <a href=""> MESSAGE INVITEES </a> </h5>
                                    Remind friends to chip in before gift deadline
                                </li>

                                <li>
                                    <h5> <a href=""> CANCEL GIFT </a> </h5>
                                    Cancel this group gift and refund contributors
                                </li>

                            </ul>
                        </div>
                    </div>
                    <br />
                    <div style="padding-left: 10px">
                        <span>
                            INR 20000 needed before March 10, 2013
                        </span>
                        <span style="float:right">
                            Gift Goal: INR 25000
                        </span>
                    </div>
                    <div style="padding-left: 10px">
                        <div class="progress">
                            <div class="bar bar-success" style="width: 20%;">5000</div>
                        </div>                        
                    </div>
                    <br />
                    <div style="padding-left: 10px">
                        <form class="form-inline" action="">
                            Change your contribution : INR 
                            <input type="text" class="input-small" value="5000" />
                            <a href="" class="btn btn-primary btn-small">
                                Apply
                            </a>
                        </form>
                    </div>
                </div>
                <div class="span5">
                    <div style="margin-right:10px" class="bordered-box">
                        <div class="left-right">
                            <div style="float:left;margin-top:10px; margin-left:10px">
                                <h5> Gift Deadline Date </h5>
                            </div>
                            <div style="float:right; margin-top:20px; margin-right:10px"> [Edit] </div>
                        </div>
                        <div style="margin-left:10px; text-align: left;">
                            March 10, 2013 [10 days left]
                        </div>
                        <hr style="margin-bottom:10px" />
                        <div class="left-right" style="margin-bottom:10px">
                            <div style="float:left; margin-left:10px">
                                <i class="icon-chevron-down"> </i>
                                CHIPPED IN
                            </div>
                            <div style="float:right; margin-right:10px"> <strong> 1 </strong> </div>
                        </div>
                        <table class="table table-striped" align="center" width="100%">
                            <tbody>
                                <tr>
                                    <td width=10%>
                                        PIC
                                    </td>
                                    <td width=55%>
                                        John Sculley
                                    </td>
                                    <td width=35% style="text-align:right">
                                        INR 5000
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="left-right" style="margin-bottom:10px">
                            <div style="float:left; margin-left:10px">
                                <i class="icon-chevron-down"> </i>
                                AWAITING REPLY
                            </div>
                            <div style="float:right; margin-right:10px"> <strong> 3 </strong> </div>
                        </div>
                        <table class="table table-striped" align="center" width="100%">
                            <tbody>
                                <tr>
                                    <td>
                                        P1
                                    </td>
                                    <td>
                                        Ram Manohar
                                    </td>
                                    <td style="text-align:right">
                                        INR 5000
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        P2
                                    </td>
                                    <td>
                                        Maton Bav
                                    </td>
                                    <td style="text-align:right">
                                        INR 5000
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        P3
                                    </td>
                                    <td>
                                        Kelly Grammar
                                    </td>
                                    <td style="text-align:right">
                                        INR 5000
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
    </body>
</html>