<?php

class TopicMeta
{
	public $topicId = 1;
	private $topicMeta = array();

	function __construct($post_id) {
		global $wpdb;
		if($post_id)
		{
			$this->topicId = $post_id;
			$sql = $wpdb->prepare("SELECT * FROM ".$wpdb->bbf_topic_meta." WHERE topic_id=%d LIMIT 1", $this->topicId);
			$results = $wpdb->get_results($sql,ARRAY_A);
			//db($results);
			//exit();
			if($results)
			{
				$this->topicMeta = $results[0];
			}
		}
   }// construct end here

   public function getMeta($meta_key)
   {
	   if($meta_key && $this->topicMeta && $this->topicMeta[$meta_key])
	   {
		   return $this->topicMeta[$meta_key];
	   }else
	   {
		 return '';
	   }
   }

}
