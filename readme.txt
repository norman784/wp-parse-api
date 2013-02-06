=== Wordpress Parse Api ===

Bridge between parse.com api and wordpress

== Description ==

The goal of this plugin its to replicate all your posts to parse.com so you can easily
develop mobile apps, because I find very useful their SDK and help us to develop
apps faster without worring about the security or if someone else can read data from our blog
and use it.

== Features ==

* Post object saved (create/update) to parse.com
* Push notifications when new post published
* Sync old post to parse.com

== Installation ==

1. Create your account on Parse.com
2. Create an app
3. Create an class in the data browser with those fields:
	* categories (Array)
	* content (String)
	* date (String)
	* guid (String)
	* photos (Array)
	* thumbnail (Object)
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

== Changelog ==

= 0.2.5 =
* Fix sync, now update/insert works
* Added log helper

= 0.2.4 =
* Fix sync, now update/insert works
* Fix img src isolation
* Changed limit per page from 20 to 10

= 0.2.3 =
* Fix sync, now the pagination works fine.
* Now refresh via javascript, instead of php wp_rediect to prevent browser error "too many redirections"
* Changed sort tag php with full php tag

= 0.2.2 =
* Added guid and photos fields
* Embed photos in the content field will be extracted and pushed on photos field
* Strip all html tags from the intro content
* Strip certain html tags from the extended content

= 0.2.1 =
* Fix: now sync all posts instead of the first 5

= 0.2 =
* Fix: parseRestClient (3rd party class) to get to work in php < 5.3
* Fix: thumbnails tag img was returned before the fix, now returns the url
* wp-parse-api.php: changed functions to class (work in progress to migrate all to OOP)
* Readme: changed thumbnail type to Object

= 0.1 =
Initial Commit 

== About ==

Contributors: normanpaniagua
Tags: parse.com, api
Requires at least: 3.0.1 (not tested)
Tested up to: 3.5
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html