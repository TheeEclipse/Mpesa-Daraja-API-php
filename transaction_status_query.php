<?php
/* YOU WILLL NOTICE I AM USING LIVE URLS FROM SAFARICOM HERE. if YOU ARE RUNNING ON SANDBOX CHANGE THE QUERY URLS FROM HTTPS://API.SAFARICOM..... TO THE ONES ON SANDBOX */
  /*FOR LIVE REMEMBER SECURITY CRRDENTIAL IS THE ENCRYPTED PASSWORD FOR LOGGIN INTO MPESA PORTAL. Encrypt using safaricom developers website account on "create credentials" */
  /* access token */
    $consumerKey = 'ky3NZ3fiQL2XFRBIOtxFd'; //Fill with your app Consumer Key
    $consumerSecret = 'j9ydDljWuK'; // Fill with your app Secret
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
      'SecurityCredential' => 'HVqQ0frsTOrMg4SS29ZqSYqZ07zm1gQKQeO2mq1OtqqaIff9+Mi8QAm22CGInls2ug7HxKUVpGmQc/pFrlqeAEWhWmsRojPQ8rz8YYqUDD8DyjTycLY9lXGlsb9URoq5Q==',
      'CommandID' => 'TransactionStatusQuery',
      'TransactionID' => 'QI136MS5C5',
      'PartyA' => '4095235', // shortcode 1
      'IdentifierType' => '4',
      'ResultURL' => 'https://freemails.astralecorp.com/result_url.php',
      //result url handles the response from this file and stores it to database or something else.timeout url also does the same somehow but on timeout or no response
      'QueueTimeOutURL' => 'https://freemails.astralecorp.com/result_url.php',
      'Remarks' => 'EcorpPay',
      'Occasion' => 'Ecorp Pay'
    );

    $data_string = json_encode($curl_post_data);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
    $curl_response = curl_exec($curl);
    print_r($curl_response);
    echo $curl_response;
?>
