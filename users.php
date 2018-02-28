<?php
  include 'sessionstuff.php';
  include_once 'logging.php';
  magdit(2);

  if(isset($_POST["delete_button"])) {
    $con=mysqli_connect("192.168.2.110", "ilwc", "ilwc", "ilwc");
    if(! $con){
      die("Foute boel" . mysqli_error($con));
    }

    $aid=$_POST["id"];

    do_log_no_aid("delete comments for article " . $aid);
    $sql="delete from comments where article_id='" . $aid . "'";
    $r=mysqli_query($con, $sql);

    do_log_no_aid("delete history for article " . $aid);
    $sql="delete from history where article_id='" . $aid . "'";
    $r=mysqli_query($con, $sql);
    
    do_log_no_aid("delete article " . $aid);
    $sql="delete from articles where id='" . $aid . "'";
    $r=mysqli_query($con, $sql);

    mysqli_close($con);
  }

?>

<html>
  <head>
    <title>
      ILWC
    </title>
  </head>
  <body >
<?php

  include 'menu.php';
  do_log("all users (admin)");
?>
    <table >
<?php
  $con=mysqli_connect("192.168.2.110", "ilwc", "ilwc", "ilwc");
  if(! $con){
    die("Foute boel" . mysqli_error($con));
  }

  $sql="select   u.id,
                 u.name, 
                 u.real_name,
                 u.email,
                 u.aanmelding,
                 r.role_name,
                 u.actief 
        from     users u
                   left join user_role ur
                     on u.id = ur. user_id
                   left join roles r
                     on ur.role_id=r.id 
        order by name asc";

  $result=mysqli_query($con, $sql);

  while ($row=mysqli_fetch_row($result)){
    printf("<form action=\"\" method=\"post\">");
    printf("<input type=hidden name=\"id\" value=\"" . $row[0] . "\">");
    printf("<tr>
              <td>%s</td>
              <td>%s</td>
              <td>%s</td>
              <td>%s</td>
              <td>%s</td>
              <td>%s</td>
              <td><input type=checkbox name=actief_veld %s/ disabled></td>
           </tr>", $row[0], $row[1],$row[2],$row[3],$row[4],$row[5],($row[6]==1?"checked":"")); 
    printf("</form>");
  }

  mysqli_close($con);

?>
    </table>
  </body>
</html>
