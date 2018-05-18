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

  $van_dagen=$_REQUEST["van_dagen_veld"];
  $van_dagen=sanitize($van_dagen);
  if(empty($van_dagen)){
    $van_dagen=1;
  }

  $naar=$_REQUEST["naar_veld"];
  $naar=sanitize($naar);
  if(empty($naar)){
    $naar="---";
  }

  $naar_dagen=$_REQUEST["naar_dagen_veld"];
  $naar_dagen=sanitize($naar_dagen);
  if(empty($naar_dagen)){
    $naar_dagen=1;
  }

  do_log("whats loading van :" . $van . " naar : " . $naar);

  $con=mysqli_connect("192.168.2.110", "ilwc", "ilwc", "ilwc", 3307);
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
?>>               <input type=submit value=submit><br>
                  In last <input type=text name=van_dagen_veld value=
<?php
  echo $van_dagen;
?> size=5> days. 
                  <hr>
                  Triggers requests to:
                </td>
              </tr>
            
<?php
  $sql1="select   naar_domein,
                  count(*)
         from     loads
         where    van_domein!=naar_domein
                  and van_domein like '%" . $van . "%'
                  and datum>now() - interval $van_dagen day
         group by naar_domein
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
                 <input type=submit value=submit><br>
                 In last <input type=text name=naar_dagen_veld value=
<?php
  echo $naar_dagen;
?>
 size=5> days. 
                 <hr>
                 Gets requested by:
              </td>
            </tr>

<?php
  $sql2="select   van_domein,
                  count(*)
         from     loads
         where    van_domein!=naar_domein
                  and naar_domein like '%" . $naar . "%'
                  and datum>now() - interval $naar_dagen day
         group by van_domein
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
