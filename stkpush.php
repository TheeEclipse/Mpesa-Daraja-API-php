<?php 
header("Content-Type:application/json");
session_start();

#first we configure variables
$config = array(
    "env"              => "sandbox",
    "BusinessShortCode"=> "174379", #The default safaricom paybill/shortcode
    "key"              => "###", //Enter your consumer key here
    "secret"           => "##", //Enter your consumer secret here
    "username"         => "apitest",
    "TransactionType"  => "CustomerPayBillOnline",
    "passkey"          => "bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919", //Enter your passkey here. WHEN GOING LIVE THE PASSKEY IS EMAILED TO YOU AFTER LOGIN TO MPESA PORTAL FOR THE FIRST TIME.OR MAKE A REQUEST
    "CallBackURL"     => "#https://yourwebsite.com/callbackurl.php", #have a website link with the live callbackurl.php file and accesible
    "AccountReference" => "CompanyXLTD",
    "TransactionDesc"  => "Payment of X" ,
);

$phone = $_POST['phone_number']; #getting the user input via post method from the form

if (isset($_POST['submit'])) {

    $phone = $_POST['phone_number'];
    $amount = 1;
    
  #here we sanitize/format the phone nurmber input by users to 254*********
    $phone = (substr($phone, 0, 1) == "+") ? str_replace("+", "", $phone) : $phone;
    $phone = (substr($phone, 0, 1) == "0") ? preg_replace("/^0/", "254", $phone) : $phone;
    $phone = (substr($phone, 0, 1) == "7") ? "254{$phone}" : $phone;

    $access_token = ($config['env']  == "live") ? "https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials" : "https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials"; 
    $credentials = base64_encode($config['key'] . ':' . $config['secret']); 
    
    $ch = curl_init($access_token);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Basic " . $credentials]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
    $response = curl_exec($ch);
    curl_close($ch);
    $result = json_decode($response); 
    $token = isset($result->{'access_token'}) ? $result->{'access_token'} : "N/A";

    $timestamp = date("YmdHis"); #sets current time
    $password  = base64_encode($config['BusinessShortCode'] . "" . $config['passkey'] ."". $timestamp); #this encodes your data in all request\s

  
    #below is the stkpush format addingour variables
    $curl_post_data = array( 
        "BusinessShortCode" => $config['BusinessShortCode'],
        "Password" => $password,
        "Timestamp" => $timestamp,
        "TransactionType" => $config['TransactionType'],
        "Amount" => $amount,
        "PartyA" => $phone,
        "PartyB" => $config['BusinessShortCode'],
        "PhoneNumber" => $phone,
        "CallBackURL" => $config['CallBackURL'],
        "AccountReference" => $config['AccountReference'],
        "TransactionDesc" => $config['TransactionDesc'],
    ); 

    $data_string = json_encode($curl_post_data);

    $endpoint = ($config['env'] == "live") ? "https://api.safaricom.co.ke/mpesa/stkpush/v1/processrequest" : "https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest"; 

    $ch = curl_init($endpoint );
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer '.$token,
        'Content-Type: application/json'
    ]);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response     = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($response);  #decodes the mpesa response to a json / easily readable 
    
    $stkpushed = $result->{'ResponseCode'};


    if($stkpushed === "0"){
        echo $result->{'ResponseDescription'};
        //header("Location: #Your any location#");
    }else{
        echo $result->{'errorMessage'};  #This error message will assist you in knowing or diagnosing any faults in your code.Will show up if no stk push was succesful
    }
}
?>
