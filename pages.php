<?php
	function create_default_pages(){
		global $wpdb;
		
		$registration_page = array(
		  'comment_status' => 'closed', // 'closed' means no comments.
		  'ping_status' => 'closed', // 'closed' means pingbacks or trackbacks turned off
		  'post_content' => '[srm_register]', //The full text of the post.
		  'post_name' => 'seminar-registration', // The name (slug) for your post
		  'post_status' => 'publish', //Set the status of the new post. 
		  'post_title' => 'Seminar Registration', //The title of your post.
		  'post_type' =>'page'//You may want to insert a regular post, page, link, a menu item or some custom post type
		);  
		
		$thanks_page = array(
		  'comment_status' => 'closed', // 'closed' means no comments.
		  'ping_status' => 'closed', // 'closed' means pingbacks or trackbacks turned off
		  'post_content' => 'Thanks for registering for the seminar! You will receive an email notification with seminar details. If you do not receive this notification, please contact us.', //The full text of the post.
		  'post_name' => 'seminar-thank-you', // The name (slug) for your post
		  'post_status' => 'publish', //Set the status of the new post. 
		  'post_title' => 'Thanks for Registering!', //The title of your post.
		  'post_type' =>'page'//You may want to insert a regular post, page, link, a menu item or some custom post type
		);
		
		
		//check if they already have a registration page
		$registration_page=$wpdb->get_results("SELECT * FROM ".$wpdb->prefix."posts WHERE page_name='seminar-registration' AND post_status='publish'");
		if (empty($registration_page)):
			wp_insert_post($registration_page, true);
		endif;
		
		$thanks_page=$wpdb->get_results("SELECT * FROM ".$wpdb->prefix."posts WHERE page_name='seminar-thank-you' AND post_status='publish'");
		if (empty($thanks_page)):
			wp_insert_post($thanks_page, true); 
		endif;
	}