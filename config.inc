<?php
//general
define( 'SRM_PLUGIN_DIR', 'seminar-registration-manager' );
define( 'SRM_PLUGIN_PATH', WP_PLUGIN_DIR.'/'.SRM_PLUGIN_DIR );
define( 'SRM_FRONT_END_PATH', get_bloginfo( 'url' ).'/wp-content/plugins/'.SRM_PLUGIN_DIR );

//database tables
define( 'SRM_SETTINGS_TABLE', $table_prefix.'srm_settings' );
define( 'SRM_REGISTRANTS_TABLE', $table_prefix.'srm_registrants' );
define( 'SRM_REGISTRANT_TYPES_TABLE', $table_prefix.'srm_registrant_types' );
define( 'SRM_SEMINARS_TABLE', $table_prefix.'srm_seminars' );
define( 'SRM_ADDITIONAL_REGISTRANTS_TABLE', $table_prefix.'srm_additional_registrants' );
define( 'SRM_REGISTRANT_BILLING_TABLE', $table_prefix.'srm_registrant_billing_info' );
define( 'SRM_COUPON_CODE_TABLE', $table_prefix.'srm_coupon_codes' );

//seminar images
define( 'SRM_IMAGE_PATH', SRM_PLUGIN_PATH.'/images' );
define( 'SRM_IMAGE_PATH_FRONTEND', get_bloginfo( 'url' ).'/wp-content/plugins/'.SRM_PLUGIN_DIR.'/images' );
$srm_image_allowable_filetypes = array( 'jpg', 'jpeg', 'gif', 'png' );

//registration
define( 'SRM_MAX_ADDITIONAL_REGISTRANTS', 10 );

//misc
define( 'SRM_TODAY', date( 'Y-m-d' ) );
define( 'SRM_NEXT_YEAR', date( 'Y-m-d', time() + ( 60 * 60 * 24 * 365 ) ) );

//global variables to reuse on all grid pages (for dates, pagination, etc.)
$start_date = SRM_TODAY;
if ( !empty( $_REQUEST['start_date'] ) ):
    $start_date = mysql_real_escape_string( $_REQUEST['start_date'] );
else:
    $_REQUEST['start_date'] = $start_date;
endif;
$end_date = SRM_NEXT_YEAR;
if ( !empty( $_REQUEST['end_date'] ) ):
    $end_date = mysql_real_escape_string( $_REQUEST['end_date'] );
else:
    $_REQUEST['end_date'] = $end_date;
endif;
$q = null;
if ( !empty( $_REQUEST['q'] ) ):
    $q = mysql_real_escape_string( $_REQUEST['q'] );
else:
    $_REQUEST['q'] = $q;
endif;
$page_num = 1;
if ( !empty( $_REQUEST['page_num'] ) ):
    $page_num = (int) round( $_REQUEST['page_num'] );
endif;
$seminar_id = null;
if ( !empty( $_REQUEST['seminar_id'] ) ):
    $seminar_id = (int) round( $_REQUEST['seminar_id'] );
endif;
