<?php
require_once("../comments/inc/config.php"); 
include 'inc/header.php';

?>
<div class="main">
  <div class="main-inner">
    <div class="container">
      <div class="row">
        <div class="span6">
          <div class="widget widget-nopad">
            <div class="widget-header"> <i class="fa fa-list-alt"></i>
              <h3> Today's Stats</h3>
            </div>
            <!-- /widget-header -->
            <div class="widget-content">
              <div class="widget big-stats-container">
                <div class="widget-content">
                  <h6 class="bigstats">A rich comment script for your website</h6>
                  <div id="big_stats" class="cf">
                    <div class="stat"> <i class="fa fa-comment"></i> <span class="value"><?php echo $dbpdo->query("select id from comments where left(date,8) like '$zamangunayyil' ")->rowCount(); ?></span> </div>
                    <!-- .stat -->
                    
                    <div class="stat"> <i class="fa fa-thumbs-up"></i> <span class="value"><?php echo $dbpdo->query("select id from likes where left(date,8) like '$zamangunayyil' and likestype='comment' and typelike='like'")->rowCount(); ?></span> </div>
                    <!-- .stat -->
                    
                    <div class="stat"> <i class="fa fa-thumbs-down"></i> <span class="value"><?php echo $dbpdo->query("select id from likes where left(date,8) like '$zamangunayyil' and likestype='comment' and typelike='unlike'")->rowCount(); ?></span> </div>
                    <!-- .stat -->
                    
                    <div class="stat"> <i class="fa fa-user"></i> <span class="value"><?php echo $dbpdo->query("select id from users where left(registerdate,8) like '$zamangunayyil' ")->rowCount(); ?>+</span> </div>
                    <!-- .stat --> 
                  </div>
                </div>
                <!-- /widget-content --> 
                
              </div>
            </div>
          </div>
  
          <!-- /widget -->
          <div class="widget">
            <div class="widget-header"> <i class="fa fa-file"></i>
              <h3>Last Comments</h3>
            </div>
            <!-- /widget-header -->
            <div class="widget-content">
              <ul class="messages_layout">
			  <?PHP

$stmtooo  = $dbpdo->query("SELECT * FROM comments  where approve = '1' and type != 'commentanswer' and type != 'commentanswerreply' ORDER BY date DESC limit 0, 5");
$rowingele = $stmtooo->fetchAll();
$ddf=1;
foreach ($rowingele as $gelenl){	

				$user_id	=  $gelenl['user_id'];
				$comment	=  $gelenl['comment'];
				$comment=nl2br(cust_text(temizle_replace($comment)));
				$commentid	=  $gelenl['id'];
				$commentdate	=  timeConvert($gelenl['date']);
				$commentspoiler	=  $gelenl['spoiler'];
				$u_name	=  $gelenl['u_name'];
				$u_email	=  $gelenl['u_email'];
				$out_id	=  $gelenl['out_id'];
                $out_name	=  $gelenl['out_name'];
                $out_link	=  $gelenl['out_link'];
                $out_icon	=  $gelenl['out_icon'];
				
				if($commentspoiler==1){$commentspoilerne="block";$commentspoilerne2="none";}else{$commentspoilerne="none";$commentspoilerne2="block";}
				
				if($out_id > 0){
                    $commentyazanid="";
                    $commentyazanuser_id=$out_name;
                    $commentyazanuser_id2='href="'.$out_link.'" target=_blank';
                    $commentyazanicon=$out_icon;
                    $commentyazanusertype="1";

                }elseif($user_id==0){
					$commentyazanid="";
					$commentyazanuser_id=$u_name;
					$commentyazanuser_id2="";
					$commentyazanicon=resimcreate("","s","member/avatar");
					$commentyazanusertype="0";
				}else{
					$rsqam  = $dbpdo->query("Select id,user_id,seoslug,icon,usertype from users where id = '".$user_id."' limit 1");
					$gelenm = $rsqam->fetch();
					$commentyazanid=$gelenm["id"];
					$commentyazanuser_id=$gelenm["user_id"];
					$commentyazanuser_id2='users.php?user='.$commentyazanid.'';
					$commentyazanicon=resimcreate($gelenm["icon"],"s","member/avatar");
					$commentyazanusertype=$gelenm["usertype"];
				}
				
				if($out_id > 0){
                $commentyazanusertypecolor='class="guest" ';
                $commentyazanusertypeyazi='<span class="tag guest">not a member of eC.</span>';

            }elseif($user_id==0){
					$commentyazanusertypecolor='class="guest" style="cursor:default"';$commentyazanusertypeyazi='<span class="tag guest">'.$lang["COMMENT_TIP_2B"].'</span>';
				}else{
				if($commentyazanusertype==1){$commentyazanusertypecolor='class="admin"';$commentyazanusertypeyazi='<span class="tag admin">'.$lang["COMMENT_TIP_2C"].'</span>';}
				elseif($commentyazanusertype==5){$commentyazanusertypecolor='class="mod"';$commentyazanusertypeyazi='<span class="tag moderator">'.$lang["COMMENT_TIP_2D"].'</span>';}
				else{$commentyazanusertypecolor="";$commentyazanusertypeyazi="";}
				}
			
			if($ddf==2 or $ddf==4){
				$leftornot="right";
				
			}else{
				$leftornot="left";
			}
		
	echo '
	              <li class="from_user '.$leftornot.'" style="float:none;display:block"> <a href="#" class="avatar"><img width=55 src="'.$commentyazanicon.'"/></a>
                  <div class="message_wrap" style="float:none;display:block"> <span class="arrow"></span>
                    <div class="info"> <a href="'.$commentyazanuser_id2.'" class="name" '.$commentyazanusertypecolor.'>'.$commentyazanuser_id.''.$commentyazanusertypeyazi.'</a> <span class="time">'.$commentdate.'</span>
                      <div class="options_arrow">
                        <div class="dropdown pull-right"> <a class="dropdown-toggle " id="dLabel" role="button" data-toggle="dropdown" data-target="#" href="#"> <i class=" fa fa-caret-down"></i> </a>
                          <ul class="dropdown-menu " role="menu" aria-labelledby="dLabel">
                            <li><a href="comments.php?comment='.$commentid.'"><i class=" fa fa-share-alt fa fa-large"></i> Edit</a></li>
                            <li><a onclick="return confirm(\'Do you realy want this?\');" href="comments.php?deletecomment='.$commentid.'"><i class=" fa fa-trash fa fa-large"></i> Delete</a></li>
                          </ul>
                        </div>
                      </div>
                    </div>
                    <div class="text">'.$comment.'</div>
					 <div class="clearfix"></div>
                  </div>
				  <div class="clearfix"></div>
                </li>
	';				
	
		
	$ddf=$ddf+1;
		
	}
	if($ddf==1){ echo 'Nothing to see here'; }
			  ?>
			  
  

              </ul>
            </div>
            <!-- /widget-content --> 
          </div>

          <div class="widget widget-table action-table">
            <div class="widget-header"> <i class="fa fa-th-list"></i>
              <h3>Pending Comments</h3>
            </div>
            <!-- /widget-header -->
            <div class="widget-content">
              <table class="table table-striped table-bordered">
                <thead>
                  <tr>
                    <th> Comment </th>
                    <th> Details</th>
                    <th class="td-actions"> </th>
                  </tr>
                </thead>
                <tbody>
				<?PHP

