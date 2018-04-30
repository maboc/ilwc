<?php
  include 'sessionstuff.php';
  include_once 'helpers.php';
  magdit(0);
?>

<html>
<?php
  include 'head.php';
?>
  <body >
<?php
  magdit(0);

  include 'menu.php';
  include_once 'logging.php';

  $van=$_REQUEST["van_veld"];
  $van=sanitize($van);
  if(empty($van)){
    $van="---";
  }

  $naar=$_REQUEST["naar_veld"];
  $naar=sanitize($naar);
  if(empty($naar)){
    $naar="---";
  }

  do_log("whats loading van :" . $van . " naar : " . $naar);

  $con=mysqli_connect("192.168.2.110", "ilwc", "ilwc", "ilwc");
  if(! $con){
    die("Foute boel" . mysqli_error($con));
  }
?>
    <form method=post action="">
      <table align="center" width="40%">
        <tr>
          <td valign=top>
            <table>
              <tr>
                <td colspan=2>
                  <input type=text name=van_veld value=
<?php
  echo $van;
?>>               <input type=submit value=submit>
                  <hr>
                  Triggers requests to:
                </td>
              </tr>
            
<?php
  $sql1="select   naar,
                  count(*)
         from     b
         where    van!=naar
                  and van like '%" . $van . "%'
         group by naar
         order by 2 desc";

  $res1=mysqli_query($con, $sql1);
  while($row=mysqli_fetch_row($res1))
  {
    printf("<tr><td>%s</td><td>%s</td></tr>", $row[0], $row[1]);
  }
?>
            </table>
          </td>
          <td valign=top>
            <table>
              <tr>
                <td colspan=2>
                  <input type=text name=naar_veld value=
<?php
  echo $naar;
?>>
                 <input type=submit value=submit>
                 <hr>
                 Gets requested by:
              </td>
            </tr>

<?php
  $sql2="select   van,
                  count(*)
         from     b
         where    van!=naar
                  and naar like '%" . $naar . "%'
         group by van
         order by 2 desc";

  $res1=mysqli_query($con, $sql2);
  while($row=mysqli_fetch_row($res1))
  {
    printf("<tr><td>%s</td><td>%s</td></tr>", $row[0], $row[1]);
  }

  mysqli_close($con);
?>
            </table>
          </td>
        </tr> 
      </table>
    </form>
  </body>
</html>
