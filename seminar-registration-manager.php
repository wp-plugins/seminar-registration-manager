<?php

/*
Plugin Name: Seminar Registration Manager
Plugin URI: http://leadgenix.com/
Description: Plugin to handle seminar registrations, integrated with eShop
Version: 1.1
Author: Tony Anderson
Author URI: http://www.leadgenix.com
License: GPL3 
*/

define( "DEBUG",true );
require_once( 'config.inc' );
require_once( 'classes/class-seminars.php' );
require_once( 'classes/class-registrants.php' );
require_once( 'classes/class-register.php' );
require_once( 'classes/class-coupon-codes.php' );
require_once( 'classes/class-common.php' );
require_once( 'classes/class-image.php' );
require_once( 'db.php' );
require_once( 'pages.php' );
/*on activation*/
	//check DB tables
	register_activation_hook( SRM_PLUGIN_PATH.'/'.SRM_PLUGIN_DIR.'.php', 'create_db_tables' );
	
	//create default pages
	register_activation_hook( SRM_PLUGIN_PATH.'/'.SRM_PLUGIN_DIR.'.php', 'create_default_pages' );
/***************/

//get default settings from DB
$plugin_settings = SrmCommon::get_plugin_settings();

foreach ( $plugin_settings as $plugin_setting):

	define( $plugin_setting['key'], $plugin_setting['value'] );

endforeach;



if( is_admin() )

    add_action( 'admin_menu', 'srm_add_menu' );





//add the menu

function srm_add_menu(){

    $page = add_menu_page( 'Manage '.ucwords(strtolower(SRM_DEFAULT_NAME)).'s',ucwords(strtolower(SRM_DEFAULT_NAME)).'s','manage_options','seminar-registration-manager','seminar_registration_manager', plugin_dir_url( __FILE__ ).'/images/srm_icon.png' );

    add_submenu_page( 'seminar-registration-manager', 'Create New '.ucwords(strtolower(SRM_DEFAULT_NAME)), 'Add/Edit '.ucwords(strtolower(SRM_DEFAULT_NAME)).'s', 'manage_options', 'srm-seminars-add-edit', 'srm_seminar_add_edit' );

    add_submenu_page( 'seminar-registration-manager', 'Manage Registrants', 'Registrants', 'manage_options', 'srm-registrants', 'srm_registrants' );

    //add_submenu_page( 'seminar-registration-manager', 'Create New Registrant', 'Add/Edit Registrants', 'manage_options', 'srm-registrants-add-edit', 'srm_registrants_add_edit' );

    add_submenu_page( 'seminar-registration-manager', 'Coupon Codes', 'Coupon Codes', 'manage_options', 'srm-coupon-codes', 'srm_coupon_codes' );

    add_submenu_page( 'seminar-registration-manager', 'Create Coupon Code', 'Add/Edit Coupons', 'manage_options', 'srm-coupon-codes-add-edit', 'srm_coupon_codes_add_edit' );

    add_submenu_page( 'seminar-registration-manager' , 'Settings', 'Settings', 'manage_options', 'srm-settings', 'srm_settings' );

    add_submenu_page( 'seminar-registration-manager' , 'Help/About', 'Help/About', 'manage_options', 'srm-about', 'srm_about' );    

}



//stylesheet, scripts

add_action( 'admin_enqueue_scripts', 'srm_stylesheet' );

function srm_stylesheet(){

    //stylesheet

    $srm_stylesheet_path = SRM_FRONT_END_PATH.'/style.css';

    wp_register_style( 'srmstyles', $srm_stylesheet_path );

    wp_enqueue_style( 'srmstyles' );

    

	if ( SRM_LOAD_JQUERYUI == 1 ){

		$srm_jqueryui_stylesheet_path = SRM_FRONT_END_PATH.'/jqueryui-style.css';

		wp_register_style( 'srm_jqueryui_styles', $srm_jqueryui_stylesheet_path );

		wp_enqueue_style( 'srm_jqueryui_styles' );	

	}

		

    //scripts

    $srm_script_path = SRM_FRONT_END_PATH.'/js/scripts.js';

    $srm_jquery_ui_path = SRM_FRONT_END_PATH.'/js/jquery-ui-1.8.18.custom.min.js';

    wp_register_script( 'srmscript', $srm_script_path );

    wp_enqueue_script( 'srmscript' );

    if ( SRM_LOAD_JQUERYUI == 1 ){

		wp_register_script( 'srmjqueryui', $srm_jquery_ui_path );

		wp_enqueue_script( 'srmjqueryui' );

	}

}

    //frontend

    add_action( 'wp_enqueue_scripts', 'srm_frontend_stylesheet' );

    function srm_frontend_stylesheet(){

        $srm_stylesheet_path = SRM_FRONT_END_PATH.'/style.css';

        wp_register_style( 'srmstyles', $srm_stylesheet_path );

        wp_enqueue_style( 'srmstyles' );

		

		if ( SRM_LOAD_JQUERYUI == 1 ){

			$srm_jqueryui_stylesheet_path = SRM_FRONT_END_PATH.'/jqueryui-style.css';

			wp_register_style( 'srm_jqueryui_styles', $srm_jqueryui_stylesheet_path );

			wp_enqueue_style( 'srm_jqueryui_styles' );	

		}

		

        $srm_script_path = SRM_FRONT_END_PATH.'/js/scripts-frontend.js';

        wp_register_script( 'srmscript', $srm_script_path );

        wp_enqueue_script( 'srmscript' );

        

        if ( SRM_LOAD_JQUERY == 1 ){

            $srm_jquery_path='http://code.jquery.com/jquery-latest.min.js';

            wp_register_script( 'srmjquery', $srm_jquery_path );

            wp_enqueue_script( 'srmjquery' );

		}

        

		if ( SRM_LOAD_JQUERYUI == 1 ){

			$srm_jquery_ui_path = SRM_FRONT_END_PATH.'/js/jquery-ui-1.8.18.custom.min.js';

			wp_register_script( 'srmjqueryui', $srm_jquery_ui_path );

			wp_enqueue_script( 'srmjqueryui' );

		}

    }    

    

