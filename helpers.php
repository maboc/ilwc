<?php
  function about_aid(){
    $sql="select int_waarde
          from   config 
          where  lower(item) = lower('about')";  

    $con=mysqli_connect("192.168.2.110", "ilwc", "ilwc", "ilwc");
    if(! $con){
      die("Foute boel" . mysqli_error($con));
    }

    $result=mysqli_query($con, $sql);
    $row=mysqli_fetch_row($result);

    return $row[0];  

  }

 function about_menu_text(){
    $sql="select string_waarde
          from   config
          where  lower(item) = lower('about_menu_text')";

    $con=mysqli_connect("192.168.2.110", "ilwc", "ilwc", "ilwc");
    if(! $con){
      die("Foute boel" . mysqli_error($con));
    }

    $result=mysqli_query($con, $sql);
    $row=mysqli_fetch_row($result);

    return $row[0];
  }

?>
