<?php
    class SrmCouponCodes{
        //get all coupon codes
        public function get_coupon_codes($q=null, $page=1){
            global $wpdb;
            $q=mysql_real_escape_string($q);
            $limit=SRM_PER_PAGE;
            $offset=($page-1)*$limit;
            
            if (!empty($q)):
                $sql="SELECT * FROM ".SRM_COUPON_CODE_TABLE." WHERE coupon_name LIKE '%$q%' OR coupon_code LIKE '%$q%' ORDER BY coupon_name ASC LIMIT $limit OFFSET $offset";
            else:
                $sql="SELECT * FROM ".SRM_COUPON_CODE_TABLE." ORDER BY coupon_name ASC LIMIT $limit OFFSET $offset";
            endif;
            $coupon_codes_results=$wpdb->get_results($sql, ARRAY_A);
            return $coupon_codes_results;
        }
        
        //get coupon code data
        public function get_coupon_code($id){
            global $wpdb;
            $id=(int) $id;
            $sql="SELECT * FROM ".SRM_COUPON_CODE_TABLE." WHERE id=$id";
            $coupon_code_result=$wpdb->get_row($sql, ARRAY_A);
            return $coupon_code_result;
        }
        
        //check a coupon code
        public function check_coupon_code($coupon_code=''){
            global $wpdb;
            $return_arr=array(
                'has_errors'=>true,
                'error_msg'=>'Your coupon code is invalid or is no longer active. <a href="#" onclick="window.history.back()">Try Again</a>',
                'return_data'=>array(
                    'id'=>0,
                    'coupon_name'=>'',
                    'coupon_code'=>'',
                    'registrant_price'=>0.00,
                    'active'=>0
                )
            );
            $coupon_code_row=$wpdb->get_row(
                $wpdb->prepare(
                    "SELECT * FROM ".SRM_COUPON_CODE_TABLE." WHERE coupon_code = %s AND active=1",
                    $coupon_code
                ), ARRAY_A
            );
            if ($coupon_code_row):
                $return_arr['has_errors']=false;
                $return_arr['error_msg']='Coupon code <i>'.strtoupper($coupon_code).'</i> valid!';
                $return_arr['return_data']['id']=$coupon_code_row['id'];
                $return_arr['return_data']['coupon_name']=$coupon_code_row['coupon_name'];
                $return_arr['return_data']['coupon_code']=$coupon_code_row['coupon_code'];
                $return_arr['return_data']['registrant_price']=$coupon_code_row['registrant_price'];
                $return_arr['return_data']['active']=$coupon_code_row['active'];
            endif;
            
            return $return_arr;
        }
        
        //add/edit coupon code
        public function edit_coupon_code($post_fields, $add_edit='add', $id=0){
            global $wpdb;
            $coupon_code_fields=SrmCouponCodes::get_coupon_code_fields();
            $has_errors=false;
            $return_arr=array(
                'success'=>1,
                'msg'=>''
            );
            
            //if it is editing a current one, let's get the data for this seminar that is currently available
            if ($add_edit=='edit'):
                $coupon_code_data=$this->get_coupon_code($id);
            endif;
            
            //make sure all required fields are there
            foreach($coupon_code_fields as $coupon_code_field=>$required):
                if ($required==1 && (!isset($post_fields[$coupon_code_field]) || empty($post_fields[$coupon_code_field])) && $post_fields[$coupon_code_field]!=0):
                    $has_errors=true;
                    $return_arr['success']=0;
                    $return_arr['msg']='You are missing required fields.';
                else:
                    //sanitize it
                    $post_fields[$coupon_code_field]=mysql_real_escape_string(stripslashes($post_fields[$coupon_code_field]));
                endif;
            endforeach;
            
            //proceed if all required fields are there
            if (!$has_errors):
                extract($post_fields);
                
                //SQL
                if ($add_edit=='add'):
                    $wpdb->query(
                        $wpdb->prepare(
                            "INSERT INTO ".SRM_COUPON_CODE_TABLE."(coupon_name, coupon_code, registrant_price, active) VALUES (%s, %s, %s, %d)",
                            $coupon_name,
                            $coupon_code,
                            $registrant_price,
                            $active
                        )
                    );
                    $return_arr['msg']='This coupon code has been added successfully!';
                else:
                    $wpdb->query(
                        $wpdb->prepare(
                            "UPDATE ".SRM_COUPON_CODE_TABLE." SET coupon_name=%s, coupon_code=%s, registrant_price=%s, active=%d WHERE id=%d",
                            $coupon_name,
                            $coupon_code,
                            $registrant_price,
                            $active,
                            $id
                        )
                    );
                    $return_arr['msg']='This coupon code has been updated successfully!';
                endif;
                
                if (!$has_errors):
                    $return_arr['success']=1;
                endif;
            
            endif;
            
            return $return_arr;
        }
        
        //delete coupon code
        public function delete_coupon_code($coupon_code_id){
            global $wpdb;
            $coupon_code_id=(int) $coupon_code_id;
            $wpdb->query(
                $wpdb->prepare(
                    "DELETE FROM ".SRM_COUPON_CODE_TABLE." WHERE id=%d",
                    $coupon_code_id
                )
            );
            return;
        }
        
        //get a count of coupon codes in the given view
        public static function get_coupon_codes_count($q=null){
            global $wpdb;
            $q=mysql_real_escape_string($q);
            if (!empty($q)):
                $sql="SELECT COUNT(id) AS coupon_codes_count FROM ".SRM_COUPON_CODE_TABLE." WHERE coupon_name LIKE '%$q%' OR coupon_code LIKE '%$q%' ORDER BY coupon_name ASC";
            else:
                $sql="SELECT COUNT(id) AS coupon_codes_count FROM ".SRM_COUPON_CODE_TABLE." ORDER BY coupon_name ASC";
            endif;
            
            $coupon_codes_count_arr=$wpdb->get_row($sql, ARRAY_A);
            $coupon_codes_count=$coupon_codes_count_arr['coupon_codes_count'];
            return $coupon_codes_count;
        }
        
        //update active status of a coupon code
        public function update_coupon_active_status($coupon_id, $active_status){
            global $wpdb;
            $coupon_id=(int) $coupon_id;
            $active_status=(int) $active_status;
            if ($wpdb->update(SRM_COUPON_CODE_TABLE, array('active'=>$active_status), array('id'=>$coupon_id))):
                return 1;
            else:
                return 0;
            endif;
        }
        
        //return all coupon code fields
        public static function get_coupon_code_fields(){
            $coupon_code_fields=array(
                'coupon_name'=>1,
                'coupon_code'=>1,
                'registrant_price'=>1,
                'active'=>1
            );
            
            return $coupon_code_fields;
        }
    }