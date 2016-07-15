<?php
if (!defined('WP_PARSE_API_APP_ID'))		define('WP_PARSE_API_APP_ID'		, get_option('app_id'));
if (!defined('WP_PARSE_API_MASTERKEY'))		define('WP_PARSE_API_MASTERKEY'		, get_option('app_masterkey'));
if (!defined('WP_PARSE_API_APP_RESTKEY'))	define('WP_PARSE_API_APP_RESTKEY'	, get_option('app_restkey'));
if (!defined('WP_PARSE_API_APP_URL'))	define('WP_PARSE_API_APP_URL'	, get_option('app_url'));
if (!defined('WP_PARSE_API_OBJECT_NAME'))	define('WP_PARSE_API_OBJECT_NAME'	, get_option('object_name') == "" ? 'Post' : get_option('object_name'));

class parseConfig {
	var $APPID = WP_PARSE_API_APP_ID;
	var $MASTERKEY = WP_PARSE_API_MASTERKEY;
	var $RESTKEY = WP_PARSE_API_APP_RESTKEY;
	var $PARSEURL = WP_PARSE_API_APP_URL;
}