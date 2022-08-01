<?php
//signin.php
require('header.php');
?>

<div id="signinForm">
<center>
    <!--form action="signAction.php" method="post"-->
    <p>Please sign in.</p>
    <input type="text" name="email" id="email" placeholder="Email Address" autocomplete="email"><br>
    <br>
    <br>
    <input type="password" name="password" id="password" placeholder="Password" autocomplete="current-password"><br>
    <br>
    <br>
    <button onclick="verify()" class="buttonSignIn">Sign In</button>
    <br>
    <br>
    <a class="buttonSignIn" href="/register.php">Register</a>
    <!--p id="debugging"></p-->
    <p id="verification"></p>
<center>
</div>

<script>
    
function register(email, password) {
  var fail = true;
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
        var fail = false;
        var response = this.responseText;
	document.getElementById("verification").innerHTML = response;
        var responseObj = JSON.parse(response);
        if (responseObj.error == 'none'){
          window.location.replace("/dashboard/session.php");
		//document.getElementById("verification").innerHTML = 'Success!';
	      //document.getElementById("success").style.display = "inline";
          //document.getElementById("signinForm").style.display = "none";
        }else{
            //document.getElementById("debugging").innerHTML = responseObj.error;
            document.getElementById("verification").innerHTML = "Something is wrong with these credentials. If the account exists, an email has been sent with a one-time login link.";
        }
    }else{
      window.setTimeout(failed(fail), 4000);
    }
  };
  xhttp.open("POST", "signAction.php", true);
  xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhttp.send("email="+email+"&password="+password);
}

function failed(fail){
      if(fail){
          document.getElementById(verification).innerHTML = 'Connection Failed....Please try again later.';
      }
}

function verify(){
    var email = encodeURIComponent(document.getElementById("email").value);
    var password = encodeURIComponent(document.getElementById("password").value);
    var cond1 = false;
    
    if (!ValidateEmail(email)){
      document.getElementById("verification").innerHTML = 'Please enter a valid email address.  ';
      document.getElementById("debugging").innerHTML = email;
      cond1 = false;
    }else{
      cond1 = true;
    }
      
    if (cond1){
        register(email, password);
    }else{
        document.getElementById("debugging").innerHTML = 'One or more conditions are false.  ';
    }
}

function ValidateEmail(email) {
    var decoded = decodeURIComponent(email);
    if (/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/.test(decoded))
    {
    return (true)
    }else{
    return (false)
    }
}

</script>

<?php
require('/var/www/html/footer.php');
?>

