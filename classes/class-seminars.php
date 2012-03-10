<?php
    class SrmSeminars{
        
        //get all seminars
        public function get_seminars($start_date=SRM_TODAY, $end_date=SRM_NEXT_YEAR, $q=null, $page=1, $limit=SRM_PER_PAGE){
            global $wpdb;
            
            //figure out the offset
            $offset=($page-1)*$limit;
            
            if (!empty($q)):
                $sql="SELECT * FROM ".SRM_SEMINARS_TABLE." WHERE start_date >= '$start_date' AND end_date <= '$end_date' AND (title LIKE '%$q%' OR description LIKE '%$q%' OR location LIKE '%$q%' OR city LIKE '%$q%') ORDER BY start_date ASC LIMIT ".$limit." OFFSET $offset";
            else:
                $sql="SELECT * FROM ".SRM_SEMINARS_TABLE." WHERE start_date >= '$start_date' AND end_date <= '$end_date' ORDER BY start_date ASC LIMIT ".$limit." OFFSET $offset";
            endif;
            $seminars_results=$wpdb->get_results($sql, ARRAY_A);
            return $seminars_results;
        }
        
        //get seminar details
        public function get_seminar($id){
            global $wpdb;
            $id=(int) $id;
            $sql="SELECT * FROM ".SRM_SEMINARS_TABLE." WHERE id=$id";
            $seminar_results=$wpdb->get_row($sql, ARRAY_A);
            return $seminar_results;
        }
        
        
        //add/edit a seminar
        public function edit_seminar($post_fields, $add_edit='add', $id=0){
            global $wpdb,$srm_image_allowable_filetypes;
            $seminar_fields=SrmSeminars::get_seminar_fields();
            $Seminars=new SrmSeminars();
            $has_errors=false;
            $return_arr=array(
                'success'=>1,
                'msg'=>''
            );
            
            //if it is editing a current one, let's get the data for this seminar that is currently available
            if ($add_edit=='edit'):
                $seminar_data=$Seminars->get_seminar($post_fields['id']);
            endif;
            
            //make sure all required fields are there
            foreach($seminar_fields as $seminar_field=>$required):
                if ($required==1 && (!isset($post_fields[$seminar_field]) || empty($post_fields[$seminar_field]))):
                    $has_errors=true;
                    $return_arr['success']=0;
                    $return_arr['msg']='You are missing required fields.';
                else:
                    //sanitize it
                    $post_fields[$seminar_field]=mysql_real_escape_string(stripslashes($post_fields[$seminar_field]));
                endif;
            endforeach;
            
            //proceed if all required fields are there
            if (!$has_errors):
                extract($post_fields);
                //handle the image
                if (!empty($_FILES['image']['tmp_name'])):
                    $extension=strtolower(SrmCommon::getExtension($_FILES['image']['name']));
                    //check the file type
                    if (!in_array($extension, $srm_image_allowable_filetypes)):
                        print_r($srm_image_allowable_filetypes);
                        $return_arr['success']=0;
                        $return_arr['msg']='Your image file type is not valid.';
                        $has_errors=true;
                    else:
                        //check the file size
                        $file_size=filesize($_FILES['image']['tmp_name']);
                        if ($file_size > SRM_IMAGE_MAX_SIZE_KB*1024):
                            $return_arr['success']=0;
                            $return_arr['msg']="Your image exceeds the maximum file size of ". SRM_IMAGE_MAX_SIZE_KB/1000 ." megabytes.";
                        else:
                            $new_file_name=str_ireplace('.'.$extension, '', $_FILES['image']['name']).time().rand(1,9999).'.'.$extension;
                            //save the image to the appropriate locations
                            
                            //copy to appropriate location
                            $file_path=SRM_IMAGE_PATH.'/seminars';
                            $complete_path=$file_path.'/'.$new_file_name;
                            $copied=copy($_FILES['image']['tmp_name'], $complete_path);
                            
                            if (!$copied):
                                $return_arr['success']=0;
                                $return_arr['msg']="There was a problem uploading your seminar image: ".error_get_last();
                            else:    
                                $image=$new_file_name;
                                //if it is an edit, delete the previous one
                                if ($add_edit=='edit'):
                                    $previous_img_path=SRM_IMAGE_PATH.'/seminars/'.$seminar_data['image'];
                                    if (!empty($previous_img_path) && file_exists($previous_img_path) && !is_dir($previous_img_path)):
                                        unlink($previous_img_path);
                                    endif;
                                endif;
                                
                            endif;
                            
                            //handle the resizing
                            $simple_img=new SimpleImage();
                            $simple_img->load($complete_path);
                            $simple_img->resizeToWidth(SRM_IMAGE_WIDTH);
                            $simple_img->save($complete_path);
                        endif;
                    endif;
                else:
                    if ($add_edit=='edit'):
                        $image=$seminar_data['image'];
                    endif;
                endif;
                
                //SQL
                if ($add_edit=='add'):
                    $wpdb->query(
                        $wpdb->prepare(
                            "INSERT INTO ".SRM_SEMINARS_TABLE."(title, description, location, address1, address2, city, state, zip, phone, image, registrant_price, room_rate, start_date, end_date, start_time, end_time, max_attendees) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %d)",
                            $title,
                            $description,
                            $location,
                            $address1,
                            $address2,
                            $city,
                            $state,
                            $zip,
                            $phone,
                            $image,
                            $registrant_price,
                            $room_rate,
                            $start_date,
                            $end_date,
                            $start_time,
                            $end_time,
                            $max_attendees
                        )
                    );
                    $wpdb->print_error(); 
                else:
                    $wpdb->query(
                        $wpdb->prepare(
                            "UPDATE ".SRM_SEMINARS_TABLE." SET title=%s, description=%s, location=%s, address1=%s, address2=%s, city=%s, state=%s, zip=%s, phone=%s, image=%s, registrant_price=%s, room_rate=%s, start_date=%s, end_date=%s, start_time=%s, end_time=%s, max_attendees=%d WHERE id=%d",
                            $title,
                            $description,
                            $location,
                            $address1,
                            $address2,
                            $city,
                            $state,
                            $zip,
                            $phone,
                            $image,
                            $registrant_price,
                            $room_rate,
                            $start_date,
                            $end_date,
                            $start_time,
                            $end_time,
                            $max_attendees,
                            $id
                        )
                    );
                    $return_arr['msg']='This seminar has been updated successfully!';
                endif;
                
                if (!$has_errors):
                    $return_arr['success']=1;
                endif;
            
            endif;
            
            return $return_arr;
        }
        
        //delete a seminar
        public function delete_seminar($seminar_id){
            global $wpdb;
            $seminar_id=(int) $seminar_id;
            $wpdb->query(
                $wpdb->prepare(
                    "DELETE FROM ".SRM_SEMINARS_TABLE." WHERE id=%d",
                    $seminar_id
                )
            );
            return;
        }
        
        //get total number of seminars
        public static function get_seminars_count($start_date=SRM_TODAY, $end_date=SRM_NEXT_YEAR, $q=null){
            global $wpdb;
            $seminars_count=0;
            if (!empty($q)):
                $sql="SELECT COUNT(id) AS seminars_count FROM ".SRM_SEMINARS_TABLE." WHERE start_date >= '$start_date' AND end_date <= '$end_date' AND (title LIKE '%$q%' OR description LIKE '%$q%' OR location LIKE '%$q%' OR city LIKE '%$q%')";
            else:
                $sql="SELECT COUNT(id) AS seminars_count FROM ".SRM_SEMINARS_TABLE." WHERE start_date >= '$start_date' AND end_date <= '$end_date'";
            endif;
            $result=$wpdb->get_results($sql, ARRAY_A);
            foreach($result as $row):
                $seminars_count=$row['seminars_count'];
            endforeach;
            return $seminars_count;
        }
        
        //get total number of attendees
        public static function get_seminar_attendees($seminar_id=0){
            global $wpdb;
            $attendees_count=0;
            $sql="SELECT id FROM ".SRM_REGISTRANTS_TABLE." WHERE seminar_id=$seminar_id";
            $result=$wpdb->get_results($sql, ARRAY_A);
            foreach($result as $row):
                $attendees_count++;
                $registrant_id=$row['id'];
                $additional_attendee_sql="SELECT COUNT(id) AS additional_attendees FROM ".SRM_ADDITIONAL_REGISTRANTS_TABLE." WHERE registrant_id=$registrant_id";
                $additional_attendee_results=$wpdb->get_results($additional_attendee_sql, ARRAY_A);
                foreach($additional_attendee_results as $additional_attendee_row):
                    $attendees_count+=$additional_attendee_row['additional_attendees'];
                endforeach;
            endforeach;
            return $attendees_count;
        }
        
        //get all fields for the seminar, and whether or not they are required
        public static function get_seminar_fields(){
            $seminar_fields=array(
                'title'=>1,
                'description'=>1,
                'location'=>1,
                'address1'=>1,
                'address2'=>0,
                'city'=>1,
                'state'=>1,
                'zip'=>1,
                'phone'=>0,
                'image'=>0,
                'registrant_price'=>1,
                'room_rate'=>0,
                'start_date'=>1,
                'end_date'=>1,
                'start_time'=>1,
                'end_time'=>1,
                'max_attendees'=>1
            );
            return $seminar_fields;
        }
        
    }