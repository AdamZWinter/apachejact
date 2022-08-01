<!--______________________________FOOTER____________________________________-->

  </div>
  <div class="col-1 mainGridBox bigScreen">
  </div>
</div>


<!-- Row 3 -->
<div class="row">
  <div class="col-1 mainGridBox bigScreen">
  </div>
  <div class="col-10 mainGridBox bigScreen">
  </div>
  <div class="col-1 mainGridBox bigScreen">
  </div>
</div><!-- End of Row 3 -->

</div><!-- End of main middle column -->


<div class="sectionright col-1 row_height">
<p></p>
</div>
</div>



<div class="clear"></div>
<div class="footer">
<p>

<?php
if($debugging){
error_log(json_encode($obj));
}
?>


<a class="buttonFooter" href="index.html">Home</a>
<a class="buttonFooter" href="/legal/privacypolicy.html">Privacy Policy</a>
<a class="buttonFooter" href="/legal/terms.html">Terms of Service</a>
</p>
</div>

</body>
</html>

<?php
$db->close();
?>
