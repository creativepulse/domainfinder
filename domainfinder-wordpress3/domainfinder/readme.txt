=== Domain Finder ===
Contributors: Creative Pulse
Donate link: 
Tags: domain, search, find, name, register, sidebar, wordpress, free, widget
Requires at least: 2.8.0
Tested up to: 3.9.0
Stable tag: 1.3
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Domain Finder searches for domain availability

== Description ==

Domain Finder searches for domain availability. It works with Ajax and shows the search results "in place" - users don't need to move to a dedicated page for the domain search process. TLDs (domain extensions like .com or .net) are fully customizable. Domain finder also features a "More info" button that shows more information about a taken domain gathered from the WHOIS server.

Results in place is a feature intentionally made so that users don't leave the page. This way webmasters can keep the good conversion rate of the page that features Domain Finder.

The supported TLDs are the ones suported by WHOIS servers.

This extension is MVC compatible. Those with enough experience with the PHP computing language can write their own layout mechanism (viewer).

You can find more information at the plugin's homepage http://www.creativepulse.gr/en/appstore/domainfinder

== Installation ==

Use the regular installation procedure to install this plugin.

That is, go to Admin > Plugins > Add New > Upload.

Alternatively you can upload it into the `/wordpress/wp-content/plugins/` directory
and then activate it in the Admin control panel for plugins.

== Frequently Asked Questions == 

= How do I control which TLDs the widget will show? =

Go to the control panel for the widget (Admin > Appearance > Widgets > Domain Finder) and find the text box area called "TLDs". Use that input box to enter the TLDs you want to use. Enter one TLD per line.

When the widget appears to the website visitor, it shows your TLDs with a checkbox. Users can check or uncheck the checkboxes to search the availability of their desired domain for multiple TLDs. By default, checkboxes are checked. If you want some of them to be unchecked, put a minus sign (-) before the TLD.

Example:  
com  
-net  
-org

= What is a TLD? =

TLD stands for Top Level Domain. It is the final part of a domain name, like for example the ".com" in the domain "www.example.com". When users search for a domain it is commonly used to get suggestions in multiple TLDs. Therefore this script may start the search for the availability of a domain like "example.com" but it may also search for "example.net" or "example.org".


== Screenshots == 

No screenshots at this moment

== Changelog ==

= 1.3 =
* Minor bugfixes
* Added a Z-Index CSS feature to avoid overlapping panel problems
* Modernization to match the new features of WordPress 3.9

= 1.2 =
* Opening version for WordPress

== Upgrade Notice ==

You can simply overwrite the old files with the new ones.

No upgrade notice exists as this is the first version of the plugin.
