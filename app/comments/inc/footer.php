<script>var lang_1='<?php echo $lang["JS_1"];?>', lang_3='<?php echo $lang["JS_3"];?>';</script>
<script type="text/javascript">
        var url = "<?php echo $db_siteadres ?>";
        var request_url = url + 'app/comments/request/';
         var POSTid = '<?php echo $C_id;?>';
        var POSTurl = '<?php echo $C_url;?>';

        var CUSER = '<?php echo $CUSER; ?>';
        var CUSER_NAME = '<?php echo $CUSER_NAME; ?>';
        var CUSER_LINK = '<?php echo $CUSER_LINK; ?>';
        var CUSER_ID = '<?php echo $CUSER_ID; ?>';
        var CUSER_ICON = '<?php echo $CUSER_ICON; ?>';

        var AccessToken = '<?php echo $domainaccess;?>';
        var facebookAPP = '<?php echo $db_faceapcode;?>';

        commentapp = <?php  if($db_commentsappow=='0' ){  echo "true";}else{echo "false";} ?>; </script>
<script type="text/javascript" src="<?php echo $db_siteadres ?>app/assets/lib/jquery-min.js"></script>
<script type="text/javascript" src="<?php echo $db_siteadres ?>app/assets/main.js"></script>
</body>
</html>
<?php
ob_end_flush();
$dbpdo=null;
?>