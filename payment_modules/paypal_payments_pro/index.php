<?php 
/** DoDirectPayment NVP example; last modified 08MAY23.
 *
 *  Process a credit card payment. 
*/

$environment = SRM_PAYMENT_GATEWAY_MODE;    // or 'beta-sandbox' or 'live'
/**
 * Send HTTP POST Request
 *
 * @param    string    The API method name
 * @param    string    The POST Message fields in &name=value pair format
 * @return    array    Parsed HTTP Response body
 */
 
function PPHttpPost($methodName_, $nvpStr_) {
    $environment = SRM_PAYMENT_GATEWAY_MODE;
    // Set up your API credentials, PayPal end point, and API version.
    $API_UserName = urlencode(PAYPAL_PAYMENTS_PRO_USERNAME);
    $API_Password = urlencode(PAYPAL_PAYMENTS_PRO_PASSWORD);
    $API_Signature = urlencode(PAYPAL_PAYMENTS_PRO_SIGNATURE);
    $API_Endpoint = "https://api-3t.paypal.com/nvp";
    if( "TEST" == $environment ) {
        $API_Endpoint = "https://api-3t.sandbox.paypal.com/nvp";
    }
    $version = urlencode('51.0');

    // Set the curl parameters.
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $API_Endpoint);
    curl_setopt($ch, CURLOPT_VERBOSE, 1);

    // Turn off the server and peer verification (TrustManager Concept).
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);

    // Set the API operation, version, and API signature in the request.
    $nvpreq = "METHOD=$methodName_&VERSION=$version&PWD=$API_Password&USER=$API_UserName&SIGNATURE=$API_Signature$nvpStr_";

    // Set the request as a POST FIELD for curl.
    curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);

    // Get response from the server.
    $httpResponse = curl_exec($ch);

    if(!$httpResponse) {
        exit("$methodName_ failed: ".curl_error($ch).'('.curl_errno($ch).')');
    }

    // Extract the response details.
    $httpResponseAr = explode("&", $httpResponse);

    $httpParsedResponseAr = array();
    foreach ($httpResponseAr as $i => $value) {
        $tmpAr = explode("=", $value);
        if(sizeof($tmpAr) > 1) {
            $httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
        }
    }

    if((0 == sizeof($httpParsedResponseAr)) || !array_key_exists('ACK', $httpParsedResponseAr)) {
        exit("Invalid HTTP Response for POST request($nvpreq) to $API_Endpoint.");
    }

    return $httpParsedResponseAr;
}

// Set request-specific fields.
$paymentType = urlencode('Authorization');                // or 'Sale'
$firstName = urlencode($billing_fname);
$lastName = urlencode($billing_lname);
$creditCardType = urlencode($card_type);
$creditCardNumber = urlencode($card_num);
$expDateMonth = $card_exp_month;
// Month must be padded with leading zero
$padDateMonth = urlencode(str_pad($expDateMonth, 2, '0', STR_PAD_LEFT));

$expDateYear = urlencode($card_exp_year);
$cvv2Number = urlencode('cc_cvv2_number');
$address1 = urlencode($address1);
$address2 = urlencode($address2);
$city = urlencode($city);
$state = urlencode($state);
$zip = urlencode($zip);
$country = urlencode('US');                // US or other valid country code
$amount = urlencode($total_amount);
$currencyID = urlencode('USD');                            // or other currency ('GBP', 'EUR', 'JPY', 'CAD', 'AUD')

// Add request-specific fields to the request string.
$nvpStr =    "&PAYMENTACTION=$paymentType&AMT=$amount&CREDITCARDTYPE=$creditCardType&ACCT=$creditCardNumber".
            "&EXPDATE=$padDateMonth$expDateYear&CVV2=$cvv2Number&FIRSTNAME=$firstName&LASTNAME=$lastName".
            "&STREET=$address1&CITY=$city&STATE=$state&ZIP=$zip&COUNTRYCODE=$country&CURRENCYCODE=$currencyID";

// Execute the API operation; see the PPHttpPost function above.
$httpParsedResponseAr = PPHttpPost('DoDirectPayment', $nvpStr);

if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {
    $payment_response['success']=1;
    $payment_response['msg']='Payment success!';
} else  {
    $payment_response['success']=0;
    $payment_response['msg']='Payment Failure: '.urldecode($httpParsedResponseAr['L_LONGMESSAGE0']);
}