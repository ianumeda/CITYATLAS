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
		echo "Today's HI for $city : __&deg;F. Fifty years ago today: ". $temp ."&deg;F";
	} 
	else 
	{
		echo "No data found :'(";
	}
?>