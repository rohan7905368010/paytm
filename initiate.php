<?php

/*
* import checksum generation utility
* You can get this utility from https://developer.paytm.com/docs/checksum/
*/
require_once('vendor/autoload.php');
use paytm\paytmchecksum\PaytmChecksum;
use paytm\checksum\PaytmChecksumLibrary;

$paytmParams["body"] = array(
    "requestType"   => "Payment",
    "mid"           => "BPNvNx00297023587976",
    "websiteName"   => "WEBSTAGING",
    "orderId"       => "ORDERID_98781",
    "callbackUrl"   => "https://merchant.com/callback",
    "txnAmount"     => array(
        "value"     => "1.00",
        "currency"  => "INR",
    ),
    "userInfo"      => array(
        "custId"    => "CUST_001",
    ),
);

/*
* Generate checksum by parameters we have in body
* Find your Merchant Key in your Paytm Dashboard at https://dashboard.paytm.com/next/apikeys 
*/
$checksum = PaytmChecksum::generateSignature(json_encode($paytmParams["body"], JSON_UNESCAPED_SLASHES), "1F7!YTdG#iOMQRNr");
// $verifySignature = PaytmChecksum::verifySignature($paytmParams, 'YOUR_MERCHANT_KEY', $paytmChecksum);

// print_r($verifySignature);exit();
$paytmParams["head"] = array(
    "signature"    => $checksum
);

$post_data = json_encode($paytmParams, JSON_UNESCAPED_SLASHES);

/* for Staging */
$url = "https://securegw-stage.paytm.in/theia/api/v1/initiateTransaction?mid=BPNvNx00297023587976&orderId=ORDERID_98781";

/* for Production */
// $url = "https://securegw.paytm.in/theia/api/v1/initiateTransaction?mid=YOUR_MID_HERE&orderId=ORDERID_98765";

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json")); 
$response = curl_exec($ch);

echo "<pre>";
print_r($response);
