<?php
require_once("../comments/inc/config.php");
include 'inc/header.php';

if ($_SESSION['usertype']=="1"){

}else{
	?><script>
		   
			(function () {
						alert("You must be logged in as an administrator.. Modaretors can not access settings.");

						setTimeout(function () {
						
								window.location.href="index.php";
							
						}, 250);
			   
			 
			}());
		</script>
		<?php

	exit();
	}




?>

<div class="main">
	
	<div class="main-inner">

	    <div class="container">
	
	      <div class="row">
	      	
	      	<div class="span12">      		
	      		     <div class="page-header">
					<h2 class="pull-left">
									  <i class="fa fa-cog "></i>
									  <span>Settings</span>
					</h2> 
					  <div class="pull-right">
									  <ul class="breadcrumb">
										<li>
										  <a href="index.php">
											<i class="fa fa-home"></i>
										  </a>
										</li>
								 
										<li class="separator">
										  <i class="fa fa-angle-right"></i>
										</li>
										<li class="active">Import Settings</li>
									  </ul>
									</div>
					</div>
					<div class="clearfix"></div>
	      		<div class="widget ">
	      			
					<div class="widget-content">

						
						
						<div class="tabbable">
						<ul class="nav nav-tabs" style="  background-color: #eee; margin: -21px -19px 0 -16px;">
						
						  <li class="active">
						    <a href="#core" data-toggle="tab">Core Settings</a>
						  </li>
						</ul>
						
						<br>
												<?php
						function write_php_ini($array, $file)
{
							$res = array();
							foreach($array as $key => $val)
							{
								if(is_array($val))
								{
									$res[] = "[$key]";
									foreach($val as $skey => $sval) {
										if (is_array($sval)) {
											foreach ($sval as $i=>$v) {
												$res[] = "{$skey}[$i] = $v";
											}
										}
										else {
											$res[] = "$skey = $sval";
										}
									}
								}
								else $res[] = "$key = $val";
							}
							safefilerewrite($file, implode("\r\n", $res));
						}

						//////
						function safefilerewrite($fileName, $dataToSave)
						{    
							 file_put_contents($fileName, $dataToSave, LOCK_EX);    
						}
						
						
						if (isset($_GET['success'])){ 
						
							ECHO '<div class="alert alert-success  alert-block"><button type="button" class="close" data-dismiss="alert">×</button> <strong>Success!</strong> Changes successfully saved.</div>';
						
						}elseif (isset($_GET['error'])){ 
						
							ECHO '<div class="alert alert-error  alert-block"><button type="button" class="close" data-dismiss="alert">×</button> <strong>Error!</strong> '.$_GET["error"].'</div>';
						
						}elseif (isset($_GET['import'])){ 
							
							$opts = array(
									'http'=>array(
											'method'=>"GET",
											'header'=>"Accept-language: en\r\n" .
											"Cookie: foo=bar\r\n" .
											"User-Agent: Mozilla/5.0 (iPad; U; CPU OS 3_2 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Version/4.0.4 Mobile/7B334b Safari/531.21.102011-10-16 20:23:10\r\n" // i.e. An iPad
									)
							);
							
							$context = stream_context_create($opts);
						
							$json = file_get_contents($_POST["jsonUrl"], false, $context);
							$siteAbv = 'ABEN';
							$siteDomain = 'http://www.arabianbusiness.com/';
							$siteDomainAccess = 'kyL2OC4xOC45';
							
							$obj = json_decode($json);
							foreach ($obj as $comment) { 
								
								$state = $comment->state == 'published' ? 1 : 0;
								$spoiler = 0;
								$u_ip = '';
								$out_id = 0;
								$out_name = '';
								$out_link = '';
								$out_icon = '';
								$userId = 0;
								$content_ref = $siteAbv."-".$comment->articleId;
								$rsqam  = $dbpdo->query("Select count(*) from comments where source_id = '".$comment->id."'");
								$commentData = $rsqam->fetch();
								
								$type = $siteDomain;
								
								if($comment->parentid != 0){
									$parentChkDataObj  = $dbpdo->query("Select id from comments where source_id = '".$comment->parentid."'");
									$parentData = $parentChkDataObj->fetch();
									$contentID = !empty($parentData) && $parentData[0] ? $parentData[0] : $content_ref;
									$type = 'commentanswer';
								}else{
									$contentID = $content_ref;
								}
								
								if(!empty($commentData) && $commentData[0] > 0){
									
									//update
									$return = $dbpdo->prepare("UpDate comments Set user_id = '$userId' ,  parent_id = '$comment->parentid' , 
										source_id = '$comment->id' ,  comment = :comment, content_id = '$contentID' , content_ref = '$content_ref' , type = '$type' ,
										domainaccess = '$siteDomainAccess' ,  date = '$comment->publishedDate' , spoiler = '$spoiler' ,  approve = '$state' , 
										u_name = :u_name , u_email = :u_email , out_name = '$out_name' , out_link = '$out_link' ,  out_icon = '$out_icon' ,  u_ip = '$u_ip' 	 
										where source_id = '$comment->id' and site = '$siteAbv'");
									$return->bindParam(":comment",$comment->body);
									$return->bindParam(":u_name",$comment->name);
									$return->bindParam(":u_email",$comment->email);
										
									$return = $return->execute();
									
								}else{
									
									
									$return = $dbpdo->prepare("INSERT INTO comments (source_id,parent_id,user_id,comment,content_id,content_ref,type,domainaccess,date,spoiler,approve,
											u_name,u_email,u_ip,out_id,out_name,out_link,out_icon,site) VALUES 
											( :sourceId, :parentId, :userId , :comment , :contentID , :contentRef , :type , :domainaccess , :publishedDate, :spoiler, :db_commentsappow, 
											:u_name, :u_email, :u_ip, :out_id, :out_name, :out_link, :out_icon, :site)");
									$return->bindParam(":userId",$userId);
									$return->bindParam(":sourceId",$comment->id);
									$return->bindParam(":parentId",$comment->parentid);
									$return->bindParam(":comment",$comment->body);
									$return->bindParam(":contentID",$contentID);
									$return->bindParam(":contentRef",$content_ref);
									$return->bindParam(":type",$type);
									$return->bindParam(":domainaccess",$siteDomainAccess);
									$return->bindParam(":publishedDate",$comment->publishedDate);
									$return->bindParam(":spoiler",$spoiler);
									$return->bindParam(":db_commentsappow",$state);
									$return->bindParam(":u_name",$comment->name);
									$return->bindParam(":u_email",$comment->email);
									$return->bindParam(":u_ip",$u_ip);
									$return->bindParam(":out_id",$out_id);
									$return->bindParam(":out_name",$out_name);
									$return->bindParam(":out_link",$out_link);
									$return->bindParam(":out_icon",$out_icon);
									$return->bindParam(":site",$siteAbv);
									
									$return = $return->execute();
								}
							}						
												
							
						
						}
						?>
						<form action="?import" method="post"  enctype="multipart/form-data">
							<fieldset>
							<div class="tab-content">
								<div class="tab-pane active" id="core">
										
										<div class="control-group">											
											<label class="control-label" for="jsonUrl">Json Url</label>
											<div class="controls">
												<input type="text" class="span6 " id="jsonUrl" name="jsonUrl" value="">
											</div> <!-- /controls -->				
										</div> <!-- /control-group -->									
									
								</div>								
								<div class="form-actions" style="    background-color: #F9F9F9;margin-bottom: -32px;margin-left: -14px; margin-right: -14px;">
											<button type="submit" class="btn btn-primary">Import</button> 
								</div> <!-- /form-actions -->
								</fieldset>
							</div>
						  </form>
						  
						</div>
						
						
						
						
						
					</div> <!-- /widget-content -->
						
				</div> <!-- /widget -->
	      		
		    </div> <!-- /span8 -->
	      	
	      	
	      	
	      	
	      </div> <!-- /row -->
	
	    </div> <!-- /container -->
	    
	</div> <!-- /main-inner -->
    
</div> <!-- /main -->
    




<?php
 include 'inc/footer.php';
 
 $pagegone="?page=yorum";
?><script type="text/javascript" charset="utf-8">
	
			$(document).ready(function() {
					$('#example').dataTable( {
					 "order": [ 1, 'desc' ], 
					"bProcessing": true,
					"bServerSide": true,
					"sAjaxSource": "posts/reports_processing.php<?php echo $pagegone ?>",
					"bPaginate":true, 
					"sPaginationType":"full_numbers",
					
					
					"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
						
						
					  return nRow;
						
						}
					
					
				});
			
			});
		</script>	