$stmtooo  = $dbpdo->query("SELECT * FROM comments  where approve = '0' ORDER BY date DESC limit 0, 10");
$rowingele = $stmtooo->fetchAll();
$ddf=1;
foreach ($rowingele as $gelenl){	

				$user_id	=  $gelenl['user_id'];
				$comment	=  $gelenl['comment'];
				$comment=nl2br(temizle_replace($comment));
				$commentid	=  $gelenl['id'];
				$commentdate	=  timeConvert($gelenl['date']);
				$commentspoiler	=  $gelenl['spoiler'];
				$u_name	=  $gelenl['u_name'];
				$u_email	=  $gelenl['u_email'];
				
				
				if($commentspoiler==1){$commentspoilerne="block";$commentspoilerne2="none";}else{$commentspoilerne="none";$commentspoilerne2="block";}
				
				if($user_id==0){
					$commentyazanid="";
					$commentyazanuser_id=$u_name;
					$commentyazanuser_id2="javascript:;";
					$commentyazanicon=resimcreate("","s","member/avatar");
					$commentyazanusertype="0";
				}else{
					$rsqam  = $dbpdo->query("Select id,user_id,seoslug,icon,usertype from users where id = '".$user_id."' limit 1");
					$gelenm = $rsqam->fetch();
					$commentyazanid=$gelenm["id"];
					$commentyazanuser_id=$gelenm["user_id"];
					$commentyazanuser_id2='users.php?user='.$commentyazanid.'';
					$commentyazanicon=resimcreate($gelenm["icon"],"s","member/avatar");
					$commentyazanusertype=$gelenm["usertype"];
				}
				
				if($user_id==0){
					$commentyazanusertypecolor='class="guest" style="cursor:default"';$commentyazanusertypeyazi='<span class="tag guest">'.$lang["COMMENT_TIP_2B"].'</span>';
				}else{
				if($commentyazanusertype==1){$commentyazanusertypecolor='class="admin"';$commentyazanusertypeyazi='<span class="tag admin">'.$lang["COMMENT_TIP_2C"].'</span>';}
				elseif($commentyazanusertype==5){$commentyazanusertypecolor='class="mod"';$commentyazanusertypeyazi='<span class="tag moderator">'.$lang["COMMENT_TIP_2D"].'</span>';}
				else{$commentyazanusertypecolor="";$commentyazanusertypeyazi="";}
				}
			
			if($ddf==2 or $ddf==4){
				$leftornot="right";
				
			}else{
				$leftornot="left";
			}
		
	echo ' <tr>
                    <td>'.substr($comment,0,20).'... </td>
                    <td><a href="'.$commentyazanuser_id2.'" '.$commentyazanusertypecolor.'><img width="25" src="'.$commentyazanicon.'"> '.$commentyazanuser_id.' '.$commentyazanusertypeyazi.'</a> </td>
                    <td class="td-actions"><a href="comments.php?approve='.$commentid.'" class="btn btn-small btn-success"><i class="btn-fa fa-only fa fa-check-square-o"> </i></a><a onclick="return confirm(\'Do you realy want this?\');" href="comments.php?deletecomment='.$commentid.'&don=unapproved" class="btn btn-danger btn-small"><i class="btn-fa fa-only fa fa-remove"> </i></a></td>
                  </tr>
		
	';				
	
		
	$ddf=$ddf+1;
		
	}
	
			  ?>
				
  
                
                </tbody>
              </table><?php if($ddf==1){ echo '<div style="padding:10px">Nothing to see here</div>'; } ?>
            </div>
            <!-- /widget-content --> 
          </div>
          <!-- /widget --> 
        </div>
        <!-- /span6 -->
        <div class="span6">
         <?php 	if ($_SESSION['usertype']=="1"){ ?>  
		 <div class="widget">
            <div class="widget-header"> <i class="fa fa-code"></i>
              <h3>Your Code</h3>
            </div>
            <!-- /widget-header -->
            <div class="widget-content">
       
<textarea style="margin: 0px 0px 9px; width: 522px; height: 361px;">
<!-- easyComment Content Div -->
<div id="easyComment_Content"></div>
	
<!-- easyComment -->
<script type="text/javascript">
	// CONFIGURATION VARIABLES
	var easyComment_ContentID = 'MyUnquieId';
	
	// CORE 
	var easyComment_Domain = '<?php echo $db_siteadres ?>';
	
	/* * * DON'T EDIT BELOW THIS LINE * * */
	(function() {
		var EC = document.createElement('script');
		EC.type = 'text/javascript';
		EC.async = true;
		EC.src = easyComment_Domain + 'plugin/embed.js';
		(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(EC);
	})();
</script>
</textarea><!-- /easyComment -->
	   
              <!-- /shortcuts --> 
            </div>
            <!-- /widget-content --> 
		 </div>   <?php } ?>        
		  <div class="widget">
            <div class="widget-header"> <i class="fa fa-bookmark"></i>
              <h3>Important Shortcuts</h3>
            </div>
            <!-- /widget-header -->
            <div class="widget-content">
              <div class="shortcuts"> <a href="settings.php" class="shortcut"><i class="shortcut-icon fa fa-cog"></i><span
                                        class="shortcut-label">App Settings</span> </a><a href="themes.php" class="shortcut"><i
                                            class="shortcut-icon fa fa-asterisk"></i><span class="shortcut-label">Themes</span> </a>
											<a href="reports.php" class="shortcut"><i class="shortcut-icon fa fa-signal"></i> <span class="shortcut-label">Reports</span> </a>
											<a href="comments.php"" class="shortcut"> <i class="shortcut-icon fa fa-comment"></i><span class="shortcut-label">Comments</span> </a>
										
													</div>
              <!-- /shortcuts --> 
            </div>
            <!-- /widget-content --> 
          </div>
          <!-- /widget -->

          <div class="widget widget-nopad">
            <div class="widget-header"> <i class="fa fa-list-alt"></i>
              <h3> Recent News</h3>
            </div>
            <!-- /widget-header -->
            <div class="widget-content">
              <ul class="news-items">
			  <?php
  $z = new XMLReader;
$z->open('http://easycomment.akbilisim.com/feeds.xml');

$doc = new DOMDocument;


while ($z->read() && $z->name !== 'feed');


while ($z->name === 'feed')
{

    $node = simplexml_import_dom($doc->importNode($z->expand(), true));

  
		echo '   <li><div class="news-item-date"> <span class="news-item-day">'.$node->date.'</span> <span class="news-item-month">'.$node->datem.'</span> </div>
                  <div class="news-item-detail"> <a href="'.$node->link.'" class="news-item-title" target="_blank">'.$node->title.'</a>
                    <p class="news-item-preview"> '.$node->desc.' </p>
                  </div>
                  
                </li>';

    $z->next('feed');
}
			  ?>
           
              </ul>
            </div>
            <!-- /widget-content --> 
          </div>
          <!-- /widget -->
        </div>
        <!-- /span6 --> 
      </div>
      <!-- /row --> 
    </div>
    <!-- /container --> 
  </div>
  <!-- /main-inner --> 
</div>
<!-- /main -->
<?php
 include 'inc/footer.php';
?>