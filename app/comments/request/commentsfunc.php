<?php


function likeitems($content_id)
{	
global $dbpdo;global $lang;global $db_siteadres;
	$iceriktype="comment";
	$commentlikedonmu="";

	$toplamliked  = $dbpdo->query("SELECT count(id) FROM likes where content_id='$content_id' and  likestype='$iceriktype' and typelike = 'like'")->fetchColumn();
	$toplamunliked  = $dbpdo->query("SELECT count(id) FROM likes where content_id='$content_id' and  likestype='$iceriktype' and typelike = 'unlike'")->fetchColumn();
		
 return '<span class="like" onclick="comment_like('.$content_id.',\'comment\')"><abbr id="like_'.$content_id.'">'.$toplamliked.'</abbr>
 <span class="fa fa-thumbs-up"></span></span>|<span class="dislike" onclick="comment_dislike('.$content_id.',\'comment\')"><abbr id="dislike_'.$content_id.'">'.$toplamunliked.'</abbr><span class="fa fa-thumbs-down"></span></span>';

	
} 

function commentaddsubcomment($content_id)
{	
global $lang;
	global $CUSER;
	$iconm="";$logincontent="";
	if(isset($_SESSION["icon"])){$iconm= $_SESSION["icon"]; }
 if($CUSER=="" and !isset($_SESSION["oturumid"])){
		
$logincontent = '<div class="loginbox">
	<div class="typerq">
		<a href="javascript:;" id="chooseiType"><i class="fa fa-sign-in"></i><span class="name">Login</span> <span class="fa fa-angle-down"></span></a>
		<ul class="dropdown-menu">
           <li><a href="javascript:;" onclick="chooseType(\'Login\',0, this);" class="selected"><i class="fa fa-sign-in"></i>Login <i class="fa fa-selected fa-check"></i></a></li>
			<li><a href="javascript:;" onclick="chooseType(\'Register\',0, this);"><i class="fa fa-user-plus"></i>Register <i class="fa fa-selected fa-check"></i></a></li>
			<li><a href="javascript:;" onclick="chooseType(\'Register\',1, this);"><i class="fa fa-user-secret"></i>Guest <i class="fa fa-selected fa-check"></i></a></li>
       </ul>
	</div>
	<div class="callingsing Login-sign open">
		<a href="javascript:;" onclick="authLogin()" title="'.$lang["C_4A"].'" class="social-button">
			<span class="fa-stack easycomment fa-lg">
				  <i class="fa fa-square fa-stack-2x"></i>
				  <i class="fa fa-comments fa-stack-1x"></i>
			</span>
		</a>	
		<a href="javascript:;" onclick="authFacebook()" title="'.$lang["C_5A"].'" class="social-button">
			<span class="fa-stack facebook fa-lg">
				  <i class="fa fa-square fa-stack-2x"></i>
				  <i class="fa fa-facebook fa-stack-1x"></i>
			</span>
		</a>
	
	
	<div class="clear"></div>
	</div>
	<div class="callingsing Register-sign">
		<span class="boxinput"><input name="user_username" placeholder="Type a Username" ></span>
		<span class="boxinput"><input name="user_email"  placeholder="Type a Email" ></span>
		<span class="boxinput"><input name="user_password"  placeholder="Password" ></span>
	</div> 

</div>';
	}
 return '<div class="add-subcomment" id="open_add_subcomment_'.$content_id.'">
 <div class="loader-ajax"></div>
 <div class="formm"><img src="'.resimcreate($iconm,"s","member/avatar").'" alt="" class="usericont"/>
 <div class="inner">
 <form id="subcomment_'.$content_id.'" action="" onsubmit="return false;">
 <input type="hidden" name="comment_id" value="'.$content_id.'" />
 <textarea name="comment_text" cols="30" rows="10" placeholder="'.$lang["COMMENT_LINK_9C"].'" onclick="excomment_text(this)"></textarea>
 <div class="subcomment-alt"><button type="submit" onclick="addSubComment(this, '.$content_id.')">'.$lang["COMMENT_LINK_3C"].'</button>
 '.$logincontent.' 
 </div></form></div></div></div>';

	
}
 
 
function commentget($commentcontent_id,$commenticerikdesc,$sayfaid,$commentdesc)
{	
global $dbpdo;
global $db_siteadres;
global $lang;
global $db_commentsnumberperpage;
global $db_populercommentcount;
global $domainaccess;	
$veri="";



	// Sayfalama 

$satir_sayisi  = $dbpdo->query("SELECT count(*) from comments where content_id = '".$commentcontent_id."' and domainaccess = '".$domainaccess."'  and approve = '1'")->fetchColumn();
$sayfa          = $sayfaid; 
$limit          = $db_commentsnumberperpage; 
$sayfa_sayisi = ceil( $satir_sayisi / $limit ); 
$sayfa          = ( $sayfa > $sayfa_sayisi ? 1 : $sayfa ); 
$goster          = ( $sayfa * $limit ) - $limit; 
			


if($commentdesc=="populer"){
	$sayfa="populer"; 
	
}
		
	if($commentdesc=="populer"){
		$stmtooo  = $dbpdo->query("
		SELECT * from (
		SELECT 
		A.*, (SELECT COUNT(*) FROM likes B where B.content_id=A.id and B.likestype = 'comment' and B.typelike = 'like') AS memberCount
		FROM comments A where A.content_id='$commentcontent_id' and A.approve = '1' and A.domainaccess = '$domainaccess'
		) T where T.memberCount >= '10'
	 ORDER BY T.memberCount DESC
    limit 0, $db_populercommentcount");
	}ELSEif($commentdesc=="user"){
		$stmtooo  = $dbpdo->query("SELECT * FROM comments where user_id='$commentcontent_id' and approve = '1' and domainaccess = '$domainaccess' ORDER BY date DESC limit $goster, $limit");
	}ELSE{
		if($commenticerikdesc=="best"){
			$stmtooo  = $dbpdo->query("
				SELECT * from (
				SELECT 
				A.*, (SELECT COUNT(*) FROM likes B where B.content_id=A.id and B.likestype = 'comment' and B.typelike = 'like') AS memberCount
				FROM comments A where A.content_id='$commentcontent_id' and A.approve = '1' 
				) T  
			 ORDER BY T.memberCount DESC
			limit $goster, $limit");
		}ELSEif($commenticerikdesc=="old"){
			$stmtooo  = $dbpdo->query("SELECT * FROM comments  where content_id='$commentcontent_id' and approve = '1' ORDER BY date ASC limit $goster, $limit");
		}ELSE{
			$stmtooo  = $dbpdo->query("SELECT * FROM comments  where content_id='$commentcontent_id' and approve = '1' ORDER BY date DESC limit $goster, $limit");
		}
		
	}
		
$rowingele = $stmtooo->fetchAll();

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
					$commentyazanuser_id2='onclick="profilego('.$commentyazanid.')"';
					$commentyazanicon=resimcreate($gelenm["icon"],"s","member/avatar");
					$commentyazanusertype=$gelenm["usertype"];
				}
				
				if($out_id > 0){
					$commentyazanusertypecolor="";$commentyazanusertypeyazi="";
				}elseif($user_id==0){
					$commentyazanusertypecolor='class="guest" style="cursor:default"';$commentyazanusertypeyazi='<span class="tag guest">'.$lang["COMMENT_TIP_2B"].'</span>';
				}else{
				if($commentyazanusertype==1){$commentyazanusertypecolor='class="admin"';$commentyazanusertypeyazi='<span class="tag admin">'.$lang["COMMENT_TIP_2C"].'</span>';}
				elseif($commentyazanusertype==5){$commentyazanusertypecolor='class="mod"';$commentyazanusertypeyazi='<span class="tag moderator">'.$lang["COMMENT_TIP_2D"].'</span>';}
				else{$commentyazanusertypecolor="";$commentyazanusertypeyazi="";}
				}
			
			
		
	$islemler='';	
	 if (isset($_SESSION['oturumid'])) { if ($_SESSION['usertype']=="1") { 
	 $islemler='<a href="../admin/comments.php?comment='.$commentid.'" target="_blank" style="margin-right:5px;"><span class="fa fa-pencil"></span></a> <a href="javascript:;" onclick="CommentDelete('.$commentid.')" title="'.$lang["COMMENT_LINK_9B"].'"><span class="fa fa-times"></span></a> '; 
	  }else if ($_SESSION['oturumid']!=$commentyazanid) {
		$islemler='<a href="javascript:;" data-open="#comment-report" onclick="comment_complain('.$commentid.')" title="'.$lang["COMMENT_LINK_9A"].'"><span class="fa fa-flag"></span></a>'; 
	  }
	  }			
				
	$veri=$veri.'
 <div class="comment" id="comment'.$commentid.'">
 <span style=""><img class="avatar" src="'.$commentyazanicon.'" alt=""/>
 <div class="c-text"><div class="c-top">
 <span class="report">'.$islemler.'</span>
 <a '.$commentyazanusertypecolor.' '.$commentyazanuser_id2.' data-id="'.$commentyazanid.'" data-user="'.$commentyazanuser_id.'" onmouseover="loaduserWidget(this)">'.$commentyazanuser_id.''.$commentyazanusertypeyazi.'</a>
 <span class="date"><span>•</span> '.$commentdate.'</span></div>
 <div class="spoiler-text" style="display: '.$commentspoilerne.'">'.$lang["COMMENT_LINK_8A"].' <span>'.$lang["COMMENT_LINK_8B"].'</span></div>
 <p style="display: '.$commentspoilerne2.'">'.$comment.'</p>
 <div class="c-alt"> <a href="javascript:;" onclick="openSubcommentForm('.$commentid.', this)" class="open-subcomment">'.$lang["COMMENT_LINK_7A"].'</a> • '.likeitems($commentid).' 
	
 </div>
 </span>
<div id="comment_content_'.$commentid.'" style="position: relative">
 <div class="form-loader"></div>
	
	'.commentanswerlarget($commentid,"").'
 
 </div>
 '.commentaddsubcomment($commentid).'
 </div><div class="clear"></div></div>';				
	

	}
	
	if ($satir_sayisi>$limit and $commentdesc!="populer"){

	$veri=$veri.'<div class="pagination center" style="margin-bottom:20px;"><ul class="pagination" >';
										if( $sayfa > 1 ) 
										{ 
								 
											$veri=$veri.'<li><a href="javascript:;" class="previous" data-page="1"> <i class="fa fa-angle-double-left"></i></a></li>';
												
											$veri=$veri.'<li><a href="javascript:;" class="previous" data-page="'.($sayfa - 1).'"><i class="fa fa-angle-left"></i></a></li>';
											
										}
										 
										for( $i = $sayfa - 3; $i < $sayfa + 4; $i++ ) 
										{ 
											if( $i > 0 && $i <= $sayfa_sayisi ) 
											{ 
												$veri=$veri.'<li class="'.($i == $sayfa ? 'active' : '').'"><a href="javascript:;" data-page="'.$i.'">'.$i.'</a></li>';
											} 
										} 
										 
										if( $sayfa != $sayfa_sayisi ) 
										{ 
											$veri=$veri.'<li><a href="javascript:;" class="next" data-page="'.($sayfa + 1).'"><i class="fa fa-angle-right"></i></a></li>';
											$veri=$veri.'<li><a href="javascript:;" class="next" data-page="'.$sayfa_sayisi.'"><i class="fa fa-angle-double-right"></i></a></li>';
										}
						$veri=$veri.'</ul></div>';			
	}

 
	
return $veri;

	
}


 function commentanswerlarget($commentid,$fullmu)
{	
global $dbpdo;
global $lang;
global $db_siteadres;
$veri="";

		$commentmesajsay  = $dbpdo->query("select count(*) from comments where content_id = '".$commentid."' and type = 'commentanswer' and approve = '1'")->fetchColumn();		
		
		if ($fullmu=="full"){
			$goster="0";
			$limit=$commentmesajsay;
			
	
		}else{
			if ($commentmesajsay>2){
			$goster=$commentmesajsay-2;
			$limit="2";
			}else{
			$goster="0";
			$limit=$commentmesajsay;
	
			}
		}
		
		if($commentmesajsay > "0"){
		
		$rsqalcev  = $dbpdo->query("select * from comments where content_id = '".$commentid."' and type = 'commentanswer' and approve = '1' order by date asc limit ".$goster.", ".$limit."");
		$rowingelew = $rsqalcev->fetchAll();
		
		}

		if ($fullmu!="full" and $commentmesajsay > "2") {$veri=$veri.'<a class="load-more-comment" href="javascript:;" onclick="loadComments('.$commentid.',this,0)">'.$lang["COMMENT_Z_1"].''.$commentmesajsay.''.$lang["COMMENT_Z_2"].' <span class="fa fa-angle-down"></span></a>';}
		
		$veri=$veri.'<div id="comments'.$commentid.'">';
		
		if($commentmesajsay > "0"){
		foreach ($rowingelew as $gelenlcev){	

		$cevapdate=timeConvert($gelenlcev["date"]);
		$cevapcomment=nl2br(cust_text(temizle_replace($gelenlcev["comment"])));
		$cevapid=$gelenlcev["id"];
		$cevapuser_id=$gelenlcev["user_id"];
		$cevapspoiler	=  $gelenlcev['spoiler'];
		$u_name	=  $gelenlcev['u_name'];
		$u_email	=  $gelenlcev['u_email'];
					$out_id	=  $gelenlcev['out_id'];
			$out_name	=  $gelenlcev['out_name'];
			$out_link	=  $gelenlcev['out_link'];
			$out_icon	=  $gelenlcev['out_icon'];
			
			if($cevapspoiler==1){$cevapspoilerne="block";$cevapspoilerne2="none";}else{$cevapspoilerne="none";$cevapspoilerne2="block";}
				
				if($out_id > 0){
				$cevapyazanid="";
				$cevapyazanuser_id=$out_name;
				$cevapyazanuser_id2='href="'.$out_link.'" target=_blank';
				$cevapyazanicon=$out_icon;
				$cevapyazanusertype="1";

			}elseif($cevapuser_id==0){
					$cevapyazanid="";
					$cevapyazanuser_id=$u_name;
					$cevapyazanuser_id2="";
					$cevapyazanicon=resimcreate("","s","member/avatar");
					$cevapyazanusertype="0";
				}else{
					$gelenmu  = $dbpdo->query("Select id,user_id,seoslug,icon,usertype from users where id = '".$cevapuser_id."' limit 1");
					$gelenmu = $gelenmu->fetch();
					
					$cevapyazanid=$gelenmu["id"];
					$cevapyazanuser_id=$gelenmu["user_id"];
					$cevapyazanuser_id2='onclick="profilego('.$cevapyazanid.')"';
					$cevapyazanicon=resimcreate($gelenmu["icon"],"s","member/avatar");
					$cevapyazanusertype=$gelenmu["usertype"];
				}
				
				
				if($out_id > 0){
				$cevapyazanusertypecolor="";$cevapyazanusertypeyazi="";
				}elseif($cevapuser_id==0){
					$cevapyazanusertypecolor='class="guest" style="cursor:default"';$cevapyazanusertypeyazi='<span class="tag guest">'.$lang["COMMENT_TIP_2B"].'</span>';
				}else{
				if($cevapyazanusertype==1){$cevapyazanusertypecolor='class="admin"';$cevapyazanusertypeyazi='<span class="tag admin">'.$lang["COMMENT_TIP_2C"].'</span>';}
				elseif($cevapyazanusertype==5){$cevapyazanusertypecolor='class="mod"';$cevapyazanusertypeyazi='<span class="tag moderator">'.$lang["COMMENT_TIP_2D"].'</span>';}
				else{$cevapyazanusertypecolor="";$cevapyazanusertypeyazi="";}
				}	
				
		
				
		$islemler='';	
	 if (isset($_SESSION['oturumid'])) { if ($_SESSION['usertype']=="1") { 
	 $islemler='<a href="../admin/comments.php?comment='.$cevapid.'" target="_blank" style="margin-right:5px;"><span class="fa fa-pencil"></span></a> <a href="javascript:;" onclick="CommentDelete('.$cevapid.')" title="'.$lang["COMMENT_LINK_9B"].'"><span class="fa fa-times"></span></a> '; 
	  }else if ($_SESSION['oturumid']!=$cevapyazanid) {
		$islemler='<a href="javascript:;" data-open="#comment-report" onclick="comment_complain('.$cevapid.')" title="'.$lang["COMMENT_LINK_9A"].'"><span class="fa fa-flag"></span></a>'; 
	  }
	  }	
				
$veri=$veri.'
<div class="comment" id="comment'.$cevapid.'" data-id="'.$cevapid.'"><img class="avatar" src="'.$cevapyazanicon.'" alt=""/>
<div class="c-text"><div class="c-top"> <span class="report">'.$islemler.'</span><a '.$cevapyazanusertypecolor.'  '.$cevapyazanuser_id2.' data-id="'.$cevapyazanid.'"  data-user="'.$cevapyazanuser_id.'" onmouseover="loaduserWidget(this)">'.$cevapyazanuser_id.''.$cevapyazanusertypeyazi.'</a>
<span class="date" style=""><span>•</span> '.$cevapdate.'</span></div>
<div class="spoiler-text" style="display: '.$cevapspoilerne.'">'.$lang["COMMENT_LINK_8C"].' <span>'.$lang["COMMENT_LINK_8B"].'</span></div>
<p style="display: '.$cevapspoilerne2.';">'.$cevapcomment.'</p>
<div class="c-alt" style="">
<a href="javascript:;" onclick="openSubcommentForm('.$cevapid.', this, 1)" class="open-subcomment">'.$lang["COMMENT_LINK_7A"].'</a> • '.likeitems($cevapid).'
</div>
<div id="comment_content_'.$cevapid.'" style="position: relative"><div class="form-loader"></div>
'.commentanswerlaryanitget($cevapid,"").'

</div>
'.commentaddsubcomment($cevapid).'
</div>
</div>';		
		
		}
		}
$veri=$veri.'</div>';


	
return $veri;

	
} 
function commentanswerlaryanitget($cevapid,$fullmu)
{	
global $dbpdo;
global $lang;
global $db_siteadres;
$veri="";



		$commentmesajsay  = $dbpdo->query("select count(*) from comments where content_id = '".$cevapid."' and type = 'commentanswerreply' and approve = '1'")->fetchColumn();		
		
		if ($fullmu=="full"){
			$goster="0";
			$limit=$commentmesajsay;
		
		}else{
			if ($commentmesajsay>2){
			$goster=$commentmesajsay-2;
			$limit="2";
			}else{
			$goster="0";
			$limit=$commentmesajsay;

			}
		}
		
		if ($fullmu!="full" and $commentmesajsay > "2") {$veri=$veri.'<a class="load-more-comment" href="javascript:;" onclick="loadComments('.$cevapid.',this,1)">'.$lang["COMMENT_Z_1"].''.$commentmesajsay.''.$lang["COMMENT_Z_2"].' <span class="fa fa-angle-down"></span></a>';}
		
		$veri=$veri.'<div id="comments'.$cevapid.'">';
		
		if($commentmesajsay > "0"){

		
			$rsqasd  = $dbpdo->query("select * from comments where content_id = '".$cevapid."' and type = 'commentanswerreply' and approve = '1' order by date asc limit $goster, $limit");
			$rowiaslew = $rsqasd->fetchAll();
			
		
		
		foreach ($rowiaslew as $gelaslcev){	

		$cevapyanitdate=timeConvert($gelaslcev["date"]);
		$cevapyanicomment=nl2br(cust_text(temizle_replace($gelaslcev["comment"])));
		$cevapyaniid=$gelaslcev["id"];
		$cevapyaniuser_id=$gelaslcev["user_id"];
		$cevapyanispoiler	=  $gelaslcev['spoiler'];
		$u_name	=  $gelaslcev['u_name'];
		$u_email	=  $gelaslcev['u_email'];
			$out_id	=  $gelaslcev['out_id'];
			$out_name	=  $gelaslcev['out_name'];
			$out_link	=  $gelaslcev['out_link'];
			$out_icon	=  $gelaslcev['out_icon'];
			
				if($cevapyanispoiler==1){$cevapyanispoilerne="block";$cevapyanispoilerne2="none";}else{$cevapyanispoilerne="none";$cevapyanispoilerne2="block";}
				
	
				if($out_id > 0){
				$cevapyaniyazanid="";
				$cevapyaniyazanuser_id=$out_name;
				$cevapyaniyazanuser_id2='href="'.$out_link.'" target=_blank';
				$cevapyaniyazanicon=$out_icon;
				$cevapyaniyazanusertype="1";

			}elseif($cevapyaniuser_id==0){
					$cevapyaniyazanid="";
					$cevapyaniyazanuser_id=$u_name;
					$cevapyaniyazanuser_id2="";
					$cevapyaniyazanicon=resimcreate("","s","member/avatar");
					$cevapyaniyazanusertype="0";
				}else{
					
				$gelenmu  = $dbpdo->query("Select id,user_id,seoslug,icon,usertype from users where id = '".$cevapyaniuser_id."' limit 1");
				$gelenmu = $gelenmu->fetch();
				$cevapyaniyazanid=$gelenmu["id"];
				$cevapyaniyazanuser_id=$gelenmu["user_id"];
				$cevapyaniyazanuser_id2='onclick="profilego('.$cevapyaniyazanid.')"';
				$cevapyaniyazanicon=resimcreate($gelenmu["icon"],"s","member/avatar");
				$cevapyaniyazanusertype=$gelenmu["usertype"];
				}
				
				
				if($out_id > 0){
				$cevapyaniyazanusertypecolor="";$cevapyaniyazanusertypeyazi="";
			}elseif($cevapyaniuser_id==0){
					$cevapyaniyazanusertypecolor='class="guest" style="cursor:default"';$cevapyaniyazanusertypeyazi='<span class="tag guest">'.$lang["COMMENT_TIP_2B"].'</span>';
				}else{
				if($cevapyaniyazanusertype==1){$cevapyaniyazanusertypecolor='class="admin"';$cevapyaniyazanusertypeyazi='<span class="tag admin">'.$lang["COMMENT_TIP_2C"].'</span>';}
				elseif($cevapyaniyazanusertype==5){$cevapyaniyazanusertypecolor='class="mod"';$cevapyaniyazanusertypeyazi='<span class="tag moderator">'.$lang["COMMENT_TIP_2D"].'</span>';}
				else{$cevapyaniyazanusertypecolor="";$cevapyaniyazanusertypeyazi="";}
				}	
				
				
				
			
				
	$islemler='';	
	 if (isset($_SESSION['oturumid'])) { if ($_SESSION['usertype']=="1") { 
	 $islemler='<a href="../admin/comments.php?comment='.$cevapyaniid.'" target="_blank" style="margin-right:5px;"><span class="fa fa-pencil"></span></a> <a href="javascript:;" onclick="CommentDelete('.$cevapyaniid.')" title="'.$lang["COMMENT_LINK_9B"].'"><span class="fa fa-times"></span></a> '; 
	  }else if ($_SESSION['oturumid']!=$cevapyaniyazanid) {
		$islemler='<a href="javascript:;" data-open="#comment-report" onclick="comment_complain('.$cevapyaniid.')" title="'.$lang["COMMENT_LINK_9A"].'"><span class="fa fa-flag"></span></a>'; 
	  }
	  }	
	  
$veri=$veri.'
<div class="comment" id="comment'.$cevapyaniid.'" data-id="'.$cevapyaniid.'">
<img class="avatar" src="'.$cevapyaniyazanicon.'" alt=""/>
<div class="c-text"><div class="c-top"><span class="report">'.$islemler.'</span><a '.$cevapyaniyazanusertypecolor.'  '.$cevapyaniyazanuser_id2.' data-id="'.$cevapyaniyazanid.'" data-user="'.$cevapyaniyazanuser_id.'" onmouseover="loaduserWidget(this)">'.$cevapyaniyazanuser_id.''.$cevapyaniyazanusertypeyazi.'</a>
<span class="date" style=""><span>•</span> '.$cevapyanitdate.'</span></div>
<div class="spoiler-text" style="display: '.$cevapyanispoilerne.'">'.$lang["COMMENT_LINK_8C"].' <span>'.$lang["COMMENT_LINK_8B"].'</span>
</div><p style="display: '.$cevapyanispoilerne2.';">'.$cevapyanicomment.'</p>
<div class="c-alt" style=""><a href="javascript:;" onclick="openSubcommentForm('.$cevapid.', this, 1)" class="open-subcomment">'.$lang["COMMENT_LINK_7A"].'</a> • '.likeitems($cevapyaniid).'

 
</div>
</div>
</div>
';
		}
		}

$veri=$veri.'</div>';

return $veri;
}
?>