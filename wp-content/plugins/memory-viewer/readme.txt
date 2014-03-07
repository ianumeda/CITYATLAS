=== Plugin Name ===
Contributors: imh_brad
Tags: memory+viewer
Requires at least: 3.2.1
Tested up to: 3.2.1
Stable Tag: 1.05

Memory Viewer is a WordPress plugin that allows you to view WordPress' memory utilization at several hooks during WordPress' execution.

== Description ==

Memory Viewer is a WordPress plugin that allows you to view WordPress' memory utilization at several hooks during WordPress' execution. It also shows a summary of MySQL Queries that have ran as well as CPU time. This is version 1 of the Memory Viewer plugin. It is very simple. All you need to do is:
1. Enable the plugin
2. Visit your WordPress site (YOU MUST be logged in as an administrator)
3. Scroll to the bottom of your page to see the data

== Installation ==

1. Upload `memory_viewer_imh.php` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress


== Changelog ==

= 1.0 =
* Plugin initially lauched

= 1.01 =
* Bug fix, last memory log should read 'shutdown' instead of 'plugins_loaded'

= 1.02 =
* Instead of showing the data in the html comments, it shows the data at the bottom of the page. You must however be logged in as admin to view this data.
* Not only do we show memory usage, but we also show data about mysql queries

= 1.03 =
* Official Documentation on the plugin has been written, and the plugin now offers a link to this documentation if further help is needed.
* The plugin now also links to the forum page for this plugin at wordpress.org

= 1.05 =
* Updated Description in readme so it makes more sense to users searching for plugins within WordPress Dashboard.
* Added the ability to submit the plugin's results to a global database of results
