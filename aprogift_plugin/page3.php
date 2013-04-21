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
            
            ul.spaced-list li {
                margin-top: 10px;
            }
            
        </style>
    </head>
    <body>
        <form action="tracker.php">
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
                         <img src="img/s 3.png" />
                    </div>
                </div>
    
                <div class="row">
                    <div class="span12" style="padding-left:10px">
                         <h4>
                            <span style="border:1px solid #eee">PIC</span>
                            <u> Share Mike's Birthday Gift </u>
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
                                            <img src="img/item.jpg" class="img-polaroid" style="height:100px; width:100px" />
                                        </td>
                                        <td style="padding-left:10px">
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
                        <div style="padding-left:10px">
                            <h5>
                                <i class="icon-list"></i>
                                <a href="">Edit Friends List</a>
                            </h5>
                            <h5>
                                <i class="icon-remove-sign"></i>
                                <a href="">Edit Gift</a>
                            </h5>
                        </div>
                    </div>
                    <div class="span7">
                        <div style="padding-left:10px; margin-bottom:10px; border-left:1px solid #eee">
                            <ul class="spaced-list">
                                <li>
                                    <strong>Your Share : INR 5000</strong>
                                    <br />
                                    You will not be charged until everyone contributes their share
                                </li>
                                <li>
                                    <strong>Your Name : PQR</strong>
                                </li>
                                <li>
                                    <strong>Your email : abc@xyz.com</strong>
                                </li>
                            </ul>
                            <br />
                            <textarea rows=4 style="width:80%" placeholder="Personal message to recipient"></textarea>
                            <br />
                            <div style="text-align:right; padding-right:3%;">
                                <button class="btn btn-danger">
                                    Contribute your share
                                    <i class="icon-chevron-right icon-white"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </body>
</html>