<!DOCTYPE html>
<html>
<head>
	<title>
		Homework6 for CSCI 571 course
	</title>

	<style type="text/css">
		body{
			position: relative;
			text-align: center;
			margin: 0 auto;
		}
		h1{
			font-style: italic;
		}
		.Init{
			background-color: rgb(245,245,245);
			border: 2px solid rgb(227,227,227);
			margin: auto;
			width: 40%;
		}
		.part1{
			text-align: left;
		}
		.table1{
			text-align: center;
		}
		.part2{
			text-align: center;
			
			margin: 0px auto;
		}
		.table2{
			text-align: center;
			margin: 0px auto;
			border-color: rgb(215,215,215);
			border-style: solid;
			border-width: 2px;
			border-collapse: collapse;
		}
		th,td{
			border:2px solid rgb(215,215,215);
		}
		.left{
			background-color: rgb(243,243,243);
			font-weight: bold;
			text-align: left;
		}
		#container{
			min-width: 310px;
			height: 400px;
			margin: 0px auto;
		}
		.link{
			color: blue;
			text-decoration: none;
		}
		a{
			text-decoration: none; 
		}
		.table3{
			text-align: left;
			margin: 0px auto;
			border-color: rgb(215,215,215);
			border-style: solid;
			border-width: 2px;
			border-collapse: collapse;
		}
	</style>
</head>

