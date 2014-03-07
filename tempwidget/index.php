<?php
extract($_REQUEST);
if(is_numeric($day))
{
	if(empty($city)) $city="NYC";
	// connect to db
	include("db_login.php");
	// get the data from db
	$query="SELECT * FROM `atlas_temp_widget` WHERE `day` = '$day' AND `city` = '$city' LIMIT 1";
	$result=mysql_query($query) or die(mysql_error());
	$row=mysql_fetch_array($result);
	if(!empty($row))
	{
		$temp=(float)$row['temp'];
		echo "The HI temperature on ". date('l dS \o\f F Y', strtotime($day)) ." in $city was ". $temp ."&deg;F";
	} 
	else 
	{
		echo "No data found :'( ... try again!";
	}
}
else 
{
	echo "give me a day like this: <a href='./?day=19690712'>thecityatlas.org/tempwidget/?day=19690712</a>";
}
?>