//main seminars page

function seminar_registration_manager(){

    global $start_date, $end_date, $q, $page_num;

    $seminars_count = SrmSeminars::get_seminars_count( $start_date, $end_date, $q );

    $Seminars = new SrmSeminars();   

    

    //query for the seminars

    $seminars_results = $Seminars->get_seminars( $start_date, $end_date, $q, $page_num );

    

    //include the view

    if ( file_exists( SRM_PLUGIN_PATH.'/views/srm-seminars.php' ) )

        include( 'views/srm-seminars.php' );

}



//settings page

function srm_settings(){

    $has_errors = false;

    $has_message = false;

    $msg = '';

    $error_msg = '';

    $Common = new SrmCommon();

    //if the settings have been updated

    if ( isset( $_POST['update_srm_settings'] ) ){

        $update_settings_arr = $Common->update_srm_settings( $_POST );

        if ( $update_settings_arr['has_errors'] ){

            $has_errors = true;

            $error_msg = $update_settings_arr['msg'];

		} else {

            $has_message = true;

            $msg = $update_settings_arr['msg'];

		}

	}

    

    if ( file_exists( SRM_PLUGIN_PATH.'/views/srm-settings.php' ) )

        include( 'views/srm-settings.php' );

}



//registrants page

function srm_registrants(){

    global $start_date, $end_date, $q, $page_num;

    $Registrants = new SrmRegistrants();

	

    //find out if we are narrowing it by a specific seminar

    $filter_by_seminar = false;

    if ( isset( $_GET['seminar_id'] ) && is_numeric( $_GET['seminar_id'] ) ):

        $filter_by_seminar = true;

        $seminar_id = (int) $_GET['seminar_id'];

    else:

        $seminar_id = null;

    endif;

    

    //get number of registrants

    $registrants_count = SrmRegistrants::get_registrants_count( $q, $seminar_id );

    

    //get the registrants

    $registrants_results = $Registrants->get_registrants( $q, $seminar_id, $page_num );



    if ( isset( $_GET['registrant_id'] ) ){

        $registrant_id = (int) $_GET['registrant_id'];

        $registrant_data = $Registrants->get_registrant( $registrant_id );

        foreach( $registrant_data as $key => $value ){

            $registrant_data[$key] = stripslashes( $value );

		}

        $additional_registrants = $Registrants->get_additional_registrants( $registrant_id );

        foreach( $additional_registrants['registrants_info'] as $key => $value ){

            if ( !is_array( $value ) ){

                $additional_registrants['registrants_info'][$key] = stripslashes( $value );

			}

	}

        if ( file_exists( SRM_PLUGIN_PATH.'/views/srm-registrants-view.php' ) ){

            include( 'views/srm-registrants-view.php' );

		}

	} else {

        if ( file_exists( SRM_PLUGIN_PATH.'/views/srm-registrants.php' ) ){

            include( 'views/srm-registrants.php' );

		}

	}

}



//coupon codes page

function srm_coupon_codes(){

    global $start_date, $end_date, $q, $page_num;

    $CouponCodes = new SrmCouponCodes();

	$coupon_codes_count = SrmCouponCodes::get_coupon_codes_count( $q );

    $coupon_codes_results = $CouponCodes->get_coupon_codes( $q, $page_num );

    if ( file_exists( SRM_PLUGIN_PATH.'/views/srm-coupon-codes.php' ) ){

        include( 'views/srm-coupon-codes.php' );

	}

}



//help/about page

