<?php
extract($_REQUEST);
include("db_login.php");

function getaveragetemperature($YYYYMMDD){
	// $YYYYMMDD can be any LIKE query such as "200104"
	if(empty($city)) $city="NYC";
	$query="SELECT * FROM `atlas_temp_widget2` WHERE `date` LIKE '$YYYYMMDD%' AND `city` = '$city'";
	$result=mysql_query($query) or die(mysql_error());
	$tempdaily=array();
	while($row=mysql_fetch_array($result)){
		if($row['tmax']<2000) $tempdaily[]=$row['tmax'];
	}
	if(empty($tempdaily)) $avgtemp=-2730;
	else $avgtemp=array_sum($tempdaily)/count($tempdaily);
	return $avgtemp;
}
function getF($C){
	// expects celsiusX10, example 100 = 10 degree celsius 
	// because this is the format tmax and tmin are given from NOAA
	if($C==-2730) return 0;
	else return round((212-32)/100 * $C/10 + 32, 1);
}
function getC($C){
	return $C/10;
}
echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <title>
      A Comparison Between Years of Temperatures Averaged by Month
    </title>
    <script type="text/javascript" src="http://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages: ["corechart"]});
    </script>
    <script type="text/javascript">
      function drawVisualization() {
        // Create and populate the data table.
        var data = google.visualization.arrayToDataTable([';

		if(is_numeric($day)) {
			$theday=strtotime($day);
		} else {
			$theday=strtotime('now');
		}
		// connect to db
		if(empty($step) || $step<1) $step=25; // years
		if(empty($maxsteps) || $maxsteps<0) $maxsteps=3;
		$earliest=1950;
		$latest=2012;
		$theyear=date('Y',$theday); 
		if($theyear>$latest) $theyear=$latest;
		elseif($theyear<$earliest) $theyear=$earliest;
		
		$theyearstocompare=array();
		for($theyear, $stepper=0; $theyear>=$earliest && $stepper<$maxsteps; $theyear-=$step, $stepper++){
			$theyearstocompare[]=$theyear;
		}
		
		// first make the chart key
		$thegraphvar="['x'";
		foreach($theyearstocompare as $key=>$value) {
			$thegraphvar.=",'$value'";
		}
		$thegraphvar.="],";

		for($monthcounter=0; $monthcounter<12; $monthcounter++){
			$themonth=substr("0".($monthcounter+1),-2);
			$thegraphvar.="['". date('M', strtotime('2000'.$themonth.'01')) ."'";
			foreach($theyearstocompare as $key=>$value){
				$thequery=$value ."". $themonth;
				$thegraphvar.=", ". getF(getaveragetemperature($thequery));
			}
			$thegraphvar.="],";
		}
		$thegraphvar=substr($thegraphvar,0,-1);
		echo $thegraphvar;

echo ']);
        // Create and draw the visualization.
		var chart = new google.visualization.LineChart(document.getElementById("visualization"));
        var options = {
			focusTarget: "category",
          title: "Average Monthly Temperatures Compared Between Years",
          vAxis: {viewWindow:{max:100, min:30}, maxValue:120, minValue:25, title: "Temp ºF"},
          isStacked: false
        };
	    chart.draw(data, options);
      }
      

      google.setOnLoadCallback(drawVisualization);
    </script>
  </head>
  <body style="font-family: Arial;border: 0 none;">
    <div id="visualization" style="height:900px;"></div>
	<div id="navback" style="display:inline; float:left;"><a href="./monthlyaverage.php?day='. (date('Y',$theday)-1) . date('md',$theday) .'&step='. $step .'&maxsteps='.$maxsteps.'">&larr; Previous Year</a></div>
<div id="navnext" style="display:inline; float:right;"><a href="./monthlyaverage.php?day='. (date('Y',$theday)+1) . date('md',$theday) .'&step='. $step .'&maxsteps='.$maxsteps.'">Next Year &rarr;</a></div>
  </body>
</html>';
?>
