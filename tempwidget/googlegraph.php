<?php
extract($_REQUEST);

echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <title>
      Google Visualization API Sample
    </title>
    <script type="text/javascript" src="http://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages: ["corechart"]});
    </script>
    <script type="text/javascript">
      function drawVisualization() {
        // Create and populate the data table.
        var data = google.visualization.arrayToDataTable([';

		if(empty($city)) $city="NYC";
		if(is_numeric($day)) {
			$theday=strtotime($day);
		} else {
			$theday=strtotime('now');
		}
		// connect to db
		include("db_login.php");
		$step=10; // years
		$earliest=1962;
		$latest=2007;
		$theyear=date('Y',$theday); 
		if($theyear>$latest) $theyear=$latest;
		// for($theyear=date('Y',$theday)-$step; $theyear>=$earliest; $theyear-=$step){
			$query="SELECT * FROM `atlas_temp_widget` WHERE `day` LIKE '$theyear%' AND `city` = '$city'";
			$result=mysql_query($query) or die(mysql_error());
			$row=mysql_fetch_array($result);
			$thevar="['x', '$theyear'],";
			while($row=mysql_fetch_array($result)){
				$thevar.="['". date('d M Y', strtotime($row['day'])) ."', ". $row['temp'] ."],";
			}
			$thevar=substr($thevar,0,-1);
			echo $thevar;
		// }
echo ']);
        // Create and draw the visualization.
		var chart = new google.visualization.SteppedAreaChart(document.getElementById("visualization"));
        var options = {
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
  </body>
</html>';
?>
