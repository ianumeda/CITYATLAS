<?php
function getF($C){
	// expects celsius, we're converting to straight up C before converting to F... 
	if($C==-2730) return 0;
	else return round((212-32)/100 * $C + 32, 1);
}
function getCfromdata($C){
	return $C/10;
}

$today=strtotime('now');
// get average for this day for the past 50 years...
$todaysdate=date('md');
if(empty($city)) $city="NYC";
// connect to db
include("db_login.php");
// get the data from db
$query="SELECT * FROM `atlas_temp_widget2` WHERE `date` LIKE '%$todaysdate' AND `city` = '$city'";
$result=mysql_query($query) or die(mysql_error());
$historicaltempsfortoday=array();
while($row=mysql_fetch_array($result)){
	$historicaltmaxfortoday[]=getCfromdata($row['tmax']);
	$historicaltminfortoday[]=getCfromdata($row['tmin']);
}
$historicalaveragetmax=getF(array_sum($historicaltmaxfortoday)/count($historicaltmaxfortoday));
$historicalaveragetmin=getF(array_sum($historicaltminfortoday)/count($historicaltminfortoday));
	
$json_string = file_get_contents("http://api.wunderground.com/api/294bfc964384148d/forecast/q/40.8,-74.0.json");
$parsed_json = json_decode($json_string);
$temp_f_high = $parsed_json->forecast->simpleforecast->forecastday[0]->high->fahrenheit;
$temp_f_low = $parsed_json->forecast->simpleforecast->forecastday[0]->low->fahrenheit;
echo "Today in $city: <span class='high'>". $temp_f_high ."&deg;F</span> / <span class='low'>". $temp_f_low ."&deg;F</span><br/>Average Since 1950: <span class='high'>". $historicalaveragetmax ."&deg;F</span> / <span class='low'>". $historicalaveragetmin ."&deg;F</span>";
?>