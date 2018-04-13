<?php
  include 'sessionstuff.php';
  magdit(1);
?>

<?php
  include_once 'logging.php';
  include_once 'helpers.php';
  do_log("Artikel editen");

  if(isset($_POST["insert_submit"])) {
    $sql="insert into articles (title, image, author_id, creation_date, published, samenvatting, body, zichtbaar_van, zichtbaar_tot)";
    $sql.=" values ('" . $_POST["titel_veld"] . "',";
    $sql .="        '" .  $_POST["image_veld"] . "',";
    $sql .="        (select id from users where name='" .  $_SESSION["user"] . "'),";
    $sql .="        now(), ";
    $sql .=         ((isset($_POST["published_veld"])) && (! empty($_POST["published_veld"]))?"true":"false") . ",";
    $sql .="        '" . $_POST["samenvatting_veld"] . "',";
    $sql .="        '" .  $_POST["body_veld"] . "',";
    $sql .="        '" . $_POST["zichtbaar_van_veld"] . "',";
    $sql .="        '" . $_POST["zichtbaar_tot_veld"] . "')";


    $con=mysqli_connect("192.168.2.110", "ilwc", "ilwc", "ilwc");
    if(! $con){
      die("Foute boel" . mysqli_error($con));
    }
    $result=mysqli_query($con, $sql);

    $sql="select id ";
    $sql.="from  articles ";
    $sql.="where title='" . $_POST["titel_veld"] . "'";
    $sql.="      and image='" . $_POST["image_veld"] . "'";
    $sql.="      and author_id=(select id from users where name='" .  $_SESSION["user"] . "')";
    $sql.="      and samenvatting='" . $_POST["samenvatting_veld"] . "'";
    $sql.="      and body='" .  $_POST["body_veld"] . "'"; 

    $result=mysqli_query($con, $sql);
    $row=mysqli_fetch_row($result);

    $aid=$row[0];

    if(isset($_POST["tags_veld"])){
      $tags_veld=$_POST["tags_veld"];
      process_tags($aid, $tags_veld);
    }

    mysqli_close($con);
  } elseif(isset($_POST["update_submit"])) {

    $sql="update articles set title='" . $_POST["titel_veld"] . "',";
    $sql.="                   image='" .  $_POST["image_veld"] . "',";
    $sql.="                   published=" . ((isset($_POST["published_veld"])) && (! empty($_POST["published_veld"]))?"true":"false") . ",";
    $sql.="                   samenvatting='" . $_POST["samenvatting_veld"] . "',";
    $sql.="                   body='" .  $_POST["body_veld"] . "', ";
    $sql.="                   zichtbaar_van='" . $_POST["zichtbaar_van_veld"] . "',";
    $sql.="                   zichtbaar_tot='" . $_POST["zichtbaar_tot_veld"] . "' ";
    $sql.="where id=" . $_POST["id_veld"]; 

    $con=mysqli_connect("192.168.2.110", "ilwc", "ilwc", "ilwc");
    if(! $con){
      die("Foute boel" . mysqli_error($con));
    }
    $result=mysqli_query($con, $sql);

    $sql="select id ";
    $sql.="from  articles ";
    $sql.="where title='" . $_POST["titel_veld"] . "'"; 
    $sql.="      and image='" . $_POST["image_veld"] . "'"; 
    $sql.="      and author_id=(select id from users where name='" .  $_SESSION["user"] . "')"; 
    $sql.="      and samenvatting='" . $_POST["samenvatting_veld"] . "'"; 
    $sql.="      and body='" .  $_POST["body_veld"] . "'"; 

    $result=mysqli_query($con, $sql);
    $row=mysqli_fetch_row($result);

    $aid=$row[0];

    if(isset($_POST["tags_veld"])){
      $tags_veld=$_POST["tags_veld"];
      process_tags($aid, $tags_veld);
    }

    mysqli_close($con);
  } else {
     if(isset($_REQUEST["id"])){
       $aid=$_REQUEST["id"];
     } else {
       $aid=0;
     }
  }
?>
<html>
<?php
  include 'head.php';
?>
  <body >
<?php
  include 'menu.php';
?>
<?php
  $con=mysqli_connect("192.168.2.110", "ilwc", "ilwc", "ilwc");
  if(! $con){
    die("Foute boel" . mysqli_error($con));
  }


  $sql="select   a.id, 
                 a.title,
                 a.body,
                 a.image,
                 u.name,
                 a.creation_date,
                 a.published,
                 a.samenvatting,
                 a.zichtbaar_van,
                 a.zichtbaar_tot
        from     articles a
                 left join users u
                   on a.author_id=u.id
        where    a.id=$aid
        order by creation_date desc";
 
  $result=mysqli_query($con, $sql);

  $row=mysqli_fetch_row($result);
  printf("<form action=\"\" method=post>");
  printf("<table>");
  printf("<tr><td>ID</td><td><input type=text name=id_veld value=\"%s\" readonly/></td></tr>",$row[0]); 
  printf("<tr><td>Titel</td><td><input type=text name=titel_veld value=\"%s\"/></td></tr>",$row[1]); 
  printf("<tr><td>Image</td><td><input type=text name=image_veld value=\"%s\"/></td></tr>",$row[3]); 
  printf("<tr><td>Auteur</td><td><input type=text name=auteur_veld value=\"%s\" readonly/></td></tr>",$row[4]); 
  printf("<tr><td>Datum Creatie</td><td><input type=text name=creatie_veld value=\"%s\" readonly/></td></tr>",$row[5]); 
  printf("<tr><td>Zichtbaar van</td><td><input type=text name=zichtbaar_van_veld value=\"%s\" /></td></tr>",$row[8]); 
  printf("<tr><td>Zichtbaar tot</td><td><input type=text name=zichtbaar_tot_veld value=\"%s\" /></td></tr>",$row[9]); 
  printf("<tr><td>Gepubliceerd</td><td><input type=checkbox value=\"Gepubliceerd\" name=published_veld %s/></td></tr>", ($row[6]==1?"checked":""));
  printf("<tr><td>Tags</td><td><input type=text name=tags_veld value=\"");

  $sql="select tag from tags t left join article_tag_link atl on atl.tag_id=t.id where atl.article_id=$aid";
  $res2=mysqli_query($con, $sql);
  $tags="";
  while ($row2=mysqli_fetch_row($res2)){
    $tags.=$row2[0] . ";";
  }
 
  $l=strlen($tags);
  $tags=substr($tags, 0, $l-1);
 
  printf("%s\"/></td></tr>", $tags);
  printf("<tr><td>Samenvatting</td><td><textarea name=samenvatting_veld id=\"samenvatting_veld\" rows=25 cols=100 onkeyup=\"samenvattingVoorbeeld();\">%s</textarea></td><td><div id=samenvattingvoorbeeld></div></td></tr>", $row[7]);
  printf("<tr><td>Body</td><td><textarea rows=50 cols=100 onkeyup=\"bodyVoorbeeld();\" name=\"body_veld\" id=\"body_veld\">%s</textarea></td><td><div id=\"bodyvoorbeeld\"></div></td></tr>", $row[2]);
  if(! empty($aid)){
    $action="update_submit";
    $value="Update";
  } else {
    $action="insert_submit";
    $value="Voegtoe";
  } 
  printf("<tr><td colspan=2><input type=submit name=\"" . $action . "\" value=\"" . $value . "\"></td></tr>");
  printf("</table>");
  printf("</form>");

  mysqli_close($con);

?>
  </body>
</html>
