<?php
  $con=mysqli_connect("192.168.2.110", "ilwc", "ilwc", "ilwc", 3307);
  if(! $con){
    die("Foute boel" . mysqli_error($con));
  }

  $van=isset($_REQUEST["van"])?$_REQUEST["van"]:'-';
  $naar=isset($_REQUEST["naar"])?$_REQUEST["naar"]:'-'; 

  $sql="insert into loads(van, naar, datum) values ('$van','$naar', now())";

  mysqli_query($con, $sql);

    mysqli_close($con);
?>
