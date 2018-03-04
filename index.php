<?php
  include 'sessionstuff.php';
  magdit(0);
?>

<html>
  <head>
    <title>
      ILWC
    </title>
  </head>
  <body >
<?php
  magdit(0);

  include 'menu.php';
  include_once 'logging.php';
  do_log("index opvrage");
?>
    <table align="center" width="40%">
<?php
  $con=mysqli_connect("192.168.2.110", "ilwc", "ilwc", "ilwc");
  if(! $con){
    die("Foute boel" . mysqli_error($con));
  }

  $sql="select   a.id, 
                 a.title,
                 a.samenvatting,
                 a.image,
                 u.real_name 
        from     articles a
                 left join users u
                   on a.author_id=u.id
        where    a.published=True
                 and now() between a.zichtbaar_van and a.zichtbaar_tot
        order by zichtbaar_van desc";
  $result=mysqli_query($con, $sql);

  while ($row=mysqli_fetch_row($result)){
    printf("<tr><td><img src=\"%s\" height=\"50px\"/></td><td><a href=detail.php?id=%s>%s<br/>%s<br/>%s</a></td></tr>", $row[3], $row[0] ,$row[0],  $row[1], $row[4]); 
    printf("<tr><td colspan=\"2\">%s</td></tr>", $row[2]);
    printf("<tr><td colspan=\"2\">&nbsp;</td></tr>");
  }

  mysqli_close($con);

?>
    </table>
  </body>
</html>
