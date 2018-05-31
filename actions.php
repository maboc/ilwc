<?php
  include 'sessionstuff.php';
  include_once 'helpers.php';
  include_once 'logging.php';
  magdit(0);

  if(isset($_REQUEST["clear_wl"])){
    do_log("Delete de (inhoud van de) loads tabel");
    $con=mysqli_connect("192.168.2.110", "ilwc", "ilwc", "ilwc", 3307);
    if(! $con){
      die("Foute boel" . mysqli_error($con));
    }

    $sql="delete from loads";

    $result=mysqli_query($con, $sql);
  }
    
?>

<html>
<?php
  include 'head.php';
?>
  <body >
<?php
  magdit(0);

  include 'menu.php';
  do_log("Action pagina");
  echo "<form method=POST action=\"\">";
  echo "<table>";
  echo "<tr><td>Clear What's loading</td><td><input type=submit name=clear_wl value=Clear></td></tr>";
  echo "</table>";
  echo "</form>";
?>
  </body>
</html>
