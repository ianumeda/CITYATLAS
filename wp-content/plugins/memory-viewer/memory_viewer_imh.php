<?php
/*
Plugin Name: Memory Viewer
Plugin URI: http://www.inmotionhosting.com/support/edu/wordpress/316-memory-viewer-wordpress-plugin
Description: This plugin shows WordPress' memory usage during various stages of WordPress' execution. It has recently been updated to show CPU time and data about the MySQL queries being ran.
Version: 1.05
Author: Brad Markle w/ InMotion Hosting
Author URI: http://www.inmotionhosting.com
License: GPL2
*/






//avoid direct calls to this file
if ( !function_exists( 'add_action' ) )
{
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}


// disable mySQL Session Cache
define( 'QUERY_CACHE_TYPE_OFF', TRUE );


// enable SAVEQUERIES
if ( !defined( 'SAVEQUERIES' ) )
{
	define( 'SAVEQUERIES', TRUE );
}


$GLOBALS["mvimh_count"] = 0;






// --------------------------------------------------------------------------------------------
// The following is a list of functions that run during several WordPress Hooks that track both
// memory usage and CPU time at execution
// --------------------------------------------------------------------------------------------
function mvimh_init()
{
	mvimh_log_data_at_hook("init");
}
function mvimh_get_header()
{
        mvimh_log_data_at_hook("get_header");
}
function mvimh_wp_loaded()
{
        mvimh_log_data_at_hook("wp_loaded");
}
function mvimh_footer()
{
	mvimh_log_data_at_hook("wp_footer");
}
function mvimh_plugins_loaded()
{
	mvimh_log_data_at_hook("plugins_loaded");
}
function mvimh_setup_theme()
{
	mvimh_log_data_at_hook("setup_theme");
}
function mvimh_loop_start()
{
	mvimh_log_data_at_hook("loop_start");
}
function mvimh_loop_end()
{
	mvimh_log_data_at_hook("loop_end");
}
function mvimh_wp_head()
{
	mvimh_log_data_at_hook("wp_head");
}
function mvimh_widgits_init()
{
	mvimh_log_data_at_hook("widgits_init");
}
function mvimh_shutdown()
{
	mvimh_log_data_at_hook("shutdown");

	// Only show the data if the user is an administrator
	global $user_ID;
	if( $user_ID )
	{
		if( current_user_can('level_10') )
		{
			mvimh_print_data();
		}
	}
}






function mvimh_log_data_at_hook($hook)
{
	$mvimh_current_time = microtime(true);
	global $wpdb;
	$count = $GLOBALS["mvimh_count"];

	$GLOBALS["mvimh_memused"][$count][$hook] = memory_get_usage();
	$GLOBALS["mvimh_maxmem"][$count][$hook] = memory_get_peak_usage();
	$GLOBALS["mvimh_cputime"][$count][$hook] = microtime(true);
	$GLOBALS["mvimh_queries_up_to_now"][$count][$hook] = count($wpdb->queries);

	// determine # mysql queries since last hook
	if ( $GLOBALS["mvimh_last_total_queries"] )
	{
		$GLOBALS["num_queries_since_last_hook"][$count][$hook] = $GLOBALS["mvimh_queries_up_to_now"][$count][$hook] - $GLOBALS["mvimh_last_total_queries"];
	}
	$GLOBALS["mvimh_last_total_queries"] = count($wpdb->queries);
	


	// determine time since last hook
	if($GLOBALS['mvimh_last_hook_cpu_time'])
	{
		$GLOBALS['mvimh_time_since_last_hook'][$count][$hook] = $GLOBALS["mvimh_cputime"][$count][$hook] - $GLOBALS['mvimh_last_hook_cpu_time'];
	}
	else
	{
		$GLOBALS['mvimh_time_since_last_hook'][$count][$hook] = "0";
	}
	$GLOBALS['mvimh_last_hook_cpu_time'] = $GLOBALS["mvimh_cputime"][$count][$hook];


	// determine total time used by MySQL up to this point
	if($wpdb->queries)
	{
		foreach($wpdb->queries as $k => $v)
		{
			// echo "<pre>asdfasdf\n"; print_r($v); echo "</pre>";
			$total_time += $v[1];
		}
		$GLOBALS["mvimh_mysql_time_this_point"][$count][$hook] = $total_time;
	}
	else
	{
		$GLOBALS["mvimh_mysql_time_this_point"][$count][$hook] = 0;
	}


        // determine the time used by mysql since the last hook
        if($GLOBALS["last_mysql_time"])
        {
                $GLOBALS["mvimh_mysql_time_since_last_hook"][$count][$hook] = $GLOBALS["mvimh_mysql_time_this_point"][$count][$hook] - $GLOBALS["last_mysql_time"];
        }
        $GLOBALS["last_mysql_time"] = $GLOBALS["mvimh_mysql_time_this_point"][$count][$hook];


	// we now want to log the queries that have been run since the last hook
	//
	// if we don't have any "# mysql queries since last hook", then the queries listed will be for this current hook
	if( ! $GLOBALS["mvimh_logged_first_queries"] && count($wpdb->queries) )
	{
		// loop through all queries and load details
		foreach($wpdb->queries as $q_k => $q_v)
		{
			$GLOBALS["specific_queries_during_this_hook"][$count][$hook][] = $q_v;
		}
		$GLOBALS["mvimh_logged_first_queries"] = true;
	}
	else
	{
		// how many should we skip?
		$skip_num_queries = count($wpdb->queries) - $GLOBALS["num_queries_since_last_hook"][$count][$hook];
		// debug // echo "<p>skip queries = $skip_num_queries</p>";
		// debug // echo "<p>" . count($wpdb->queries) . " - " . $GLOBALS["num_queries_since_last_hook"][$count][$hook] . "</p>";
		$current_count = 1;
		if($wpdb->queries)
		{
			foreach($wpdb->queries as $q_k => $q_v)
			{
				if( $current_count > $skip_num_queries )
				{
					$GLOBALS["specific_queries_during_this_hook"][$count][$hook][] = $q_v;
				}
				$current_count++;
			}
		}
	}


	$GLOBALS["mvimh_count"]++;

	$GLOBALS["mvimh_time_used_by_this_plugin"] += microtime(true) - $mvimh_current_time;
}






