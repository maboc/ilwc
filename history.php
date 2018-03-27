<?php
  include 'sessionstuff.php';
  magdit(2);
?>

<html>
  <head>
    <link rel="icon" href="http://ilwc.nl/cross.ico">
    <title>
      ILWC
    </title>
  </head>
  <body >
<?php
  include 'menu.php';
  include_once 'logging.php';
  do_log("Check history");
?>
    <table >
<?php
  $con=mysqli_connect("192.168.2.110", "ilwc", "ilwc", "ilwc");
  if(! $con){
    die("Foute boel" . mysqli_error($con));
  }

  $sql="select   h.id, 
                 left(a.title,50),
                 u.name,
                 h.what,
                 left(h.address,50),
                 h.wanneer,
                 a.id
        from     history h
                 left join users u
                   on h.login_id=u.id
                 left join articles a
                   on a.id=h.article_id
        order by wanneer desc";

  $result=mysqli_query($con, $sql);

  while ($row=mysqli_fetch_row($result)){
    printf("<tr><td>%s</td><td><a href=detail.php?id=%s>%s</a></td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>", $row[0], $row[6], $row[1],$row[2],$row[3],$row[4],$row[5]); 
  }

  mysqli_close($con);

?>
    </table>
  </body>
</html>
