<?php
    $this->start('head');
	if ($validUser) {
?>
<!--[if lt IE 9]><script language="javascript" type="text/javascript" src="js/plotting/excanvas.min.js"></script><![endif]-->
<script language="javascript" type="text/javascript" src="js/plotting/jquery.flot.min.js"></script>
<script language="javascript" type="text/javascript" src="js/plotting/jquery.flot.pie.min.js"></script>
<script language="javascript" type="text/javascript" src="js/plotting/jquery.flot.navigate.min.js"></script>
<script language="javascript" type="text/javascript" src="js/plotting/jquery.flot.time.min.js"></script>
<script language="javascript" type="text/javascript" src="js/plotting/jquery.flot.axislabels.js"></script>

<script type='text/javascript'>
$(document).ready(function(){
	$selectAnalytic = $(".select-type-of-analytic");
	$selectAnalytic.on("click", function(){
		$selectAnalytic.removeClass("selected");
		$(this).addClass("selected");
		getAndPublishAnalytics();
		
	});
	
	$plotType = $(".plot-type");
	$plotType.on("click", function() {
		$plotType.removeClass("selected");
		$(this).addClass("selected");
		getAndPublishAnalytics();
	});

	getAndPublishAnalytics();
});

function isEmpty(obj) {

    // null and undefined are "empty"
    if (obj == null) return true;

    // Assume if it has a length property with a non-zero value
    // that that property is correct.
    if (obj.length && obj.length > 0)    return false;
    if (obj.length === 0)  return true;

    // Otherwise, does it have any properties of its own?
    // Note that this doesn't handle
    // toString and toValue enumeration bugs in IE < 9
    for (var key in obj) {
        if (hasOwnProperty.call(obj, key)) return false;
    }

    return true;
}

function getAndPublishAnalytics() {
	var subject = $(".select-type-of-analytic.selected").attr('data-metric');
	var plotType = $(".plot-type.selected").attr("data-plot-type");
	var startDate = $("#date-range-container input[name='start-date']").val();
	var endDate = $("#date-range-container input[name='end-date']").val();
    
    if (startDate == "" || endDate == "") {
        alert("Please enter a proper date");
        return;
    }
    
    $("#plot-holder").html("");
	$("#loader").fadeIn(100);

	var url = document.URL;
    var path = url.substring(0,url.lastIndexOf("/")+1);
	
    $.ajax({
		url:path+"GetAnalytics",
		method:"post",
		dataType:'json',
		data:{"metric":subject, "plot-type":plotType, "start-date":startDate, "end-date":endDate},
		success:function(response){
			$("#loader").hide();
			$("#plot-holder").html("");
            
            if (isEmpty(response.metrics)) {
                $("#plot-holder").html("<div style='text-align:center;margin-top:5em;font-size:1.6em;color:#bbb'>No data</div>");
                return;
            }
			
			if (plotType == "aggregate") {
				if (subject == "average_price_sold") {
					var label, price;
					$.each(response.metrics, function(index, entry) {
						label = entry['label'];
						price = parseInt(entry['value'])
					});
					
					$.plot('#plot-holder', [{data:[[0,price]], label:label}], {
						series: {
							 bars: {
								 show: true,
								 barWidth: 2,
								 align: 'center'
							 }
						},
						grid:{
							hoverable:true
						},
						yxis:{
							axisLabel:"Average Price (in Rupees)"
						},
						xaxis:{
							show:false,
							min:-5,
							max:5,
							tickLength:0
						}
					});
					previousPoint = null; 
					$("#tooltip").remove();
					
					$("#plot-holder").off("plothover");
					$("#plot-holder").on("plothover", function(event, pos, item) {
						if (item) {
							if (previousPoint != item.seriesIndex) {
								$("#tooltip").remove();
								previousPoint = item.seriesIndex;
								//console.log(item);
								var labelString = makeLabelForBarChart(item.series.label, item.datapoint[1]);
								showTooltipChart(labelString, pos.pageX, pos.pageY);		
							}					
						} else {
							$("#tooltip").remove();
							previousPoint = null;
						}
						
					});
					
				} else {
					var flotData = JsonToFlotPieData(response.metrics);
					var total = getTotalPieChart(response.metrics);
					
					$.plot('#plot-holder', flotData, {
						series: {
							pie: {
								show: true,
								radius: 1,
							}
						},
						grid:{
							hoverable:true
						},
						legend: {
							show: true
						}
					});
					
					previousPoint = null;
					$("#tooltip").remove();
					
					$("#plot-holder").off("plothover");
					$("#plot-holder").on("plothover", function(event, pos, item) {
						if (item) {
							if (previousPoint != item.seriesIndex) {
								$("#tooltip").remove();
								previousPoint = item.seriesIndex;
								var labelString = makeLabelForPieChart(item.series.label, item.datapoint[1][0][1], total, item.datapoint[0]);
								showTooltipChart(labelString, pos.pageX, pos.pageY);		
							}					
						} else {
							$("#tooltip").remove();
							previousPoint = null;
						}
						
					});
				}
			} else if (plotType == "detailed") {
				var flotData = JsonToFlotBarData(response.metrics);
				//console.log(flotData);
				var plotInfo = response.axisLabels;
				var options = {
					series: {
						 bars: {
							 show: true,
							 barWidth: 7000000,
							 align: 'center'
						 },
					},
					pan:{
						interactive:true  
					},
					 yaxis: {
						 min: 0,
						 minTickSize:1,
						 panRange:false,
						 tickDecimals: 0,
						 axisLabel:plotInfo.yLabel
					 },
					 xaxis: {
						 mode: 'time',
						 timeformat: "%b %d",
						 ticks:10,
						 max:dateTotalEvents['max']+parseInt(1.4*24*3600*1000),
						 min:dateTotalEvents['max']-parseInt(4.3*24*3600*1000),
						 tickSize: [1, "day"],
						 tickLength:0,
						 panRange:[dateTotalEvents['min']-14*3600*1000,dateTotalEvents['max']+parseInt(1.4*24*3600*1000)],
						 axisLabel:plotInfo.xLabel
					 },
					 grid:{hoverable:true}
				 };
				
				$.plot('#plot-holder', flotData, options);
				
				previousPoint = null;
				$("#tooltip").remove();
				
				$("#plot-holder").off("plothover");
				$("#plot-holder").on("plothover", function(event, pos, item) {
					
					if (item) {
						if (previousPoint != item.seriesIndex) {							

							$("#tooltip").remove();
							previousPoint = item.seriesIndex;
							var labelString = makeLabelForBarChart(item.series.label, item.datapoint[1]);
							showTooltipChart(labelString, pos.pageX, pos.pageY);		
						}		
					} else {
						$("#tooltip").remove();
						previousPoint = null;
					}
					
				});
				
			}
			
		}
	});

}

function makeLabelForPieChart(label, value, total, percent) {
	var str = "<b>"+label+"</b><br />"+value + " out of " + total + " ("+parseInt(percent)+"%)";
	return str;
}

function makeLabelForBarChart(label, value) {
	var str = "<b>"+label+"</b><br />"+value;
	return str;
}

var previousPoint = null;

function showTooltipChart(labelStr, x, y) {
	$("<div id='tooltip'>"+labelStr+"</div>").css({
		position:"fixed",
		display:"none",
		"font-size":"0.9em",
		top:((y-43)+"px"),
		left:((x+5)+"px"),
		opacity:0.8,
		"text-align":"center",
		"border-radius":"5px",
		"box-shadow":"1px 1px 1px #555",
		"background-color":"#333",
		color:"#fff",
		padding:"3px"
	}).appendTo("body").fadeIn(200);
}

function getTotalPieChart(jsondata){
	var total = 0
	$.each(jsondata, function(index, entry) {
		total += parseInt(entry['value']);
	});
	return total;
}

var dateTotalEvents = {};

function JsonToFlotBarData(jsondata) {

	$.each(jsondata, function(key1, entry1) {
		var total = 0;
        var labeldate = entry1['label'];
		$.each(entry1['value'], function(key2, entry2) {
			total++;
		});
		// for jittering to display multiple bar charts
		dateTotalEvents[labeldate] = {"total":total, "iterator":(-1*parseInt(total/2))};
		
	});
	
	var data = {};
	
	dateTotalEvents['max'] = 0;
	dateTotalEvents['min'] = 9999999999999;
	
	$.each(jsondata, function(key1, entry1) {
		var d = entry1['label'].split('-');
		var t = new Date(parseInt(d[0]), parseInt(d[1])-1, parseInt(d[2]), 5,30);
		t = t.getTime();

        $.each(entry1['value'], function(key2, entry2) {

			if (typeof data[entry2['label']] == 'undefined') {
				data[entry2['label']] = {"label":entry2['label'], "data":[]};
			}
			
            var ntime = t+dateTotalEvents[entry1['label']]['iterator']*9000000;
			data[entry2['label']]['data'].push([ntime, entry2['value']]);
            dateTotalEvents[entry1['label']]['iterator']++;
		});
        
		var ntime = t+dateTotalEvents[entry1['label']]['iterator']*9000000;
		
		if (ntime > dateTotalEvents['max'])
			dateTotalEvents['max'] = ntime;
		if (ntime < dateTotalEvents['min'])
			dateTotalEvents['min'] = ntime;
            
	});
	
	var data2 = [];
	$.each(data, function(index, entry) {
		data2.push(entry);
	});
	
	return data2;
}

function JsonToFlotPieData(jsondata) {
	var data = [];
	
	$.each(jsondata, function (key, entry) {
		var tmp = {};
		tmp['label'] = entry['label'];
		tmp['data'] = parseInt(entry['value']);
		data.push(tmp);
	});

	return data;
}
</script>
<?php }?>
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
	#main-container{
		min-width:600px;
		padding:0 1px 10px 2px;
		margin-bottom:2em;
	}
	#left-container, #right-container{
		padding-bottom:14px;
	}
	#left-container{
		float:left;
		width:27%;
		min-width:179px;
		min-height:340px;
		border-right:1px solid #aaa;
	}
	#right-container{
		float:right;
		width:71%;
		min-width:419px;
		text-align:center;
	}
	.clear{
		clear:both;
	}
	.select-type-of-analytic{
		border:1px solid #aaa;
		border-right:1px solid #999;
		background-color:#eeeef5;
		padding:7px;
		margin:11px;
		margin-bottom:17px;
		text-align:center;
		cursor:pointer;
		font-size:1.2em;
		border-radius:10px 0 0 10px;
		box-shadow:1px 1px 1px #555;
	}
	.select-type-of-analytic:hover{
		background-color:#ededf5;
		border-right:1px solid #ccc;
	}
	.select-type-of-analytic.selected{
		font-weight:bold;
		background-color:#fff;
		border-right:1px solid #fff;
		width:92.5%;
		border-radius:10px 0 0 10px;
		box-shadow:inset 1px 1px 1px #555;
        
	}
	.select-type-of-analytic.selected:hover{
		cursor:default;
	}
	#plot-type-container{
		width:87%;
		margin:-1em auto 1.4em;
		padding:0.2em 0;
		border-radius:10px;
	}
	.plot-type{
		background-color:#eee;
		color:#444;
		display:inline;
		cursor:pointer;
		border:1px solid #bbb;
        border-bottom:0;
		padding:0.3em;
		margin:0;
        border-radius:8px 8px 0 0;
	}
    #date-range-container{
        float:left;
        margin-top:-0.2em;
        display:inline;
        font-size:0.9em
    }
    #date-range-container input{
        display:inline;
        width:10em;
        height:1em
    }
	.plot-type.selected{
        z-index:1;
		background-color:#fff;
		color:#000;
        border:2px solid #444;
        border-bottom:0;
		font-weight:bold;
        font-size:1.2em;
		cursor:default;
		box-shadow:inset 2px 2px 2px #555;
        
	}
	#plot-holder-holder{
		/* width:100%; */
        z-index:-1;
        margin-top:-1.45em;
        border-top:1px solid #777;
		padding-top:3em;
		padding-bottom:1em;
        height:29em;
		text-align:left;
	}
    #plot-holder{
        height:25em;
    }
	#loader{
		margin-top:-41em;
		text-align:center;
	}
