<?php
//index.html
require('indexHeader.php');
?>

<div class="row"><br><br></div>

<div class="col-6 bigScreen indexLeft" style="background-color:black;">
<img src='https://topsecondhost.s3-us-west-2.amazonaws.com/installsh.PNG' width="100%" height=auto />
</div>

<div class="col-6 indexRight" style="top:0">
<p class="adTitle">Authenticated SSL Site in Minutes</p>
<br>
<p class="indented">
TopSecondHost is an installation script that will take your fresh AWS Linux AMI to a fully-functioning SSL/HHTPS website, with user login capability.
</p>
<br>
<p class="indented">
This very website you are looking at is the base installation.  After installation, you will have a copy of this site as you see it now.
</p>
<p class="indented">
The TopSecondHost website comes with built-in (secure) editing pages that will allow you to edit code, and upload files, from within the site itself.
</p>
</div>


<div class="clear40"></div>


<div class="col-6 bigScreen indexLeft" style="background-color:black;">
<img src='https://topsecondhost.s3-us-west-2.amazonaws.com/indexhtml.PNG' width="100%" height=auto />
</div>

<div class="col-6 indexRight">
<p class="adTitle">
What does it do?
</p>
<p class="indented">
The TopSecondHost install.sh script performs the following:
<br>
<br>
-Installs common dependencies
<br>
-Installs PHP
<br>
-Installs and configures Apache (httpd2.4)
<br>
-Installs MySQL and configures users (with your input)
<br>
-Creates database tables for user authntication (name and password etc..)
<br>
-Installs SSL certificate with Certbot.
<br>
-Installs SES-Mailer libraries and phpMyAdmin 
</p>
</div>

<div class="clear40"></div>


<?php
require('footer.php');
?>

