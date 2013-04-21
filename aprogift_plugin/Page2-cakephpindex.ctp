<?php
    $this->start('head');
?>

    <link href="bootstrap/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />
    <script type="text/javascript" src="bootstrap/js/bootstrap-datetimepicker.min.js"> </script>
    
    <style type="text/css">        
        .recipient-input, .occasion-select {
            width: 200px;
            padding: 0;
            margin: 0;
            height: 20px;
            -moz-box-sizing: border-box;
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
        }
        
        input[type="text"] {
            padding: 0px 0px 0px 5px !important;
        }
        
    </style>
    
    <script type="text/javascript">
    
    cost = <?php echo $giftcost; ?>;
    people = 1;
    
    function computeShare() {
        return (1.0 * cost) / people;
    }
    
    function updateShare(share) {
        $(".share").html(share);
    }
    
    $(document).ready(function() {
        $("#organizer").change(function() {
           $("#myname").html($("#organizer").val());
        });
    });

    function updateHiddenParams(name, email) {
        $("#numpeople").val(people);
        $("#hiddeninputs").append(
            "<input type=hidden name=person" + people + " value=\"" + name + "\" />" +
            "<input type=hidden name=personemail" + people + " value=\"" + email + "\" />"
        );
    }
    
    function addPerson() {
        var name = $("#personname").val();
        var email = $("#personemail").val();
        people++;
        share = computeShare();
        updateShare(share);
        $("#peopletable").append(
            "<tr>" +
                "<td> P" + people + " </td>" +
                "<td> " + name + " </td>" +
                "<td class=\"share\" style=\"text-align:right\"> " + share + " </td>" +
            "</tr>"
        );
        updateHiddenParams(name, email);
    }
    
    </script>
    
<?php
    $this->end();
?>

    <div class="row pagination-centered">
        <div class="span12">
             <img src="img/s 2.png" />
        </div>
		
    </div>
    <br />
    <div class="row">
        <div class="span12" style="padding-left: 10px">
            Split the gift cost (INR <?php echo $giftcost; ?>) among your friends
        </div>
    </div>
    <br />
    <form action="Page3" method="POST">
        <div id="hiddeninputs">
            <input type="hidden" id="numpeople" name="numpeople" value="1" />
            <input type="hidden" name="giftid" value="<?php echo $giftid; ?>" />    
        </div>
        <div class="row">
            <div class="span8">
                <div style="padding-left:10px">
                    <table class="" align="center" width=100%>
                        <tbody>
                            <tr style="text-align:center; border-bottom: solid 1px #eee;">
                                <!--
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
                                -->
                                <td width=25% style="padding-bottom:10px">
                                    <input class="span2" style="height: 22px; margin-bottom:0px" placeholder="Your name" type="text" name="organizer" id="organizer">
                                </td>
                                <td width=35% style="padding-bottom:10px">
                                    <input class="span2p3" style="height: 22px; margin-bottom:0px" placeholder="Your email address" type="text" name="organizeremail" id="myemail">
                                </td>
                            </tr>
                            <tr style="text-align:center;">
                                <!--
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
                                -->
                                <td width=25% style="padding-top:10px">
                                    <input class="span2" style="height: 22px; margin-bottom:0px" placeholder="Friend's Name" type="text" id="personname">
                                </td>
                                <td width=35% style="padding-top:10px">
                                    <input class="span2p3" style="height: 22px; margin-bottom:0px" placeholder="Friend's email address" type="text" id="personemail">
                                </td>
                            </tr>
                            <tr>
                                <td>          
                                </td>
                                <td>
                                </td>
                                <td>
                                </td>
                                <td style="text-align:right; padding-top:10px; padding-right: 27px" >
                                    <a onclick="javascript:addPerson()" class="btn btn-small">
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
                    <table class="table table-bordered table-condensed table-striped" align="center" width="100%" id="peopletable">
                        <tbody>
                            <tr>
                                <td>
                                </td>
                                <td>
                                    TOTAL
                                </td>
                                <td style="text-align:right">
                                    INR <?php echo $giftcost; ?>
                                </td>
                            </tr>                            
                            <tr>
                                <td width=10%>
                                    P1
                                </td>
                                <td id="myname" width=55%>
                                    Me
                                </td>
                                <td class="share" width=35% style="text-align:right">
                                    <?php echo $giftcost; ?>
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