<body>
	<?php 
		date_default_timezone_set("America/New_York");
	?>

	<script type="text/javascript">
		var flag = 0;
		var flag2 = 0;
		function view(what){
			var value = what.name.value;
			if(value == ""){alert('Please enter a symbol');}
			else{
				document.getElementById("txt1").value = value;
			}
			//console.log(value);
			
			
		}

		function loadJSON(url)	{
				
				if	(window.XMLHttpRequest)
				{//	code	for	IE7+,	Firefox,	Chrome,	Opera,	Safari
					xmlhttp=new	XMLHttpRequest();
				}	else	{//	code	for	IE6,	IE5
					xmlhttp=new	ActiveXObject("Microsoft.XMLHTTP");		
				}
				//try{				
					xmlhttp.open("GET",url,false);
					xmlhttp.send();
				//}
				//catch(err){
					//alert("No such files!");
					//return null;
				//}
				//try{
					jsonObj = JSON.parse(xmlhttp.responseText);
					return	jsonObj;
				//}
				//catch(err){					
					//alert("Failed JSON Parse!");
					//return null;
				//}						
		}

		function iupdate1(symbol){
			var url = "https://www.alphavantage.co/query?function=TIME_SERIES_DAILY&symbol=" + symbol + "&outputsize=full&apikey=KQS20D4CNURTYXU7";
			//console.log(url);
			jsonObj =	loadJSON(url);
			//console.log(jsonObj);
			//var title2 = jsonObj["Meta Data"]["2: Indicator"];
			main_txt = jsonObj["Time Series (Daily)"];
			var date2 = Object.keys(main_txt);
			date2.reverse();
			date2 = date2.slice(date2.length-130,date2.length);
			var value21 = [];
			var value22 = [];
			for(var i = 0; i < date2.length; i++){
				num = parseFloat(main_txt[date2[i]]["4. close"]);
				value21.push(num);
			}
			for(var i = 0; i < date2.length; i++){
				num = parseFloat(main_txt[date2[i]]["5. volume"])/1000000;
				value22.push(num);
			}
			//console.log(value21);
			//console.log(date2);
			var min1 = Math.floor(Math.min.apply(null, value21));
			var min1d = min1 - 10 - min1%10;
			var max1 = Math.ceil(Math.max.apply(null, value21));
			var max1d = max1 + 15 - max1%10;
			var max2 = Math.ceil(Math.max.apply(null,value22));
			var max2d = max2 + 100 - max2%10; 
			var d = date2[date2.length-1].split("-");
			//var d_s = date2[0].split("-");

			var chart = new Highcharts.chart('container', {
    			chart: {
        			zoomType: 'xy'
    			},
    			title: {
        			text: 'Stock Price(' + d[0] + '/' + d[1] + '/' + d[2] + ')',
        			style:{
        				fontWeight: 'bold'
        			}
    			},
    			subtitle: {
        			text: '<a href="http://www.alphavantage.co">Source: Alpha Vantage </a>',
        			style:{
        				color: 'blue'
        			}
    			},
    			xAxis: [{
        			tickInterval: 5,
        			categories: date2,
        	//type: 'datetime',
        	//dateTimeLabelFormats:{
        	//	day: '%m'+'/'+'%d',
        	//},
        			labels: {
        				formatter: function(){
        					var arr = this.value.split('-');
        					return arr[1] + '/' + arr[2];
        				}
        			},
        	//tickInterval: 7*24*3600*1000
    			}],
    			yAxis: [{ // Primary yAxis
    				min: min1d,
    				max: max1d,
    				tickInterval: 5,
        			labels: {
            			format: '{value}',
            			style: {
                	//color: Highcharts.getOptions().colors[1]
            			}
        			},
        			title: {
            			text: 'Stock Price',
            			style: {
                	//color: Highcharts.getOptions().colors[1]
            			}
        			}
    			}, { // Secondary yAxis
    				min: 0,
    				max: max2d,
    				//tickInterval: 50,
        			title: {
            			text: 'Volume',
            			style: {
                	//color: Highcharts.getOptions().colors[0]
            			}
        			},
        			labels: {
            			format: '{value}M',
            			style: {
                	//color: Highcharts.getOptions().colors[0]
            			}
        			},
        			opposite: true
    			}],
    			tooltip: {
        	//shared: true
        	//formatter: function(){
        	//	return this.x + '<br/>' + this.series.name + ': ' + this.y;
        	//}
    			},
    			legend: {
        			layout: 'vertical',
        			align: 'right',
        			//x: 0,
        			verticalAlign: 'middle',
        			//y: 100
        	//floating: true,
        	//backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'
    			},
    			plotOptions: {
    				series:{
    			//pointStart: Date.UTC(d_s[0],d_s[1],d_s[2]),
    			//pointEnd: Date.UTC(d[0],d[1],d[2]),
    			//pointInterval: 24*3600*1000
    				}
    			},
    			series: [{
        			name: symbol,
        			type: 'area',
        			yAxis: 0,
        			data: value21,
        			color: 'rgb(241,146,143)'

    			}, {
        			name: symbol + 'Volume',
        			type: 'column',
        			yAxis: 1,
        			data: value22,
        			color: 'rgb(255,255,255)'
    			}]
			});
		}

		function iupdate(symbol,indic){
			var url = "https://www.alphavantage.co/query?function=" + indic + "&symbol=" + symbol + "&interval=daily&time_period=10&series_type=close&apikey=KQS20D4CNURTYXU7";
			//console.log(url);
			jsonObj =	loadJSON(url);
			//console.log(jsonObj);
			var title2 = jsonObj["Meta Data"]["2: Indicator"];
			main_txt = jsonObj["Technical Analysis: "+indic];
			var date2 = Object.keys(main_txt);
			date2.reverse();
			date2 = date2.slice(date2.length-130,date2.length);
			var value2 = [];
			for(var i = 0; i < date2.length; i++){
				num = parseFloat(main_txt[date2[i]][indic]);
				value2.push(num);
			}
			//console.log(value2);
			//console.log(date2);

			var chart = new Highcharts.chart('container', {

			chart:{
				type: 'line'
			},
    		title: {
        		text: title2
    		},

    		subtitle: {
        		text: '<a href="http://www.alphavantage.co">Source: Alpha Vantage </a>',
        		style:{
        			color: 'blue'
        		}
    		},

    		xAxis: [{
        		tickInterval: 5,
        		categories: date2,
        	
        		labels: {
        			formatter: function(){
        				var arr = this.value.split('-');
        				return arr[1] + '/' + arr[2];
        			}
        		},
        	
    		}],

    		yAxis: {
    			//min: 0,
    			//max: 300,
    			//tickInterval: 50,
        		labels: {
            		format: '{value}',
            	
        	},
        		title: {
            		text: indic
        		}
    		},
    		legend: {
        		layout: 'vertical',
        		align: 'right',
        		verticalAlign: 'middle'
    		},


    		series: [{
        		name: symbol,
        		data: value2
        		//color: 'red'
    		}] 
    
		});
		}

		function iupdate2(symbol,indic){
			var url = "https://www.alphavantage.co/query?function=" + indic + "&symbol=" + symbol + "&interval=daily&apikey=KQS20D4CNURTYXU7";
			//console.log(url);
			jsonObj =	loadJSON(url);
			//console.log(jsonObj);
			var title2 = jsonObj["Meta Data"]["2: Indicator"];
			main_txt = jsonObj["Technical Analysis: "+indic];
			var date2 = Object.keys(main_txt);
			date2.reverse();
			date2 = date2.slice(date2.length-130,date2.length);
			var value21 = [];
			var value22 = [];
			for(var i = 0; i < date2.length; i++){
				num = parseFloat(main_txt[date2[i]]["SlowK"]);
				value21.push(num);
			}
			for(var i = 0; i < date2.length; i++){
				num = parseFloat(main_txt[date2[i]]["SlowD"]);
				value22.push(num);
			}
			//console.log(value21);
			//console.log(date2);

			var chart = new Highcharts.chart('container', {

			chart:{
				type: 'line'
			},
    		title: {
        		text: title2
    		},

    		subtitle: {
        		text: '<a href="http://www.alphavantage.co">Source: Alpha Vantage </a>',
        		style:{
        			color: 'blue'
        		}
    		},

    		xAxis: [{
        		tickInterval: 5,
        		categories: date2,
        	
        		labels: {
        			formatter: function(){
        				var arr = this.value.split('-');
        				return arr[1] + '/' + arr[2];
        			}
        		},
        	
    		}],

    		yAxis: {
    			//min: 0,
    			//max: 300,
    			//tickInterval: 50,
        		labels: {
            		format: '{value}',
            	
        	},
        		title: {
            		text: indic
        		}
    		},
    		legend: {
        		layout: 'vertical',
        		align: 'right',
        		verticalAlign: 'middle'
    		},


    		series: [{
        		name: symbol + "SlowK",
        		data: value21
        		//color: 'red'
    		}, {
    			name: symbol + "SlowD",
    			data: value22
    		}] 
    
		});
		}


		function iupdate3(symbol,indic){
			var url = "https://www.alphavantage.co/query?function=" + indic + "&symbol=" + symbol + "&interval=daily&time_period=10&series_type=close&apikey=KQS20D4CNURTYXU7";
			//console.log(url);
			jsonObj =	loadJSON(url);
			//console.log(jsonObj);
			var title2 = jsonObj["Meta Data"]["2: Indicator"];
			main_txt = jsonObj["Technical Analysis: "+indic];
			var date2 = Object.keys(main_txt);
			date2.reverse();
			date2 = date2.slice(date2.length-130,date2.length);
			var value21 = [];
			var value22 = [];
			var value23 = [];
			for(var i = 0; i < date2.length; i++){
				num = parseFloat(main_txt[date2[i]]["Real Middle Band"]);
				value21.push(num);
			}
			for(var i = 0; i < date2.length; i++){
				num = parseFloat(main_txt[date2[i]]["Real Upper Band"]);
				value22.push(num);
			}
			for(var i = 0; i < date2.length; i++){
				num = parseFloat(main_txt[date2[i]]["Real Lower Band"]);
				value23.push(num);
			}
			//console.log(value21);
			//console.log(date2);

			var chart = new Highcharts.chart('container', {

			chart:{
				type: 'line'
			},
    		title: {
        		text: title2
    		},

    		subtitle: {
        		text: '<a href="http://www.alphavantage.co">Source: Alpha Vantage </a>',
        		style:{
        			color: 'blue'
        		}
    		},

    		xAxis: [{
        		tickInterval: 5,
        		categories: date2,
        	
        		labels: {
        			formatter: function(){
        				var arr = this.value.split('-');
        				return arr[1] + '/' + arr[2];
        			}
        		},
        	
    		}],

    		yAxis: {
    			//min: 0,
    			//max: 300,
    			//tickInterval: 50,
        		labels: {
            		format: '{value}',
            	
        	},
        		title: {
            		text: indic
        		}
    		},
    		legend: {
        		layout: 'vertical',
        		align: 'right',
        		verticalAlign: 'middle'
    		},


    		series: [{
        		name: symbol + "Real Middle Band",
        		data: value21
        		//color: 'red'
    		}, {
    			name: symbol + "Real Upper Band",
    			data: value22
    		}, {
    			name: symbol + "Real Lower Band",
    			data: value23
    		}] 
    
		});
		}


		function iupdate32(symbol,indic){
			var url = "https://www.alphavantage.co/query?function=" + indic + "&symbol=" + symbol + "&interval=daily&series_type=close&apikey=KQS20D4CNURTYXU7";
			//console.log(url);
			jsonObj =	loadJSON(url);
			//console.log(jsonObj);
			var title2 = jsonObj["Meta Data"]["2: Indicator"];
			main_txt = jsonObj["Technical Analysis: "+indic];
			var date2 = Object.keys(main_txt);
			date2.reverse();
			date2 = date2.slice(date2.length-130,date2.length);
			var value21 = [];
			var value22 = [];
			var value23 = [];
			for(var i = 0; i < date2.length; i++){
				num = parseFloat(main_txt[date2[i]]["MACD_Signal"]);
				value21.push(num);
			}
			for(var i = 0; i < date2.length; i++){
				num = parseFloat(main_txt[date2[i]]["MACD_Hist"]);
				value22.push(num);
			}
			for(var i = 0; i < date2.length; i++){
				num = parseFloat(main_txt[date2[i]]["MACD"]);
				value23.push(num);
			}
			//console.log(value21);
			//console.log(date2);

			var chart = new Highcharts.chart('container', {

			chart:{
				type: 'line'
			},
    		title: {
        		text: title2
    		},

    		subtitle: {
        		text: '<a href="http://www.alphavantage.co">Source: Alpha Vantage </a>',
        		style:{
        			color: 'blue'
        		}
    		},

    		xAxis: [{
        		tickInterval: 5,
        		categories: date2,
        	
        		labels: {
        			formatter: function(){
        				var arr = this.value.split('-');
        				return arr[1] + '/' + arr[2];
        			}
        		},
        	
    		}],

    		yAxis: {
    			//min: 0,
    			//max: 300,
    			//tickInterval: 50,
        		labels: {
            		format: '{value}',
            	
        	},
        		title: {
            		text: indic
        		}
    		},
    		legend: {
        		layout: 'vertical',
        		align: 'right',
        		verticalAlign: 'middle'
    		},


    		series: [{
        		name: symbol + "MACD_Signal",
        		data: value21
        		//color: 'red'
    		}, {
    			name: symbol + "MACD_Hist",
    			data: value22
    		}, {
    			name: symbol + "MACD",
    			data: value23
    		}] 
    
		});
		}
	</script>

	<script type="text/javascript">
		function reset(){
			document.getElementById("txt1").value = "";
		}
	</script>


	<div class="Init">
		<h1>Stock Search</h1>
		<hr style="width: 80%;border:none;background-color: rgb(225,225,225);height: 1px;"/>
		<div class="part1">
			<div class="table1">
				<form method = "post" action="stock.php">
					<?php echo "Enter Stock Ticker Symbol:*"; ?><input type = "text" id = "txt1" name = "name" value = ""><br>

					<input type = "submit" value = "Search" onclick="view(this.form)">  <input type = "submit" value = "Clear" onclick="reset()">
				</form>
			</div>
		
			<?php echo "<i>*-Mandatory fields.</i>"; ?>
		</div>		
	</div>


	<?php
	$check = 0;
	if(isset($_POST["name"])){

		$symbol = $_POST["name"];
		if($symbol=="") {
			//echo "<script type='text/javascript'>alert('Please enter a symbol');</script>";
		}
		else{
			echo "<script> document.getElementById('txt1').value = \"$symbol\"; </script>";

			$URL = "https://www.alphavantage.co/query?";
			$URL = $URL."function=TIME_SERIES_DAILY&symbol=";
			$URL = $URL.$symbol;
			$URL = $URL."&outputsize=full";
			$URL = $URL."&apikey=KQS20D4CNURTYXU7";
			
			//$ch = curl_init();
			$content = file_get_contents($URL);
			//curl_setopt($ch, CURLOPT_URL, $URL);
			//curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			//curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

			//$content = curl_exec($ch);
			//curl_close($ch);
			$result = json_decode($content,true);
			if(isset($result["Meta Data"])){
				generateHTML($result,$symbol);
				$check = 1;
				//var_dump($result["Time Series (Daily)"]["2017-10-18"]);
			}
			else{
				die("Error: No record has been found, please enter a valid symbol");
			}
		}		
	}
	?>

	<?php
		function generateHTML($result,$symbol){
			
			$date = array_keys($result["Time Series (Daily)"]);
			
			$HTML = "<br/>"."<div class = 'part2'>";
			$HTML = $HTML. "<table class='table2'>";
			$HTML = $HTML. "<tr><td class='left'>Stock Ticker Symbol</td><td>$symbol</td></tr>";
			$HTML = $HTML. "<tr><td class='left'>Close</td><td>".$result["Time Series (Daily)"][$date[0]]["4. close"]."</td></tr>";
			$HTML = $HTML. "<tr><td class='left'>Open</td><td>".$result["Time Series (Daily)"][$date[0]]["1. open"]."</td></tr>";
			$HTML = $HTML. "<tr><td class='left'>Previous Close</td><td>".$result["Time Series (Daily)"][$date[1]]["4. close"]."</td></tr>";
			$change = floatval($result["Time Series (Daily)"][$date[0]]["4. close"]) - floatval($result["Time Series (Daily)"][$date[1]]["4. close"]);
			$change = round($change,2);
			if($change>0){
				$change = $change."<img src = 'http://cs-server.usc.edu:45678/hw/hw6/images/Green_Arrow_Up.png' width = '20px' height = '18px'>";
			}
			if($change<0){
				$change = $change."<img src = 'http://cs-server.usc.edu:45678/hw/hw6/images/Red_Arrow_Down.png' width = '20px' height = '18px'>";
			}
			$percent = $change/floatval($result["Time Series (Daily)"][$date[1]]["4. close"]);
			$percent = round($percent*100,2)."%";
			if($percent>0){
				$percent = $percent."<img src = 'http://cs-server.usc.edu:45678/hw/hw6/images/Green_Arrow_Up.png' width = '20px' height = '18px'>";
			}
			if($percent<0){
				$percent = $percent."<img src = 'http://cs-server.usc.edu:45678/hw/hw6/images/Red_Arrow_Down.png' width = '20px' height = '18px'>";
			}
			$range = $result["Time Series (Daily)"][$date[0]]["3. low"]."-".$result["Time Series (Daily)"][$date[0]]["2. high"];
			$HTML = $HTML. "<tr><td class='left'>Change</td><td>".$change."</td></tr>";
			$HTML = $HTML. "<tr><td class='left'>Change Percent</td><td>".$percent."</td></tr>";
			$HTML = $HTML. "<tr><td class='left'>Day's Range</td><td>".$range."</td></tr>";
			$HTML = $HTML. "<tr><td class='left'>Volume</td><td>".$result["Time Series (Daily)"][$date[0]]["5. volume"]."</td></tr>";
			$HTML = $HTML. "<tr><td class='left'>Timestamp</td><td>".$date[0]."</td></tr>";

			$indic1 = "SMA";
			$indic2 = "EMA";
			$indic3 = "STOCH";
			$indic4 = "RSI";
			$indic5 = "ADX";
			$indic6 = "CCI";
			$indic7 = "BBANDS";
			$indic8 = "MACD";
			$HTML = $HTML. "<tr><td class='left'>Indicators</td><td>"
			."<a class='link' href='javascript:void(0);' onclick='iupdate1(\"$symbol\")'>Price</a>"
			."<a class='link' href='javascript:void(0);' onclick='iupdate(\"$symbol\",\"$indic1\")'>  SMA</a>"
			."<a class='link' href='javascript:void(0);' onclick='iupdate(\"$symbol\",\"$indic2\")'>  EMA</a>"
			."<a class='link' href='javascript:void(0);' onclick='iupdate2(\"$symbol\",\"$indic3\")'>  STOCH</a>"
			."<a class='link' href='javascript:void(0);' onclick='iupdate(\"$symbol\",\"$indic4\")'>  RSI</a>"
			."<a class='link' href='javascript:void(0);' onclick='iupdate(\"$symbol\",\"$indic5\")'>  ADX</a>"
			."<a class='link' href='javascript:void(0);' onclick='iupdate(\"$symbol\",\"$indic6\")'>  CCI</a>"
			."<a class='link' href='javascript:void(0);' onclick='iupdate3(\"$symbol\",\"$indic7\")'>  BBANDS</a>"
			."<a class='link' href='javascript:void(0);' onclick='iupdate32(\"$symbol\",\"$indic8\")'>  MACD</a>"."</td></tr>";

			$HTML = $HTML. "</table>"."</div>"."<br/>";
			echo $HTML;

			echo "<script> var data_P = [];</script>";
			echo "<script> var data_V = [];</script>";
			echo "<script> var timedt = [];</script>";
			foreach ($date as $value) {			
				echo "<script> data_P.push({$result['Time Series (Daily)'][$value]['4. close']});</script>";
			}
			foreach ($date as $value) {
				echo "<script> data_V.push({$result['Time Series (Daily)'][$value]['5. volume']}/1000000);</script>";
			}
			foreach ($date as $value) {
				echo "<script> timedt.push(\"$value\");</script>";
			}
			$date_st = end($date);
			echo "<script> var date = \"$date[0]\"; var date_s = \"$date_st\";</script>";
			echo "<script> flag = 1; data_P.reverse(); data_V.reverse(); timedt.reverse();</script>";
			echo "<script> var symb = \"$symbol\";</script>";			
		}
	?>


	<script src="http://code.highcharts.com/highcharts.js">
	</script>
	<script src="http://code.highcharts.com/modules/exporting.js">		
	</script>

	<div id = "container">
		
	</div>
	<script type="text/javascript">
	if(flag){
		var d = date.split("-");
		//var d_s = date_s.split("-");
		data_P = data_P.slice(data_P.length-130,data_P.length);
		data_V = data_V.slice(data_V.length-130,data_V.length);
		//console.log(data_P);
		
		var min1 = Math.floor(Math.min.apply(null, data_P));
		var min1d = min1 - 10 - min1%10;
		var max1 = Math.ceil(Math.max.apply(null, data_P));
		var max1d = max1 + 15 - max1%10;
		var max2 = Math.ceil(Math.max.apply(null,data_V));
		var max2d = max2 + 100 - max2%10; 

		//console.log(max1d);
		//console.log(min1d);

		Highcharts.chart('container', {
    	chart: {
        	zoomType: 'xy'
    	},
    	title: {
        	text: 'Stock Price(' + d[0] + '/' + d[1] + '/' + d[2] + ')',
        	style:{
        		fontWeight: 'bold'
        	}
    	},
    	subtitle: {
        	text: '<a href="http://www.alphavantage.co">Source: Alpha Vantage </a>',
        	style:{
        		color: 'blue'
        	}
    	},
    	xAxis: [{
        	//crosshair: true
        	tickInterval: 5,
        	categories: timedt.slice(timedt.length-130,timedt.length),
        	//type: 'datetime',
        	//dateTimeLabelFormats:{
        	//	day: '%m'+'/'+'%d',
        	//},
        	labels: {
        		formatter: function(){
        			var arr = this.value.split('-');
        			return arr[1] + '/' + arr[2];
        		}
        	},
        	//tickInterval: 7*24*3600*1000
    	}],
    	yAxis: [{ // Primary yAxis
    		min: min1d,
    		max: max1d,
    		tickInterval: 5,
        	labels: {
            	format: '{value}',
            	style: {
                	//color: Highcharts.getOptions().colors[1]
            	}
        	},
        	title: {
            	text: 'Stock Price',
            	style: {
                	//color: Highcharts.getOptions().colors[1]
            	}
        	}
    	}, { // Secondary yAxis
    		min: 0,
    		max: max2d,
    		//tickInterval: 50,
        	title: {
            	text: 'Volume',
            	style: {
                	//color: Highcharts.getOptions().colors[0]
            	}
        	},
        	labels: {
            	format: '{value}M',
            	style: {
                	//color: Highcharts.getOptions().colors[0]
            	}
        	},
        	opposite: true
    	}],
    	tooltip: {
        	//shared: true
        	//formatter: function(){
        	//	return this.x + '<br/>' + this.series.name + ': ' + this.y;
        	//}
    	},
    	legend: {
        	layout: 'vertical',
        	align: 'right',
        	x: 0,
        	verticalAlign: 'top',
        	y: 100
        	//floating: true,
        	//backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'
    	},
    	plotOptions: {
    		series:{
    			//pointStart: Date.UTC(d_s[0],d_s[1],d_s[2]),
    			//pointEnd: Date.UTC(d[0],d[1],d[2]),
    			//pointInterval: 24*3600*1000
    		}
    	},
    	series: [{
        	name: symb,
        	type: 'area',
        	yAxis: 0,
        	data: data_P,
        	color: 'rgb(241,146,143)'

    	}, {
        	name: symb + 'Volume',
        	type: 'column',
        	yAxis: 1,
        	data: data_V,
        	color: 'rgb(255,255,255)'
    	}]
	});
	}
	</script>


	<script type="text/javascript">
		function endfunc(dataNews){
			if(document.getElementById("endbtn").value=='0'){
				//console.log(dataNews);
				document.getElementById("endbtn").value = "1";
				document.getElementById("endtxt").innerHTML = "click to hide stock news";
				document.getElementById("endbtn").innerHTML = "<img src='http://cs-server.usc.edu:45678/hw/hw6/images/Gray_Arrow_Up.png' width = '35px' height = '25px'>";
				var htmnews = "<table class='table3' border='2'>";
				item = dataNews.channel.item;//an array of item
				var count = 0;
				for(var i=0; i < item.length; i++){
					NodeL = item[i];
					if(NodeL["link"].split("/")[3]=="article"){
						strt = "   Publicated Time:"+NodeL["pubDate"].substr(0,NodeL["pubDate"].length-6);
						htmnews += "<tr><td class='left2'><a href=" + NodeL["link"] + ">" + NodeL["title"] + "</a>" + strt +"</td></tr>";
						count++;
						//console.log(NodeL["link"]);
					}
					if(count==5){
						break;
					}
				}				
				
				htmnews += "</table></br>";
				document.getElementById('part32').innerHTML = htmnews;
			}
			else{
				document.getElementById("endbtn").value = "0";
				document.getElementById("endtxt").innerHTML = "click to show stock news";
				document.getElementById("endbtn").innerHTML = "<img src='http://cs-server.usc.edu:45678/hw/hw6/images/Gray_Arrow_Down.png' width = '35px' height = '25px'>";
				document.getElementById("part32").innerHTML = "";
			}
		}
		
	</script>

	<div id="part3">
		<?php
			$xmltxt = null;
			if($check){
				ini_set('allow_url_fopen', 'on');
				$xurl = "https://seekingalpha.com/api/sa/combined/".$symbol.".xml";
				//$ch = curl_init();
				//curl_setopt($ch, CURLOPT_URL, $xurl);
				//curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				//curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
				//$xmlresponse = curl_exec($ch);
				//curl_close($ch);

				$xmltxt = simplexml_load_file($xurl);
				echo "<script> flag2=$check;</script>";
			//$result = json_decode($content,true);
			}
		?>
		<div id="part31">
			
		</div>
		<br/>
		<div id="part32">
			
		</div>
		<script type="text/javascript">
			
			
			if(flag){
				//console.log(flag2);
				if(flag2){
					dataNews = <?php echo json_encode($xmltxt) ?>;
					document.getElementById("part31").innerHTML = "<p id='endtxt'>click to show stock news</p>"+"<button id='endbtn' type='button' onclick = 'endfunc(dataNews)' value='0'> <img src='http://cs-server.usc.edu:45678/hw/hw6/images/Gray_Arrow_Down.png' width = '35px' height = '25px'></button>";
				}
			}
			
		</script>			
		
	</div>
</body>
</html>