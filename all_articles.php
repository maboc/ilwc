<?php
  include 'sessionstuff.php';
  include_once 'logging.php';
  magdit(1);

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
  do_log("alle artikelen (editor)");
?>
    <table >
<?php
  $con=mysqli_connect("192.168.2.110", "ilwc", "ilwc", "ilwc");
  if(! $con){
    die("Foute boel" . mysqli_error($con));
  }

  $sql="select   a.id, 
                 a.title,
                 a.image,
                 u.real_name,
                 a.published, 
                 a.visits,
                 c.aantal,
                 a.zichtbaar_van,
                 a.zichtbaar_tot  
        from     articles a
                   left join users u
                     on a.author_id=u.id
                   left join (
                               select   article_id, 
                                        count(*) aantal 
                               from     comments 
                               group by article_id
                             ) c 
                     on a.id=c.article_id
        order by creation_date desc";
  $result=mysqli_query($con, $sql);

  while ($row=mysqli_fetch_row($result)){
    printf("<form action=\"\" method=\"post\">");
    printf("<input type=hidden name=\"id\" value=\"" . $row[0] . "\">");
    printf("<tr>
              <td>%s</td>
              <td><a href=/editor.php?id=%s>%s</a></td>
              <td><img src=\"%s\" width=\"25px\"/></td>
              <td>%s</td>
              <td>%s</td>
              <td>%s</td>
              <td>%s</td>
              <td>%s</td>
              <td>%s</td>
              <td><input type=submit name=\"delete_button\" value=\"Delete\"</td>
           </tr>", $row[0], $row[0],$row[1],$row[2],$row[3],$row[4],$row[5],$row[6],$row[7],$row[8]); 
    printf("</form>");
  }

  mysqli_close($con);

?>
    </table>
  </body>
</html>
