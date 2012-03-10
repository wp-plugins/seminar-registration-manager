<?php
    class SrmCommon{
        
        public static function render_filter($include_search=true, $include_dates=false){
            $return='
                <div class="srm-search">
                    <form action="" method="get">
                        <h3>Filter Results</h3>
            ';
            if ($include_dates):
                $return.='
                    <span class="section">Start Date: <input type="text" name="start_date" id="start_date" value="'.$_REQUEST['start_date'].'" class="srm-date" /></span>
                    <span class="section">End Date: <input type="text" name="end_date" id="end_date" value="'.$_REQUEST['end_date'].'" class="srm-date" /></span>                    
                ';
            endif;
            if ($include_search):
                $return.='
                    <span class="section">Text: <input type="text" name="q" id="q" value="'.$_REQUEST['q'].'" /></span>
                ';
            endif;
            $return.='
                        <input type="submit" name="go" value="Filter Results" />
                        <input type="hidden" name="page" value="'.$_REQUEST['page'].'" />
                        <input type="hidden" name="page_num" value="'.$_REQUEST['page_num'].'" />
                    </form>
                </div>
            ';
            
            if (!empty($_REQUEST['start_date']) || !empty($_REQUEST['end_date']) || !empty($_REQUEST['q'])):
                $return.='<strong>Filtering By: </strong> ';
            endif;
            if (!empty($_REQUEST['start_date'])):
                $return.=' &nbsp;&bull;Start Date ('.$_REQUEST['start_date'].') ';
            endif;
            if (!empty($_REQUEST['end_date'])):
                $return.=' &nbsp;&bull;End Date ('.$_REQUEST['end_date'].') ';
            endif;
            if (!empty($_REQUEST['q'])):
                $return.=' &nbsp;&bull;Text Query ('.$_REQUEST['q'].') ';
            endif;
            return $return;
        }
        
        public static function render_pagination($total, $current_page){
            $total_pages=ceil(($total/SRM_PER_PAGE));
            
            //figure out the URL to link to
            $url_string='http://'.$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME'].'?';
            $i=0;
            foreach($_GET as $key=>$value):
                if ($key!='page_num'):
                    if ($i!=0):
                        $url_string.='&';
                    endif;
                    $url_string.="$key=$value";
                    $i++;
                endif;
            endforeach;
            
            $return='
                <div class="srm-pagination">
                    Go to page: 
            ';
            for($i=1;$i<=$total_pages; $i++):
                if ($i!=$current_page):
                    $return.='<span class="srm-page"><a href="'.$url_string.'&page_num='.$i.'">'.$i.'</a></span>';
                else:
                    $return.='<span class="srm-page">'.$i.'</span>';
                endif;
            endfor;
            
            $return.='    
                </div>
            ';
            return $return;
        }
        
        //get the file extension
        public static function getExtension($str) {
            $i = strrpos($str,".");
            if (!$i):
                return "";
            endif;
            $l = strlen($str) - $i;
            $ext = substr($str,$i+1,$l);
            return $ext;
        }
        
        //get U.S. states
        public static function get_states($country='US'){
            switch($country):
                //US states
                case 'US':
                    $return_arr = array(
                        'AL'=>"Alabama",  
                        'AK'=>"Alaska",  
                        'AZ'=>"Arizona",  
                        'AR'=>"Arkansas",  
                        'CA'=>"California",  
                        'CO'=>"Colorado",  
                        'CT'=>"Connecticut",  
                        'DE'=>"Delaware",  
                        'DC'=>"District Of Columbia",  
                        'FL'=>"Florida",  
                        'GA'=>"Georgia",  
                        'HI'=>"Hawaii",  
                        'ID'=>"Idaho",  
                        'IL'=>"Illinois",  
                        'IN'=>"Indiana",  
                        'IA'=>"Iowa",  
                        'KS'=>"Kansas",  
                        'KY'=>"Kentucky",  
                        'LA'=>"Louisiana",  
                        'ME'=>"Maine",  
                        'MD'=>"Maryland",  
                        'MA'=>"Massachusetts",  
                        'MI'=>"Michigan",  
                        'MN'=>"Minnesota",  
                        'MS'=>"Mississippi",  
                        'MO'=>"Missouri",  
                        'MT'=>"Montana",
                        'NE'=>"Nebraska",
                        'NV'=>"Nevada",
                        'NH'=>"New Hampshire",
                        'NJ'=>"New Jersey",
                        'NM'=>"New Mexico",
                        'NY'=>"New York",
                        'NC'=>"North Carolina",
                        'ND'=>"North Dakota",
                        'OH'=>"Ohio",  
                        'OK'=>"Oklahoma",  
                        'OR'=>"Oregon",  
                        'PA'=>"Pennsylvania",  
                        'RI'=>"Rhode Island",  
                        'SC'=>"South Carolina",  
                        'SD'=>"South Dakota",
                        'TN'=>"Tennessee",  
                        'TX'=>"Texas",  
                        'UT'=>"Utah",  
                        'VT'=>"Vermont",  
                        'VA'=>"Virginia",  
                        'WA'=>"Washington",  
                        'WV'=>"West Virginia",  
                        'WI'=>"Wisconsin",  
                        'WY'=>"Wyoming");
                break;
                default:
                    $return_arr=array(
                        'N/A'=>'None Available'
                    );
                break;
            endswitch;
            
            return $return_arr;
        }
        
        public static function get_plugin_settings(){
            global $wpdb;
            $results = $wpdb->get_results( "SELECT * FROM ".SRM_SETTINGS_TABLE, ARRAY_A );
            return $results;
        }
        
        public function update_srm_settings($post_vars){
            global $wpdb;
            $plugin_settings = SrmCommon::get_plugin_settings();
            $return_arr = array( 'has_errors' => false, 'msg' => 'Settings were updated successfully!' );
            foreach ( $plugin_settings as $plugin_setting ):
                if ( isset( $post_vars[$plugin_setting['key']] )):
                    $wpdb->update(
                        SRM_SETTINGS_TABLE,
                        array(
                            'value' => $post_vars[$plugin_setting['key']]
                        ),
                        array(
                            'key' => $plugin_setting['key']
                        ),
                        array(
                            '%s'
                        ),
                        array(
                            '%s'
                        )
                    );
                endif;
            endforeach;
            
            return $return_arr;
        }
    }