function mvimh_print_data()
{
	echo "
		<style type='text/css'>
			.mvimh { font-size:14px; background: url('http://img05.inmotionhosting.com/_img/body_bg.gif') #fff; background-repeat:repeat-x; padding:3px; border:1px solid #000; margin-left:10px; margin-right:10px; }
			.mvimh h1 {font-weight:bold; font-size:16px;}
			.mvimh table { background-color:#fff; border:1px solid #bbb; margin-left:45px; margin-right:45px; margin-bottom:25px; }
			.mvimh table td { padding:3px; border-bottom:1px solid #000; border-right:1px solid #ccc; }
			.mvimh table th { padding:3px; border-bottom:1px solid #000; border-right:1px solid #ccc; font-weight:bold; background:#003366; color:#fff; text-align:left;}
			.mvimh_oddrow { background: #FFF; }
			.mvimh_evenrow { background: #E6E6E6; }
		</style>
		<div class='mvimh'>
                        <div style=\"background: url('http://img01.inmotionhosting.com/_img/head_logo.gif'); background-repeat:no-repeat; min-height:70px; padding-left:240px; margin-bottom:20px;\">
                                        <h1>Memory Viewer WordPress Plugin</h1>
					<div>Below are the results of the Memory Viewer Plugin. For more help using this plugin, please see: <a href='http://www.inmotionhosting.com/support/edu/wordpress/316-memory-viewer-wordpress-plugin' target='_blank'>Using InMotion Hosting's Memory Viewer WordPress Plugin</a></div>
					<div style='font-size:11px;'>Like this plugin? Check out <a href='http://www.inmotionhosting.com'>web hosting</a> offered by InMotion Hosting</div>
                        </div>
			<h1 style='margin-left:45px;'>Raw Data:</h1>
			<table border='1' cellspacing='0' cellpadding='3'>
				<tr>
					<th>Hook</th>
					<th>Current Memory Usage</th>
					<th>Max Memory Used at this point in the script</th>
					<th>Current Server Time</th>
					<th>Total Time since last hook</th>
					<th># MySQL Queries Run up to this point</th>
					<th># MySQL Queries since last hook</th>
					<th>Time used by MySQL up to this point</th>
					<th>Time used by MySQL since last hook</th>
				</tr>
	";


	// var to keep track of odd / even rows
	$odd_even_row = 1;


	// loop through the global memused and cputime data
	foreach($GLOBALS["mvimh_memused"] as $key => $value)
	{
		$hook = key($GLOBALS["mvimh_memused"][$key]);
		$mem_used = $GLOBALS["mvimh_memused"][$key][$hook];
		$maxmem = $GLOBALS["mvimh_maxmem"][$key][$hook];
		$cpu_time = $GLOBALS["mvimh_cputime"][$key][$hook];
		$queries_up_to_now = $GLOBALS["mvimh_queries_up_to_now"][$key][$hook];
		$mysql_time_this_point = $GLOBALS["mvimh_mysql_time_this_point"][$key][$hook];
		$mysql_time_since_last_hook = $GLOBALS["mvimh_mysql_time_since_last_hook"][$key][$hook];
		$mysql_queries_since_last_hook = $GLOBALS["num_queries_since_last_hook"][$key][$hook];


		// track data for json
		$imhmv_json["Plugin Summary"]["total_mysql_queries"] = $queries_up_to_now;


		// create the link to show the queries
		unset($show_queries);
		if($previous_queries_up_to_this_point != $queries_up_to_now)
		{
			$show_queries = "<div style='text-align:right; font-size:10px;'><a style=\"cursor:pointer;\" onClick=\" var showqueries = document.getElementById('specific_queries_" . $key. "_" . $hook . "'); showqueries.style.display = '';\">[show queries]</a></div>";
			$hide_queries = "<div style='font-size:12px;'><a style=\"cursor:pointer;\" onClick=\" var showqueries = document.getElementById('specific_queries_" . $key. "_" . $hook . "'); showqueries.style.display = 'none';\">[hide these queries]</a></div>";
		}


		// determine the backbround color of this row (so we can alternate colors)
		if ( $odd_even_row % 2 != 0 )
			$rowclass = 'mvimh_oddrow';
		else
			$rowclass = 'mvimh_evenrow';


		// determine time since last hook
		if($last_hook_cpu_time)
		{
			$since_last_hook = $cpu_time - $last_hook_cpu_time;
		}
		else
		{
			$since_last_hook = "-";
		}
		$last_hook_cpu_time = $cpu_time;
		$last_hook = $hook;

		echo "		<tr class='$rowclass'>
					<td>$hook</td>
					<td>" . round( $mem_used / (1024 * 1024) , 2 ) . " MBs</td>
					<td>" . round( $maxmem / (1024 * 1024) , 2 ) . " MBs</td>
					<td>" . $cpu_time . "</td>
					<td>" . $since_last_hook . "</td>
					<td>" . $queries_up_to_now . "</td>
					<td>" . $mysql_queries_since_last_hook . $show_queries . "</td>
					<td>" . $mysql_time_this_point . "</td>
					<td>" . $mysql_time_since_last_hook . "</td>
				</tr>
		";


		// save the data as a json variable in the event the user wants to send the results to IMH
		$imhmv_json[$hook]["Current Memory Usage"] = $mem_used;
		$imhmv_json[$hook]["Max Memory Used at this point in the script"] = $maxmem;
		$imhmv_json[$hook]["Current Server Time"] = $cpu_time;
		$imhmv_json[$hook]["Total Time since last hook"] = $since_last_hook;
		$imhmv_json[$hook]["# MySQL Queries Run up to this point"] = $queries_up_to_now;
		$imhmv_json[$hook]["# MySQL Queries since last hook"] = $mysql_queries_since_last_hook;
		$imhmv_json[$hook]["Time used my MySQL up to this point"] = $mysql_time_this_point;
		$imhmv_json[$hook]["Time used by MySQL since last hook"] = $mysql_time_since_last_hook;


		// keep track of max memory used at any time for json data
		if( ! $imhmv_json["Plugin Summary"]["max_memory_all_time"] )
		{
			$imhmv_json["Plugin Summary"]["max_memory_all_time"] = $maxmem;
		}
		else
		{
			if( $maxmem > $imhmv_json["Plugin Summary"]["max_memory_all_time"] )
			{
				$imhmv_json["Plugin Summary"]["max_memory_all_time"] = $maxmem;
			}
		}

		if($GLOBALS["specific_queries_during_this_hook"][$key][$hook])
		{
			echo "	<tr style='display:none;' id='specific_queries_" . $key . "_" . $hook . "'>
					<td colspan='9' style='padding:20px;' class='$rowclass'>
						<div style='font-weight:bold; margin-bottom:20px;'>MySQL Queries since last hook</div>
			";
			foreach($GLOBALS["specific_queries_during_this_hook"][$key][$hook] as $sq_k => $sq_v)
			{
				echo "		<table style='margin-bottom:15px; width:80%; font-size:11px;'>
							<tr>
								<th>Query:</th>
								<td style='color:red; background:#E5F3FF;'>" . $sq_v[0] . "</td>
							</tr>
							<tr>
								<th>Time it took query to run:</th>
								<td style='background:#E5F3FF;'>" . $sq_v[1] . "</td>
							</tr>
							<tr>
								<th style='width:20%;'>The function that called the query:</th>
								<td style='background:#E5F3FF;'>" . $sq_v[2] . "</td>
							</tr>
						</table>
				";
			}
			echo $hide_queries;

			echo "		</td>
				</tr>
			";

			// keep track of the number of queries run up to this point
			$previous_queries_up_to_this_point = $queries_up_to_now;
		}


		$odd_even_row++;
	}
	echo "		</table>";


	// --------------------------------------------------------------------------
	// Now we want to print Summary Data, such as total php time / mysql time
	// --------------------------------------------------------------------------

	$total_script_time =  timer_stop( false, 22 );
	$time_used_by_php = $total_script_time - $mysql_time_this_point - $GLOBALS['mvimh_time_used_by_this_plugin'];


	echo "	<h1 style='margin-left:45px;'>Summary of Overall Time & Percentage Data:</h1>
		<table>
			<tr>
				<th>Total Time to generate this page:</td>
				<td style='font-weight:bold;'>$total_script_time</td>
				<td style='font-weight:bold;'>100%</td>
			</tr>
			<tr>
				<th>Total Time used by PHP:</th>
				<td>$time_used_by_php</td>
				<td>" . round( $time_used_by_php / $total_script_time * 100,4) . "%</td>
			</tr>
			<tr>
				<th>Total Time used by MySQL:</td>
				<td>$mysql_time_this_point</td>
				<td>" . round( $mysql_time_this_point / $total_script_time * 100,4) . "%</td>
			</tr>
			<tr>
				<th>Total Time used by Memory Viewer Plugin:</td>
				<td>" . $GLOBALS["mvimh_time_used_by_this_plugin"] . "</td>
				<td>" . round( $GLOBALS["mvimh_time_used_by_this_plugin"] / $total_script_time * 100,4) . "%</td>
			</tr>
		</table>
	";


        // add summary data to json var
        $imhmv_json["Plugin Summary"]["Total Time to generate this page"] = $total_script_time;
        $imhmv_json["Plugin Summary"]["Total Time used by PHP"] = $time_used_by_php;
        $imhmv_json["Plugin Summary"]["Total Time used by MySQL"] = $mysql_time_this_point;
        $imhmv_json["Plugin Summary"]["Total Time used by Memory Viewer Plugin"] = $GLOBALS["mvimh_time_used_by_this_plugin"];
        $imhmv_json["Plugin Summary"]["Current URL"] = (!empty($_SERVER['HTTPS'])) ? "https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] : "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
	$imhmv_json["Plugin Summary"]["Theme"] = get_current_theme();


	// --------------------------------------------------------------------------
	// Now we want to offer the user the option to submit the json data for
	// Statistical Analysis
	// --------------------------------------------------------------------------
	echo "	<div style='margin-left:45px; width:600px;'>
			<h1>Share your Data!</h1>
			<p>Click \"Share Data\" below to share the data that the Memory Viewer Plugin has gathered. This data will help create statistical data about memory usage and script timing, and will be used by others in the future to help compare their results against. This is a new feature we've added. As more data is submitted, we will begin to make it available for public review.</p>
			<p>All data in the tables above will be submitted, EXCEPT for the MySQL queries (for security / privacy reasons). We also send the current page url to help determine unique usage of the plugin and the name of the theme you're using.</p>
			<p>
				<form method='post' action='http://www.inmotionhosting.com/support/edu/wordpress/316-memory-viewer-wordpress-plugin' target='_blank'>
					<strong>Tags</strong><br />
					Tag your results (comma separated) so that others will be able to find and compare results. For example, if you're running plugin X and Y, tag the results with:<br /><i>Plugin X,Plugin Y</i><br />
					<input type='text' name='tags' id='tags' maxlength='254' style='width:575px;' /><br />
					<textarea name='jsondata' id='jsondata' style='display:none;'>"; echo json_encode($imhmv_json); echo "</textarea>
					<input type='hidden' name='url' id='url' value='" . $imhmv_json["Plugin Summary"]["Current URL"] . "' />
					<input type='hidden' name='maxmem' id='maxmem' value='" . $imhmv_json["Plugin Summary"]["max_memory_all_time"] . "' />
					<input type='hidden' name='total_queries' id='total_queries' value='" . $imhmv_json["Plugin Summary"]["total_mysql_queries"] . "' />
					<input type='hidden' name='total_time' id='total_time' value='$total_script_time' />
					<input type='hidden' name='total_php_time' id='total_php_time' value='$time_used_by_php' />
					<input type='hidden' name='total_mysql_time' id='total_mysql_time' value='$mysql_time_this_point' />
					<input type='submit' value='Share Data' />
				</form>
			</p>
		</div>
	";



	echo "	</div>	";


	// $imhmv_json = json_encode($imhmv_json);
	// $imhmv_json = json_decode($imhmv_json);
	// echo "<pre>"; print_r($imhmv_json); echo "</pre>";
}






add_action("init", "mvimh_init");
add_action("get_header", "mvimh_get_header");
add_action("wp_loaded", "mvimh_wp_loaded");
add_action("wp_footer", "mvimh_footer");
add_action("plugins_loaded", "mvimh_plugins_loaded");
add_action("setup_theme", "mvimh_setup_theme");
add_action("loop_start", "mvimh_loop_start");
add_action("loop_end", "mvimh_loop_end");
add_action("wp_head", "mvimh_wp_head");
add_action("widgits_init", "mvimh_widgits_init");
add_action("shutdown", "mvimh_shutdown");
?>
