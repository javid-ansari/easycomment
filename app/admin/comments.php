<?php
require_once("../comments/inc/config.php"); 
include 'inc/header.php';

$options = array
(
		'hostname' => $cong["SOLR_SERVER_HOSTNAME"],
		'path' 	   => $cong["SOLR_SERVER_PATH"],
		'port'     => $cong["SOLR_SERVER_PORT"],
);
$client = new SolrClient($options);

function addCommentDoc($commentid, $client, $dbpdo){
	if(isset($commentid) && $commentid !='' && isset($client) && $client != NULL){
		$yeniko  = $dbpdo->query("SELECT id , CONCAT('comment-',id) as comment_id, source_id, parent_id, `comment`, content_id, content_ref, content_url, content_title, site, user_id, u_name, u_email, DATE_FORMAT(STR_TO_DATE(`date`, '%Y%m%d%H%i%s'), '%Y-%m-%dT%H:%i:%sZ') AS formattedDate FROM comments where id = '$commentid' and approve=1 ");
		
		if($yeniko = $yeniko->fetch()){
			$doc = new SolrInputDocument();
			try {
			$doc->addField("comment_id", $yeniko["id"] );			
			$doc->addField("id", $yeniko["comment_id"] );		
			$doc->addField("source_id" , $yeniko["source_id"] != NULL ? $yeniko["source_id"] : 0 );	
			$doc->addField("parent_id" , $yeniko["parent_id"] != NULL ? $yeniko["parent_id"] : 0 );	
			$doc->addField("comment" , $yeniko["comment"] );	
			$doc->addField("content_id", $yeniko["content_id"]);		
			$doc->addField("content_ref" , $yeniko["content_ref"] != NULL ? $yeniko["content_ref"] : "" );	
			$doc->addField("content_url" , $yeniko["content_url"] != NULL ? $yeniko["content_url"] : "" );	
			$doc->addField("content_title" , $yeniko["content_title"] != NULL ? $yeniko["content_title"] : "" );
			$doc->addField("u_id" , $yeniko["user_id"] != NULL ? $yeniko["user_id"] : 0 );
			$doc->addField("u_email" , $yeniko["u_email"] != NULL ? $yeniko["u_email"] : "" );
			$doc->addField("u_name" , $yeniko["u_name"] != NULL ? $yeniko["u_name"] : "" );
			$doc->addField("ds_created", $yeniko["formattedDate"] );
			$doc->addField("site" , $yeniko["site"] );
			
			$updateResponse = $client->addDocument($doc);
			$client->commit();
			return true;
			}catch (Exception $e) {
				echo $e->getMessage();
				exit;
			}
		}
	}
	return false;
}

function updateCommentDoc($commentid, $client, $dbpdo){
	if(isset($commentid) && $commentid !='' && isset($client) && $client != NULL){
		$yeniko  = $dbpdo->query("SELECT id , CONCAT('comment-',id) as comment_id, source_id, parent_id, `comment`, content_id, content_ref, content_url, content_title, site, user_id, u_name, u_email, DATE_FORMAT(STR_TO_DATE(`date`, '%Y%m%d%H%i%s'), '%Y-%m-%dT%H:%i:%sZ') AS formattedDate FROM comments where id = '$commentid' and approve=1 ");
		
		deleteCommentDoc($commentid, $client);
		
		if($yeniko = $yeniko->fetch()){
			/*
			 * TODO update doc
			 */
			
			$doc = new SolrInputDocument();
			try {	
			$doc->addField("comment_id", $yeniko["id"] );			
			$doc->addField("id", $yeniko["comment_id"] );		
			$doc->addField("source_id" , $yeniko["source_id"] != NULL ? $yeniko["source_id"] : 0 );	
			$doc->addField("parent_id" , $yeniko["parent_id"] != NULL ? $yeniko["parent_id"] : 0 );	
			$doc->addField("comment" , $yeniko["comment"] );	
			$doc->addField("content_id", $yeniko["content_id"]);		
			$doc->addField("content_ref" , $yeniko["content_ref"] != NULL ? $yeniko["content_ref"] : "" );	
			$doc->addField("content_url" , $yeniko["content_url"] != NULL ? $yeniko["content_url"] : "" );	
			$doc->addField("content_title" , $yeniko["content_title"] != NULL ? $yeniko["content_title"] : "" );
			$doc->addField("u_id" , $yeniko["user_id"] != NULL ? $yeniko["user_id"] : 0 );
			$doc->addField("u_email" , $yeniko["u_email"] != NULL ? $yeniko["u_email"] : "" );
			$doc->addField("u_name" , $yeniko["u_name"] != NULL ? $yeniko["u_name"] : "" );
			$doc->addField("ds_created", $yeniko["formattedDate"] );
			$doc->addField("site" , $yeniko["site"] );
				
			$updateResponse = $client->addDocument($doc);
			$client->commit();
			}catch (Exception $e) {
				echo $e->getMessage();
				exit;
			}
		}
	}
	return false;
}

