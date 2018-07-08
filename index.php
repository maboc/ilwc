<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

  include 'sessionstuff.php';
  include_once 'helpers.php';
  magdit(0);
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
  do_log("index opvragen");
?>
    <table align="center" width="40%">
<?php
  
  if(isset($_REQUEST["list_type"])){
    $list_type=$_REQUEST["list_type"];
    if($list_type=="tag"){
      if(isset($_REQUEST["tag"])){
        $tag=$_REQUEST["tag"];
        show_articles("tag", $tag);
      } else {
        echo "No tag given <br>";
      }
    }
  } else {
    show_articles("index", "");
  }
?>
    </table>
  </body>
</html>
