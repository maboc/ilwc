<?php
  include 'sessionstuff.php';
  magdit(0);
?>

<?php
  include_once 'logging.php';
  $aid=$_REQUEST["id"];
  do_log("Artikel bekijken");

  $con=mysqli_connect("192.168.2.110", "ilwc", "ilwc", "ilwc", 3307);
  if(! $con){
    die("Foute boel" . mysqli_error($con));
  }

  if(isset($_POST["submit_comment"])){
    $comment=$_REQUEST["comment"];
    if(! empty($_SESSION["user"])){
      $user=$_SESSION["user"];
      $sql="insert into comments (author_id, comment, creation_date, article_id) values((select id from users where name='" . $user . "') ,'" . $comment . "', now(), '" . $aid . "')";
    } else {
       $sql="insert into comments (author_id, comment, creation_date, article_id) values(null ,'" . $comment . "', now(), '" . $aid . "')";
    }
    
    $result=mysqli_query($con, $sql);

    do_log("comment toegevoegd");
  }

  if(isset($_POST["disable_comment"])){
    $cid=$_REQUEST["cid"];
    $sql="update comments set published=false where id=" . $cid;

    $result=mysqli_query($con, $sql);

    do_log("comment " . $cid . "verwijderd");
  }

  $sql="update articles set visits=visits+1 where id='" . $aid . "'";
  mysqli_query($con, $sql);
  mysqli_close($con);

?>

<html>
<?php
  include 'head.php';
?>
  <body >
<?php
  include 'menu.php';

  if (zichtbaar($aid)){
?>
    <table align="center" width="40%">
<?php
  $con=mysqli_connect("192.168.2.110", "ilwc", "ilwc", "ilwc", 3307);
  if(! $con){
    die("Foute boel" . mysqli_error($con));
  }

  $sql="select   a.id, 
                 a.title,
                 a.body,
                 a.image,
                 u.name,
                 a.creation_date,
                 a.zichtbaar_van,
                 a.zichtbaar_tot
        from     articles a
                 left join users u
                   on a.author_id=u.id
        where    a.id=$aid
        order by creation_date desc";

  $result=mysqli_query($con, $sql);

  $sql="select tag from tags t left join article_tag_link atl on atl.tag_id=t.id where atl.article_id=$aid";
  $res2=mysqli_query($con, $sql);

  $tags="";
  while ($row2=mysqli_fetch_row($res2)){
    $tags.="<a href=index.php?list_type=tag&tag=" . $row2[0] . ">" . $row2[0] . "</a> ";
  }

  while ($row=mysqli_fetch_row($result)){
    $twitter=$row[1];
    printf("<tr><td><img src=\"%s\" height=\"50px\"/></td><td>", $row[3]);
    if(magditboolean(1)){
      printf("<a href=/editor.php?id=%s>%s</a>", $row[0], $row[0]);
    } else {
      printf("%s", $row[0]);
    }
    printf("<br/>%s<br/>%s<br/>%s</td>", $row[1], $row[4], $tags);
    printf("<td style=\"font-size:x-small; text-align: left;\">%s<br/>%s</br>%s</td></tr>", $row[5], $row[6], $row[7]);
    printf("<tr><td colspan=\"3\">%s</td></tr>", $row[2]);
  }
 
  mysqli_close($con);

  printf("</table>");

  printf("<a class=\"twitter-share-button\" href=\"https://twitter.com/intent/tweet?text=%s\" data-size=\"small\" ></a>", $twitter);

  printf("<br/><br/>");
  printf("<div align=\"center\">");

  printf("<form action=\"detail.php\" method=\"post\">");
  printf("<input type=\"hidden\" name=\"id\" value=\"%s\"/>", $aid);
?>
        <textarea name="comment" cols=80 rows=5></textarea><br/>
        <input type="submit" name="submit_comment" value="Toevoegen"/>
      </form>
    </div>

    <table align="center" width="40%">
<?php
  $con=mysqli_connect("192.168.2.110", "ilwc", "ilwc", "ilwc", 3307);
  if(! $con){
    die("Foute boel" . mysqli_error($con));
  }
  
  $sql="select   c.id,
                 case when isnull(c.author_id) then 'Anonymous' else u.name end,
                 c.comment,
                 c.creation_date
        from     comments c
                   left join users u
                     on c.author_id=u.id
        where    c.article_id=" . $aid ."
                 and c.published=true
        order by c.creation_date desc";

  $result=mysqli_query($con, $sql);
  while($row=mysqli_fetch_array($result)){
    printf("<tr><td>%s %s %s</td></tr><tr><td>%s</td>", $row[0], $row[1], $row[3], $row[2]);

    if(magditboolean(1)){
      printf("<td>");
      printf("<form method=post action=\"\">");
      printf("<input type=hidden name=cid value=\"%s\" />", $row[0]);
      printf("<input type=\"hidden\" name=\"id\" value=\"%s\" />", $aid);
      printf("<input type=\"submit\" name=\"disable_comment\" value=\"wegermee\"/>");
      printf("</form>");
      printf("</td>");
    }
    printf("</tr>");
    printf("<tr><td>&nbsp;</td></tr>");
  } 
?>
    </table>

  <?php
  } else {
    do_log("Dit artikel ($aid) bestaat niet of is unpublished");
    printf("<b>Dit artikel bestaat niet of is niet zichtbaar</b>");
  }
  ?>
  </body>
</html>
