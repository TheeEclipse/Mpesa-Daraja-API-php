<!doctype html>
<html lang="en">
  <head>
    
 <!-- tyle your own way , i used bootstrap so add bootstrap stylesheets links here to format the form-->
    
</head>

<body style="height:100vh" style="background-color: #eaedf4;">
<form action="./confirm-pay" method="POST" >
  <div class="container">
    <h1>ECORP PAY</h1>
    <p>Please enter Phone nurmber to confirm payment</p>
    <label for="phone"><b>Phone Nurmber</b></label>
    <input type="text" name="phone" placeholder="Enter Phone Number e.g 254712345678"  title="Only nurmbers/digits" type="number" pattern="[0-9]+"  required >
     <?php
	$phone = $_POST["phone"];
    #connect to database to view data / transaction records
    
    $conn=mysqli_connect("localhost","username","password","database") or die ('Connection Failed');

    // Check connection
    if ($conn->connect_error) {
        die("<h1>Connection failed:</h1> " . $conn->connect_error);
    }

if(isset($_POST['submit'])) {
	$sql = mysqli_query($conn,
	"SELECT * FROM the database table WHERE PhoneNumber='$phone'");

	$num = mysqli_num_rows($sql);

  #The if below checks if the phone is in the database and remember the database only stores succesful transactions
  #Add your own conditions as well
  
	if($num > 0) {
		$row = mysqli_fetch_array($sql);
		echo '<span style="color:green;text-align:center;">Transaction was succesfull!</span>';
        //header("location:getyourorder.php");
        
        
    } else {
      echo '<span style="color:red;text-align:center;">Transaction was not succesfull! <a href="https://wa.me/+254759661289">Contact Us</a>  for any issues</span>';
     
    }
}
    
?>

    <button   name="submit" value="submit" class="registerbtn fw-bold" >CONFIRM PAY</button>
  
</div>
    </body>
</footer>
</html>