function deleteCommentDoc($commentid, $client){
	if(isset($commentid) && $commentid !='' && isset($client) && $client != NULL){
			try {
			$client->deleteByQuery('id:comment-'.$commentid.'');
			$client->commit();
			return true;
			}catch (Exception $e) {
				echo $e->getMessage();
				exit;
			}
	}
	return false;
}

if(isset($_GET['bysite'])){

	$bysite = $_GET['bysite'];
	$encoded_bysite=base64_encode($bysite);
	

}else if(isset($_POST['bysite'])){

	$bysite = $_POST['bysite'];
	$encoded_bysite=base64_encode($bysite);

}


if(isset($_GET['commentedit2'])){

$commentid=$_POST['commentid'];
$commenticerik=tirnak_replace($_POST['commenticerik']);

 $dbpdo->exec("UpDate comments Set comment = '$commenticerik' where id = '$commentid'");
 
 /*
  * add comment doc to solr
  */
 updateCommentDoc($commentid, $client, $dbpdo);
 
//header("Location: comments.php?comment=$commentid");

//exit();
}

if(isset($_GET['deletecomment'])){

	$commentid=$_GET['deletecomment'];
	$don=$_GET['don'];

	$dbpdo->exec("DELETE FROM comments where id = '$commentid'");
	$dbpdo->exec("DELETE FROM likes where content_id = '$commentid' and likestype='comment'");
	$dbpdo->exec("DELETE FROM flags where content_id = '$commentid' ");

	/*
	 * delete doc from solr
	 */
	deleteCommentDoc($commentid, $client);
	
	if ($don=="unapproved"){
	header("Location: comments.php?unapproved".(isset($bysite) ? "&bysite=".$bysite : ""));
		
	}elseif ($don=="reports"){
	header("Location: reports.php".(isset($bysite) ? "?bysite=".$bysite : ""));
		
	}else{
		header("Location: comments.php".(isset($bysite) ? "?bysite=".$bysite : ""));
		
	
	}

exit();
}
if(isset($_GET['approve'])){

	$commentid=$_GET['approve'];

	$dbpdo->exec("UpDate comments Set approve = '1' where id = '$commentid'");
	
	/*
	 * add comment doc to solr
	 */
	addCommentDoc($commentid, $client, $dbpdo);
	
	//header("Location: comments.php?unapproved".(isset($bysite) ? "&bysite=".$bysite : ""));
	header("Location: comments.php?".(isset($bysite) ? "bysite=".$bysite : ""));

exit();
}


