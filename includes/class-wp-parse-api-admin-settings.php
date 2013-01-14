<?php
if (!defined('WP_PARSE_API_PATH')) die('.______.');

if (is_admin()){	 // admin actions
	add_action('admin_menu', 'wp_parse_api_menu');
	add_action('admin_post_wp_parse_api_sync', 'wp_parse_api_sync');
	
	function wp_parse_api_menu() {
		add_options_page('Parse Api Options', 'Parse Api', 'manage_options', 'wp-parse-api-options', 'wp_parse_api_page');
		add_action('admin_init', 'wp_parse_api_admin_init');
	}
	
	function wp_parse_api_admin_init() {
		//register our settings
		register_setting('wp-parse-api-settings-group', 'app_id');
		register_setting('wp-parse-api-settings-group', 'app_masterkey');
		register_setting('wp-parse-api-settings-group', 'app_restkey');
		register_setting('wp-parse-api-settings-group', 'object_name');
	}
	
	function wp_parse_api_page() {
		require WP_PARSE_API_PATH .'includes/class-wp-parse-api-admin-settings-template.php';
	}
	
	function wp_parse_api_sync() {
		$numberposts = 20;
		$_GET['wp-parse-api-parse'] = (int)$_GET['wp-parse-api-parse'];
		
		$q = new parseQuery(WP_PARSE_API_OBJECT_NAME);
		$parse_posts = $q->find();
		$parse_posts = $parse_posts->results;
		$wp_posts = get_posts(array(
			'numberposts' => $numberposts,
			'offset' => ($_GET['wp-parse-api-page'] * $numberposts) / $numberposts
		));
			
		if (count($wp_posts) == 0) {
			wp_redirect( 'options-general.php?page=wp-parse-api-options' );
			exit;
		}
		
		foreach ($wp_posts as $wp) {
			if ($wp->post_status != 'publish') continue;
			
			$post = WpParseApiHelpers::postToObject($wp->ID);
			
			foreach ($parse_posts as $pp) {
				if ((int)$pp->wpId == (int)$wp->ID) {
					$post->update($pp->objectId);
					$post = null;
				}
			}
			
			if ($post != null) $post->save();
		}
		
		++$_GET['wp-parse-api-parse'];
		$url = $_SERVER['PHP_SELF'];
		
		foreach ($_GET as $k=>$v):
			$qs = (strpos($url, '?') === false ? '?' : '&');
			$url .= sprintf("%s%s=%s", $qs, $k, $v);
		endforeach;
		
		wp_redirect( $url );
	}
}