<?php

define('SOLR_SERVER_HOSTNAME', '192.168.18.8');
define('SOLR_SERVER_PORT', '8984');
define('SOLR_SERVER_USERNAME', '');
define('SOLR_SERVER_PASSWORD', '');
define('SOLR_PATH', 'solr/comments');

function getOptionsArray(){
	$options = array
	(
			'hostname' => SOLR_SERVER_HOSTNAME,
			'login'    => SOLR_SERVER_USERNAME,
			'password' => SOLR_SERVER_PASSWORD,
			'port'     => SOLR_SERVER_PORT,
			'path'     => SOLR_PATH
	);
	
	return $options;
}

function getSolrClient(){
	return new SolrClient(getOptionsArray());
}
function getCommentsCount($filters){
	$query = new SolrQuery();
	$count = 0;
	try{
		$query->setQuery('*:*');
		$query->addFilterQuery($filters);
		$query->setStart(0);
		$query->setRows(50);
		$query_response = getSolrClient()->query($query)->getResponse();
		$count = (int) $query_response->response->numFound;
		
	}catch(Exception $e){
		syslog(0, $e->__toString());
		return $count;		
	}
	  // don't actually request any data
	return $count;
}

function getSolrComments($commentcontent_id,$commenticerikdesc,$sayfaid,$commentdesc)
{
	global $dbpdo;
	global $db_siteadres;
	global $lang;
	global $db_commentsnumberperpage;
	global $db_populercommentcount;
	global $domainaccess;
	$veri="";
	
	/*
	 * TODO pagination 
	 */
	$satir_sayisi  = getCommentsCount('content_ref:'.$commentcontent_id.'');
	$sayfa          = $sayfaid;
	$limit          = $db_commentsnumberperpage;
	$sayfa_sayisi = ceil( $satir_sayisi / $limit );
	$sayfa          = ( $sayfa > $sayfa_sayisi ? 1 : $sayfa );
	$goster          = ( $sayfa * $limit ) - $limit;
		

	
	$filters = 'content_ref:'.$commentcontent_id.'';

	$query = new SolrQuery();
	try{
		$query->setQuery('*:*');
		$query->addFilterQuery($filters);
		$query_response = getSolrClient()->query($query)->getResponse();
	
	}catch(Exception $e){
		syslog(0, $e->__toString());		
	}
	
	
	if ($query_response)
	{
		foreach ($query_response->response->docs as $doc){
			
			$user_id	=  $doc['user_id'];
			$comment	=  $doc['comment'];
			$comment=nl2br(cust_text(temizle_replace($comment)));
			$commentid	=  $doc['id'];
			$commentdate	=  timeConvert($doc['date']);
			$commentspoiler	=  $doc['spoiler'];
			$u_name	=  $doc['u_name'];
			$u_email	=  $doc['u_email'];
			$out_id	=  $doc['out_id'];
			$out_name	=  $doc['out_name'];
			$out_link	=  $doc['out_link'];
			$out_icon	=  $doc['out_icon'];
			
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
			
			 $veri = $veri.'
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
	//$dbpdo->query("SELECT * FROM comments  where content_id='$commentcontent_id' and approve = '1' ORDER BY date DESC limit $goster, $limit");
	
	}

	

	return $veri;


}