</style>
<?php
    $this->end();
    if (!$validUser) {
    	if (isset($formMessage)) {
    		echo "<div id='form-message'>{$formMessage}</div>";
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

<?php } else { ?>

<div id='main-container'>
<div id='left-container'>
<div id="major-occasions" data-metric='major_occassions' class="select-type-of-analytic selected">Major Occasions</div>
<div id="social-quotient" data-metric='social_quotient' class="select-type-of-analytic">Social Quotient</div>
<div id="group-cardinality" data-metric='group_cardinality' class="select-type-of-analytic">Group Cardinality</div>
<div id="cart-factor" data-metric='cart_factor' class="select-type-of-analytic">Cart Factor</div>
<div id="virality-factor" data-metric='virality_factor' class="select-type-of-analytic">Virality Factor</div>
<div id="conversion-rate" data-metric='conversion_rate' class="select-type-of-analytic">Conversion Rate</div>
<div id="average-price-sold" data-metric='average_price_sold' class="select-type-of-analytic">Average Ticket Price</div>
<div id="average-campaign-time" data-metric='average_campaign_time' class="select-type-of-analytic">Average Campaign Time</div>

</div>
<div id='right-container'>
<div id='plot-type-container'>
<div id=date-range-container>
    <input type='date' title='start date' placeholder='start date' name='start-date' value='<?php echo $startDate; ?>'>
     to 
    <input type='date' title='end date' placeholder='end date' name='end-date' value='<?php echo $endDate; ?>'>
</div>
<div class="plot-type selected" data-plot-type='aggregate'>Aggregate</div>
<div class="plot-type" data-plot-type='detailed'>Detailed</div>
</div>
<div id='plot-holder-holder'>
 <div id='plot-holder'> 
 </div>
</div>
<img id='loader' src='img/ajax-loader.gif'>
<div class="clear"></div>
</div>
<?php } ?>