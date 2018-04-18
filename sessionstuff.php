<?php
  session_start();
  
  function banned(){
    $rc=false;
    $client  = isset($_SERVER['HTTP_CLIENT_IP'])?$_SERVER['HTTP_CLIENT_IP']:"";
    $forward = isset($_SERVER['HTTP_X_FORWARDED_FOR'])?$_SERVER['HTTP_X_FORWARDED_FOR']:"";
    $remote  = isset($_SERVER['REMOTE_ADDR'])?$_SERVER['REMOTE_ADDR']:"";
   
    $sql="select count(*) from bans where adres='$client' or adres='$forward' or adres='$remote'";

    $con=mysqli_connect("192.168.2.110", "ilwc", "ilwc", "ilwc");
    if(! $con){
      echo "voor<br>";
      die("Foute boel" . mysqli_error($con));
      echo "na<br>";
    }
    
    $result=mysqli_query($con, $sql);
    $row=mysqli_fetch_row($result);
    mysqli_close($con);

    if($row[0]>0){
      $rc=true;
    }
  
    return $rc; 
  }

  if(banned()){
    die("You are banned");
  }

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

  function magditboolean($action_level){
    if (! isset($_SESSION["level"])){
      $session_level=0;
    } else {
      $session_level=$_SESSION["level"];
    }

    if ($action_level>$session_level) {
      $rc=False;
    } else {
      $rc=True;
    }

   return $rc;
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
