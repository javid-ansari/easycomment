<?php
require_once("../comments/inc/config.php"); 
include 'inc/header.php';

if(isset($_GET['deletereport'])){

	$reportid=$_GET['deletereport'];
	
	 $dbpdo->exec("DELETE FROM flags where id = '$reportid' ");
	header("Location: reports.php");
		
	
exit();
}



?>
<div class="main">
	
	<div class="main-inner">

	    <div class="container">
		<div class="page-header">
		<h2 class="pull-left">
						  <i class="fa fa-flag red"></i>
						  <span>Reports</span>
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
							<li class="active">Reports</li>
						  </ul>
						</div>
		</div>

		<?php if(isset($_GET['commentedit'])){

		$commentid=$_GET['commentedit'];

	$yeniko  = $dbpdo->query("select * from comments where id = '$commentid'")->fetch();
	if(!$yeniko){
	echo "Already deleted";
	exit();
	}
	$comment=$yeniko["comment"];
	$user_id=$yeniko["user_id"];
	$date=$yeniko["date"];
	$content_id=$yeniko["content_id"];
	$feedtype=$yeniko["type"];

	$rsqa  = $dbpdo->query("select icon,seoslug,user_id from users where id = '$user_id'");
	if($yeni = $rsqa->fetch()){
	
	$user_id=$yeni["user_id"];
	$icon=$yeni["icon"];
	$seoslug=$yeni["seoslug"];
	}
	$orjorbas="";
							
			?>
		<div class="row">
			<div class="span12">
					
					<div class="widget">
							
						<div class="widget-header">
							<i class="fa fa-pushpin"></i>
							<h3>Sample Frequently Asked Questions</h3>
						</div> <!-- /widget-header -->
						
						<div class="widget-content">
							
						
							
				
			<form action="?commentedit2" method="post" enctype="multypeart/form-data" >
			<input class="input-style addon" name="commentid" id="commentid" type="hidden" value="<?php echo $commentid ?>" style="width:350px">
			<div class="form-group clear">
				<label class="lab">Sender</label>
				
				<a href="uyeler.php?uye=<?php echo $seoslug ?>"><img src="/upload/member/avatar/<?php echo $icon ?>-s.jpg" style="width:35px;float:left;margin-right:8px;"><b style="float:left;font-weight:bold;"><?php echo $user_id ?></b><br><font size=2><?php echo timeConvert($date) ?> wrote</font></a>
				
				
				<div class="clear"> </div>
			</div>	
		
			<div class="form-group clear">
				<label class="lab">comment</label>
				
				
				<textarea name="commenticerik"  class="inputug" style="width:700px;height:217px;"><?php echo $comment ?></textarea>
				<div class="clear"> </div>
			</div>
			
			

			<div class="postgo_section">
				<button class="btn btn-primary " type="submit"><i class="fa fa-save"></i> Edit</button>
				<a class="btn" href="comments.php" type="submit">Cancel</a>
			</div>
			</form>
			
					</div> <!-- /widget-content -->
							
					</div> <!-- /widget -->	
					
			 </div>

		</div>	

		<?php }else{ ?>
		
		
	      <div class="row">
	      	
	      	<div class="span12">
	      		
	    
		
			<table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
					<thead>
						<tr>
						<th width="5%" style="text-align:left">#</th>
						<th width="30%" style="text-align:left">Reported</th>
						<th width="30%" style="text-align:left">Reporter</th>	
						<th width="20%">Time</th>
						<th width="15%">Settings</th>
						</tr>
					</thead>

					<tfoot>
						<tr>
						<th width="5%" style="text-align:left">#</th>
						<th width="30%" style="text-align:left">Reported</th>	
						<th width="30%" style="text-align:left">Reporter</th>	
						<th width="20%">Time</th>
						<th width="15%">Settings</th>
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
 
 $pagegone="?page=comment";
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
						
						var raporid = aData[0]; 
						var content_id = aData[3]; 
						var uyeid = aData[1]; 
						var date = showDate(aData[2]); 
				
				
						$('td:eq(0)', nRow).html('<em class="date">#'+aData[0]+'</em>'); 
						
						$('td:eq(1)', nRow).html('<a href=comments.php?comment='+content_id+' target=_blank>Comment #'+content_id+'</a>'); 
						
					var url = "posts/adminposts.php?action=uyebilgicek";
					$.post(url, {id: uyeid}, function (ajaxCevap) {
						var chunks = ajaxCevap.split("|");

						var user_id=chunks[0]; 
						var icon=resimcreate(chunks[1],"s","member/avatar");
						var seoslug=chunks[2];
						
						$('td:eq(2)', nRow).html('<a href="users.php?user='+seoslug+'"><img src="'+icon+'" style="width:35px;float:left;margin-right:8px;"><b style="float:left;font-weight:bold;">'+user_id+'</b><br><font size=2 color=#ccc></font></a>'); 
									
					});
						$('td:eq(3)', nRow).addClass("center").html(date); 

						$('td:eq(4)', nRow).addClass("center").html("<a class='btn btn-danger ' onclick='return confirm(\"Do you realy want this?\");' title='Delete Comment & Report' href='?deletecomment="+content_id+"&don=reports'><i class='fa fa-comment'></i> & <i class='fa fa-flag'></i></a>  <a class='btn btn-danger ' onclick='return confirm(\"Do you realy want this?\");' title='Only Delete Report' href='?deletereport="+raporid+"'><i class='fa fa-remove'></i></a>");
						
				
					  return nRow;
							
						}
					
					
				});
			
			});
		</script>	