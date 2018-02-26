<?php
include 'sessionstuff.php';
?>

<?php
  include_once 'logging.php';
  $aid=$_REQUEST["id"];
  do_log("Artikel bekijken");

  $con=mysqli_connect("192.168.2.110", "ilwc", "ilwc", "ilwc");
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

  $sql="update articles set visits=visits+1 where id='" . $aid . "'";
  mysqli_query($con, $sql);
  mysqli_close($con);

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
?>
    <table align="center" width="40%">
<?php
  $con=mysqli_connect("192.168.2.110", "ilwc", "ilwc", "ilwc");
  if(! $con){
    die("Foute boel" . mysqli_error($con));
  }


  $sql="select   a.id, 
                 a.title,
                 a.body,
                 a.image,
                 u.name
        from     articles a
                 left join users u
                   on a.author_id=u.id
        where    a.id=$aid
        order by creation_date desc";
 
  $result=mysqli_query($con, $sql);

  while ($row=mysqli_fetch_row($result)){
    printf("<tr><td><img src=\"%s\" height=\"50px\"/></td><td>%s<br/>%s<br/>%s</td></tr>", $row[3], $row[0],  $row[1], $row[4]); 
    printf("<tr><td colspan=\"2\">%s</td></tr>", $row[2]);
  }

  mysqli_close($con);

?>
    </table>
    <br/><br/>
    <div align="center">
<?php
  printf("<form action=\"detail.php\" method=\"post\">", $aid );
  printf("<input type=\"hidden\" name=\"id\" value=\"%s\"/>", $aid);
?>
        <textarea name="comment" cols=80 rows=5></textarea><br/>
        <input type="submit" name="submit_comment" value="Toevoegen"/>
      </form>
    </div>

    <table align="center" width="40%">
<?php
  $con=mysqli_connect("192.168.2.110", "ilwc", "ilwc", "ilwc");
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
    printf("<tr><td>%s %s %s</td></tr><tr><td>%s</td></tr>", $row[0], $row[1], $row[3], $row[2]);
    printf("<tr><td>&nbsp;</td></tr>");
  } 
?>
    </table>
  </body>
</html>
