<?php
    class SrmRegister{
        //register for a seminar
        public function register($post_fields){
            global $wpdb;
            $has_errors=false;
            $return_arr=array(
                'success'=>1,
                'msg'=>'Missing required fields: '
            );
            
            //check required fields
            $registration_fields=SrmRegister::get_registration_fields();
            foreach ($registration_fields as $field=>$required):
                if ($required==1 && !isset($post_fields[$field])):
                    $has_errors=true;
                    $return_arr['success']=0;
                    $return_arr['msg']=' &bull;'.$field.'&nbsp;';
                endif;
            endforeach;
            
            if (!$has_errors):
                extract($post_fields);
                
                //figure out the registrant amount
                $Seminars=new SrmSeminars();
                $seminar_data=$Seminars->get_seminar($seminar_id);
                $registrant_price=$seminar_data['registrant_price'];
                
                //make sure there are enough open spots!
                $max_attendees=$seminar_data['max_attendees'];
                $cur_attendees=SrmSeminars::get_seminar_attendees($seminar_id);
                $spots_left=$max_attendees-$cur_attendees;
                $attempted_attendees_num=1+$additional_registrants;
                if ($attempted_attendees_num > $spots_left):
                    $has_errors=true;
                    $return_arr['success']=0;
                    $return_arr['msg']="You are attempting to register $attempted_attendees_num attendees for this seminar, however there are only $spots_left spots remaining.";
                else:
                    //find out if they are eligible for a discount
                    if (!empty($coupon_code)):
                        $CouponCodes=new SrmCouponCodes();
                        $coupon_code_arr=$CouponCodes->check_coupon_code($coupon_code);
                        if (!$coupon_code_arr['has_errors']):
                            $doctor_price=$coupon_code_arr['return_data']['registrant_price'];
                            $coupon_id=$coupon_code_arr['return_data']['id'];
                        endif;
                    endif;
                
                    //figure out if any additional registrants need to be added to the total
                    $total_amount=$doctor_price;
                    $total_amount+=($additional_doctors*$registrant_price);
                                    
                    //split billing name into either first, middle, last, or first and last
                    $billing_name_arr=explode(' ', $billing_name);
                    if (count($billing_name_arr)==2):
                        $billing_fname=$billing_name_arr[0];
                        $billing_lname=$billing_name_arr[1];
                    elseif (count($billing_name_arr)==3):
                        $billing_fname=$billing_name_arr[0].' '.$billing_name_arr[1];
                        $billing_lname=$billing_name_arr[2];
                    else:
                        $billing_fname=$billing_name_arr[0];
                        $billing_lname=$billing_name;
                    endif;
                    $payment_response=array('success'=>0, 'msg'=>''); //THIS WILL BE USED LATER FOR SWITCHING GATEWAYS
                    
                    //payment gateway
                    require_once( SRM_PLUGIN_PATH.'/payment_modules/'.SRM_PAYMENT_GATEWAY.'/index.php' );
                    
					if ($payment_response['success']==1):
                        $return_arr['success']=1;
                        
                        //insert registrant info
                        $wpdb->query(
                            $wpdb->prepare(
                                "INSERT INTO ".SRM_REGISTRANTS_TABLE."(seminar_id, coupon_id, fname, mname, lname, phone, fax, email, paid) VALUES (%d, %d, %s, %s, %s, %s, %s, %s, 1)",
                                $seminar_id,
                                $coupon_id,
                                $fname,
                                $mname,
                                $lname,
                                $phone,
                                $fax,
                                $email
                            )
                        );
                        $registrant_id = $wpdb->insert_id;
                        
                        //insert billing info
                        $wpdb->query(
                            $wpdb->prepare(
                                "INSERT INTO ".SRM_REGISTRANT_BILLING_TABLE."(registrant_id, billing_name, address1, address2, city, state, zip, card_num, card_type, card_exp_month, card_exp_year) VALUES (%d, %s, %s, %s, %s, %s, %s, %s, %s, %d, %d)",
                                $registrant_id,
                                $billing_name,
                                $address1,
                                $address2,
                                $city,
                                $state,
                                $zip,
                                base64_encode($card_num), 
                                $card_type,
                                $card_exp_month,
                                $card_exp_year
                            )
                        );
                        
                        //insert additional registrants
                        if (!empty($additional_registrants_entry)):
                            foreach($additional_registrants_entry as $additional_registrant):
                                $additional_registrant_name_arr=explode(' ',$additional_registrant['name']);
                                switch(count($additional_registrant_name_arr)):
                                    case '1':
                                        $fname=$additional_registrant_name_arr[0];
                                        $mname='';
                                        $lname='';
                                    break;
                                    case '2':
                                        $fname=$additional_registrant_name_arr[0];
                                        $mname='';
                                        $lname=$additional_registrant_name_arr[1];
                                    break;
                                    case '3':
                                        $fname=$additional_registrant_name_arr[0];
                                        $mname=$additional_registrant_name_arr[1];
                                        $lname=$additional_registrant_name_arr[2];
                                    break;
                                    default:
                                        $fname='';
                                        $mname='';
                                        $lname='';
                                    break;
                                endswitch;
                                $wpdb->query(
                                    $wpdb->prepare(
                                        "INSERT INTO ".SRM_ADDITIONAL_REGISTRANTS_TABLE."(registrant_id, fname, mname, lname, phone, fax, email) VALUES (%d, %s, %s, %s, %s, %s, %s)",
                                        $registrant_id,
                                        $fname,
                                        $mname,
                                        $lname,
                                        $additional_registrant['phone'],
                                        $additional_registrant['fax'],
                                        $additional_registrant['email']
                                    )
                                );
                            endforeach;
                        endif;
                        
                        //send the confirmation email
                        $this->send_registration_email($post_fields);
                        
                    else:
                        $return_arr['success']=0;
                        $return_arr['msg']='There was a problem processing your request: '.$payment_response['msg'];
                    endif;
                endif;
            endif;
            
            return $return_arr;
            
        }
        
        //send a registration email
        public function send_registration_email($post_fields){
            extract($post_fields);
            
            //get seminar data
            $Seminars=new SrmSeminars();
            $seminar_data=$Seminars->get_seminar($seminar_id);
            $registrant_price=$seminar_data['registrant_price'];
            
            //get coupon data
            if (!empty($coupon_code)):
                $CouponCodes=new SrmCouponCodes();
                $coupon_code_data=$CouponCodes->check_coupon_code($coupon_code);
                if (!$coupon_code_data['has_errors']):
                    $registrant_price=$coupon_code_data['return_data']['registrant_price'];
                endif;
                $coupon_code=strtoupper($coupon_code);
            endif;
            
            //add up the total
            $total_amount=$registrant_price;
            $total_amount+=($registrant_price*$additional_registrants);
            
            //set up the message
            $message="
                <html>
                    <body>
                        <p>
                            <strong>Seminar:</strong> ID #".$seminar_data['id']." ".$seminar_data['title']."
                            <br />
                            <strong>Registrant Name:</strong> $fname $mname $lname
                            <br />
                            <strong>Coupon Code:</strong> $coupon_code
                            <br />
                            <strong>Doctor Price:</strong> $".$registrant_price."
                            <br />
                            <strong>Phone:</strong> $phone
                            <br />
                            <strong>Email:</strong> $email
                            <br />
                            <strong>Additional Registrants:</strong> $additional_registrants
                            <br />
                            <strong>Total Amount:</strong> $".$total_amount."
                        </p>
                    </body>
                </html>
            ";
            
            //send the message
            $headers  = "MIME-Version: 1.0\n";
            $headers .= "Content-type: text/html; charset=utf-8\n";
            $headers .= "From: ".SRM_MAIL_FROM."\n";
            $headers .= "Reply-To: ".SRM_MAIL_FROM."\n"; 
            $subject='New Seminar Registration!';
            mail(SRM_ADMIN_EMAIL, $subject, $message, $headers);
            mail($email, $subject, $message, $headers);
        }
        
        public static function get_registration_fields(){
            $registration_fields=array(
                'fname'=>1,
                'mname'=>0,
                'lname'=>1,
                'phone'=>1,
                'fax'=>0,
                'email'=>1,
                'billing_name'=>1,
                'address1'=>1,
                'address2'=>0,
                'city'=>1,
                'state'=>1,
                'zip'=>1,
                'card_num'=>1,
                'card_type'=>1,
                'card_exp_month'=>1,
                'card_exp_year'=>1,
                'seminar_id'=>1
            );
            
            return $registration_fields;
        }
    }