<?php
require('/var/www/html/authorizedHeader.php');
require('/var/www/html/classlib/Privileges.php');

if(!Privileges::granted($email, 'utilities')){echo '<p>You are not authorized.</p>'; require('/var/www/html/footer.php'); exit;}

        if(isset($_GET['edit'])) {
            $edit = $_GET['edit'];
        }else{
 		$edit = '/var/www/html/';
        }

?>

<script src="codemirror/lib/codemirror.js"></script>
<link rel="stylesheet" href="codemirror/lib/codemirror.css">
<link rel="stylesheet" href="codemirror/theme/darcula.css">
<script src="codemirror/mode/php/php.js"></script>
<script src="codemirror/mode/clike/clike.js"></script>
<script src="codemirror/mode/htmlmixed/htmlmixed.js"></script>
<script src="codemirror/mode/javascript/javascript.js"></script>
<script src="codemirror/mode/xml/xml.js"></script>
<script src="codemirror/mode/css/css.js"></script>


<div class="col-6">
<form>
<input type="text" name="filename" id="filepath" style="width:400px;" value="<?php echo $edit; ?>" />
</form>
</div>
<div class="col-6">
<button onclick="loadCM();" class="buttonSignIn">Code Mirror</button>
<button onclick="scanDir();" class="buttonSignIn">Scan Dir</button>
<button onclick="loadDoc();" class="buttonSignIn">Load File</button>
<button onclick="writeDoc();" class="buttonSignIn">OverWrite</button>
<input type="password" name="password" id="password" style="width:100px" />
<a id="success">...</a>
</div>


<textarea id="editarea" rows="200" cols="160">
Do not edit editor.  It cannot edit itself, it will only erase itself.


Pressing Enter in filepath will save the entry to autofill and reload the page.

New Files created here will have rw- r-- r-- permission.

Some of these characters may need to be escaped by using Hex codes.  Write these contents to a new file to find out which:

		      %09           Horizontal tab
		      %0A           Line feed
		      %0D           Carriage return
 &#32;                %20           Space
 &#33;                %21     !     Exclamation mark
 &#34; &quot;         %22     "     Quotation mark
 &#35;                %23     #     Number sign
 &#36;                %24     $     Dollar sign
 &#37;                %25     %     Percent sign
 &amp;                %26     &     Ampersand "&"        (problem)
 &#39;                %27           Apostrophe           (problem)
 &#40;                %28     (     Left parenthesis
 &#41;                %29     )     Right parenthesis
 &#42;                %2A     *     Asterisk
 &#43;                %2B     +     Plus sign            (problem)  
 &#44;                %2C     ,     Comma
 &#45;                %2D     -     Hyphen
 &#46;                %2E     .     Period (fullstop)
 &#47;                %2F     /     Solidus (slash)
 &#58;                %3A     :     Colon
 &#59;                %3B     ;     Semi-colon
 &#60; &lt;           %3C     <     Less than
 &#61;                %3D     =     Equals sign
 &#62; &gt;           %3E     >     Greater than
 &#63;                %3F     ?     Question mark
 &#64;                %40     @     Commercial at
 &#91;                %5B     [     Left square bracket
 &#92;                %5C     \     Reverse solidus (backslash)
 &#93;                %5D     ]     Right square bracket
 &#94;                %5E     ^     Caret
 &#95;                %5F     _     Horizontal bar (underscore)
 &#96;                %60     `     Acute accent
&#123;                %7B     {     Left curly brace
&#124;                %7C     |     Vertical bar
&#125;                %7D     }     Right curly brace
&#126;                %7E     ~     Tilde

</textarea>


<script>
    function loadCM(){
        var myTextArea = document.getElementById('editarea');
	var myCodeMirror = CodeMirror.fromTextArea(myTextArea);
	myCodeMirror.setSize(1500, 1500);	
	console.log("Executed loadCM()");
    }

function scanDir() {
  var filePath = document.getElementById("filepath").value;
  var password = document.getElementById("password").value;
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      document.getElementById("editarea").value = this.responseText;
	console.log(this.responseText);
    }
  };
  xhttp.open("POST", "scanDir.php", true);
  xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhttp.send("filename="+filePath+"&password="+password);
}


function loadDoc() {
  var filePath = document.getElementById("filepath").value;
  var password = document.getElementById("password").value;
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      document.getElementById("editarea").value = this.responseText;
    }
  };
  xhttp.open("POST", "readFile.php", true);
  xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhttp.send("filename="+filePath+"&password="+password);
}


function writeDoc() {
  var filePath = document.getElementById("filepath").value;
  var fileContents = encodeURIComponent(document.getElementById("editarea").value);
  var password = document.getElementById("password").value;
  var yhttp = new XMLHttpRequest();
  yhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      document.getElementById("success").innerHTML = this.responseText;
      setTimeout(function(){document.getElementById("success").innerHTML = "..";}, 4000);
    }
  };
  yhttp.open("POST", "writeFile.php", true);
  yhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  yhttp.send("filename="+filePath+"&contents="+fileContents+"&password="+password);
}

</script>



<?php
require('/var/www/html/footer.php');
?>



