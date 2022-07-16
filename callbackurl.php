<?php
#This is the url that gets transaction callbak from safaricom in real time so that you can diagnose data from the transaction or keep records
//include "conn.php/add your connection to database below in $conn";

$callbackJSONData=file_get_contents('php://input');

$logFile = "stkPush.json"; #creates the log file stkPush.json
$log = fopen($logFile, "a");
fwrite($log, $callbackJSONData);
fclose($log);

$callbackData=json_decode($callbackJSONData); #decodes the transaction data recieved from safaricom

#below we set the data to our own variables and according to the database we have/are creating

$resultCode=$callbackData->Body->stkCallback->ResultCode;
$resultDesc=$callbackData->Body->stkCallback->ResultDesc;
$merchantRequestID=$callbackData->Body->stkCallback->MerchantRequestID;
$checkoutRequestID=$callbackData->Body->stkCallback->CheckoutRequestID;
$pesa=$callbackData->stkCallback->Body->CallbackMetadata->Item[0]->Name;
$amount=$callbackData->Body->stkCallback->CallbackMetadata->Item[0]->Value;
$mpesaReceiptNumber=$callbackData->Body->stkCallback->CallbackMetadata->Item[1]->Value;
$balance=$callbackData->stkCallback->Body->CallbackMetadata->Item[2]->Value;
$b2CUtilityAccountAvailableFunds=$callbackData->Body->stkCallback->CallbackMetadata->Item[3]->Value;
$transactionDate=$callbackData->Body->stkCallback->CallbackMetadata->Item[3]->Value;
$phoneNumber=$callbackData->Body->stkCallback->CallbackMetadata->Item[4]->Value;

$amount = strval($amount);
#The if saves the data only when transaction was successful where result code is always 0 unless otherwise

if($resultCode == 0){
$conn =mysqli_connect("localhost","username","password","database");
$insert = $conn->query("INSERT INTO `#Your Database table`(`CheckoutRequestID`, `ResultCode`,`amount`, `MpesaReceiptNumber`, `PhoneNumber`)
VALUES ('$checkoutRequestID','$resultCode','$amount','$mpesaReceiptNumber','$phoneNumber')");

$sql = $conn->query("UPDATE invoice SET status = 'Paid' WHERE phone = '$phoneNumber' order by id desc limit 1"); #sets invoice status in database/in your records
}

$conn = null;

echo "
<script>alert('payment has been successfully completed!')</script>
<script>window.location = 'google.com/your own url'</script>
";

?>
