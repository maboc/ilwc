<?php
  include 'sessionstuff.php';
  include_once 'logging.php';
  magdit(2);

  if(isset($_POST["submit_ban"])) {
    $con=mysqli_connect("192.168.2.110", "ilwc", "ilwc", "ilwc", 3307);
    if(! $con){
      die("Foute boel" . mysqli_error($con));
    }
    $adres=$_POST["adres_veld"];
    $reden=$_POST["reden_veld"];
    do_log_no_aid("IP-adres $adres is verbannen");

    $sql="insert into bans (adres, reden, datum) values ('$adres','$reden', now())";
    mysqli_query($con, $sql);

    mysqli_close($con);
  }

  if(isset($_POST["release_ban"])) {
    $con=mysqli_connect("192.168.2.110", "ilwc", "ilwc", "ilwc", 3307);
    if(! $con){
      die("Foute boel" . mysqli_error($con));
    }
    $bid=$_POST["bid"];
    $adres=$_POST["adres"];
    do_log_no_aid("Release ban $bid ($adres)");

    $sql="delete from bans where id=$bid";
    mysqli_query($con, $sql);

    mysqli_close($con);
  }

?>

<html>
<?php
  include 'head.php';
?>
  
  <body >
<?php

  include 'menu.php';
  do_log("In banned...er moeten boefjes gecorrigeerd...");
?>
    <table>
      <thead>
        <tr>
          <td>ID</td>
          <td>adres</td>
          <td>Reden</td>
      </tr>
    </thead>
    <tr>
      <form action="" method=POST>
        <td></td><td><input type=text name=adres_veld /></td><td><input type=text name=reden_veld /></td><td><input type=submit name=submit_ban value=voegtoe /></td></tr>
      </form>
<?php
  $con=mysqli_connect("192.168.2.110", "ilwc", "ilwc", "ilwc", 3307);
  if(! $con){
    die("Foute boel" . mysqli_error($con));
  }

  $sql="select   b.id, 
                 b.adres,
                 b.reden,
                 b.datum
        from     bans b
        order by datum desc";
  $result=mysqli_query($con, $sql);

  while ($row=mysqli_fetch_row($result)){
    printf("<tr>
              <td>%s</td>
              <td>%s</td>
              <td>%s</td>
              <td>%s</td>
              <td>
                <form action=\"\" method=POST> 
                  <input type=submit value=Release name=release_ban />
                  <input type=hidden name=bid value=%s />
                  <input type=hidden name=adres value=%s />
                </form>
              </td>
           </tr>", $row[0], $row[1],$row[2],$row[3], $row[0], $row[1]);
  }

  mysqli_close($con);

?>
    </table>
  </body>
</html>
