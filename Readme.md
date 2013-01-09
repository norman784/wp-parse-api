=== Wordpress Parse Api ===
Contributors: markjaquith, mdawaffe (this should be a list of wordpress.org userid's)
Tags: parse.com, api
Requires at least: 3.0.1
Tested up to: 3.5
Stable tag: 4.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Bridge between parse.com api and wordpress

== Description ==

The goal of this plugin its to replicate all your posts to parse.com so you can easily
develop mobile apps, because I find very useful their SDK and help us to develop
apps faster without worring about the security or if someone else can read data from our blog
and use it.

== Features ==

* Post object saved to parse.com
* Push notifications when new post published
* 

== Installation ==

1. Create your account on Parse.com
2. Create an app
3. Create an class in the data browser with those fields:
	* categories (Array)
	* content (String)
	* date (String)
	* thumbnail (Array)
	* title (String)
	* wpId (Number)
4. Upload `wp-parse-api` to the `/wp-content/plugins/` directory
5. Activate the plugin through the 'Plugins' menu in WordPress
6. Go to Settings -> Parse Api
7. Fill the form with the data from your Parse.com app dashboard
8. At this point it must be ready to use it

== Frequently Asked Questions ==

= Why Parse.com? =

Because I find there a well documented SDK's for the most popular mobile platforms.

= How it works? =

Configure the plugin under Settings -> Parse Api as describe in the instalation. 
Every time you publish or save a published post it will create/update their 
respective row on parse.com

Then use the SDK of your prefered platform and start coding.

== Screenshots ==

1. This screen shot description corresponds to screenshot-1.(png|jpg|jpeg|gif). Note that the screenshot is taken from
the /assets directory or the directory that contains the stable readme.txt (tags or trunk). Screenshots in the /assets 
directory take precedence. For example, `/assets/screenshot-1.png` would win over `/tags/4.3/screenshot-1.png` 
(or jpg, jpeg, gif).
2. This is the second screen shot

== Changelog ==

= 0.1 =
Initial Commit 