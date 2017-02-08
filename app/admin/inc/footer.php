<?php if( !(isset($_SESSION["authKey"]) && $_SESSION["authKey"] == 'pSTyWw9UrabxUdQT')){ ?>
<div class="footer">
  <div class="footer-inner">
    <div class="container">
      <div class="row">
        <div class="span12"> 
        <?php echo $db_sitemotto; ?> © <?php echo date('Y'); ?>
        </div>
        <!-- /span12 --> 
      </div>
      <!-- /row --> 
    </div>
    <!-- /container --> 
  </div>
  <!-- /footer-inner --> 
</div>
<!-- /footer --> 
<?php }?>
<script src="../assets/bootstrap/js/jquery.min.js"></script> 
<script src="../assets/bootstrap/js/bootstrap.js"></script>
<script language="javascript" type="text/javascript" src="../assets/bootstrap/js/dataTables/jquery.dataTables.min.js"></script>
<script language="javascript" type="text/javascript" src="../assets/bootstrap/js/dataTables/dataTables.bootstrap.min.js"></script>
<script src="js/base.js"></script> 
<!-- /footer js --> 

<!-- analytics -->
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-67014778-1', 'auto');
  ga('send', 'pageview');

</script>
<script src="../assets/iframeResizer.contentWindow.js"></script>
</body>
</html>
<?php
$dbpdo=null;
ob_end_flush();
?>