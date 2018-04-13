<?php
  include 'sessionstuff.php';
  include_once 'logging.php';
  magdit(2);
?>

<html>
<?php
  include 'head.php';
?>
  <body >
<?php

  include 'menu.php';
  do_log("Zoektermen bekijken");
?>
    <table >
<?php
  $con=mysqli_connect("192.168.2.110", "ilwc", "ilwc", "ilwc");
  if(! $con){
    die("Foute boel" . mysqli_error($con));
  }

  $sql="select  z.zoekterm, count(*) 
        from    zoekterm z 
        group by zoekterm 
        order by 2 desc"; 
  $result=mysqli_query($con, $sql);

  while ($row=mysqli_fetch_row($result)){
    printf("<tr>
              <td>%s</td>
              <td>%s</td>
           </tr>", $row[0], $row[1]); 
  }

  mysqli_close($con);

?>
    </table>
  </body>
</html>
