<?php
if(empty($city)) $city="NYC";
if($city=="NYC") date_default_timezone_set('America/New_York');
else date_default_timezone_set();

function getF($C){
	// expects celsius, we're converting to straight up C before converting to F... 
	if($C==-2730) return 0;
	else return round((212-32)/100 * $C + 32, 1);
}
function getCfromdata($C){
	return $C/10;
}
function getnewforecast($city){
	echo " getting new forecast. ";
	// we need to limit the number of checks to wunderground like this...
	$aforecast=array();
	$json_string = file_get_contents("http://api.wunderground.com/api/294bfc964384148d/forecast/q/40.8,-74.0.json");
	$parsed_json = json_decode($json_string);
	$aforecast[] = $parsed_json->forecast->simpleforecast->forecastday[0]->high->celsius;
	$aforecast[] = $parsed_json->forecast->simpleforecast->forecastday[0]->low->celsius;
	return $aforecast;
}
function getthedata($city){
	$todaysyearmonthday=date('Ymd');
	$aforecast=array();
	$histavg=array();
	$query="SELECT * FROM `atlas_temp_widget_temp` WHERE `date` LIKE '$todaysyearmonthday%' AND `city` = '$city'";
	$result=mysql_query($query) or die(mysql_error());
	if($row=mysql_fetch_array($result)){
		$lastrecord=strtotime($row['date']);
		if(strtotime('now')-$lastrecord < 14400){
			echo "old data: (".(strtotime('now')-$lastrecord).")";
			// last recorded forecast was less than so many seconds ago so use that...
			$aforecast[]=getCfromdata($row['tmax']);
			$aforecast[]=getCfromdata($row['tmin']);
			$histavg[]=getCfromdata($row['avgtmax']);
			$histavg[]=getCfromdata($row['avgtmin']);
		} else { 
			echo "new forecast: ";
			$aforecast=getnewforecast($city);
			$query="UPDATE `atlas_temp_widget_temp` SET `city`='$city', `date`='". date('YmdHi',strtotime('now')) ."', `tmax`='". (10*$aforecast[0]) ."', `tmin`='". (10*$aforecast[1]) ."', `updatecounter`='". ($row['updatecounter']+1) ."' WHERE `date`='". $row['date'] ."'";// don't need to update averages
			$result=mysql_query($query) or die(mysql_error());
		}
	} else { 
		// there is no recent record stored in db so get it from wunderground and store it...
		$aforecast=getnewforecast($city);
		$histavg=gethistoricalaverage($city);
		echo "new data: "; 
		$query="INSERT INTO `atlas_temp_widget_temp` (`city`, `date`, `tmax`, `tmin`, `updatecounter`, `avgtmax`, `avgtmin`) VALUES ('". $city ."', '". (date('YmdHi'),strtotime('now'))) ."', '". (10*$aforecast[0]) ."', '". (10*$aforecast[1]) ."', '0', '". $histavg[0] ."', '". $histavg[1] ."');";//using noaa temperature syntax for consistency
		$result=mysql_query($query) or die(mysql_error());
		//histavg is not in human format in this case. convert...
		$histavg[0]/=10;
		$histavg[1]/=10;
	}
	return array('forecast'=>$aforecast, 'historicalaverage'=>$histavg);
}
function gethistoricalaverage($city){
	$todaysmonthday=date('md',strtotime('now'));
	$query="SELECT * FROM `atlas_temp_widget2` WHERE `date` LIKE '%$todaysmonthday' AND `city` = '$city'";
	$result=mysql_query($query) or die(mysql_error());
	$historicaltempsfortoday=array();
	while($row=mysql_fetch_array($result)){
		if(abs($row['tmax'])<2000){
			// this gets rid of bad data
			$historicaltmaxfortoday[]=($row['tmax']);
		}
		if(abs($row['tmin'])<2000){
			$historicaltminfortoday[]=($row['tmin']);
		}
	}
	$historicalaveragetmax=(array_sum($historicaltmaxfortoday)/count($historicaltmaxfortoday));
	$historicalaveragetmin=(array_sum($historicaltminfortoday)/count($historicaltminfortoday));
	return array($historicalaveragetmax,$historicalaveragetmin);
}

// get average for this day for the past 50 years...
// connect to db
include("tempwidget_db_login.php");
// get the data from db

$thedata=getthedata($city);
print_r($thedata);

echo "Today in $city: <span class='high'>". getF($thedata['forecast'][0]) ."&deg;F</span><br/>Average Since 1950: <span class='high'>". getF($thedata['historicalaverage'][0]) ."&deg;F</span>";
?>