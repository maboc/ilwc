<?php
  $con=mysqli_connect("192.168.2.110", "ilwc", "ilwc", "ilwc");
  if(! $con){
    die("Foute boel" . mysqli_error($con));
  }

  $van=isset($_REQUEST["van"])?$_REQUEST["van"]:'-';
  $naar=isset($_REQUEST["naar"])?$_REQUEST["naar"]:'-'; 

  $sql="insert into loads(van, naar) values ('$van','$naar')";

  mysqli_query($con, $sql);

    mysqli_close($con);
?>
