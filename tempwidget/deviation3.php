<?php
extract($_REQUEST);
include("db_login.php");

$city=getcity($city);
function getcity($city){
	$cities=array('newyork','sfbay','chicago','paris','barcelona','london','kolkata');
	if(in_array($city,$cities)) return $city;
	else return 'newyork';
}
$timezone=getTimezone($city);
date_default_timezone_set($timezone);
function getcityindbtableformat(){
	$sitename=get_bloginfo('name');
	$sitename=str_replace(' ','',$sitename);
	return strtolower($sitename);
}
function getTimezone($city){
	if($city=='newyork') return 'America/New_York';
	elseif($city=='barcelona') return 'Europe/Madrid';
	elseif($city=='sfbay') return 'America/Los_Angeles';
	elseif($city=='paris') return 'Europe/Paris';
	elseif($city=='london') return 'Europe/London';
	elseif($city=='kolkata') return 'Asia/Calcutta';
	else return 'America/New_York';
}
function getF($C){
	// expects celsius, we're converting to straight up C before converting to F... 
	if($C==-2730) return 0;
	else return round((212-32)/100 * $C + 32, 1);
}
function getC($F){
	return $F; // actually the data is already in C so no conversion needed. But we still need this function to make it work. Tee hee!
}
function getCfromdata($C){
	return $C/10;
}
function getFfromCabsolute($C){
	return $C*9/5;
}
function getWundergroundCityQuery($city){
	if($city=='newyork') return '40.8,-74.0';
	elseif($city=='barcelona') return 'Spain/Barcelona';
	elseif($city=='sfbay') return 'CA/San_Francisco';
	elseif($city=='paris') return 'France/Paris';
	elseif($city=='london') return 'England/London';
	elseif($city=='kolkata') return 'India/Kolkata';
}
function getnewforecast($city, $yearmonthday){
	// send in yearmonthday to get historical data
	if(!empty($yearmonthday) && date('Ymd')!=$yearmonthday) $wundergroundquery='http://api.wunderground.com/api/294bfc964384148d/history_$yearmonthday/q/'. getWundergroundCityQuery($city) .'.json';
	else $wundergroundquery='http://api.wunderground.com/api/294bfc964384148d/forecast/q/'. getWundergroundCityQuery($city) .'.json';
	$aforecast=array();
	$json_string = file_get_contents($wundergroundquery);
	$parsed_json = json_decode($json_string);
	$aforecast[] = $parsed_json->forecast->simpleforecast->forecastday[0]->high->celsius;
	$aforecast[] = $parsed_json->forecast->simpleforecast->forecastday[0]->low->celsius;
	return $aforecast;
}
function getthedata($city, $yearmonthday){
	if(empty($yearmonthday)) $yearmonthday=date('Ymd');
	$aforecast=array();
	$histavg=array();
	$db_table='atlas_temp_widget_'.$city;
	$query="SELECT * FROM $db_table WHERE `date` LIKE '$yearmonthday' LIMIT 1";
	$result=mysql_query($query) or die(mysql_error());
	if($row=mysql_fetch_array($result)){
		$aforecast[]=($row['tmax']);
		$aforecast[]=($row['tmin']);
		$histavg=gethistoricalaverage($city, date('md', strtotime($yearmonthday)));
		// $histavg[]=($row['avgtmax']);
		// $histavg[]=($row['avgtmin']);
		// $query="INSERT INTO `atlas_temp_widget_temp` (`city`, `date`, `tmax`, `tmin`, `updatecounter`, `avgtmax`, `avgtmin`) VALUES ('$city', '". $yearmonthday ."0000', '". (10*$aforecast[0]) ."', '". (10*$aforecast[1]) ."', '0', '". $histavg[0] ."', '". $histavg[1] ."');";
		//using noaa temperature syntax for consistency
		// $result=mysql_query($query) or die(mysql_error());
		$stddev=getstandarddeviation($city,$histavg, date('md',strtotime($yearmonthday)));
	}
	return array('forecast'=>$aforecast, 'historicalaverage'=>$histavg, 'standarddeviation'=>$stddev);
}
function getstandarddeviation($city, $historicalaverage, $monthday){
	if(empty($monthday)) $monthday=date('md',strtotime('now'));
	$db_table='atlas_temp_widget_'.$city;
	$query="SELECT * FROM `$db_table` WHERE `date` LIKE '%$monthday'";
	$result=mysql_query($query) or die(mysql_error());
	$maxdevdata=array();
	$mindevdata=array();
	while($row=mysql_fetch_array($result)){
		if(abs($row['tmax'])<2000) $maxdevdata[]=pow($row['tmax']-$historicalaverage[0],2);
		if(abs($row['tmin'])<2000) $mindevdata[]=pow($row['tmin']-$historicalaverage[1],2);
	}
	$maxstddev=sqrt(array_sum($maxdevdata)/count($maxdevdata));
	$minstddev=sqrt(array_sum($mindevdata)/count($mindevdata));
	return array($maxstddev,$minstddev);
}
function gethistoricalaverage($city, $monthday){
	if(empty($monthday)) $monthday=date('md',strtotime('now'));
	$db_table='atlas_temp_widget_'.$city;
	$query="SELECT * FROM `$db_table` WHERE `date` LIKE '%$monthday'";
	$result=mysql_query($query) or die(mysql_error());
	$historicaltempsfortoday=array();
	while($row=mysql_fetch_array($result)){
		if(abs($row['tmax'])<2000) $historicaltmaxfortoday[]=($row['tmax']);
		if(abs($row['tmin'])<2000) $historicaltminfortoday[]=($row['tmin']);
	}
	$historicalaveragetmax=(array_sum($historicaltmaxfortoday)/count($historicaltmaxfortoday));
	$historicalaveragetmin=(array_sum($historicaltminfortoday)/count($historicaltminfortoday));
	return array($historicalaveragetmax,$historicalaveragetmin);
}
function getEarliestDataYear($city){
	$db_table='atlas_temp_widget_'.$city;
	$query = "SELECT MIN(`date`) AS `min_date` FROM `$db_table`"; 
	$result = mysql_query($query) or die(mysql_error());
	
	if($row = mysql_fetch_array($result)){
		return date('Y',strtotime($row['min_date']));
	}
	else return "####";
}
function getTempFormatForCity($city,$temp){
	$Ccities=array('barcelona','paris','london','kolkata');
	$Fcities=array('newyork','sfbay','losangeles');
	if(in_array($city,$Ccities)) return $temp."&deg;C";
	else return getF($temp)."&deg;F";
}

echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <title>
      This year\'s daily temps as compared to their historical deviation from average 
    </title>
    <script type="text/javascript" src="http://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages: ["corechart"]});
    </script>
    <script type="text/javascript">
      function drawVisualization() {
        // Create and populate the data table.
        var data = google.visualization.arrayToDataTable([';

		if(empty($data)) $data='tmax';
		if($data=='tmax') $otherdata='tmin';
		else $otherdata='tmax';
		if(is_numeric($deviationx)) {} else $deviationx=1;
		if(is_numeric($year) && $year<=date('Y') && $year>=getEarliestDataYear($city) ) {} 
		else $year=date('Y', time());
		// if(empty($maxsteps) || $maxsteps<0) $maxsteps=10;
		if($year<date('Y')) $yearday=365;
		else $yearday=date('z', time("now"));
		$daysthisyear=array();
		for($i=0; $i<$yearday; $i++){
			$daysthisyear[]=date('Ymd',strtotime($year.'0000')+$i*86400);
		}
		
		// first make the chart key
		$thegraphvar="['x','HistoricalAvg+StdDev','HistoricalAvg-StdDev','Days Temperature'],";
		// $thedata=array();

		for($daycounter=0; $daycounter<$yearday; $daycounter++){
			// collect all days' data into one huge array because the google graph var is built like that.
			$thedata=getthedata($city, $daysthisyear[$daycounter]);
			$thegraphvar.="[ ". $daycounter ." , ". round(($thedata['historicalaverage'][0]+$deviationx*$thedata['standarddeviation'][0])/10,1) ." , ". round(($thedata['historicalaverage'][0]-$deviationx*$thedata['standarddeviation'][0])/10,1) ." , ". ($thedata['forecast'][0])/10 ."],";
		}
		$thegraphvar=substr($thegraphvar,0,-1);
		echo $thegraphvar;

echo ']);
        // Create and draw the visualization.
		var chart = new google.visualization.LineChart(document.getElementById("visualization"));
        var options = {
			curveType: "function",
			focusTarget: "category",
          title: "Historical Temperature Averages and Deviation for '. strtoupper($city) .' for '. $year .' ('. strtoupper($data) .')",
          vAxis: { title: "Temperature ºC"},
          isStacked: false
        };
	    chart.draw(data, options);
      }
      

      google.setOnLoadCallback(drawVisualization);
    </script>
  </head>
  <body style="font-family: Arial;border: 0 none;">
    <div id="visualization" style="height:900px;"></div>
	<div id="navback" style="display:inline; float:left;"><a href="./deviation3.php?city='.$city.'&year='. ($year-1) .'&data='. $data .'&deviationx='.$deviationx.'">&larr; '. ($year-1) .'</a></div>
<div id="navnext" style="display:inline; float:right;"><a href="./deviation3.php?city='.$city.'&year='. ($year+1) .'&data='. $data .'&deviationx='.$deviationx.'">'. ($year+1) .' &rarr;</a></div>
	<div id="navdata" style="display:inline; position:absolute; left:50%;"><a href="./deviation3.php?city='.$city.'&year='. $year .'&data='. $otherdata .'&deviationx='.$deviationx.'">'. strtoupper($otherdata) .'</a></div>
  </body>
</html>';
?>