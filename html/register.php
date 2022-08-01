<?php
//register.php
require('/var/www/html/header.php');
?>

<div id="regForm">
<center>
    <!--form action="regAction.php" method="post"-->
  
    <p>Please enter your information below.</p>
    <input type="text" name="displayname" id="displayname" placeholder="Name"><br>
    <br>
    <br>
    <input type="text" name="email" id="email" placeholder="Email Address"><br>
    <br>
    <br>
    <input type="password" name="password" id="password" placeholder="Password"><br>
    <br>
    <br>
    <input type="password" name="password2" id="password2" placeholder="Enter Password Again"><br>
    <br>
    <br>
    <button onclick="verify()" class="buttonSignIn">Register</button>
    <p id="verification"></p>
    <p id="debugging"></p>
<center>
</div>

<div id="success" style="display:none;">
<p>A verification email has been sent.  Please, check your email and spam box.  Click on the link you find there to continue.  You can close this window.</p>
</div>

<script>
    
function register(displayname, email, password) {
  var fail = true;
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
        var fail = false;
        var response = this.responseText;
	//document.getElementById("verification").innerHTML = response;
        var responseObj = JSON.parse(response);
        if (responseObj.error == 'none'){
          //  document.getElementById("verification").innerHTML = 'Success!';
	    document.getElementById("success").style.display = "inline";
            document.getElementById("regForm").style.display = "none";
        }else{
            document.getElementById("verification").innerHTML = responseObj.message;
	    document.getElementById("debugging").innerHTML = responseObj.error;
        }
    }else{
      window.setTimeout(failed(fail), 4000);
    }
  };
  xhttp.open("POST", "regAction.php", true);
  xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhttp.send("displayname="+displayname+"&email="+email+"&password="+password);
}

function failed(fail){
      if(fail){
          document.getElementById(verification).innerHTML = 'Connection Failed....Please try again later.';
      }
}

function verify(){
    var displayname = encodeURIComponent(document.getElementById("displayname").value);
    var email = encodeURIComponent(document.getElementById("email").value);
    var password = encodeURIComponent(document.getElementById("password").value);
    var password2 = encodeURIComponent(document.getElementById("password2").value);
    var cond1 = false;
    var cond2 = false;
    var cond3 = false;
    var cond4 = false;
    
    if (!displayname || 0 === displayname.length){
      document.getElementById("verification").innerHTML = 'Please enter your name.  ';
    }else{
        cond1 = true;
    }
    
    if (!ValidateEmail(email)){
      document.getElementById("verification").innerHTML = 'Please enter a valid email address.  ';
      document.getElementById("debugging").innerHTML = email;
      cond2 = true;
    }else{
        cond2 = true;
    }
    
    if (!password || password.length <= 8){
      document.getElementById("verification").innerHTML = 'Password must be at least eight characters.  ';
    }else{
        cond3 = true;
    }
    
    if (!password2 || password2.length <= 8){
      document.getElementById("verification").innerHTML = 'Passwords do not match, or are less than 8 characters.  ';
    }else{
        cond4 = true;
    }
    
    if (cond1 && cond2 && cond3 && cond4){
        register(displayname, email, password);
    }else{
        document.getElementById("debugging").innerHTML = 'One or more conditions are false.  ';
    }
}

function ValidateEmail(email) 
{
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

