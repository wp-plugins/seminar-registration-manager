<?php 
    $trans_id=SRM_AUTHORIZE_NET_TRANSID;
    $api_key=SRM_AUTHORIZE_NET_APIKEY;
    //format the credit card expiration properly
    $card_expiration=str_pad($card_exp_month, 2, "0", STR_PAD_LEFT).'/'.$card_exp_year;
    
    /*SWITCH PAYMENT BASED ON WHICHEVER GATEWAY THEY ARE USING*/
    //check and transact payment
    $x_Login= urlencode($trans_id); // Auth.Net ID
    $x_Password= urlencode($api_key); // Auth.Net password
    $x_Delim_Data= urlencode("TRUE");
    $x_Delim_Char= urlencode("|");
    $x_Encap_Char= urlencode("'");
    $x_Type= urlencode("AUTH_CAPTURE");
    $x_ADC_Relay_Response = urlencode("FALSE");
    if ( SRM_PAYMENT_GATEWAY_MODE == 'TEST' ):
        $x_Test_Request= urlencode("TRUE"); // Remove this line of code when you are ready to go live
    endif;

    $x_Method= urlencode("CC");
    $x_Amount= urlencode($total_amount);
    $x_First_Name= urlencode($billing_fname);
    $x_Last_Name= urlencode($billing_lname);
    $x_Card_Num= urlencode($card_num);
    $x_Exp_Date= urlencode($card_expiration);
    $x_Address= urlencode($address1.' '.$address2);
    $x_City= urlencode($city);
    $x_State= urlencode($state);
    $x_Zip= urlencode($zip);
    $x_Email= urlencode($email);
    $x_Email_Customer= urlencode("TRUE");
    //$x_Merchant_Email= urlencode("rogersp@hitwebdesign.com"); //  Replace MERCHANT_EMAIL with the merchant email address

    $fields="x_Version=3.1&x_Login=$x_Login&x_Delim_Data=$x_Delim_Data&x_Delim_Char=$x_Delim_Char&x_Encap_Char=$x_Encap_Char";
    $fields.="&x_Type=$x_Type&x_Test_Request=$x_Test_Request&x_Method=$x_Method&x_Amount=$x_Amount&x_First_Name=$x_First_Name";
    $fields.="&x_Last_Name=$x_Last_Name&x_Card_Num=$x_Card_Num&x_Exp_Date=$x_Exp_Date&x_Address=$x_Address&x_City=$x_City&x_State=$x_State&x_Zip=$x_Zip&x_Email=$x_Email&x_Email_Customer=$x_Email_Customer&x_Merchant_Email=$x_Merchant_Email&x_ADC_Relay_Response=$x_ADC_Relay_Response";
    if($x_Password!='')
    {
        $fields.="&x_Password=$x_Password";
    }

    $agent = $_SERVER['HTTP_USER_AGENT'];
    $ref = $_SERVER['HTTP_REFERER']; // Replace this URL with the URL of this script
    
    $ch=curl_init();
    //curl_setopt($ch, CURLOPT_URL, "https://test.authorize.net/gateway/transact.dll");
    curl_setopt($ch, CURLOPT_URL, "https://secure.authorize.net/gateway/transact.dll");
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_NOPROGRESS, 1);
    curl_setopt($ch, CURLOPT_VERBOSE, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION,0);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,15);
    curl_setopt($ch, CURLOPT_USERAGENT, $agent);
    curl_setopt($ch, CURLOPT_REFERER, $ref);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($ch);
    curl_close($ch);
    $response_arr = explode('|', $response); // Splits out the buffer return into an array so . . .
    
    if ( $response_arr[0] == "'1'" ):
        $payment_response['success']=1;
        $payment_response['msg']='Payment success!';
    else:
        $payment_response['success']=0;
        $payment_response['msg']=$response_arr[3];
    endif;