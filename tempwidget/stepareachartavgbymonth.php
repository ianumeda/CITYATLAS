<?php
extract($_REQUEST);
include("db_login.php");

function getaveragetemperature($YYYYMMDD){
	// $YYYYMMDD can be any LIKE query such as "200104"
	if(empty($city)) $city="NYC";
	$query="SELECT * FROM `atlas_temp_widget` WHERE `day` LIKE '$YYYYMMDD%' AND `city` = '$city'";
	$result=mysql_query($query) or die(mysql_error());
	$tempdaily=array();
	while($row=mysql_fetch_array($result)){
		$tempdaily[]=$row['temp'];
	}
	if(empty($tempdaily)) $avgtemp=0;
	else $avgtemp=array_sum($tempdaily)/count($tempdaily);
	return $avgtemp;
}

echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <title>
      Stepped Area Chart: Temperatures Averaged by Month
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
		$step=10; // years
		$earliest=1962;
		$latest=2007;
		$theyear=date('Y',$theday); 
		if($theyear>$latest) $theyear=$latest;
		elseif($theyear<$earliest) $theyear=$earliest;
		
		$theyearstocompare=array();
		for($theyear; $theyear>=$earliest; $theyear-=$step){
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
				$thegraphvar.=", ". getaveragetemperature($thequery);
			}
			$thegraphvar.="],";
		}
		$thegraphvar=substr($thegraphvar,0,-1);
		echo $thegraphvar;

echo ']);
        // Create and draw the visualization.
		var chart = new google.visualization.AreaChart(document.getElementById("visualization"));
        var options = {
			curveType: "function",
          title: "Temperature",
          vAxis: {title: "Temp ºF"},
          isStacked: true
        };
	    chart.draw(data, options);
      }
      

      google.setOnLoadCallback(drawVisualization);
    </script>
  </head>
  <body style="font-family: Arial;border: 0 none;">
    <div id="visualization" style=""></div>
	<div id="navback" style="display:inline; float:left;"><a href="http://thecityatlas.org/tempwidget/stepareachartavgbymonth.php?day='. (date('Y',$theday)-1) . date('md',$theday) .'">&larr; Previous Year</a></div>
<div id="navnext" style="display:inline; float:right;"><a href="http://thecityatlas.org/tempwidget/stepareachartavgbymonth.php?day='. (date('Y',$theday)+1) . date('md',$theday) .'">Next Year &rarr;</a></div>
  </body>
</html>';
?>
