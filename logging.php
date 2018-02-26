<?php
  function do_log($textje){
    $con=mysqli_connect("192.168.2.110", "ilwc", "ilwc", "ilwc");
    if(! $con){
      die("Foute boel" . mysqli_error($con));
    }

    if(isset($_REQUEST["id"])){
      $aid=$_REQUEST["id"];
    } else {
      $aid="";
    }

    if(isset($_SESSION["session"])){
      $login=$_SESSION["user"];
    } else {
      $login="";
    }

    $client  = isset($_SERVER['HTTP_CLIENT_IP'])?$_SERVER['HTTP_CLIENT_IP']:"";
    $forward = isset($_SERVER['HTTP_X_FORWARDED_FOR'])?$_SERVER['HTTP_X_FORWARDED_FOR']:"";
    $remote  = isset($_SERVER['REMOTE_ADDR'])?$_SERVER['REMOTE_ADDR']:"";

    $ip=$client . "-" . $forward . "-" . $remote;

    if(! empty($aid)){
      $sql="insert into history (article_id, login_id, what, address, wanneer) 
                         values ('" . $aid . "', (select id from users where name='" . $login . "'), '" . $textje . "', '" . $ip ."', now())";
    } else {
      $sql="insert into history (article_id, login_id, what, address, wanneer)
                        values (null, (select id from users where name='" . $login . "'), '" . $textje . "', '". $ip . "', now())";
    }
    $result=mysqli_query($con, $sql);

    mysqli_close($con);
  }

  function do_log_no_aid($textje){
    $con=mysqli_connect("192.168.2.110", "ilwc", "ilwc", "ilwc");
    if(! $con){
      die("Foute boel" . mysqli_error($con));
    }

    if(isset($_SESSION["session"])){
      $login=$_SESSION["user"];
    } else {
      $login="";
    }

    $client  = isset($_SERVER['HTTP_CLIENT_IP'])?$_SERVER['HTTP_CLIENT_IP']:"";
    $forward = isset($_SERVER['HTTP_X_FORWARDED_FOR'])?$_SERVER['HTTP_X_FORWARDED_FOR']:"";
    $remote  = isset($_SERVER['REMOTE_ADDR'])?$_SERVER['REMOTE_ADDR']:"";

    $ip=$client . "-" . $forward . "-" . $remote;

    $sql="insert into history (login_id, what, address, wanneer)
                        values ((select id from users where name='" . $login . "'), '" . $textje . "', '". $ip . "', now())";

    $result=mysqli_query($con, $sql);

    mysqli_close($con);

  }
?>
