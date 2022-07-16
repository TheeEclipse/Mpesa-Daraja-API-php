 <!-- Here we just use this simple form to get user data/input-->
<!doctype html>
<html lang="en">
  <head>
    
</head>

<body style="height:100vh" style="background-color: #eaedf4;">
<form action="./stkpush" method="POST" >
  <div class="container">
    <h1>ECORP PAY</h1>
    <p>Please add details below to pay</p>
    <label for="username"><b>Amount</b></label>
    <input type="text" name="amount" placeholder="Enter Amount" title="Only nurmbers/digits" type="number" pattern="[0-9]+" required>
    
    <label for="psw"><b>Phone Nurmber</b></label>
    <input type="text" name="phone_number" placeholder="Enter Phone Number"  title="Only nurmbers/digits" type="number" pattern="[0-9]+"  required >
    
   
     <p>By using our service you agree to our <a href="/privacy">Terms & Privacy</a>.</p>

    <button   name="submit" value="submit" class="registerbtn fw-bold" >MAKE PAYMENT</button>
  
</div>
 
  <div class="container signin">
    <p>Already Paid? <a href="/confirm-pay">Confirm Payment</a>.</p>
  </div>
</form>


<footer>
     <div class="all" style=" text-align: center; background-color: aliceblue;">
  <div class="spinner-border text-info" role="status" style="margin-top: 10vh;">
    <span class="visually-hidden">Loading...</span>
  </div>
  Made with &#10084;&#65039; by <a href="##">Your Name maybe</a>
  <div class="spinner-border text-info" role="status">
    <span class="visually-hidden">Loading...</span>
  </div>
  </div>
</footer>
 </body>
</html>