?>
<div class="main">
	
	<div class="main-inner">

	    <div class="container">
		<div class="page-header">
		<h2 class="pull-left">
						  <i class="fa fa-comments red"></i>
						  <?php if(isset($_GET['unapproved'])){ ?>
						  <span>Unapproved Comments (<?php echo $dbpdo->query("select id from comments where approve = '0' ".(isset($encoded_bysite) && $encoded_bysite != '' ? 'AND domainaccess=\''.$encoded_bysite.'\'' : ''))->rowCount(); ?>)</span>
						  <?php }else{ ?>
						  <span>Comments (<a href="?unapproved<?php if(isset($bysite)){ echo '&bysite='.$bysite; }?>"><font color="green"><?php echo $dbpdo->query("select id from comments where approve = '0' ".(isset($encoded_bysite) && $encoded_bysite != '' ? 'AND domainaccess=\''.$encoded_bysite.'\'' : ''))->rowCount(); ?> Unapproved</font></a>)</span>
						  
						  <?php } ?>
		
		</h2> 
		  <div class="pull-right">

		
					  <div class="btn-group">
					  <a class="btn btn-primary" href="#"><i class="icon-user icon-white"></i><?php if(isset($bysite)){ echo $bysite; }else{ echo 'All'; } ?></a>
					  <a class="btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#"><span class="caret"></span></a>
					  <ul class="dropdown-menu">
					  <?php if(isset($bysite)){ 
					
					  echo '<li><a href="comments.php"> All</a></li>';
								
					  
					  } ?>
					  	<?php
							$sites="";
							foreach(explode(",", $db_allowedsites) as $line){
								if($line>""){

								//$sites=$sites."$line\r\n";
								echo '<li><a href="?bysite='.$line.'"> '.$line.'</a></li>';
								}
								
							} 				
						?>
		
					 
					  </ul>
					</div>
                                         

		</div>
		</div>
