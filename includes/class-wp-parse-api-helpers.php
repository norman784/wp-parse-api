<?
class WpParseApiHelpers {
	static public function postToObject($post_id) {
		$wp_post = get_post($post_id);
		$post = new parseObject(WP_PARSE_API_OBJECT_NAME);
		$_categories = get_the_category($post_id);
		$categories = array();
	
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
			'thumbnail'	=> get_the_post_thumbnail($post_id, 'thumbnail'),
			'medium'	=> get_the_post_thumbnail($post_id, 'medium'),
			'large' 	=> get_the_post_thumbnail($post_id, 'large'),
			'full'		=> get_the_post_thumbnail($post_id, 'full')
		);
	
		foreach ($thumbnails as $k=>$v):
			if ($v == null || strlen($v) == 0)
				unset($thumbnails[$k]);
		endforeach;
	
		$post->wpId			= (int)$post_id;
		$post->categories	= $categories;
		$post->content 		= $wp_post->post_content;
		$post->date			= $date;
		$post->title		= $wp_post->post_title;
		$post->thumbnail	= $thumbnails;
		
		return $post;
	}
}