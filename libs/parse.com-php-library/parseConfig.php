<?php
define('WP_PARSE_API_APP_ID'		, get_option('app_id'));
define('WP_PARSE_API_MASTERKEY'		, get_option('app_masterkey'));
define('WP_PARSE_API_APP_RESTKEY'	, get_option('app_restkey'));
define('WP_PARSE_API_OBJECT_NAME'	, get_option('object_name') == "" ? 'Post' : get_option('object_name'));

class parseConfig {
	const APPID = WP_PARSE_API_APP_ID;
	const MASTERKEY = WP_PARSE_API_MASTERKEY;
	const RESTKEY = WP_PARSE_API_APP_RESTKEY;
	const PARSEURL = 'https://api.parse.com/1/';
}