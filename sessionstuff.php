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
?>
