<?php
  include 'sessionstuff.php';
  include_once 'logging.php';
  magdit(2);
  do_log("Statistieken bekijken");
?>

<html>
<?php
  include 'head.php';
?>
  <body >
<?php

  include 'menu.php';

  $con=mysqli_connect("192.168.2.110", "ilwc", "ilwc", "ilwc", 3307);
  if(! $con){
    die("Foute boel" . mysqli_error($con));
  }

  $sql="select count(*) from history";
  $result=mysqli_query($con, $sql);
  $row=mysqli_fetch_row($result);
  printf("Total number of lines in history : %s", $row[0]);
  printf("<br><br>");

  $sql="select   date(wanneer), 
                 count(*) 
        from     history 
        where    not(wanneer is null)
        group by date(wanneer) 
        order by date(wanneer) desc";
  $result=mysqli_query($con, $sql);

  printf("Bewegingen per dag");
  printf("<table>");
  while($row=mysqli_fetch_row($result)) {
    printf("<tr><td>%s</td><td>%s</td></tr>", $row[0], $row[1]);
  }
  printf("</table>");

  printf("<br><br>");
  $sql="select u.name, t.n
        from   users u
                 left join (
                             select   login_id, 
                                      count(*) n 
                             from     history 
                             where    not(login_id is null)
                             group by login_id 
                           ) t
                   on u.id=t.login_id
        order by 2 desc";
  $result=mysqli_query($con, $sql);

  printf("Bewegingen per bekende login");
  printf("<table>");
  while($row=mysqli_fetch_row($result)) {
    printf("<tr><td>%s</td><td>%s</td></tr>", $row[0], $row[1]);
  }
  printf("</table>");

  $sql="select a.h, 
               round(100*a.n/b.n), 
               rpad('',round(100*a.n/b.n),'|') 
        from   (
                 select hour(wanneer) h, 
                        count(*) n 
                 from   history 
                 where  not(wanneer is null) 
                 group by hour(wanneer) 
                 order by hour(wanneer)
               ) a, 
               (
                 select count(*) n 
                 from   history 
                 where  not(wanneer is null)
               ) b";
  $result=mysqli_query($con, $sql);

  printf("Bewegingen per uur");
  printf("<table>");
  while($row=mysqli_fetch_row($result)) {
    printf("<tr><td>%s</td><td>%s</td><td>%s</td></tr>", $row[0], $row[1], $row[2]);
  }
  printf("</table>");




  printf("<br><br>");
  $sql="select   address, 
                 count(*) n 
        from     history 
        where    not(address is null)
        group by address 
        having   n>1
        order by 2 desc";
  $result=mysqli_query($con, $sql);

  printf("Bezoek per IP-adres");
  printf("<table>");
  while($row=mysqli_fetch_row($result)) {
    printf("<tr><td>%s</td><td>%s</td></tr>", $row[0], $row[1]);
  }
  printf("</table>");

  mysqli_close($con);
?>
  </body>
</html>
