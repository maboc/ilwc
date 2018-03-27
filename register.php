<?php
  include 'sessionstuff.php';
  magdit(0);
?>

<?php
  include_once 'logging.php';
?>

<?php
  if(isset($_POST["register_submit"])){
    do_log("Try to register : " . $_POST["naam_veld"] . "/" . $_POST["echte_naam_veld"]);

    $con=mysqli_connect("192.168.2.110", "ilwc", "ilwc", "ilwc");
    if(! $con){
      die("Foute boel" . mysqli_error($con));
    }

    $sql="insert into users (name, real_name, email, pwd_hash, aanmelding) values('" . $_POST["naam_veld"] . "','" . $_POST["echte_naam_veld"] . "','" . $_POST["email_veld"] . "',password('" . $_POST["password_veld"] . "'), now())";
    $result=mysqli_query($con, $sql);

    $sql="insert into user_role (user_id, role_id) values ((select id from users where name='". $_POST["naam_veld"] ."'), (select id from roles where role_name='User'))";
    $r1=mysqli_query($con, $sql);

  }
?>

<html>
  <head>
    <link rel="icon" href="http://ilwc.nl/cross.ico">
    <title>
      ILWC
    </title>
  </head>
  <body >
<?php
  include 'menu.php';
  do_log("register pagina");

  if (isset($result)){
    if ($result==1){
      printf("Gelukt...Je kunt nu inloggen met de Login naam en het password dat je zojuist hebt geregistreerd.");
      do_log("apparently we succeeded");
    } else {
      printf("Helaas ... Niet gelukt, probeer het nog een keer");
      do_log("apparently we did not succeed");
    }
  }

?>
    <form action="register.php" method="post">
      <table>
        <tr><td>Login naam</td><td><input type=text name=naam_veld /></td></tr>
        <tr><td>Echte naam</td><td><input type=text name=echte_naam_veld /></td></tr>
        <tr><td>Password</td><td><input type=secret name=password_veld /></td></tr>
        <tr><td>Email</td><td><input type=text name=email_veld /></td></tr>
        <tr><td colspan=2><input type=submit name=register_submit value=Registreer /></td></tr>
      </table>
    </form>
  </body>
</html
