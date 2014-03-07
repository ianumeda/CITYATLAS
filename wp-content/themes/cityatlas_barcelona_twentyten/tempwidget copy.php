<?php
$today=strtotime('now');
$fiftyyearsago=date('Y', $today)-50;
$fiftyyearsagotoday=$fiftyyearsago.date('md', $today);
	if(empty($city)) $city="NYC";
	// connect to db
	include("db_login.php");
	// get the data from db
	$query="SELECT * FROM `atlas_temp_widget` WHERE `day` = '$fiftyyearsagotoday' AND `city` = '$city' LIMIT 1";
	$result=mysql_query($query) or die(mysql_error());
	$row=mysql_fetch_array($result);
	if(!empty($row))
	{
		$temp=(float)$row['temp'];
		$json_string = file_get_contents("http://api.wunderground.com/api/294bfc964384148d/forecast/q/40.8,-74.0.json");
		$parsed_json = json_decode($json_string);
		$temp_f = $parsed_json->forecast->simpleforecast->forecastday[0]->high->fahrenheit;
		echo "Today in $city: <span class='temp'>". $temp_f ."&deg;F</span><br/>Fifty years ago today: <span class='temp'>". $temp ."&deg;F</span>";
	} 
	else 
	{
		echo "No data found :'(";
	}
?>