<?php
  include 'sessionstuff.php';
  include_once 'logging.php';
  magdit(2);

  if(isset($_POST["change_button"])) {
    $con=mysqli_connect("192.168.2.110", "ilwc", "ilwc", "ilwc", 3307);
    if(! $con){
      die("Foute boel" . mysqli_error($con));
    }

    $cid=$_POST["cid"];
    $old_int=$_POST["old_int"];
    $new_int=$_POST["new_int"];
    $old_string=$_POST["old_string"];
    $new_string=$_POST["new_string"];
    
    do_log_no_aid("Changing config item $cid ($old_int --> $new_int) en ($old_string --> $new_string)");

    $sql="update config set int_waarde='" . $new_int . "', string_waarde='" . $new_string . "' where id='" . $cid . "'";
    mysqli_query($con, $sql);

    mysqli_close($con);
  }

?>

<html>
<?php
  include 'head.php';
?>
  
  <body >
<?php

  include 'menu.php';
  do_log("Config editor");
?>
    <table>
      <thead>
        <tr>
          <td>ID</td>
          <td>Item</td>
          <td>Int waarde</td>
          <td>String waarde</td>
      </tr>
    </thead>
<?php
  $con=mysqli_connect("192.168.2.110", "ilwc", "ilwc", "ilwc", 3307);
  if(! $con){
    die("Foute boel" . mysqli_error($con));
  }

  $sql="select   c.id, 
                 c.item,
                 c.int_waarde,
                 c.string_waarde
        from     config c";
  $result=mysqli_query($con, $sql);

  while ($row=mysqli_fetch_row($result)){
    printf("<form action=\"\" method=\"post\">");
    printf("<input type=hidden name=\"cid\" value=\"" . $row[0] . "\">");
    printf("<input type=hidden name=\"old_int\" value=\"" . $row[2] . "\">");
    printf("<input type=hidden name=\"old_string\" value=\"" . $row[3] . "\">");
    printf("<tr>
              <td>%s</td>
              <td>%s</td>
              <td><input type=text value=\"%s\" name=new_int></td>
              <td><input type=text value=\"%s\" name=new_string></td>
              <td><input type=submit value=\"Change\" name=change_button></td>
           </tr>", $row[0], $row[1],$row[2],$row[3]);
    printf("</form>");
  }

  mysqli_close($con);

?>
    </table>
  </body>
</html>