function srm_about(){

    if ( file_exists( SRM_PLUGIN_PATH.'/views/srm-about.php' ) ){

        include( 'views/srm-about.php' );

	}

}





//add/edit seminar

function srm_seminar_add_edit(){

	$us_states = SrmCommon::get_states( 'US' );	

    $Seminars = new SrmSeminars();

	if ( file_exists( SRM_PLUGIN_PATH.'/views/srm-seminars-edit.php' ) ){

        //list of all fields of data for the seminar, and which are required

        $seminar_fields = SrmSeminars::get_seminar_fields();

        

        //array of actual data to prepopulate the form

        $seminar_data = array();

               

        //find out whether it is add or edit

        $is_edit_mode = false;

        $add_edit = 'add';

        if ( isset( $_GET['id'] ) && is_numeric( $_GET['id'] ) ){

            $id = (int) $_GET['id'];

            $is_edit_mode = true;

            $add_edit = 'edit';

            $seminar = new SrmSeminars();

            $seminar_data = $seminar->get_seminar( $id );

            foreach( $seminar_data as $seminar_field => $value ){

                $seminar_data[$seminar_field] = stripslashes( $value );

			}

		}

        

        //populate $seminar_data

        foreach ( $seminar_fields as $seminar_field => $value ){

            //seminar data that has been posted

            if ( isset( $_POST[$seminar_field] ) && !empty( $_POST[$seminar_field] ) ){

                $seminar_data[$seminar_field] = stripslashes( $_POST[$seminar_field] );

			} else {

                if ( ! $is_edit_mode ){

                    $seminar_data[$seminar_field] = '';

				}

			}

		}

        

        if ( isset( $_POST['add_edit_seminar'] ) ){

            $add_edit_result_arr = $Seminars->edit_seminar( $_POST, $add_edit, $id );

            if ( $add_edit_result_arr['success'] == 1 ){

                $has_message = true;

                $msg = $add_edit_result_arr['msg'];

			} else {

                $has_errors = true;

                $error_msg = $add_edit_result_arr['msg'];

			}

		}

        

        include( 'views/srm-seminars-edit.php' );

	}

}



//add/edit registrant

function srm_registrants_add_edit(){

    global $registration_success, $registration_msg;

    $Seminars = new SrmSeminars();

    $Registrants = new SrmRegistrants();

    $Register = new SrmRegister();

    $Coupons = new SrmCouponCodes();

    $seminar_chosen = false;

    $seminars_list = SrmSeminars::get_seminars( SRM_TODAY, SRM_NEXT_YEAR, null, 1, 100 );

    $seminar_fields = SrmSeminars::get_seminar_fields();

    $registration_fields = SrmRegister::get_registration_fields();

	$us_states = SrmCommon::get_states( 'US' );

	

    //see if they have chosen a seminar already

    if ( isset( $_REQUEST['seminar_id'] ) ){

        $seminar_id = (int) $_REQUEST['seminar_id'];

        $seminar_data = $Seminars->get_seminar( $seminar_id );

        if (!empty( $seminar_data ) ){

            $seminar_chosen = true;

            //strip slashes on data

            foreach( $seminar_data as $key => $value ){

                $seminar_data[$key] = stripslashes( $value );

			}

            

            //figure out the prices for each type

            $registrant_price = $seminar_data['registrant_price'];

			

			//spots left

			$max_attendees = $seminar_data['max_attendees'];

			$cur_attendees = SrmSeminars::get_seminar_attendees( $seminar_id );

			$spots_left = $max_attendees - $cur_attendees;

            

            //check and see whether any coupon code was used, and if it is valid, note the discount

            $coupon_code_submitted = false;

            if ( isset( $_REQUEST['coupon_code'] ) && ! empty( $_REQUEST['coupon_code'] ) ){

                $coupon_code_submitted = true;

                $coupon_code = $_REQUEST['coupon_code'];

                $coupon_code_valid = false;

                $coupon_code_arr = $Coupons->check_coupon_code( $coupon_code );

                if ( ! $coupon_code_arr['has_errors'] ){

                    $coupon_code_valid = true;

                    $registrant_price = $coupon_code_arr['return_data']['registrant_price'];

				}

                $coupon_code_msg = $coupon_code_arr['error_msg'];

			}

		}

	}

    

    if ( file_exists( SRM_PLUGIN_PATH.'/views/srm-registrants-edit.php' ) )

        include( 'views/srm-registrants-edit.php' );

}



add_shortcode( 'srm_register', 'srm_registrants_add_edit' );



//add/edit coupon

