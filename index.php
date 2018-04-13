<?php
  include 'sessionstuff.php';
  include_once 'helpers.php';
  magdit(0);
?>

<html>
  <head>
    <link rel="icon" href="http://ilwc.nl/cross.ico">
    <script src="bieb.js" type="text/javascript"></script>
    <title>
      ILWC
    </title>
  </head>
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
