<?php
  include 'sessionstuff.php';
  magdit(0);
?>

<html>
  <head>
    <link rel="icon" href="http://ilwc.nl/cross.ico">
    <script src="bieb.js" type="text/javascript"></script>
    <title>
      ILWC
    </title>
  </head>
  <body >
<?php
  magdit(0);

  include 'menu.php';
  include_once 'logging.php';
  $zoeker=$_REQUEST["zoek_veld"];
  do_log("Search opgeroepen : " . $zoeker);

?>
    <table align="center" width="40%">
<?php
  $con=mysqli_connect("192.168.2.110", "ilwc", "ilwc", "ilwc");
  if(! $con){
    die("Foute boel" . mysqli_error($con));
  }

  $sql="insert into zoekterm (zoekterm) values ('" . $zoeker . "')";
  $result=mysqli_query($con, $sql);

  $sql="select   a.id,
                 a.title,
                 a.samenvatting
        from     articles a
        where    a.published=true
                 and (
                       (lower(a.samenvatting like lower('%" . $zoeker . "%'))) 
                       or (lower(a.body like lower('%" . $zoeker . "%'))) 
                       or (lower(a.title like lower('%" . $zoeker . "%')))
                     )
        order by zichtbaar_van desc";
  $result=mysqli_query($con, $sql);

  while ($row=mysqli_fetch_row($result)){
    printf("<tr><td><a href=detail.php?id=%s title=\"%s\">%s</a></td></tr>", $row[0], $row[2], $row[1]); 
  }

  mysqli_close($con);

?>
    </table>
  </body>
</html>
