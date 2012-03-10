<?php
    class SrmRegistrants{
        //get data for all registrants within filter range
        public function get_registrants($q=null, $seminar_id=null, $page=1){
            global $wpdb;
            $q=mysql_real_escape_string($q);
            $seminar_id=(int) $seminar_id;
            $limit=SRM_PER_PAGE;
            $offset=($page-1)*$limit;
            
            if (!empty($q)):
                $where_clause=" AND CONCAT(fname, ' ', mname, ' ', lname) LIKE '%$q%' OR email LIKE '%$q%'";
            endif;
            if (!empty($seminar_id)):
                $sql="SELECT r.*, b.billing_name, b.address1 AS billing_address1, b.address2 AS billing_address2, b.city AS billing_city, b.state AS billing_state, b.zip AS billing_zip, b.card_num, b.card_type, b.card_exp_month, b.card_exp_year FROM ".SRM_REGISTRANTS_TABLE." r LEFT JOIN ".SRM_REGISTRANT_BILLING_TABLE." b ON r.id=b.registrant_id WHERE seminar_id=$seminar_id $where_clause ORDER BY r.lname ASC, r.fname ASC LIMIT $limit OFFSET $offset";
            else:
                $sql="SELECT r.*, b.billing_name, b.address1 AS billing_address1, b.address2 AS billing_address2, b.city AS billing_city, b.state AS billing_state, b.zip AS billing_zip, b.card_num, b.card_type, b.card_exp_month, b.card_exp_year FROM ".SRM_REGISTRANTS_TABLE." r LEFT JOIN ".SRM_REGISTRANT_BILLING_TABLE." b ON r.id=b.registrant_id WHERE seminar_id <> 0 $where_clause ORDER BY r.lname ASC, r.fname ASC LIMIT $limit OFFSET $offset";
            endif;
            
            $registrants_results=$wpdb->get_results($sql, ARRAY_A);
            return $registrants_results;
        }
        
        //get data for an individual registrant
        public function get_registrant($registrant_id){
            global $wpdb;
            $registrant_id=(int) $registrant_id;
            $sql="SELECT r.*, b.* FROM ".SRM_REGISTRANTS_TABLE." r LEFT JOIN ".SRM_REGISTRANT_BILLING_TABLE." b ON r.id=b.registrant_id WHERE r.id=$registrant_id";
            $results=$wpdb->get_row($sql, ARRAY_A);
            return $results;
        }
        
        //get a count of all registrants within the filter range
        public static function get_registrants_count($q=null, $seminar_id=null){
            global $wpdb;
            $q=mysql_real_escape_string($q);
            $seminar_id=(int) $seminar_id;
            if (!empty($q)):
                $where_clause=" AND CONCAT(fname, ' ', mname, ' ', lname) LIKE '%$q%' OR email LIKE '%$q%'";
            endif;
            if (!empty($seminar_id)):
                $sql="SELECT COUNT(id) AS registrants_count FROM ".SRM_REGISTRANTS_TABLE." WHERE seminar_id=$seminar_id $where_clause";
            else:
                $sql="SELECT COUNT(id) AS registrants_count FROM ".SRM_REGISTRANTS_TABLE." WHERE seminar_id <> 0 $where_clause";
            endif;
            
            $registrants_count_arr=$wpdb->get_row($sql, ARRAY_A);
            $registrants_count=$registrants_count_arr['registrants_count'];
            return $registrants_count;
        }
        
        //get all additional registrants for a registrant
        public function get_additional_registrants($registrant_id, $orderby='lname, fname', $order='ASC'){
            global $wpdb;
            $registrant_id=(int) $registrant_id;
            $return_arr=array(
                'num_registrants'=>0,
                'registrants_info'=>array()
            );
            
            //query the totals
            $additional_registrants_count_arr=$wpdb->get_row("SELECT COUNT(id) AS total_registrants FROM ".SRM_ADDITIONAL_REGISTRANTS_TABLE." WHERE registrant_id=$registrant_id", ARRAY_A);
            $additional_registrants=$additional_registrants_count_arr['total_registrants'];
            
            $return_arr['num_registrants']=$additional_registrants;
            
            //query the rows of information, and stuff them in the array
            $additional_registrants_arr=$wpdb->get_results("SELECT r.* FROM ".SRM_ADDITIONAL_REGISTRANTS_TABLE." r  WHERE registrant_id=$registrant_id ORDER BY $orderby $order", ARRAY_A);
            foreach($additional_registrants_arr as $additional_registrants_arr_row):
                $return_arr['registrants_info'][]=$additional_registrants_arr_row;
            endforeach;
            
            return $return_arr;
        }
        
        //delete a registrant
        public function delete_registrant($registrant_id){
            global $wpdb;
            $registrant_id=(int) $registrant_id;
            $wpdb->query(
                $wpdb->prepare(
                    "DELETE FROM ".SRM_REGISTRANTS_TABLE." WHERE id=%d",
                    $registrant_id
                )
            );
            
            //delete additional registrants attached to this one
            $wpdb->query(
                $wpdb->prepare(
                    "DELETE FROM ".SRM_ADDITIONAL_REGISTRANTS_TABLE." WHERE registrant_id=%d",
                    $registrant_id
                )
            );
            return;
        }
        
        //update registrant paid status
        public function update_registrant_paid_status($registrant_id, $value){
            global $wpdb;
            $registrant_id=(int) $registrant_id;
            $value=(int) $value;
            if ($wpdb->update(SRM_REGISTRANTS_TABLE, array('paid'=>$value), array('id'=>$registrant_id))):
                return 1;
            else:
                return 0;
            endif;
        }
    }