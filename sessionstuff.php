<?php
  session_start();

  function magdit($page_level){
  if (! isset($_SESSION["level"])){
      $session_level=0;
    } else {
      $session_level=$_SESSION["level"];
    }
  
    if ($page_level>$session_level) {
      die("Je mag hier helemaal niet komen");
    }
  }
  
  function zichtbaar($aid){
    $sql="select count(*) 
          from   articles 
          where  id='" . $aid . "' 
                 and published=true";  

    $con=mysqli_connect("192.168.2.110", "ilwc", "ilwc", "ilwc");
    if(! $con){
      die("Foute boel" . mysqli_error($con));
    }

    $result=mysqli_query($con, $sql);
    $row=mysqli_fetch_row($result);

    if($row[0]>=1){
      $rc=1;
    } else {
      $rc=0;
    }

    return $rc;  
  }
?>
