 
<?php
header("Content-Type:application/json");

$content=file_get_contents('php://input');
$logFile = "m-pesaResponse.json";
$log = fopen($logFile, "a");
fwrite($log, $content);
fclose($log);
$res =json_decode($content);


$ResultCode = $res->Body->stkCallback->ResultCode;
$ResultDesc = $res->Body->stkCallback->ResultDesc;
$CheckoutRequestID = $res->Body->stkCallback->CheckoutRequestID;
$Amount = $res->Body->stkCallback->CallbackMetadata->Item[0]->Value;
$MpesaReceiptNumber = $res->Body->stkCallback->CallbackMetadata->Item[1]->Value;
$PhoneNumber = $res->Body->stkCallback->CallbackMetadata->Item[4]->Value;
 

    $con=mysqli_connect("localhost",'username","password","database") or die ('Connection Failed');


    // Check connection
    if ($con->connect_error) {
        die("<h1>Connection failed:</h1> " . $con->connect_error);
    }else{
      
        $sql = "INSERT INTO mpesa_payments(CheckoutRequestID,ResultCode,ResultDesc,amount,MpesaReceiptNumber,PhoneNumber) VALUES ('$CheckoutRequestID','$ResultCode'      ,'$ResultDesc','$Amount','$MpesaReceiptNumber','$PhoneNumber')";
        
        $rs = mysqli_query($con, $sql); //Record the response to the database
        if($rs){
           echo "Records Inserted";
        }
        else{
            echo "Records Inserted not";
        }
}if($ResultCode==0){
//QUERY THE TRANSACTION ONLY IF IT WAS A SUCCESS. CODE IS 0 FOR SUCCESSFUL. REMEMBER YOU CANT QUERY ON SANDBOX
           /* access token */
    $consumerKey = 'ky3NZ3fiQL2XFXISZOtxFd'; //Fill with your app Consumer Key
    $consumerSecret = 'j9ydDlj6UMuK'; // Fill with your app Secret
    $headers = ['Content-Type:application/json; charset=utf8'];
    $access_token_url = 'https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
    $curl = curl_init($access_token_url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_HEADER, FALSE);
    curl_setopt($curl, CURLOPT_USERPWD, $consumerKey.':'.$consumerSecret);
    $result = curl_exec($curl);
    $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $result = json_decode($result);
    $access_token = $result->access_token;
    curl_close($curl);


  /* making the request */
    $tstatus_url = 'https://api.safaricom.co.ke/mpesa/transactionstatus/v1/query';

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $tstatus_url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer '.$access_token)); //setting custom header

    $curl_post_data = array(
      //Fill in the request parameters with valid values
      'Initiator' => 'ecorppay',
      'SecurityCredential' => 'HVqQ0frsTOrMg4SS23J0pUb3v6fuzG4FmN8qnTgZSy9ZqSYqZ07zm1gQKQeO2mqvzlVyEPPNDrC2hGek8Rlh7HQIaOsAJBd9IFfBhwkhBMEJwToRxzJFp3tyFQLfzzdOhOQrrg4mPnCe1zBc6pJiWIJOxMdTVtKw9JuQjKuMB+Bho1rN52Rfxw23cAf4F1OtqqaIff9+Mi8QAm22CGInls2ug7HxKUVpGmQc/pFrlqeAEWhWmsRojPQ8rz8YYqUDD8DyjTycLY9lXGlsb9URoq5Q==',
      'CommandID' => 'TransactionStatusQuery',
      'TransactionID' => $MpesaReceiptNumber,
      'PartyA' => '4095235', // shortcode 1
      'IdentifierType' => '4',
      'ResultURL' => 'https://freemails.astralecorp.com/result_url.php',
      'QueueTimeOutURL' => 'https://freemails.astralecorp.com/resul_turl.php',
      'Remarks' => 'EcorpPay',
      'Occasion' => 'Ecorp Pay'
    );

    $data_string = json_encode($curl_post_data);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
    $curl_response = curl_exec($curl);
    //print_r($curl_response);
    //echo $curl_response;
}
?>
