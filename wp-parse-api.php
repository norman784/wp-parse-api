<?php
/*
Plugin Name: Parse.com Api
Plugin URI: http://github.com/norman784/wp-parse-api
Description: Bridge between parse.com api and wordpress
Version: 0.1
Author: Norman Paniagua
Author URI: http://github.com/norman784
License: GPL2

Copyright 2013  Wordpress Parse Api  (email : normanpaniagua at gmail dot com)

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

define('WP_PARSE_API_PATH', plugin_dir_path(__FILE__));
require WP_PARSE_API_PATH . 'libs/parse.com-php-library/parse.php';
require WP_PARSE_API_PATH . 'includes/class-wp-parse-api-helpers.php';
require WP_PARSE_API_PATH . 'includes/class-wp-parse-api-admin-settings.php';

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
	    NULL === self::$instance and self::$instance = new self;
	    return self::$instance;
	}
	
	/**
	 * Add the hook to create/update the post on parse.com
	 *
	 */
	public function register()
	{
		add_action('save_post', array($this, ''));
	}
	
	/**
	 * Create/Update the post on parse.com
	 *
	 */
	public function save_post()
	{
		$post_id = wp_is_post_revision($post_id) || $post_id;
	
		// Check if the parse api app id is defined
		if (!defined('WP_PARSE_API_APP_ID') || WP_PARSE_API_APP_ID == null) return;
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
		if (!wp_verify_nonce( $_POST[ $this->option_name . '_nonce' ], $this->action)) return;
		if (get_post_status($post_id) != 'publish') return;
	
		$post = WpParseApiHelpers::postToObject($post_id);
	
		// Creates a new post on parse.com
		if (!get_post_meta($post_id, 'wp_parse_api_code_run', true)) {
			update_post_meta($post_id, 'wp_parse_api_code_run', true);
	
			$push = new parsePush($post->data['title']);
			$push->channels = $categories;
			$push->send();
		
			$post->save();
		// Update an existin post on parse.com
		} else {
			$q = new parseQuery(WP_PARSE_API_OBJECT_NAME);
			$q->where('wpId', (int)$post_id);
			$r = $q->find();
		
			if (is_array($r->results)) $r = array_shift($r->results);
			if ($r != null) $post->update($r->objectId);
		}
	}
}