<?php

class ForumMeta
{
	public $forumId = 1;
	private $forumMeta = array();

	function __construct($post_id) {
		global $wpdb;
		if($post_id)
		{
			$this->forumId = $post_id;
			$sql = $wpdb->prepare("SELECT * FROM ".$wpdb->bbf_forum_meta." WHERE forum_id=%d LIMIT 1", $this->forumId);
			$results = $wpdb->get_results($sql,ARRAY_A);
			//db($results);
			//exit();
			if($results)
			{
				$this->forumMeta = $results[0];
			}
		}else{
			return NULL;
		}
   }// construct end here

   public function getMeta($meta_key)
   {
	   if($meta_key && isset($this->forumMeta) && isset($this->forumMeta[$meta_key]))
	   {
		   return $this->forumMeta[$meta_key];
	   }else
	   {
		 return NULL;
	   }
   }

}