<div class="clearfix"></div>
<?php if(isset($_GET['comment'])){

		$commentid=$_GET['comment'];

	$yeniko  = $dbpdo->query("select * from comments where id = '$commentid'");

	if($yeniko = $yeniko->fetch()){
		$comment=temizle_replace($yeniko["comment"]);
		$user_id=$yeniko["user_id"];
		$date=$yeniko["date"];
		$content_id=$yeniko["content_id"];
		$feedtype=$yeniko["type"];
		$out_id	=  $yeniko['out_id'];
		$out_name	=  $yeniko['out_name'];
		$out_link	=  $yeniko['out_link'];
		$out_icon	=  $yeniko['out_icon'];

	}else{
		echo "Not found this comment.";
		exit();
	}
if($out_id==null){
	$rsqa  = $dbpdo->query("select icon,seoslug,user_id from users where id = '$user_id'");
	if($yeni = $rsqa->fetch()){
	
	$user_id=$yeni["user_id"];
	$icon=$yeni["icon"];
	$seoslug=$yeni["seoslug"];
	}
$iconne=resimcreate($icon,"s","member/avatar");	
		}					
			$orjorbas="";				
			?>
			
		<div class="row">
			<div class="span8">
					
					<div class="widget">
							
						<div class="widget-header">
							<i class="fa fa-comment"></i>
							<h3>Comment ID: <?php echo $commentid ?></h3>
						</div> <!-- /widget-header -->
						
						<div class="widget-content">
							
						
							
				
			<form action="?commentedit2" method="post" enctype="multypeart/form-data" >
			<input class="input-style addon" name="commentid" id="commentid" type="hidden" value="<?php echo $commentid ?>" style="width:350px">
			<div class="form-group clear">
				<label class="lab">User</label>
				<?php
				if($out_id > 0){
					?>
					<a href="<?php echo $out_link ?>" ><img src="<?php echo $out_icon ?>" style="width:35px;float:left;margin-right:8px;"><b style="float:left;font-weight:bold;"><?php echo $out_name ?></b><br><font size=2><?php echo timeConvert($tarih) ?></font></a>
					<?php
				}else{

				?>
				<a href="users.php?user=<?php echo $seoslug ?>"><img src="<?php echo $iconne ?>" style="width:35px;float:left;margin-right:8px;"><b style="float:left;font-weight:bold;"><?php echo $user_id ?></b><br><font size=2><?php echo timeConvert($date) ?></font></a>
				<?php

				}

				?>
				
				<div class="clear"> </div>
			</div>	

		<br>
		<br>
		
			<div class="form-group clear">
				<label class="lab">Comment</label>
				
				
				<textarea name="commenticerik"  class="inputug" style="width:700px;height:217px;"><?php echo $comment ?></textarea>
				<div class="clear"> </div>
			</div>
			
			

			<div class="postgo_section">
				<button class="btn btn-primary " type="submit"><i class="fa fa-save"></i> Edit</button>
				<a class="btn" href="comments.php" type="submit">Cancel</a>
				<a class='btn btn-danger ' style="float:right" onclick='return confirm("Do you realy want this?");' title='Delete Comment & Report' href='?deletecomment=<?php echo $commentid ?>&don=comments'><i class='fa fa-remove-sign'></i> Delete</a>
			</div>
			</form>
			
					</div> <!-- /widget-content -->
							
					</div> <!-- /widget -->	
					
			 </div>
			<div class="span4">
				<div class="widget">
							
					<div class="widget-header">
						<i class="fa fa-thumbs-up"></i>
						<h3>Who Likes: #<?php echo $commentid ?></h3>
					</div> <!-- /widget-header -->
						
					<div class="widget-content">
						
								<?php
								$kkkl=0;
	$yeniko  = $dbpdo->query("select * from likes where content_id = '$commentid' and typelike='like' limit 15");
	while($yenikoa = $yeniko->fetch()){
		
	$user_id=$yenikoa["user_id"];
	$date=$yenikoa["date"];

		$rsqa  = $dbpdo->query("select icon,seoslug,user_id from users where id = '$user_id'");
		if($yeni = $rsqa->fetch()){
		
		$user_id=$yeni["user_id"];
		$icon=$yeni["icon"];
		$seoslug=$yeni["seoslug"];
		$iconne=resimcreate($icon,"s","member/avatar");		
		
		echo '<a href="users.php?user='.$seoslug.'"><img src="'.$iconne.'" style="width:35px;float:left;margin-right:8px;"><b style="float:left;font-weight:bold;">'.$user_id.'</b><br><font size=2 color=#ccc>'.timeConvert($date).' </font></a><div class="clearfix"></div>';
		}
		$kkkl=1;
	}
	if($kkkl==0){
		echo 'Nothing to see here';
		
	}
		?>
						
					</div> <!-- /widget-content -->
							
				</div> <!-- /widget -->					
				<div class="widget">
							
					<div class="widget-header">
						<i class="fa fa-thumbs-down"></i>
						<h3>Who Unlikes: #<?php echo $commentid ?> </h3>
					</div> <!-- /widget-header -->
						
					<div class="widget-content">
						
										<?php 
										$kkkl=0;
	$yeniko  = $dbpdo->query("select * from likes where content_id = '$commentid' and typelike='unlike' limit 15");
	while($yenikof = $yeniko->fetch()){
	$user_id=$yenikof["user_id"];
	$date=$yenikof["date"];

		$rsqa  = $dbpdo->query("select icon,seoslug,user_id from users where id = '$user_id'");
		if($yeni = $rsqa->fetch()){
		
		$user_id=$yeni["user_id"];
		$icon=$yeni["icon"];
		$seoslug=$yeni["seoslug"];
		$iconne=resimcreate($icon,"s","member/avatar");		
		
		echo '<a href="users.php?user='.$seoslug.'"><img src="'.$iconne.'" style="width:35px;float:left;margin-right:8px;"><b style="float:left;font-weight:bold;">'.$user_id.'</b><br><font size=2 color=#ccc>'.timeConvert($date).' </font></a><div class="clearfix"></div>
				';
		}
	$kkkl=1;
	}
	if($kkkl==0){
		echo 'Nothing to see here';
		
	}
		?>
						
					</div> <!-- /widget-content -->
							
				</div> <!-- /widget -->		
					
				<div class="widget">
							
					<div class="widget-header">
						<i class="fa fa-flag"></i>
						<h3>Who Reported: #<?php echo $commentid ?></h3>
					</div> <!-- /widget-header -->
						
					<div class="widget-content">
						
										<?php 
										$kkkl=0;
	$yeniko  = $dbpdo->query("select * from flags where content_id = '$commentid' limit 15");
	while($yenikof = $yeniko->fetch()){
	$who=$yenikof["who"];
	$date=$yenikof["date"];

		$rsqa  = $dbpdo->query("select icon,seoslug,user_id from users where id = '$who'");
		if($yeni = $rsqa->fetch()){
		
		$user_id=$yeni["user_id"];
		$icon=$yeni["icon"];
		$seoslug=$yeni["seoslug"];
		$iconne=resimcreate($icon,"s","member/avatar");		
		
		echo '<a href="users.php?user='.$seoslug.'"><img src="'.$iconne.'" style="width:35px;float:left;margin-right:8px;"><b style="float:left;font-weight:bold;">'.$user_id.'</b><br><font size=2 color=#ccc>'.timeConvert($date).' </font></a><div class="clearfix"></div>
				';
		}
	$kkkl=1;
	}
	if($kkkl==0){
		echo 'Nothing to see here';
		
	}
		?>
						
					</div> <!-- /widget-content -->
							
				</div> <!-- /widget -->	
			</div>
		</div>	

		<?php }else{ ?>
		
		
	      <div class="row">
	      	
	      	<div class="span12">
	      		
	    
		
			<table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%" >
					<thead>
						<tr>
						
						<th width="1%" style="text-align:left">#</th>
						<th width="25%" style="text-align:left">By</th>	
						<th width="14%" style="text-align:left">Content</th>	
						<th width="40%">Comment</th>
						<th width="20%" style="text-align:center">Setting</th>
						</tr>
					</thead>

					<tfoot>
						<tr>
							
						<th width="1%" style="text-align:left">#</th>
						<th width="25%" style="text-align:left">By</th>	
						<th width="14%" style="text-align:left">Identifier</th>	
						<th width="40%">Comment</th>
						<th width="20%">Setting</th>
						</tr>
					</tfoot>

					<tbody>
				
					</tbody>
				</table>

		
		    </div> <!-- /spa12 -->
		    
		    	
			<?php } ?>

		    
	      </div> <!-- /row -->
	
	    </div> <!-- /container -->
    
	</div> <!-- /main-inner -->
	    
