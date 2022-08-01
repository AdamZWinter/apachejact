<?php
require('/var/www/html/authorizedHeader.php');
require('/var/www/html/classlib/Privileges.php');
if(!Privileges::granted($email, 'utilities')){echo 'You are not authorized to do this.'; exit;}
?>

<div class="row">
<div class="sectionleft col-1 row_height">
<p></p>
</div>

<div class="col-10 row_height content">

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

    $cwd = getcwd();

    if(isset($_GET['dir'])) {
        $cwd = $_GET['dir'];
    }

    echo '
    <table width=50%>
        <thead>
            <tr>
                <th width=60%><p>'.$cwd.'</p></th>
                <!--th width=20%><p>Owner</p></th-->
                <!--th width=20%><p>Permission</p></th-->
            </tr>
        </thead>
        <tbody>
    ';

        $fil = scandir($cwd);

        foreach ($fil as $file) {
            $path = realpath($file);
            $dir = '/'.$file;
            echo '<p><tr>';
            if($file == '..'){
                $parent = dirname($cwd);
                echo '<td><a href=" '.$_SERVER['PHP_SELF'].'?dir='.$parent.' ">'.$file.'</a></td>';
                //echo '<td>'.posix_getpwuid(fileowner($file))["name"].'</td>';
                //echo '<td>'.substr(sprintf('%o', fileperms($file)), -3).'</td>';
            }
            elseif($file == '.') {
                //do nothing
            }
            elseif(is_dir($cwd.'/'.$file)) {
                echo '<td><a href=" '.$_SERVER['PHP_SELF'].'?dir='.$cwd.'/'.$file.' ">'.$cwd.'/'.$file.'</a></td>';
                //echo '<td>'.posix_getpwuid(fileowner($cwd.'/'.$file))["name"].'</td>';
                //echo '<td>'.substr(sprintf('%o', fileperms($cwd.'/'.$file)), -3).'</td>';
            }
            else {
                echo '<td><a href="https://'.$_SERVER['SERVER_NAME'].'/utilities/editor.php?edit='.$cwd.'/'.$file.' " target="_blank">'.$file.'</a></td>';
                //echo '<td>'.posix_getpwuid(fileowner($cwd.'/'.$file))["name"].'</td>';
                //echo '<td>'.substr(sprintf('%o', fileperms($cwd.'/'.$file)), -3).'</td>';
            }
            echo '<tr></p>';
        }
    echo '</tbody></table>';

require('/var/www/html/footer.php');
?>
