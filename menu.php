<?php
  include_once 'logging.php';
  include_once 'helpers.php';

  if(isset($_REQUEST["loginsubmit"])){
   
    $con=mysqli_connect("192.168.2.110", "ilwc", "ilwc", "ilwc", 3307);
    if(! $con){
      die("Foute boel" . mysqli_error($con));
    }

    $sql="select count(*)
          from   users
          where  name='" . $_REQUEST["loginname"] . "'  
                 and pwd_hash=password('" . $_REQUEST["password"] . "')";

    $result=mysqli_query($con, $sql);

    $row=mysqli_fetch_row($result);

    if($row[0]>=1){
      $_SESSION["session"]="yes";
      $_SESSION["user"]=$_REQUEST["loginname"];

      $sql="select lvl,
                   role_name
            from   roles r,
                   user_role ur,
                   users u
            where  ur.role_id =r.id
                   and ur.user_id=u.id
                   and u.name='" . $_SESSION["user"] . "'";
      $result=mysqli_query($con, $sql);
      $row=mysqli_fetch_row($result);

      $_SESSION["level"]=$row[0];
      $_SESSION["role_name"]=$row[1];

      do_log("Geslaagde inlog poging : ". $_SESSION["user"]); 
    } else {
      printf("Helaas...valse bingo");
      do_log("Mislukte inlog poging : ". $_REQUEST["loginname"]); 
    }

    mysqli_close($con);
  }

  if(isset($_REQUEST["logout"])){
    do_log("En weer uitloggen : ". $_SESSION["user"]);
    session_unset();
    session_destroy();
  }
?>

<!-- menu voor iedereen -->
<div style="background-color:lightblue;">
  <table width=100%>
    <tr>
<!-- main menu -->
      <td valign=top>
        <a href="http://www.ilwc.nl/">ILWC</a>
      </td>
<!-- tags menu -->
      <td valign=top>
        <div class=dropdown-menu>
          Tags
          <div class=dropdown-content>
<?php
  $sql="select   t.tag, 
                 count(*) 
        from     tags t 
                   inner join article_tag_link atl 
                     on t.id=atl.tag_id 
                   left join articles a 
                     on a.id=atl.article_id 
        where    a.published=true 
        group by t.tag
        order by count(*) desc,
                 t.tag";

  $con=mysqli_connect("192.168.2.110", "ilwc", "ilwc", "ilwc", 3307);
    if(! $con){
      die("Foute boel" . mysqli_error($con));
    }

  $result=mysqli_query($con, $sql);
  while($row=mysqli_fetch_row($result)){
    echo "<a href=index.php?list_type=tag&tag=$row[0]>$row[0] ($row[1])</a>";
  }

  mysqli_close($con);
?>
          </div>
        </div>
      </td>
<!-- Year-Month menu -->
      <td valign=top>
        <div class=dropdown-menu>
          Month-Year
          <div class=dropdown-content>
<?php
  $sql="select   YEAR(creation_date), 
                 MONTH(creation_date),
                 count(*) 
        from     articles 
        group by YEAR(creation_date), 
                 MONTH(Creation_date) 
        order by year(creation_date) desc,
                 month(creation_date) desc";

  $con=mysqli_connect("192.168.2.110", "ilwc", "ilwc", "ilwc", 3307);
    if(! $con){
      die("Foute boel" . mysqli_error($con));
    }

  $result=mysqli_query($con, $sql);
  while($row=mysqli_fetch_row($result)){
    echo "<a href=index.php?list_type=year_month&y=$row[0]&m=$row[1]>$row[0] - $row[1] ($row[2])</a>";
  }

  mysqli_close($con);
?>
          </div>
        </div>

      </td>
<!-- What's loading menu -->
      <td valign=top>
        <a href=whatsloading.php>What is loading</a>
      </td>
<!-- Privacy menu -->
      <td valign=top>
<?php
  printf("<a href=\"http://www.ilwc.nl/detail.php?id=%s\">%s</a>",privacy_aid(),privacy_menu_text());
?>
      </td>
<!-- about menu -->
      <td valign=top>
<?php
  printf("<a href=\"http://www.ilwc.nl/detail.php?id=%s\">%s</a>",about_aid(),about_menu_text());
?>
      </td>
      <td align=right>
        <form action="search.php" method="post">
          <input type=text name=zoek_veld value="Zoek">
        </form> 
      </td>
      <td align=right>
<?php
  if(! isset($_SESSION["session"])){
?>
  <form action="/" method="post">
    <a href="register.php">Registreer</a>
    <input type="text" name="loginname"/>
    <input type="password" name="password"/>
    <input type="submit" name="loginsubmit" value="login"/>
  </form>
<?php
  } else {
    printf("Welcome %s", $_SESSION["user"] . "(" . $_SESSION["role_name"] . ")");
?>
</td>
<td align=right>
<form action="" method="post">
  <input type="submit" name="logout" value="logout"/>
</form>
<?php
  }
?>
</td>
</tr>
</table>
</div>

<!-- menu voor editors -->
<?php
//if ((isset($_SESSION["level"])) && ($_SESSION["level"]>=1)){
if(magditboolean(1)){
?>
<div style="background-color:lightyellow;">
  <a href="all_articles.php">Alle artikelen</a>
  <a href="editor.php">New Article</a>
  <a href="upload.php">Upload</a>
</div>
<?php
}
?>

<!-- menu voor admins -->
<?php
//if ((isset($_SESSION["level"])) && ($_SESSION["level"]>=2)){
if(magditboolean(2)){
?>
<div style="background-color:lightpink;">
  <a href="history.php">History</a>
  <a href="users.php">Users</a>
  <a href="meta.php">Statistieken</a>
  <a href="zoektermen.php">Zoektermen</a>
  <a href="config.php">Config</a>
  <a href="bans.php">Bans</a>
  <a href="actions.php">Actions</a>
</div>
<?php
}
?>
