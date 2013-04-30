<?php
class WpParseApiHelpers {
	
	private $lang = array(
		'en' => array(), // Default
		'es' => array(
			'Jan' => 'Ene',
			'Apr' => 'Abr',
			'Aug' => 'Ago',
			'Dec' => 'Dic'
		)
	);
	
	static public function postToObject($post_id) {
		// Get the post
		$wp_post = get_post($post_id);
		// Init the parse object
		$post = new parseObject(WP_PARSE_API_OBJECT_NAME);
		// Get the categories
		$_categories = get_the_category($post_id);
		
		// Initializing vars
		$categories = array();
		$photos = array();
		$videos = array();
		
		// Add the categories
		foreach ($_categories as $row) {
			$categories[] = $row->name;
		}
		
		// Set the date in spanish format
		$date = strtr(date("d/M/Y", strtotime($wp_post->post_date)), $lang[get_option('lang') || 'en']);
		
		// Add the thumbnails
		$thumbnails = array(
			'thumbnail'	=> wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), 'thumbnail' ),
			'medium'	=> wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), 'medium' ),
			'large' 	=> wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), 'large' ),
			'full'		=> wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), 'full' )
		);
		
		foreach ($thumbnails as $k=>$v):
			if (!is_array($v)) unset($thumbnails[$k]);
			else $thumbnails[$k] = array_shift($v);
		endforeach;
		
		if (count($thumbnails) == 0) $thumbnails = new stdClass();
		
		// Extract the photos from the content
		preg_match_all('/<img[^>]+src=[\'"]([^\'"]+)[\'"].*>/i', $wp_post->post_content, $photos);
		
		// Extract the youtube video id's from the content
		$urls = array('#http://www.youtube.com/embed/([A-Za-z0-9\-_]+)#s', '#http://www.youtube.com/watch?v=([A-Za-z0-9\-_]+)#s');
		
		foreach ($urls as $url) :
			$tmp = array();
			preg_match_all($url, $wp_post->post_content, $tmp);
			foreach ($tmp[1] as $v) :
				if (!in_array($v, $videos)) $videos[] = $v;
			endforeach;
		endforeach;
		
		// Remove unwanted strings from contents
		$content = preg_replace('/\[.*?\](.+?)\[\/.*?\]/is', '', $wp_post->post_content);
		$content = strtr($content, array('<p>&nbsp;</p>'=>''));
		// $content = strip_tags($content, "<p><strong><div><em><ul><li><br><span>");
		$content = explode('<!--more-->', $content);
		$content[0] = strip_tags($content[0]);
		$content = implode('<!--more-->', $content);
		
		// Set the post properties
		$post->categories	= $categories;
		$post->content 		= $content;
		$post->date			= $date;
		$post->guid			= $wp_post->guid;
		$post->photos		= $photos[1];
		$post->thumbnail	= $thumbnails;
		$post->title		= $wp_post->post_title;
		$post->videos		= $videos;
		$post->wpId			= (int)$post_id;
		
		// Return the post
		return $post;
	}
	
	public static function log($message) {
		if (!defined('LOG')) return;
		file_put_contents(LOG, "{$message}\n", FILE_APPEND);
	}
}