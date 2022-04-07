<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="footer">
  <strong>Copyright</strong> <a href=""  target="_blank"></a> &copy; <?= date("Y");?>
    <input type="hidden" id="get_csrf_hash" name="<?=$name?>" value="<?=$value?>">

</div>
<script type="text/javascript">
   /* $(document).ready(function () {
    var token = $("#get_csrf_hash").val();
    setTimeout(
    $.ajax({
        type: 'POST',
        url: base_url+'sessionRenew.php',
        data: 'rand='+Math.random()+"&get_csrf_name="+token,
        dataType: 'json',
    })
    ,100);
    });*/
   var token = document.getElementById("#get_csrf_hash");
    window.setInterval(function() {
        var el=document.createElement('img');
        el.src= base_url+'sessionRenew.php?rand='+Math.random()+"&get_csrf_name="+token;
        el.style.opacity=.01;
        el.style.width=1;
        el.style.height=1;
        el.style.display="none";
        el.onload=function() {
            document.body.removeChild(el);
        }
        document.body.appendChild(el);
    }, 30000);
</script>

</div>
</div>
</body>
</html>
