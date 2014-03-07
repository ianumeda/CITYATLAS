<?php
extract($_REQUEST);
include("db_login.php");

function getaveragetemperature($YYYYMMDD, $columntoget, $citytoget){
	// $YYYYMMDD can be any LIKE query such as "200104"
	$query="SELECT * FROM `atlas_temp_widget_$citytoget` WHERE `date` LIKE '$YYYYMMDD%'";
	$result=mysql_query($query) or die(mysql_error());
	$tempdaily=array();
	while($row=mysql_fetch_array($result)){
		if(abs($row[$columntoget])<2000) $tempdaily[]=getCfromdata($row[$columntoget]);
	}
	if(empty($tempdaily)) $avgtemp=-2730;
	else $avgtemp=array_sum($tempdaily)/count($tempdaily);
	return $avgtemp;
}
function getF($C){
	// expects celsius, we're converting to straight up C before converting to F... 
	if($C==-2730) return 0;
	else return round((212-32)/100 * $C + 32, 1);
}
function getCfromdata($C){
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
		if(empty($city)) $city="NYC";
		if(empty($data)) $data='tmax';
		if($data=='tmax') $otherdata='tmin';
		else $otherdata='tmax';

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
		// get average temps for desired year to setup basis for comparison
		$baselineyear=array();
		for($i=0; $i<12; $i++){
			$themonth=substr("0".($i+1),-2);
			$thequery=date('Y', $theday) ."". $themonth;
			$baselineyear[]=getaveragetemperature($thequery, $data, $city);
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
				$thegraphvar.=", ". (getF(getaveragetemperature($thequery, $data, $city))-getF($baselineyear[$monthcounter]));
			}
			$thegraphvar.="],";
		}
		$thegraphvar=substr($thegraphvar,0,-1);
		echo $thegraphvar;

echo ']);
        // Create and draw the visualization.
		var chart = new google.visualization.SteppedAreaChart(document.getElementById("visualization"));
        var options = {
			curveType: "function",
			focusTarget: "category",
          title: "Historical Temperature Deviation for '. strtoupper($city) .' in '. date('Y', $theday) .' ('. strtoupper($data) .', Monthly Averages)",
          vAxis: { title: "Temperature Deviation ºF"},
          isStacked: true
        };
	    chart.draw(data, options);
      }
      

      google.setOnLoadCallback(drawVisualization);
    </script>
  </head>
  <body style="font-family: Arial;border: 0 none;">
    <div id="visualization" style="height:900px;"></div>
	<div id="navback" style="display:inline; float:left;"><a href="./deviation.php?day='. (date('Y',$theday)-1) . date('md',$theday) .'&data='. $data .'&step='. $step .'&maxsteps='.$maxsteps.'">&larr; '. (date('Y',$theday)-1) .'</a></div>
<div id="navnext" style="display:inline; float:right;"><a href="./deviation.php?day='. (date('Y',$theday)+1) . date('md',$theday) .'&data='. $data .'&step='. $step .'&maxsteps='.$maxsteps.'">'. (date('Y',$theday)+1) .' &rarr;</a></div>
	<div id="navdata" style="display:inline; position:absolute; left:50%;"><a href="./deviation.php?day='. date('Ymd',$theday) .'&data='. $otherdata .'&step='. $step .'&maxsteps='.$maxsteps.'">'. strtoupper($otherdata) .'</a></div>
  </body>
</html>';
?>