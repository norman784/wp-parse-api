<?php
class WpParseApiHelpers {
	static public function postToObject($post_id) {
		$wp_post = get_post($post_id);
		$post = new parseObject(WP_PARSE_API_OBJECT_NAME);
		$_categories = get_the_category($post_id);
		$categories = array();
		$photos = array();
	
		foreach ($_categories as $row) {
			$categories[] = $row->name;
		}
	
		$date = date("d/M/Y", strtotime($wp_post->post_date));
		strtr($date, array(
			'Jan' => 'Ene',
			'Apr' => 'Abr',
			'Aug' => 'Ago',
			'Dec' => 'Dic'
		));
		
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
		
		preg_match_all('/<img[^>]+src=[\'"]([^\'"]+)[\'"].*>/i',$wp_post->post_content, $photos);
		
		$content = preg_replace('/\[.*?\](.+?)\[\/.*?\]/is', '', $wp_post->post_content);
		$content = strtr($content, array('<p>&nbsp;</p>'=>''));
		// $content = strip_tags($content, "<p><strong><div><em><ul><li><br><span>");
		$content = explode('<!--more-->', $content);
		$content[0] = strip_tags($content[0]);
		$content = implode('<!--more-->', $content);
	
		$post->categories	= $categories;
		$post->content 		= $content;
		$post->date			= $date;
		$post->guid			= $wp_post->guid;
		$post->photos		= $photos[1];
		$post->thumbnail	= $thumbnails;
		$post->title		= $wp_post->post_title;
		$post->wpId			= (int)$post_id;
		
		return $post;
	}
	
	public static function log($message) {
		if (!defined('LOG')) return;
		file_put_contents(LOG, "{$message}\n", FILE_APPEND);
	}
}