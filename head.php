<?php
echo "<head>";
echo "  <link rel=\"stylesheet\" href=\"ilwc.css\">";
echo "  <link rel=\"icon\" href=\"http://ilwc.nl/cross.ico\">";
echo "  <script src=\"bieb.js\" type=\"text/javascript\"></script>";
?>
<script>
      window.twttr = (
                       function(d, s, id) {
                         var js, fjs = d.getElementsByTagName(s)[0], t = window.twttr || {};
                         if (d.getElementById(id)) return t;
                         js = d.createElement(s);
                         js.id = id;
                         js.src = "https://platform.twitter.com/widgets.js";
                         fjs.parentNode.insertBefore(js, fjs);

                         t._e = [];
                         t.ready = function(f) {
                           t._e.push(f);
                         };

                         return t;
                       }(document, "script", "twitter-wjs")
                     );

      function bodyVoorbeeld(){
        input=document.getElementById("body_veld");
        output=document.getElementById("bodyvoorbeeld");

        output.innerHTML=input.value;
      }

      function samenvattingVoorbeeld(){
        input=document.getElementById("samenvatting_veld");
        output=document.getElementById("samenvattingvoorbeeld");

        output.innerHTML=input.value;
      }
    </script>
<?php
echo "  <title>";
echo "    ILWC";
echo "  </title>";
echo "</head>";
?>
