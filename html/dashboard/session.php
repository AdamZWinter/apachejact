<?php
//dashboard/session.php
require('/var/www/html/authorizedHeader.php');
$domain = array_pop(explode('@', $email));
?>

<div class="row">
<div class="sectionleft col-1 row_height">
<p></p>
</div>


<div class="col-10 row_height content">
<!-- MENU -->
<div class="row authorized" id="divMenu">

<?php
//require('/var/www/html/dashboard/menu.php');
?>

</div>

<!-- Row 1 -->
<div class="row">
  <div class="col-1 mainGridBox bigScreen">
  </div>
  <div class="col-10 mainGridBox bigScreen">
  </div>
  <div class="col-1 mainGridBox bigScreen">
  </div>
</div>

<!-- Row 2 -->
<div class="row">
  <div class="col-1 mainGridBox bigScreen">
  </div>
  <div class="col-10 mainGridBox">

<?php

?>

<div id="divActive" class="row">
    
    <div class="row">
        <p class="adTitle">
        <?php echo $domain." DMARC reports"; ?>
        </p>
	<br>
	<p>

    <?php
        $query = "SELECT dateread, orgname, spfresult, spfdomain, dkimresult, dkimdomain, selector, dkimalign, spfalign, sourceip FROM spfreports WHERE domain = ? ORDER BY start DESC";
        $stmt = $db->prepare($query);
        $stmt->bind_param('s', $domain);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $displaycolumns = ['Date', 'From', 'SPF result', 'SPF domain', 'DKIM result', 'DKIM domain', 'selector', 'dkimalign', 'spfalign', 'Source IP'];
        echo '<table style="width: 100%;">';
        echo '<tr>';
        foreach($displaycolumns as $columnValue){
            echo '<td><p>'.$columnValue.'</p></td>';
        }
        echo '</tr>';
        while ($row = $result->fetch_row()) {
            echo '<tr>';
            foreach($row as $columnValue){
                echo '<td><p>'.$columnValue.'</p></td>';
            }
            echo '</tr>';
        }
        echo '</table>';

        $result->close();
        $stmt->close();
    ?>

    </p>



    </div>
    
</div>


     


<?php
require('/var/www/html/footer.php');
?>



