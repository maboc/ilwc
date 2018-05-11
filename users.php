<?php
  include 'sessionstuff.php';
  include_once 'logging.php';
  magdit(2);

  if(isset($_POST["delete_submit"])) {
    $con=mysqli_connect("192.168.2.110", "ilwc", "ilwc", "ilwc", 3307);
    if(! $con){
      die("Foute boel" . mysqli_error($con));
    }

    $uid=$_POST["id"];
    $sql="select name from users where id=". $uid;
    $result=mysqli_query($con, $sql);
    $row=mysqli_fetch_row($result);

    $name=$row[0];

    do_log_no_aid("adapt comments while deleting user_id " . $uid . " (" . $name .")" );
    $sql="update comments set author_id=null, ";
    $sql.="      comment=concat('(deleted user " . $name . "(" . $uid . ") commented: )', comment), ";
    $sql.="      published=false ";
    $sql.="where author_id=" . $uid;
    $result=mysqli_query($con, $sql);
    
    do_log_no_aid("adapt articles while deleting user_id " . $uid . " (" . $name .")" );
    $sql="update articles set author_id=null, ";
    $sql.="      samenvatting=concat('(deleted user " . $name . "(" . $uid . ") wrote: )', samenvatting), ";
    $sql.="      body=concat('(deleted user " . $name . "(" . $uid . ") wrote: )', body), ";
    $sql.="      published=false ";
    $sql.="where author_id=" . $uid;
    $result=mysqli_query($con, $sql);

    
    do_log_no_aid("remove user role link while deleting user " . $uid . " (". $name . ")"); 
    $sql="delete from user_role where user_id=". $uid;
    $result=mysqli_query($con, $sql);

    do_log_no_uid("adapt history while deleting user_id " . $uid . " (" . $name .")" );
    $sql="update history set login_id=null, ";
    $sql.="      what=concat('(deleted user " . $name . "(" . $uid . ") did: )', what) ";
    $sql.="where login_id=" . $uid;
    $result=mysqli_query($con, $sql);

    do_log_no_uid("remove user " . $uid . " (". $name . ")"); 
    $sql="delete from users where id=" . $uid;
    $result=mysqli_query($con, $sql);

    mysqli_close($con);
  }

?>

<html>
<?php
  include 'head.php';
?>
  <head>
    <link rel="icon" href="http://ilwc.nl/cross.ico">
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
  $con=mysqli_connect("192.168.2.110", "ilwc", "ilwc", "ilwc", 3307);
  if(! $con){
    die("Foute boel" . mysqli_error($con));
  }

  $sql="select   u.id,
                 u.name, 
                 u.real_name,
                 u.email,
                 u.aanmelding,
                 r.role_name,
                 u.actief,
                 na.n_art,
                 nc.n_com 
        from     users u
                   left join user_role ur
                     on u.id = ur. user_id
                   left join roles r
                     on ur.role_id=r.id 
                   left join (select author_id, 
                                     count(*) n_art
                              from articles
                              group by author_id) na
                     on na. author_id=u.id
                   left join (
                               select author_id,
                                      count(*) n_com
                               from   comments
                               group by author_id
                             ) nc
                    on nc.author_id=u.id
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
              <td>%s</td>
              <td>%s</td>
              <td><input type=submit name=delete_submit value=delete></td>
           </tr>", $row[0], $row[1],$row[2],$row[3],$row[4],$row[5],($row[6]==1?"checked":""),$row[7],$row[8]); 
    printf("</form>");
  }

  mysqli_close($con);

?>
    </table>
  </body>
</html>
