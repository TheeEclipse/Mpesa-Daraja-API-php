<?php
//THIS IS A TYPICAL RESULT URL FILE THAT RECIVES DATA AND STORES IT TO DATABASE ON TRANSACTION QUERY

  $callbackResponse = file_get_contents('php://input');

  $logFile = "transaction_status.json";
  $log = fopen($logFile, "a");
  fwrite($log, $callbackResponse);
  fclose($log);
  
  $callbackContent = json_decode($callbackResponse);
  //$ResultCode = $callbackContent->Result->ResultCode;
  //$ResultDesc = $callbackContent->Result->ResultDesc;
  //$TransactionID = $callbackContent->Result->TransactionID;
  $PayPerson = $callbackContent->Result->ResultParameters->ResultParameter[0]->Value;
  //$TransactionReason = $callbackContent->Result->ResultParameters->ResultParameter[8]->Value;
  //$TransactionStatus = $callbackContent->Result->ResultParameters->ResultParameter[9]->Value;
  //$TransactionAmount = $callbackContent->Result->ResultParameters->ResultParameter[10]->Value;
  $TransactionReceipt = $callbackContent->Result->ResultParameters->ResultParameter[12]->Value;

  $TransactionOccasion = $callbackContent->Result->ReferenceData->ReferenceItem->Value;
  
//COMMENTED OUT HERE WE CAN USE THIS CODE TO DEBUG ERRORS FROM THE RECEIVED JSON DATA
//$logFile = "transaction_status11.json";
//$log = fopen($logFile, "a");  
//fwrite($log, $ResultCode);
//fwrite($log, $TransactionAmount);
//fwrite($log, $PayPerson);
//fwrite($log, $TransactionReason);
//fwrite($log, $TransactionStatus);
//fwrite($log, $TransactionReceipt);
//fwrite($log, $TransactionOccasion);
//fclose($log);

//UPDATE DATABASE BELOW WITH NAMES / ADD DATA TO NEW DATABASE WITH PAYERS NAMES AND EXTRA CUSTOMER INFO.
 $con=mysqli_connect("localhost","username","password","database") or die ('Connection Failed');
$sql = ( "UPDATE `mpesa-payments` SET `fnames` = '$PayPerson' , `TOccasion` = '$TransactionOccasion' WHERE `MpesaReceiptNumber` = '$TransactionReceipt'");
$rs = mysqli_query($con, $sql); //Record the response to the database
        if($rs){
           echo "Records Inserted";
        }
        else{
            echo "Records Inserted not";
        }
