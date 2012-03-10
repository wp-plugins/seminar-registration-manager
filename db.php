<?php
	function create_db_tables(){
		global $wpdb;
		$admin_email=get_option('admin_email');
		
		//settings table
		$wpdb->query("
			CREATE TABLE IF NOT EXISTS `".$wpdb->prefix ."srm_settings` (
				`id` int(11) unsigned NOT NULL auto_increment,
				`key` varchar(100) NOT NULL,
				`value`	varchar(500) NOT NULL,
				PRIMARY KEY (`id`)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1
		");
		
		//populate settings table
		$settings_arr=array(
            'SRM_ADMIN_EMAIL' => $admin_email, 
            'SRM_MAIL_FROM' => $admin_email, 
            'SRM_IMAGE_WIDTH' => '300', 
            'SRM_IMAGE_MAX_SIZE_KB' => '2000', 
            'SRM_DEFAULT_REGISTRATION_PAGE' => 'seminar-registration', 
            'SRM_DEFAULT_THANKS_PAGE' => 'seminar-thank-you',
			'SRM_DEFAULT_TERMS_PAGE' => 'seminar-terms-and-conditions', 
            'SRM_LOAD_JQUERY' => '1', 
            'SRM_LOAD_JQUERYUI' => '1', 
            'SRM_PER_PAGE' => '10', 
            'SRM_PAYMENT_GATEWAY' => 'authorize_net',
            'SRM_PAYMENT_GATEWAY_MODE' => 'TEST',
            'SRM_AUTHORIZE_NET_APIKEY' => 'api_key',
            'SRM_AUTHORIZE_NET_TRANSID' => 'trans_id',
            'SRM_PAYPAL_PAYMENTS_PRO_USERNAME' => 'paypal_username',
            'SRM_PAYPAL_PAYMENTS_PRO_PASSWORD' => 'paypal_password',
            'SRM_PAYPAL_PAYMENTS_PRO_SIGNATURE' => 'paypal_signature',
			'SRM_DEFAULT_NAME' => 'Seminar'
             
        ); 
		$settings_insert_sql="INSERT INTO `".$wpdb->prefix."srm_settings` (`key`, `value`) VALUES ";
        foreach($settings_arr as $key=>$value):
            if (!check_db_setting_exists($key)):
                $settings_insert_sql.="('$key', '$value'), ";
            endif;
        endforeach;
        
        $last_comma_pos=strrpos($settings_insert_sql, ',');
        if ($last_comma_pos!==false):
            $settings_insert_sql=substr($settings_insert_sql, 0, $last_comma_pos);
        endif;
                
        $wpdb->query($settings_insert_sql);
		
		//seminars table
		$wpdb->query("
			CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."srm_seminars` (
			  `id` int(11) unsigned NOT NULL auto_increment,
			  `title` varchar(250) NOT NULL,
			  `description` varchar(1000) NOT NULL,
			  `location` varchar(250) NOT NULL,
			  `address1` varchar(250) NOT NULL,
			  `address2` varchar(250) NOT NULL,
			  `city` varchar(100) NOT NULL,
			  `state` varchar(2) NOT NULL,
			  `zip` varchar(25) NOT NULL,
			  `phone` varchar(15) NOT NULL,
			  `image` varchar(100) NOT NULL,
			  `registrant_price` decimal(10,2) NOT NULL,
			  `room_rate` decimal(10,2) NOT NULL,
			  `start_date` date NOT NULL,
			  `end_date` date NOT NULL,
			  `start_time` varchar(100) NOT NULL,
			  `end_time` varchar(100) NOT NULL,
			  `max_attendees` int(11) NOT NULL,
			  PRIMARY KEY  (`id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;
		");
		
		//registrants table
		$wpdb->query("
			CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."srm_registrants` (
			  `id` int(11) unsigned NOT NULL auto_increment,
			  `seminar_id` int(11) NOT NULL,
			  `coupon_id` int(11) default NULL,
			  `fname` varchar(100) NOT NULL,
			  `mname` varchar(100) default NULL,
			  `lname` varchar(100) NOT NULL,
			  `phone` varchar(25) NOT NULL,
			  `fax` varchar(25) NOT NULL,
			  `email` varchar(100) NOT NULL,
			  `paid` int(1) unsigned NOT NULL default '0',
			  PRIMARY KEY  (`id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;
		");
		
		//registrant billing info table
		$wpdb->query("
			CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."srm_registrant_billing_info` (
			  `id` int(11) NOT NULL auto_increment,
			  `registrant_id` int(11) NOT NULL,
			  `billing_name` varchar(200) NOT NULL,
			  `address1` varchar(250) NOT NULL,
			  `address2` varchar(250) NOT NULL,
			  `city` varchar(100) NOT NULL,
			  `state` varchar(2) NOT NULL,
			  `zip` varchar(25) NOT NULL,
			  `card_num` varchar(100) NOT NULL,
			  `card_type` varchar(25) NOT NULL,
			  `card_exp_month` int(11) NOT NULL,
			  `card_exp_year` int(11) NOT NULL,
			  PRIMARY KEY  (`id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;
		");
		
		//additional registrants table
		$wpdb->query("
			CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."srm_additional_registrants` (
			  `id` int(11) NOT NULL auto_increment,
			  `registrant_id` int(11) NOT NULL,
			  `type` int(11) NOT NULL,
			  `fname` varchar(100) NOT NULL,
			  `mname` varchar(100) default NULL,
			  `lname` varchar(100) NOT NULL,
			  `phone` varchar(25) NOT NULL,
			  `fax` varchar(25) default NULL,
			  `email` varchar(100) NOT NULL,
			  PRIMARY KEY  (`id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;
		");
		
		//coupon code table
		$wpdb->query("
			CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."srm_coupon_codes` (
			  `id` int(11) NOT NULL auto_increment,
			  `coupon_name` varchar(250) NOT NULL,
			  `coupon_code` varchar(25) NOT NULL,
			  `registrant_price` decimal(10,2) NOT NULL,
			  `active` int(11) NOT NULL,
			  PRIMARY KEY  (`id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;
		");
	}
    
    
    function check_db_setting_exists($setting_key){
        global $wpdb;
        $setting_exists=false;
        $setting_arr=$wpdb->get_row(
            $wpdb->prepare(
                "SELECT * FROM `".$wpdb->prefix ."srm_settings` WHERE `key` = %s ",
                $setting_key
            ), 
            ARRAY_A
        );
        $wpdb->print_error();
        if (!empty($setting_arr)):
            $setting_exists=true;
        endif;
        
        return $setting_exists;
    }