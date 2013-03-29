<?php
/*
Plugin Name: Parse.com Api
Plugin URI: http://github.com/norman784/wp-parse-api
Description: Bridge between parse.com api and wordpress
Version: 0.4.1
Author: Norman Paniagua
Author URI: http://github.com/norman784
License: GPL2

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// define('LOG', dirname(__FILE__) . '/log.txt');

define( 'WP_PARSE_API_PATH', 			plugin_dir_path(__FILE__));
define( 'WP_PARSE_API_SLUG', 			plugin_basename( __FILE__ ) );
define( 'WP_PARSE_API_VERSION', 		0.4 );
define( 'WP_PARSE_API_PROPER_NAME', 	'wp-parse-api' );
define( 'WP_PARSE_API_GITHUB_URL', 		'https://github.com/norman784/wp-parse-api' );
define( 'WP_PARSE_API_GITHUB_ZIP_URL',	'https://github.com/norman784/wp-parse-api/zipball/master' );
define( 'WP_PARSE_API_GITHUB_API_URL',	'https://api.github.com/repos/norman784/wp-parse-api' );
define( 'WP_PARSE_API_GITHUB_RAW_URL',	'https://raw.github.com/norman784/wp-parse-api/master' );
define( 'WP_PARSE_API_REQUIRES_WP',		'3.0.1' );
define( 'WP_PARSE_API_TESTED_WP',		'3.5.0' );

require_once WP_PARSE_API_PATH . 'libs/parse.com-php-library/parse.php';
require_once WP_PARSE_API_PATH . 'includes/class-wp-parse-api-helpers.php';
require_once WP_PARSE_API_PATH . 'includes/class-wp-parse-api-admin-settings.php';

add_action('wp_loaded', array(WpParseApi::get_instance(), 'register'));

class WpParseApi
{
	/**
	 * Plugin instance.
	 *
	 * @see get_instance()
	 * @type object
	 */
	protected static $instance	= NULL;
	
	protected $action			= 'wpparseapi_79898';
	protected $option_name		= 'wpparseapi_79898';
	protected $page_id			= NULL;
	
	/**
	 * Access this plugin’s working instance
	 *
	 * @wp-hook wp_loaded
	 * @return  object of this class
	 */
	public static function get_instance()
	{
		WpParseApiHelpers::log('WpParseApi::get_instance()');
	    NULL === self::$instance and self::$instance = new self;
	    return self::$instance;
	}
	
	/**
	 * Add the hook to create/update the post on parse.com
	 *
	 */
	public function register()
	{
		WpParseApiHelpers::log('WpParseApi::register()');
		add_action('save_post', array($this, 'save_post'));
	}
	
	/**
	 * Create/Update the post on parse.com
	 *
	 */
	public function save_post($post_id)
	{
		WpParseApiHelpers::log("WpParseApi::save_post($post_id) | START");
		
		// Verify post is a revision
		if (wp_is_post_revision($post_id)) return;
		// Check if the parse api app id is defined
		if (!defined('WP_PARSE_API_APP_ID') || WP_PARSE_API_APP_ID == null) return;
		WpParseApiHelpers::log("WpParseApi::save_post($post_id) | WP_PARSE_API_APP_ID passed");
		// Verify post is an autosave
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
		WpParseApiHelpers::log("WpParseApi::save_post($post_id) | DOING_AUTOSAVE passed");
		// Verify post nonce
		// if (!wp_verify_nonce( $_POST[ $this->option_name . '_nonce' ], $this->action)) return;
		// WpParseApiHelpers::log("WpParseApi::save_post($post_id) | nonce passed");
		// Verify post status
		if (get_post_status($post_id) != 'publish') return;
		WpParseApiHelpers::log("WpParseApi::save_post($post_id) | status passed");
	
		$post = WpParseApiHelpers::postToObject($post_id);
	
		// Creates a new post on parse.com
		if (!get_post_meta($post_id, 'wp_parse_api_code_run', true)) {
			update_post_meta($post_id, 'wp_parse_api_code_run', true);
			
			$categories = array();
			
			foreach ($post->data['categories'] as $row) {
				$row = trim(preg_replace('/[^a-zA-Z]/', '', $row));
				if ($row != '') $categories[] = $row;
			}
			
			// Check if there is no categories or push notifications are disabled
			if (is_array($categories) && count($categories) > 0 && get_option('app_push_notifications') != 'Off') {
				try {
					$push = new parsePush();
					$push->alert = $post->data['title'];
					$push->channels = $categories;
					$push->send();
				} catch (Exception $e) {
					// do nothing, this was added because 
					// parse lib throws an exception if the account
					// has not been configured
					// special thanks to raymondmuller for find the issue
				}
			}
			
			$post->save();
		// Update an existin post on parse.com
		} else {
			$q = new parseQuery(WP_PARSE_API_OBJECT_NAME);
			$q->where('wpId', (int)$post_id);
			$r = $q->find();
		
			if (is_array($r->results)) $r = array_shift($r->results);
			if ($r != null) $post->update($r->objectId);
		}
		
		WpParseApiHelpers::log("WpParseApi::save_post($post_id) | END");
	}
}