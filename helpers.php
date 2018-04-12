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

  function process_tags($aid, $tags_veld){
    $con2=mysqli_connect("192.168.2.110", "ilwc", "ilwc", "ilwc");
    if(! $con2){
      die("Foute boel" . mysqli_error($con));
    }

    /*first delete all article_tag_links*/
    $sql="delete from article_tag_link where article_id=$aid";
    mysqli_query($con2, $sql);

    $tags=array();

    $start=0;
    $p=strpos($tags_veld, ";", $start);
    while($p !== false){
      $tag=substr($tags_veld, $start, $p-$start);
      array_push($tags, $tag);
      $start=$p+1;
      $p=strpos($tags_veld, ";", $start);
    }
    $tag=substr($tags_veld,$start);
    array_push($tags, $tag);

    foreach($tags as $tag){
      $sql="insert into tags (tag) select lower(trim('$tag')) from dual where not exists (select * from tags where lower(tag)=lower(trim('$tag')))";
      mysqli_query($con2, $sql);

      $sql="insert into article_tag_link (article_id, tag_id) values('$aid', (select id from tags where lower(tag)=lower(trim('$tag'))))";
      mysqli_query($con2, $sql);
    }

    mysqli_close($con2);
  }

?>
