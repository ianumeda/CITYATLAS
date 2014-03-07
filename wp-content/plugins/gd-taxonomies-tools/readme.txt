=== GD Custom Posts And Taxonomies Tools ===
Contributors: GDragoN
Donate link: http://www.gdcpttools.com/
Version: 1.6
Tags: gdragon, dev4press, tools, taxonomy, custom post types, post type, custom post, custom taxonomies, taxonomies, management, widget, cloud
Requires at least: 3.0
Tested up to: 3.6
Stable tag: trunk
License: GPLv2 or later

GD Custom Posts And Taxonomies Tools is plugin for management and tools collection for working with custom posts and taxonomies.

== Description ==
GD Custom Posts And Taxonomies Tools is a plugin that can be used to expand custom taxonomies and custom post types support. Plugin adds many tools including custom post types and taxonomies management and widget for taxonomies terms cloud.

Supported languages: english, chinese, spanish, danish, polish, dutch, french

To work with custom post types you need WordPress 3.0. Plugin supports all latest features added to custom post types and supported by WordPress.

= Important URL's =
[GD Custom Posts And Taxonomies Tools Home](http://www.gdcpttools.com/)

[Plugin on Dev4Press](http://www.dev4press.com/plugins/gd-taxonomies-tools/) | 
[Support Forum](http://www.dev4press.com/forums/forum/free-plugins/gd-taxonomies-tools-lite/) | 
[Feedburner](http://feeds2.feedburner.com/dev4press) | 
[Follow on Twitter](http://twitter.com/milangd)

== Installation ==
= Requirements =
* PHP: 5.x.x
* WordPress: 3.x.x

= Basic Installation =
* Plugin folder in the WordPress plugins folder must be `gd-taxonomies-tools`.
* Upload folder `gd-taxonomies-tools` to the `/wp-content/plugins/` directory
* Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==
= Does plugin works with WordPress MultiSite installations? =
Yes.

= Can I set structures for permalinks for custom post types? =
Not with Lite version. Pro version has support for custom permalinks, and also for archives intersections with taxonomies. More about Pro version: http://d4p.me/gdtt

= Can I edit built in (default) post types and taxonomies (post, page, category, tag)? =
Not with Lite version. Pro version has full support of editing all public (including default) post types and taxonomies. Pro version includes much more features not available in Lite: http://d4p.me/gdtt

= I need support for using this plugin and some of it's features? =
Lite version is regularly maintained, but it doesn't include any kind of support beyond bug fixing. If you need support, upgrade plugin to Pro edition and get much more additional features: http://d4p.me/gdtt

= I want to translate the plugin to my language, or to improve existing translations? =
You only need POEdit program that works on Windows, Linux and MacOS. Instructions on how to make or update translations are here: http://d4p.me/wa. POT file to start translation is included with the plugin.

== Screenshots ==
1. Post Type Management
2. Adding Custom Post Type
2. Editing Custom Taxonomy
4. Taxonomy Terms Cloud Widget
5. Taxonomy Terms List Widget

== Upgrade Notice ==
= 1.6 =
* Custom Post Type: all items label, show in admin bar, show in menu. Custom Taxonomy: view item label, show admin column, sort order saving. Few changes to saving and data cleanup.

== Changelog ==
= 1.6.0.1 =
* Fixed problem with undefined main plugin variable on some pages

= 1.6 =
* Custom Post Type: all items label
* Custom Post Type: show in admin bar
* Custom Post Type: show in menu
* Custom Taxonomy: view item label
* Custom Taxonomy: show admin column
* Custom Taxonomy: sort order saving
* Few changes to saving and data cleanup

= 1.5.2 =
* Improved validation of data when saving
* Few changes from testing with WordPress 3.5
* Reindexing data on every update for consistency
* Fixed missing styling for the errors display
* Fixed issue with registering custom query variable

= 1.5.1 =
* Replaced load_textdomain with load_plugin_textdomain
* Minor changes from testing with WordPress 3.4
* Many small changes, improvements updated URL's

= 1.5 =
* Admin side loading optimization
* Using new context help for WordPress 3.3
* Interface changes for WordPress 3.3
* Fixed minor issues with the registration code

= 1.4.2 =
* Custom post type admin menu position
* Custom post type admin menu icon url
* Improved registration process for taxonomies

= 1.4.1 =
* Simpler checkbox selection for plugin editors
* Removed all jqueryui specific code, images and styling
* Removed dropdown checklist due to browsers problems

= 1.4.0 =
* Additional custom post types options
* Improved generating of the post types code
* Improved generating of the taxonomies code
* Updates for the WordPress 3.2
* Dropped support for WordPress 2.9
* Minor changes and code cleanup

= 1.3.5 =
* Support for hierarchical taxonomy rewrite slugs

= 1.3.4 =
* Making sure that plugin is fully WP 3.1 compatible
* Minor changes to the post type registration procedure

= 1.3.3 =
* Support for has_archive post type options in WP 3.1
* Some minor panels HTML markup cleanup

= 1.3.2 =
* Updated list of capabilites and labels for custom post types
* Updated list of capabilites and labels for custom taxonomies
* Interface improvements and new editor options

= 1.3.1 =
* Use of capability type for custom post types
* More updates for upcoming WordPress 3.1
* Limiting names for post types and taxonomies

= 1.3.0 =
* French translation added
* Full support for upcoming WordPress 3.1
* Post type editor shows only public taxonomies
* Updated drop down checkbox jQuery library

= 1.2.9 =
* Several minor changes and updates

= 1.2.8 =
* Polish and Dutch translation added
* Use of capabilities for access panels control
* Fixed saving custom rewrite slug for custom taxonomies

= 1.2.7 =
* Minor changes in registering post types
* Minor changes in registering taxonomies

= 1.2.6 =
* Option to deactivate temporarily custom post type
* Improvements in the editors layouts
* compatibility with WP 3.1 development version

= 1.2.5 =
* Mandatory checking for lowercase names for taxonomy and post type

= 1.2.4 =
* Removed sitemap expand options as obsolete
* Fixed cloud widget and missing taxonomy parameter

= 1.2.3 =
* Updated translation strings

= 1.2.2 =
* Improved flushing of rewrite rules after adding post type or taxonomy
* Fixed internal links from plugins panel

= 1.2.1 =
* Updated readme file to include upgrade notice
* Updates to some of plugin URL's

= 1.2.0 =
* Additional custom post types options
* Custom taxonomy visibility options
* Getting plugin url uses wp specific function
* Several small interface problems
