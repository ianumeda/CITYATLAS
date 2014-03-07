<?php
// City Atlas Temperature Widget version 1.8
$city=getcityindbtableformat(); // get city name for db table reference
if($city=='developmentsite') $city='newyork';
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
function getFfromCabsolute($C){
	return $C*9/5;
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
		if(strtotime('now')-$lastrecord < 28800){
			// last recorded forecast was less than so many seconds ago so use that...
			$aforecast[]=($row['tmax']);
			$aforecast[]=($row['tmin']);
			$histavg[]=($row['avgtmax']);
			$histavg[]=($row['avgtmin']);
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
		// $histavg[0]/=10;
		// $histavg[1]/=10;
	}
	$stddev=getstandarddeviation($city,$histavg);
	return array('forecast'=>$aforecast, 'historicalaverage'=>$histavg, 'standarddeviation'=>$stddev);
}
function getstandarddeviation($city, $historicalaverage){
	$todaysmonthday=date('md',strtotime('now'));
	$db_table='atlas_temp_widget_'.$city;
	$query="SELECT * FROM `$db_table` WHERE `date` LIKE '%$todaysmonthday'";
	$result=mysql_query($query) or die(mysql_error());
	$maxdevdata=array();
	$mindevdata=array();
	while($row=mysql_fetch_array($result)){
		if(abs($row['tmax'])<2000){
			$maxdevdata[]=pow($row['tmax']-$historicalaverage[0],2);
		}
		if(abs($row['tmin'])<2000){
			$mindevdata[]=pow($row['tmin']-$historicalaverage[1],2);
		}
	}
	$maxstddev=sqrt(array_sum($maxdevdata)/count($maxdevdata));
	$minstddev=sqrt(array_sum($mindevdata)/count($mindevdata));
	return array($maxstddev,$minstddev);
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
function getTempFormatForCity($city,$temp){
	$Ccities=array('barcelona','paris','london','kolkata');
	$Fcities=array('newyork','sfbay','losangeles');
	if(in_array($city,$Ccities)) return $temp."&deg;C";
	else return getF($temp)."&deg;F";
}
// connect to db
include("tempwidget_db_login.php");
// get the data from db
$thedata=getthedata($city);
$prettycity=get_bloginfo('name');

$widgetlink="http://$city.thecityatlas.org/temperature-widget/";
$forecastvshistoricalaverage=getFfromCabsolute(getCfromdata($thedata['forecast'][0]-$thedata['historicalaverage'][0]));
$comparisonvsstandarddeviation=abs($forecastvshistoricalaverage)-round(getFfromCabsolute(getCfromdata($thedata['standarddeviation'][0])),1);
// if($comparison>0) $comparison="+".$comparison."&deg;";
// else $comparison=$comparison."&deg;";
echo "<a href='". $widgetlink ."'>
<span class='concise'>
<span class='words'>Today in<br/>$city:</span> 
<span class='data'><span class='high'>". getF(getCfromdata($thedata['forecast'][0])) ."&deg;F</span><br/><span class='comparison'>(".$comparisonvsstandarddeviation."&deg;)</span></span>
</span><!-- .concise -->
<span class='verbose'>
<span class='infobutton'>i</span>
<span class='words'>Today in $prettycity:<br/>Historical Avg:</span>
<span class='data'><span class='high'>". getF(getCfromdata($thedata['forecast'][0])) ."&deg;F</span><br/><span class='high'>". getF(getCfromdata($thedata['historicalaverage'][0])) ."&deg;F</span><span class='stddev'>(&plusmn;". round(getFfromCabsolute(getCfromdata($thedata['standarddeviation'][0])),1) ."&deg;)</span></span>
</span><!-- .verbose -->
</a>";
?>