function srm_coupon_codes_add_edit(){

    global $q, $page_num;

    $coupon_code_fields = SrmCouponCodes::get_coupon_code_fields();

    //array of actual data to prepopulate the form

    $coupon_code_data = array();

           

    //find out whether it is add or edit

    $is_edit_mode = false;

    $add_edit = 'add';

    $CouponCodes = new SrmCouponCodes();

    if ( isset( $_GET['id'] ) && is_numeric( $_GET['id'] ) ){

        $id = (int) $_GET['id'];

        $is_edit_mode = true;

        $add_edit = 'edit';

        $coupon_code_data = $CouponCodes->get_coupon_code( $id );

        foreach( $coupon_code_data as $coupon_code_field => $value ){

            $coupon_code_data[$coupon_code_field] = stripslashes( $value );

		}

	}

    

    //populate coupon code data

    foreach ( $coupon_code_fields as $coupon_code_field => $value ){

        //coupon code data that has been posted

        if ( isset( $_POST[$coupon_code_field] ) && ! empty( $_POST[$coupon_code_field] ) ){

            $coupon_code_data[$coupon_code_field] = stripslashes( $_POST[$coupon_code_field] );

		} else {

            if ( ! $is_edit_mode ){

                $coupon_code_data[$coupon_code_field] = '';

			}

		}

	}

    

    if ( isset( $_POST['add_edit_coupon_code'] ) ){

        if ( isset( $_POST['active'] ) )

            $coupon_code_data['active']=$_POST['active'];

        $add_edit_result_arr = $CouponCodes->edit_coupon_code( $_POST, $add_edit, $id);

        if ( $add_edit_result_arr['success'] == 1 ){

            $has_message = true;

            $msg = $add_edit_result_arr['msg'];

		} else {

            $has_errors = true;

            $error_msg = $add_edit_result_arr['msg'];

		}

	}

    if ( file_exists( SRM_PLUGIN_PATH.'/views/srm-coupon-codes-edit.php' ) )

        include( 'views/srm-coupon-codes-edit.php' );

}





//actions

    //delete seminar

    if ( isset( $_GET['delete_seminar'] ) && is_numeric( $_GET['delete_seminar'] ) ){

        $Seminars = new SrmSeminars();

        $seminar_to_delete_id = (int) $_GET['delete_seminar'];

        $Seminars->delete_seminar( $seminar_to_delete_id );

        header( 'Location: '.get_bloginfo( 'url' ).'/wp-admin/admin.php?page='.$_GET['page'].'&start_date='.$start_date.'&end_date='.$end_date.'&q='.$q.'&page_num='.$page_num );

	}

    

    //delete registrant

    if ( isset( $_GET['delete_registrant'] ) && is_numeric( $_GET['delete_registrant'] ) ){

        $Registrants = new SrmRegistrants();

        $registrant_to_delete_id = (int) $_GET['delete_registrant'];

        $Registrants->delete_registrant( $registrant_to_delete_id );

        header( 'Location: '.get_bloginfo( 'url' ).'/wp-admin/admin.php?page='.$_GET['page'].'&q='.$q.'&page_num='.$page_num.'&seminar_id='.$seminar_id );

	}

    

    //delete coupon

    if ( isset( $_GET['delete_coupon'] ) && is_numeric( $_GET['delete_coupon'] ) ){

        $CouponCodes = new SrmCouponCodes();

        $coupon_code_to_delete_id = (int) $_GET['delete_coupon'];

        $CouponCodes->delete_coupon_code( $coupon_code_to_delete_id );

        header( 'Location: '.get_bloginfo( 'url' ).'/wp-admin/admin.php?page='.$_GET['page'].'&q='.$q.'&page_num='.$page_num );

	}

    

    //update registrant paid status

    if ( isset( $_POST['update_registrant_paid_status'] ) && isset( $_POST['registrant_id'] ) && is_numeric( $_POST['registrant_id'] ) && isset( $_POST['paid_status'] ) ){

        $registrant_id = (int) $_POST['registrant_id'];

        $paid_status = (int) $_POST['paid_status'];

        $Registrants = new SrmRegistrants();

        echo $Registrants->update_registrant_paid_status( $registrant_id, $paid_status );

        exit();

	}

    

    //update coupon active status

    if ( isset( $_POST['update_coupon_active'] ) && isset( $_POST['coupon_id'] ) && is_numeric( $_POST['coupon_id'] ) && isset( $_POST['coupon_active'] ) ){

        $coupon_id = (int) $_POST['coupon_id'];

        $active_status = (int) $_POST['coupon_active'];

        $CouponCodes = new SrmCouponCodes();

        echo $CouponCodes->update_coupon_active_status( $coupon_id, $active_status );

        exit();

	}

    

    //register a user

    if ( isset( $_POST['submit_registration'] ) ){

        $Register = new SrmRegister();

        $registration_response = $Register->register( $_POST);

        $registration_msg = $registration_response['msg'];

        $registration_success = $registration_response['success'];

        if ( $registration_success == 1 ){

            header( 'Location: '.SRM_DEFAULT_THANKS_PAGE );

		}

	}

