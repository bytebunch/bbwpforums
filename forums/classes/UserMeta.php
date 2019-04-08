<?php

class UserMeta
{
	private $userID = false;
	private $userMeta = array();

	function __construct($user_id = false) {
		if($user_id)
		{
			global $wpdb;
			if($user_id)
			{
				$this->userID = $user_id;
				$sql = "SELECT * FROM ".$wpdb->bbf_topic_meta." WHERE topic_id=".$this->topicId." LIMIT 1";
				$results = $wpdb->get_results($sql,ARRAY_A);
				//db($results);
				//exit();
				if($results)
				{
					$this->topicMeta = $results[0];
				}
			}
		}

   }// construct end here


   public function logged_in_user_meta()
   {
	   if(is_user_logged_in())
	   {
		   global $currentUser;
	   }
   }

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
