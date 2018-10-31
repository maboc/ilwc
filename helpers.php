<?php
  function privacy_aid(){
    $sql="select int_waarde
          from   config
          where  lower(item) = lower('privacy_article')";

    $con=mysqli_connect("192.168.2.110", "ilwc", "ilwc", "ilwc", 3307);
    if(! $con){
      die("Foute boel" . mysqli_error($con));
    }

    $result=mysqli_query($con, $sql);
    $row=mysqli_fetch_row($result);

    return $row[0];

  }

  function privacy_menu_text(){
    $sql="select string_waarde
          from   config
          where  lower(item) = lower('privacy_menu_text')";

    $con=mysqli_connect("192.168.2.110", "ilwc", "ilwc", "ilwc", 3307);
    if(! $con){
      die("Foute boel" . mysqli_error($con));
    }

    $result=mysqli_query($con, $sql);
    $row=mysqli_fetch_row($result);

    return $row[0];
  }

  function about_aid(){
    $sql="select int_waarde
          from   config 
          where  lower(item) = lower('about')";  

    $con=mysqli_connect("192.168.2.110", "ilwc", "ilwc", "ilwc", 3307);
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

    $con=mysqli_connect("192.168.2.110", "ilwc", "ilwc", "ilwc", 3307);
    if(! $con){
      die("Foute boel" . mysqli_error($con));
    }

    $result=mysqli_query($con, $sql);
    $row=mysqli_fetch_row($result);

    return $row[0];
  }

  function process_tags($aid, $tags_veld){
    $con2=mysqli_connect("192.168.2.110", "ilwc", "ilwc", "ilwc", 3307);
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

  function show_articles($list_type, $p1, $p2){
    if ($list_type=="index"){
      $sql="select   a.id,
                     a.title,
                     a.samenvatting,
                     a.image,
                     u.real_name
            from     articles a
                     left join users u
                       on a.author_id=u.id
            where    a.published=True
                     and now() between a.zichtbaar_van and a.zichtbaar_tot
            order by zichtbaar_van desc";
    } elseif($list_type=="tag"){
      $sql="select   a.id,
                     a.title,
                     a.samenvatting,
                     a.image,
                     u.real_name
            from     articles a
                     left join users u
                       on a.author_id=u.id
                     left join article_tag_link atl
                       on a.id=atl.article_id
                     left join tags t
                       on t.id=atl.tag_id
            where    a.published=True
                     -- and now() between a.zichtbaar_van and a.zichtbaar_tot
                     and t.tag='$p1'  
            order by zichtbaar_van desc"; 
    } elseif($list_type=="year_month") {
      $sql="select   a.id,
                     a.title,
                     a.samenvatting,
                     a.image,
                     u.real_name
            from     articles a
                     left join users u
                       on a.author_id=u.id
            where    a.published=True
                     -- and now() between a.zichtbaar_van and a.zichtbaar_tot
                     and year(a.creation_date)=$p1
                     and month(a.creation_date)=$p2
            order by zichtbaar_van desc";
    }
    

    $con=mysqli_connect("192.168.2.110", "ilwc", "ilwc", "ilwc", 3307);
    if(! $con){
      die("Foute boel" . mysqli_error($con));
    }
    $result=mysqli_query($con, $sql);

    while ($row=mysqli_fetch_row($result)){
      printf("<tr><td><img src=\"%s\" height=\"50px\"/></td><td><a href=detail.php?id=%s>%s<br/>%s<br/>%s</a></td></tr>", $row[3], $row[0] ,$row[0],  $row[1], $row[4]);
      printf("<tr><td colspan=\"2\">%s</td></tr>", $row[2]);
      printf("<tr><td colspan=\"2\">&nbsp;</td></tr>");
    }

    mysqli_close($con);
  }


  function sanitize($inp){
    $terug=str_replace("'", "&apos;", $inp);
    $a=str_replace("<<", "&#60;", $terug);
    $b=str_replace(">>", "&#62;", $a);
    $c=str_replace("\\", "&#92;", $b);
    return $c;
  }
?>