</div> <!-- /main -->
    



<?php
 include 'inc/footer.php';
?><script type="text/javascript" charset="utf-8">
	
			$(document).ready(function() {
					$('#example').dataTable( {
					 "order": [ 0, 'desc' ], 
					"bProcessing": true,
					"bServerSide": true,
					"sAjaxSource": "posts/comments_processing.php<?php if(isset($_GET['unapproved'])){ echo '?unapproved'; if(isset($bysite)){ echo '&bysite='.$bysite; } }elseif(isset($bysite)){ echo '?bysite='.$bysite; }elseif(isset($_GET['onlyreplies'])){ echo '?onlyreplies'; } ?>",
					"bPaginate":true, 
					"sPaginationType":"full_numbers",
					
					
					"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
						var u_email = aData[7]; // where 4 is the zero-origin column for 2D
						var u_name = aData[6]; // where 4 is the zero-origin column for 2D
						var comment = aData[5]; // where 4 is the zero-origin column for 2D
						var user_id = aData[1]; // where 4 is the zero-origin column for 2D
						var type = aData[2]; // where 4 is the zero-origin column for 2D
						var content_id = aData[3]; // where 4 is the zero-origin column for 2D
						var date = aData[4]; // where 4 is the zero-origin column for 2D
						var id = aData[0]; // where 4 is the zero-origin column for 2D
						var content_title = aData[8]; // where 4 is the zero-origin column for 2D
						var content_href = aData[9]; // where 4 is the zero-origin column for 2D
			
							
						$('td:eq(0)', nRow).html('<em class="date">'+id+'</em>'); 
						
						if(user_id==0){
							$('td:eq(1)', nRow).html('<a href="javascript:void(0);" style="color:#000"><img src="'+resimcreate("","s","member/avatar")+'" style="width:35px;float:left;margin-right:8px;"><b style="float:left;font-weight:bold;">Guest ('+u_name+')</b><br><font size=2 color=#ccc>'+showDate(date)+'</font></a>'); 
							
						}else{

							$('td:eq(1)', nRow).html('<a href="javascript:void(0);" style="color:#000"><img src="'+resimcreate("","s","member/avatar")+'" style="width:35px;float:left;margin-right:8px;"><b style="float:left;font-weight:bold;">User ('+u_name+')</b><br><font size=2 color=#ccc>'+showDate(date)+'</font></a>');
						/*					
							var url = "posts/adminposts.php?action=uyebilgicek";
							$.post(url, {id: user_id}, function (ajaxCevap) {
						
							var chunks = ajaxCevap.split("|");

							var user_id=chunks[0]; 
							var icon=resimcreate(chunks[1],"s","member/avatar");
							var seoslug=chunks[2];
							$('td:eq(1)', nRow).html('<a href="users.php?user='+seoslug+'"><img src="'+icon+'" style="width:35px;float:left;margin-right:8px;"><b style="float:left;font-weight:bold;">'+user_id+'</b><br><font size=2 color=#ccc>'+showDate(date)+'</font></a>'); 
							});*/
						}
						/*
						if(type=="commentcevap" || type=="commentcevapyanit"){
						$('td:eq(2)', nRow).html("Reply of <a class='btn' href='?comment="+id+"' style='padding:1px 6px;'>#"+content_id+"</a> "); 
						}else{
						$('td:eq(2)', nRow).html(content_id+' <a href="?comment='+id+'" class="btn" style="padding:1px 6px;" ><i class="fa fa-external-link"></i></a>'); 
						}*/
						$('td:eq(2)', nRow).html('<a href="'+content_href+'" target="_blank">'+content_title.substr(0, 155)+'..</a>');
					 		
						$('td:eq(3)', nRow).html('<span class="konu" style="color:#999;">'+comment.substr(0, 155)+'..</span>'); 

						$('td:eq(4)', nRow).attr("style", "text-align:center").html("<?php if(isset($_GET['unapproved'])){ ?><a class='btn btn-success' style='width:10px; margin-right: .5em;' href='?approve="+id+"<?php if(isset($bysite)){ echo '&bysite='.$bysite; }?>'><i class='fa fa-check-square-o'></i></a><?php }?><!--<a class='btn btn-primary' style='width:10px; margin-right: .5em;' href='?comment="+id+"<?php if(isset($bysite)){ echo '&bysite='.$bysite; }?>'><i class='fa fa-cog'></i></a>--><a href='#myModal"+id+"' role='button' class='btn btn-success' style='width:10px; margin-right: .5em;' data-toggle='modal'><i class='fa fa-edit'></i></a><div id='myModal"+id+"' class='modal hide fade in' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'><div class='modal-header'><button type='button' class='close' data-dismiss='modal' aria-hidden='true'>×</button> <h3 id='myModalLabel'>Edit Comment : #"+id+"</h3></div> <form action='?commentedit2' method='post' enctype='multypeart/form-data'><div class='modal-body'><input class='input-style addon' name='commentid' id='commentid' type='hidden' value='"+id+"' style='width:350px'><input class='input-style addon' name='bysite' id='bysite' type='hidden' value='<?php if(isset($bysite)){ echo $bysite; }?>' style='width:350px'><textarea name='commenticerik'  class='inputug' style='width:98%;height:217px;'>"+comment+"</textarea></div> <div class='modal-footer'><button class='btn' data-dismiss='modal' aria-hidden='true'>Close</button><button class='btn btn-primary'>Save changes</button></div></form></div><a class='btn btn-danger' style='width:10px' onclick='return confirm(\"Do you realy want this?\");' href='?deletecomment="+id+"<?php if(isset($_GET['unapproved'])){ echo "&don=unapproved"; }else{ echo "&don=comments"; }?><?php if(isset($bysite)){ echo '&bysite='.$bysite; }?>'><i class='fa fa-remove'></i></a>"); 
						
						return nRow;
						
						}
					
					
				});
			
			});
		</script>	
