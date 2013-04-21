<?php
    $this->start('head');
?>

    <link href="bootstrap/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />
    <script type="text/javascript" src="bootstrap/js/bootstrap-datetimepicker.min.js"> </script>
    
    <style type="text/css">        
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
    
<?php
    $this->end();
?>

<form action="Tracker" method=POST>
    <div class="row pagination-centered">
        <div class="span12">
             <img src="img/s 3.png" />
        </div>
    </div>

    <div class="row">
        <div class="span12" style="padding-left:10px">
             <h4>
                <span style="border:1px solid #eee">PIC</span>
                <u> Share <?php echo $recipient; ?>'s Birthday Gift </u>
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
                                    <li style="margin-bottom:10px;"> <?php echo $giftname; ?> </li>
                                    <li style="margin-bottom:10px;"> <?php echo $giftcost; ?> </li>
                                    <li> Item No. <?php echo $giftid; ?> </li>
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
                        <strong>Your Name : <?php echo $organizer; ?></strong>
                    </li>
                    <li>
                        <strong>Your email : <?php echo $organizerEmail; ?></strong>
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
</form>