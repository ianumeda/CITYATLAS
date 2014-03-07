<?php
// City Atlas Temperature Widget version 1.7
$city=getcityindbtableformat(); // get city name for db table reference
if(empty($city)) $city="newyork";
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
function getWundergroundCityQuery($city){
	if($city=='newyork') return '40.8,-74.0';
	elseif($city=='barcelona') return 'Spain/Barcelona';
	elseif($city=='sfbay') return 'CA/San_Francisco';
	elseif($city=='paris') return 'France/Paris';
	elseif($city=='london') return 'England/London';
	elseif($city=='kolkata') return 'India/Kolkata';
}
function getnewforecast($city){
	// we need to limit the number of checks to wunderground like this...
	$wundergroundquery='http://api.wunderground.com/api/294bfc964384148d/forecast/q/'. getWundergroundCityQuery($city) .'.json';
	$aforecast=array();
	$json_string = file_get_contents($wundergroundquery);
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
			// last recorded forecast was less than so many seconds ago so use that...
			$aforecast[]=getCfromdata($row['tmax']);
			$aforecast[]=getCfromdata($row['tmin']);
			$histavg[]=getCfromdata($row['avgtmax']);
			$histavg[]=getCfromdata($row['avgtmin']);
		} else { 
			$aforecast=getnewforecast($city);
			$query="UPDATE `atlas_temp_widget_temp` SET `city`='$city', `date`='". date('YmdHi',strtotime('now')) ."', `tmax`='". (10*$aforecast[0]) ."', `tmin`='". (10*$aforecast[1]) ."', `updatecounter`='". ($row['updatecounter']+1) ."' WHERE `date`='". $row['date'] ."'";// don't need to update averages
			$result=mysql_query($query) or die(mysql_error());
		}
	} else { 
		// there is no recent record stored in db so get it from wunderground and store it...
		$aforecast=getnewforecast($city);
		$histavg=gethistoricalaverage($city);
		$query="INSERT INTO `atlas_temp_widget_temp` (`city`, `date`, `tmax`, `tmin`, `updatecounter`, `avgtmax`, `avgtmin`) VALUES ('$city', '". date('YmdHi',strtotime('now')) ."', '". (10*$aforecast[0]) ."', '". (10*$aforecast[1]) ."', '0', '". $histavg[0] ."', '". $histavg[1] ."');";
		//using noaa temperature syntax for consistency
		$result=mysql_query($query) or die(mysql_error());
		//histavg is not in human format in this case. convert...
		$histavg[0]/=10;
		$histavg[1]/=10;
	}
	return array('forecast'=>$aforecast, 'historicalaverage'=>$histavg);
}
function gethistoricalaverage($city){
	$todaysmonthday=date('md',strtotime('now'));
	$db_table='atlas_temp_widget_'.$city;
	$query="SELECT * FROM `$db_table` WHERE `date` LIKE '%$todaysmonthday'";
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
function getEarliestDataYear($city){
	$db_table='atlas_temp_widget_'.$city;
	$query = "SELECT MIN(`date`) AS `min_date` FROM `$db_table`"; 
	$result = mysql_query($query) or die(mysql_error());
	
	if($row = mysql_fetch_array($result)){
		return date('Y',strtotime($row['min_date']));
	}
	else return "####";
}
function getTempFormatForCity($city,$tempinC){
	$Ccities=array('barcelona','paris','london','kolkata');
	$Fcities=array('newyork','sfbay','losangeles');
	if(in_array($city,$Ccities)) return $tempinC."&deg;C";
	else return getF($temp)."&deg;F";
}
// get average for this day for the past 50 years...
// connect to db
include("tempwidget_db_login.php");
// get the data from db
$thedata=getthedata($city);
$prettycity=get_bloginfo('name');
$widgetlink="http://$city.thecityatlas.org/temperature-widget/";
// $comparison=getF($thedata['forecast'][0])-getF($thedata['historicalaverage'][0]);
// if($comparison>0) $comparison="+".$comparison."&deg;";
// else $comparison=$comparison."&deg;";
echo "<a href='". $widgetlink ."'>
<span class='concise'>
<span class='words'>Today in<br/>$city:</span> 
<span class='data'><span class='high'>". getTempFormatForCity($city,$thedata['forecast'][0]) ."</span></span>
</span><!-- .concise -->
<span class='verbose'>
<span class='infobutton'>i</span>
<span class='words'>Today in $prettycity:<br/>Historical Avg:</span>
<span class='data'><span class='high'>". getTempFormatForCity($city,$thedata['forecast'][0]) ."</span><br/><span class='high'>". getTempFormatForCity($city, $thedata['historicalaverage'][0]) ."</span></span>
</span><!-- .verbose -->
</a>";
?>