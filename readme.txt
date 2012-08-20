=== Pure Web Brilliant's base framework ===
Contributors: 				kevinjohngallagher, purewebbrilliant, pure-web-brilliant 
Donate link:				http://kevinjohngallagher.com/
Tags: 						kevinjohn gallagher, pure web brilliant, framework, cms, simple, multisite, settings, dependancies, testing
Requires at least:			3.0
Tested up to: 				3.5
Stable tag: 				2.4



Framework required for all Pure Web Brilliant plug-ins, themes and CMS features.



== Installation ==

1. Upload `kevinjohn_gallagher_____framework.php` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress


== Frequently Asked Questions ==

= This plug-in does not seem to do anything? =

On it's own, you are correct. 
This plug-in houses commonly used framework and functionality for the other plug-ins & themes created in the Pure Web Brilliant WordPress offering. They cannot work without this plug-in.

= There is a menu item, but it has no options =

Correct.
It's a high level menu wrapper for the other plug-ins. On it's own, it won't do anything, but that changes when you add additional plug-ins that work well together.

= Where are these other plug-ins? =

We're releasing them to the WordPress repository at the quickest rate we can; but virtually all of our code was written for clients who required a non-GLPv2 compatible licence. There may be a small window where the framework is availible prior to the other plug-ins.


= Can this framework be used by plug-ins by other authors? =

Yes! We'd love that. This framework was built in response to our belief that in its rapid's growth the WordPress' lexington has muddied the water between functionality that should be in plug-ins versus those that should be in themes. We all duplicate so much code and functionality, and hopefully this will help others in the way it's helped us.



== Changelog ==


= 2.3 =
* Updated security check


= 2.2 =

Added stylesheet to wp_admin
Added action: define_child_settings_sections
Added action: define_child_settings_array
Added action: add_plugin_to_menu
Added function: framework_on_action_admin_init()
Added function: framework_valid_callback ()
Added function: get_post_custom_fields ()
Added function: framework_get_value ()
Added function: framework_set_value ()
Added function: is_page_mine ()
Added function: get_the_permalink ()



= 2.0 =
* Removal of non-GPLv3 compatible functions.
* Publish to WP.org repository.


== Upgrade Notice ==

= 2.0 =
* Initial upgrade to public / GPL compatible version.



== Arbitrary section ==


**Kevinjohn Gallagher:** [Kevinjohn Gallagher](http://kevinjohngallagher.com/ "Kevinjohn Gallagher .com")

**Agency:** [Pure Web Brilliant](http://purewebbrilliant.com/ "Pure Web Brilliant")

Framework release blog post: [Pure Web Brilliant’s plugin framework released](http://kevinjohngallagher.com/2012/05/pure-web-brilliants-plugin-framework-released/ "Pure Web Brilliant’s plugin framework released")

> " I want to go on record thanking my colleagues and many of our current & past clients, who were (mostly) happy to negotiate changes in the licence of our past work so that we could make it open source. "

* Package:						Pure Web Brilliant
* Version:						2.0.1
* Author:							Kevinjohn Gallagher <framework@KevinjohnGallagher.com>
* Copyright:					Copyright (c) 2012, Kevinjohn Gallagher
* Link:								http://KevinjohnGallagher.com
* Licence:						http://www.gnu.org/licenses/gpl-3.0.txt
