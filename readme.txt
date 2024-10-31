=== ProLocker ===

Requires at least: WordPress 5.0.0
Tested up to: WordPress 5.4.1
Stable tag: 1.1.2
Version: 1.1.2
License: GNU General Public License v2
License URI: https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
Tags: lock, locking, viral, share

== Description ==

Plugin that enables content sharing by means of social unlocking. It allows you to lock content you deem interesting and then allowing your public to unlock said content by sharing the URL of your website along with a unique identifier.

== Copyright ==

ProLocker WordPress Plugin, Copyright 2020 Frontier Themes.
ProLocker WordPress Plugin is distributed under the terms of the GNU GPL.

ProLocker bundles the following third-party resources:

React, Copyright (c) 2013-2020 Facebook, Inc., Copyright (c) 2013-2020 React Team
Licenses: MIT
Source: https://reactjs.org/

== Frequently Asked Questions ==

= What does this plugin address? =

The purpose of this plugin is to provide greater reach of your content through the means of share locking. Let’s say you have an interesting content to share (be it news, pictures, videos, among other types of content), and you wish to maximize reach by having a mechanism for virality. You just have to lock that content with our plugin and configure the sharing requirements (hit counter) per visitor.

= What’s a good number to set up as the hit number? =

We recommend keeping it fairly low, perhaps 2 or 3 is a good choice. Making it big enough will make people try to cheat on it using proxies or VPNs.

= What is a Prokey and if a Prokey has been abused, what can I do? =

A Prokey is a sharing link instance ID, which identifies a visitor from another and counts hits against to. If a Prokey has been abused, every Prokey has an IP address associated with it, so you can add it to the blacklist mechanism we have also shipped with this release. An IP address blacklisted can’t create new Prokeys nor count hits for other Prokeys.

== Screenshots ==

1. Locked content example.
2. Hit Counter block configuration.
3. Prokeys list.
4. Prokey detail.

== Changelog ==

= 1.1.2 =
* Released: June 1, 2020
* Updated readme.
* Added screenshots.

= 1.1.1 =
* Added sanitation and validation for $_GET and $_REQUEST values.

= 1.1.0 =
* Released: April 29, 2020
* Renamed every single namespace, class, methods and constants in favor of more unique names.
* Renamed the custom post types included in the plugin. 

= 1.0.1 =
* Released: April 13, 2020
* Removed unused code from the render callback of the prolocker/hit-counter block.

= 1.0.0 =
* Released: March 26, 2020
* First stable release.
