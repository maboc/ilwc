<?php
  include 'sessionstuff.php';
  magdit(1);
?>

<?php
  //We gaan uploaden
  include 'helpers.php';

  if(isset($_REQUEST["submit_document"])){
    $dest="/var/www/html/uploads/";
    $f=$_FILES["fileToUpload"]["name"];
    $t=$_FILES["fileToUpload"]["type"];
    $s=$_FILES["fileToUpload"]["size"];
    $target=$dest . $f;

    $sql="insert into documents (naam, locatie, w, h, datum) values('" . sanitize($_REQUEST["naam_veld"]) . "', '$target',  '" . sanitize($_REQUEST["w_veld"]) . "', '" . $_REQUEST["h_veld"] . "', now())";
    
    $con=mysqli_connect("192.168.2.110", "ilwc", "ilwc", "ilwc", 3307);
    if(! $con){
      die("Foute boel" . mysqli_error($con));
    }

    $res=mysqli_query($con, $sql);
    if($res==true){
      move_uploaded_file($_FILES["fileToUpload"]["tmp_name"] , $target);
    } else {
      echo "Er gaat iets mis met uploaden";
    }
  } else {
    echo "Nee... niets te uploaden<br>";
  }
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
  do_log("Uploaden aangeroepen");

?>
    <table align="center" width="40%">
      <tr>
        <form method=POST action="" enctype="multipart/form-data">
          <td></td>
          <td><input type=text name=naam_veld></td>
          <td><input type=file name="fileToUpload" id="fileToUpload">
          <td><input type=text name=w_veld></td>
          <td><input type=text name=h_veld></td>
          <td><input type=submit name=submit_document value="Doe dan"></td>
        </form>
      </tr>

<?php
  $con=mysqli_connect("192.168.2.110", "ilwc", "ilwc", "ilwc", 3307);
  if(! $con){
    die("Foute boel" . mysqli_error($con));
  }
  
  $sql="select   d.id,
                 d.naam,
                 d.locatie,
                 d.w,
                 d.h,
                 d.datum
        from     documents d
        order by d.naam";
  $result=mysqli_query($con, $sql);

  while ($row=mysqli_fetch_row($result)){
    printf("<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>", $row[0], $row[1], $row[2], $row[3], $row[4], $row[5]); 
  }

  mysqli_close($con);

?>
    </table>
  </body>
</html>
