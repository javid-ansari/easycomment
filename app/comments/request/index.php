<?php
require_once("../inc/config.php"); 
$response = array();


			
function uyeregister($user_username,$user_email,$user_password,$social,$socialtype,$first_name,$last_name,$hometown,$gender,$birthday){

	global $dbpdo;
	global $lang;
	global $phptarih;
	global $db_sitemotto;
	global $seflink;

					
	if (isset($_SESSION['oturumid'])){ 
					return $lang["REQUEST_2"];
					exit();
					}

					$user_id = $user_username; 
					$password = $user_password;
					$passwordtekrar = $password; 
					$email = $user_email;
				
					
					if(!preg_match('/^[^@]+@[a-zA-Z0-9._-]+\.[a-zA-Z]+$/', $email)) {
						return $lang["REQUEST_3A"];
					  exit();
					}

					if ($user_id=="") {
						return  $lang["REQUEST_3C"];
					exit();
					}elseif ($password=="") {
						return  $lang["REQUEST_4D"];
					exit();
				
					}elseif ($email=="") {
						return  $lang["REQUEST_3F"];
		
					exit();
					}elseif (strlen($user_id)<3 or strlen($user_id)>15) {
						return  $lang["REQUEST_3G"];
				
					exit();
					}
					$password2=$password;
					$password=md5($password);
					
					if ($birthday> "") {
					$doguma=explode("/",$birthday);  
					$dogumay=$doguma[0];
					$dogumgun=$doguma[1];
					$dogumyil=$doguma[2];
					$birthday=$dogumyil.$dogumay.$dogumgun;
					}
					
					
					if ($social > "") {
							if ($socialtype == "facebook") {
							$icon="https://graph.facebook.com/$social/picture";
							}else{
							$icon="";
							}
		
					}else{
					$icon="";
					}
					
					
						$rsqajlm  = $dbpdo->query("Select user_id from users where user_id = '$user_id'");
												
						if ($rsqajlm = $rsqajlm->fetch()){
						return  $lang["REQUEST_3H"];
						
						exit();
						}

						$rsqajlma  = $dbpdo->query("Select user_id from users where email = '$email'");
						if ($rsqajlma = $rsqajlma->fetch()){
						return $lang["REQUEST_3T"];
			
						exit();
						}

					if(getenv("HTTP_CLIENT_IP")) {
						 $ip = getenv("HTTP_CLIENT_IP");
					 } elseif(getenv("HTTP_X_FORWARDED_FOR")) {
						 $ip = getenv("HTTP_X_FORWARDED_FOR");
						 if (strstr($ip, ',')) {
							 $tmp = explode (',', $ip);
							 $ip = trim($tmp[0]);
						 }
					 } else {
					 $ip = getenv("REMOTE_ADDR");
					 }

					$seflinkne=seflink($user_id);
					$return = $dbpdo->prepare("INSERT INTO users (user_id,password,email,registerdate,last_date,usertype,ipno,ban,seoslug,icon,age,name,surname,city,gender,social,socialtype) VALUES ('$user_id' ,'$password' , '$email' , '$phptarih' ,  '$phptarih', '0', '$ip', '0', '$seflinkne',  '$icon', '$birthday','$first_name', '$last_name', '$hometown', '$gender', '$social', '$socialtype' )");
					$return->bindParam(":user_id",$user_id);
					$return->bindParam(":email",$email);
					$return = $return->execute();

					$rsqa  = $dbpdo->query("Select * from users where user_id = '$user_id' and password = '$password'");
					if($gelenz = $rsqa->fetch()){
					$_SESSION['oturumid'] = $gelenz['id'];
					$_SESSION['user_id'] = $gelenz['user_id'];
					$_SESSION['password'] = $gelenz['password'];
					$_SESSION['usertype'] = $gelenz['usertype'];
					$_SESSION['seoslug'] = $gelenz['seoslug'];
					$_SESSION['icon'] = $gelenz['icon'];
					$_SESSION['last_date'] = $phptarih;
						
					
					setcookie($db_sitemotto."user",$_SESSION['user_id'],time() + (86400 * 7), "/");
					setcookie($db_sitemotto."pass",$_SESSION['password'],time() + (86400 * 7), "/");
					return "ok";
					}else{
					return "error";	
					}
}


function uyelogin($user_username,$user_email,$user_password,$page){
	global $dbpdo;
	global $lang;
	global $phptarih;
	global $db_sitemotto;
	global $seflink;

	if (isset($_SESSION['oturumid'])){ 
		return $lang["REQUEST_2"];
		echo json_encode($response);
		exit();
	}

	$user_id	=  $user_username;
	$email	=  $user_email;
	$password2	=  $user_password;
	

	if ($user_id=="" or $password2=="") {
	return $lang["REQUEST_3"];
	}else{
		
	$password	=  md5($password2);
	
	$pagelogin="";
	if($page=="adminlogin"){
		$pagelogin="and usertype=1";
	}
	
	
	$rsqa  = $dbpdo->query("Select id,user_id,password,usertype,seoslug,icon,ban from users where user_id = '$user_id' and password = '$password' $pagelogin or email = '$email' and password = '$password' $pagelogin");
	if($gelenz = $rsqa->fetch()){
	$banlimi = $gelenz['ban'];
		
		if ($banlimi == '1'){ 
		return $lang["REQUEST_7A"];
		}else{
		
		$_SESSION['oturumid'] = $gelenz['id'];
		$_SESSION['user_id'] = $gelenz['user_id'];
		$_SESSION['password'] = $gelenz['password'];
		$_SESSION['usertype'] = $gelenz['usertype'];
		$_SESSION['seoslug'] = $gelenz['seoslug'];
		$_SESSION['icon'] = $gelenz['icon'];
		$_SESSION['last_date'] = $phptarih;
		
		    if(getenv("HTTP_CLIENT_IP")) {
				 $ip = getenv("HTTP_CLIENT_IP");
			 } elseif(getenv("HTTP_X_FORWARDED_FOR")) {
				 $ip = getenv("HTTP_X_FORWARDED_FOR");
				 if (strstr($ip, ',')) {
					 $tmp = explode (',', $ip);
					 $ip = trim($tmp[0]);
				 }
			 } else {
			 $ip = getenv("REMOTE_ADDR");
			 }
			 

		$return1 = $dbpdo->prepare("UpDate users Set ipno = '$ip' , last_date = '$phptarih' where id = '$gelenz[id]'");
		$return1 = $return1->execute();
		

		setcookie($db_sitemotto."user",$_SESSION['user_id'],time() + (86400 * 7), "/");
		setcookie($db_sitemotto."pass",$_SESSION['password'],time() + (86400 * 7), "/");
		
		return "ok";
		}
	}else{
		if($page=="adminlogin"){
			return "No account found with that username/password";
		}else{
			return $lang["REQUEST_7B"];
		}
		
	}
}
}

$type ="";
if(isset($_POST["type"])){
$type = $_POST["type"]; 
}
$domainaccess ="";
if(isset($_POST["AccessToken"])){
$domainaccess = $_POST["AccessToken"]; 
}
$CUSER ="";$CUSER_NAME ="";$CUSER_LINK ="";$CUSER_ID ="";$CUSER_ICON ="";
if(isset($_POST["CUSER"])){
	$CUSER = $_POST["CUSER"];


		if(isset($_POST["CUSER_NAME"])){
		$CUSER_NAME = $_POST["CUSER_NAME"];
		}
		if(isset($_POST["CUSER_LINK"])){
		$CUSER_LINK = $_POST["CUSER_LINK"];
		}
		if(isset($_POST["CUSER_ID"])){
		$CUSER_ID = $_POST["CUSER_ID"];
		}
		if(isset($_POST["CUSER_ICON"])){
		$CUSER_ICON = $_POST["CUSER_ICON"];
		}


}


if ($type == '') { 
$response['error'] = $lang["REQUEST_1"];

}elseif ($type == 'logout'){ 

	session_destroy();

	setcookie($db_sitemotto."user", "", time()-3600, "/");
				
	setcookie($db_sitemotto."pass", "", time()-3600, "/");
		
	$response['success'] = "ok";
	
}elseif ($type == 'loginControl'){ 



	if (!isset($_SESSION['oturumid'])){ 
		$response['error'] =  $lang["REQUEST_2"];
	}else{
		$response['username'] = $_SESSION['user_id'];
		$response['icon'] = resimcreate($_SESSION['icon'],"s","member/avatar");;
	}

}elseif ($type == 'login'){ 
	if (isset($_SESSION['oturumid'])){ 
		$response['error'] =  $lang["REQUEST_2"];
		exit();
	}
	
	$username = "";
	if (isset($_POST["username"])) { 
		$username = strip_tags(tirnak_replace($_POST["username"]));
	}			
	$password = "";
	if (isset($_POST["password"])) { 
		$password = htmlentities(strip_tags(tirnak_replace($_POST["password"])));
	}		
	$page = "";
	if (isset($_POST["page"])) { 
		$page = htmlentities(strip_tags(tirnak_replace($_POST["page"])));
	}		
	
	if ($username=="" or $password=="" ){
	$response['error'] = $lang["REQUEST_13A"];
	}else{
			$okmu = uyelogin($username,$username,$password,$page);

			if ($okmu == 'ok') { 
					$response['success'] = $okmu;
					$response['username'] = $_SESSION['user_id'];
				}else{ 
					$response['error'] = $okmu;
				
			}
		
	}
}elseif ($type == 'register'){ 


	if (isset($_SESSION['oturumid'])){ 
		$response['error'] =  $lang["REQUEST_2"];
		echo json_encode($response);
		exit();
	}
	
	$username = "";
	if (isset($_POST["username"])) { 
		$username = htmlentities(strip_tags(tirnak_replace($_POST["username"])));
	}		
	$email = "";
	if (isset($_POST["email"])) { 
		$email = htmlentities(strip_tags(tirnak_replace($_POST["email"])));
	}		
	$password = "";
		if (isset($_POST["password"])) { 
		$password = htmlentities(strip_tags(tirnak_replace($_POST["password"])));
	}		
	
	if ($username=="" or $email=="" or $password=="" ){
	$response['error'] = $lang["REQUEST_13A"];
	}else{
	
	$social = "";$socialtype = "";$birthday = "";$hometown = "";$gender = "";$name = "";$surname = "";
	if (isset($_SESSION['social_id'])){ $social = $_SESSION['social_id']; }
	if (isset($_SESSION['social_type'])){ $socialtype = $_SESSION['social_type']; }
	if (isset($_SESSION['social_birthday'])){ $birthday = $_SESSION['social_birthday']; }
	if (isset($_SESSION['social_hometown'])){ $hometown = $_SESSION['social_hometown']; }
	if (isset($_SESSION['social_gender'])){ $gender = $_SESSION['social_gender']; }
	if (isset($_SESSION['social_name'])){ $name = $_SESSION['social_name']; }
	if (isset($_SESSION['social_surname'])){ $surname = $_SESSION['social_surname']; }
	if (isset($_SESSION['social_email'])){ $email = $_SESSION['social_email']; }

	
	$okmu = uyeregister($username,$email,$password,$social,$socialtype,$name,$surname,$hometown,$gender,$birthday);

	
				if ($okmu == 'ok') { 
					$response['success'] = $okmu;
					$response['username'] = $_SESSION['user_id'];
				}else{ 
					$response['error'] = $okmu;
				
				}
 
	}
}elseif ($type == 'settingedit'){ 


	if (!isset($_SESSION['oturumid'])){ 
		$response['error'] =  "Error";
		echo json_encode($response);
		exit();
	}
	
	$username = "";
	if (isset($_POST["username"])) { 
		$username = strip_tags(tirnak_replace($_POST["username"]));
	}		
	$email = "";
	if (isset($_POST["email"])) { 
		$email = htmlentities(strip_tags(tirnak_replace($_POST["email"])));
	}		
	$password = "";
	if (isset($_POST["password"])) { 
		$password = htmlentities(strip_tags(tirnak_replace($_POST["password"])));
	}		
	$name = "";
	if (isset($_POST["name"])) { 
		$name = strip_tags(tirnak_replace($_POST["name"]));
	}		
	$surname = "";
	if (isset($_POST["surname"])) { 
		$surname = strip_tags(tirnak_replace($_POST["surname"]));
	}			

	$hometown = "";
	if (isset($_POST["hometown"])) { 
		$hometown = strip_tags(tirnak_replace($_POST["hometown"]));
	}			
	$gender = "";
	if (isset($_POST["gender"])) { 
		$gender = htmlentities(strip_tags(tirnak_replace($_POST["gender"])));
	}			
	$birthdayyear = "";
	if (isset($_POST["birthdayyear"])) { 
		$birthdayyear = htmlentities(strip_tags(tirnak_replace($_POST["birthdayyear"])));
	}		
	$birthdaymouth = "";
	if (isset($_POST["birthdaymouth"])) { 
		$birthdaymouth = htmlentities(strip_tags(tirnak_replace($_POST["birthdaymouth"])));
	}		
	$birthdayday = "";
	if (isset($_POST["birthdayday"])) { 
		$birthdayday = htmlentities(strip_tags(tirnak_replace($_POST["birthdayday"])));
	}		
	$oldpassword = "";
	if (isset($_POST["oldpassword"])) { 
		$oldpassword = htmlentities(strip_tags(tirnak_replace($_POST["oldpassword"])));
	}	
	
	if($oldpassword==""){
			$response['error'] =  "Current password can't be empty.";
	}else{	
	
	if ($username=="" or $email=="" or $oldpassword==""){
		$response['error'] = $lang["REQUEST_13A"];
		}else{
			
	
		
			$passwordmd5 =md5($oldpassword);
			if($_SESSION['password']!=$passwordmd5){
				$response['error'] =  "Not correct password";
			}else{	

		
		

				if(!preg_match('/^[^@]+@[a-zA-Z0-9._-]+\.[a-zA-Z]+$/', $email)) {
						$response['error'] =  $lang["REQUEST_3A"];
						echo json_encode($response);
					  exit();
					}

					if (strlen($username)<3 or strlen($username)>15) {
						$response['error'] =   $lang["REQUEST_3G"];
						echo json_encode($response);
					exit();
					}

		
			if($password>""){
					if (strlen($password)<5 or strlen($password)>15) {
						$response['error'] =   "Password must be min-5 max-15 characters ";
						echo json_encode($response);
					exit();
					}

				$password=md5($password);
			}else{
				$password=$_SESSION['password'];
				
			}
			
		
			
			$birthday=$birthdayyear.$birthdaymouth.$birthdayday;
			
			$usernameslug=seflink($username);
			
			
			$return2 = $dbpdo->prepare("UpDate users Set  user_id = '$username', seoslug = '$usernameslug', email = '$email', password = '$password', name = '$name' , surname = '$surname', gender = '$gender', age = '$birthday', city = '$hometown' where id = '$_SESSION[oturumid]'");
			 $return2 = $return2->execute();
			

			$response['success'] = "Success";
				
		
	
	 
		}
		}
	}
}elseif ($type == 'sociallogin'){ 


	if (isset($_SESSION['oturumid'])){ 
		$response['error'] = $lang["REQUEST_2"];
		echo json_encode($response);
		exit();
	}
	
		$social = $_POST["social"]; 
		$email = $_POST["email"]; 
		$socialtype = $_POST["socialtype"]; 
		
		$first_name = "";
		if (isset($_POST["first_name"])) { 
		$first_name = $_POST["first_name"];
		}		
		$last_name = "";
		if (isset($_POST["last_name"])) { 
		$last_name = $_POST["last_name"];
		}		
		$hometown = "";
		if (isset($_POST["hometown"])) { 
		$hometown = $_POST["hometown"];
		}		
		$gender = "";
		if (isset($_POST["gender"])) { 
		$gender = $_POST["gender"];
		}		
		$birthday = "";
		if (isset($_POST["birthday"])) { 
		$birthday = $_POST["birthday"];
		}
		
	

	$rsqa  = $dbpdo->query("Select id,user_id,password,usertype,seoslug,icon,ban from users where social = '$social' and email = '$email' ");
	if($gelenz = $rsqa->fetch()){
	$banlimi = $gelenz['ban'];
		
		if ($banlimi == '1'){ 
		$response['error'] = $lang["REQUEST_3"];
		}else{
		
		$_SESSION['oturumid'] = $gelenz['id'];
		$_SESSION['user_id'] = $gelenz['user_id'];
		$_SESSION['password'] = $gelenz['password'];
		$_SESSION['usertype'] = $gelenz['usertype'];
		$_SESSION['seoslug'] = $gelenz['seoslug'];
		$_SESSION['icon'] = $gelenz['icon'];
		$_SESSION['last_date'] = $phptarih;
		
		    if(getenv("HTTP_CLIENT_IP")) {
				 $ip = getenv("HTTP_CLIENT_IP");
			 } elseif(getenv("HTTP_X_FORWARDED_FOR")) {
				 $ip = getenv("HTTP_X_FORWARDED_FOR");
				 if (strstr($ip, ',')) {
					 $tmp = explode (',', $ip);
					 $ip = trim($tmp[0]);
				 }
			 } else {
			 $ip = getenv("REMOTE_ADDR");
			 }
			 

		$return1 = $dbpdo->prepare("UpDate users Set ipno = '$ip' , last_date = '$phptarih' where id = '$gelenz[id]'");
		$return1 = $return1->execute();
		

		setcookie($db_sitemotto."user",$_SESSION['user_id'],time() + (86400 * 7), "/");
		setcookie($db_sitemotto."pass",$_SESSION['password'],time() + (86400 * 7), "/");
		
		$response['success'] = "go";
		$response['username'] = $_SESSION['user_id'];
		}
	}else{
		
		$_SESSION['social_username'] = $first_name.' '.$last_name;
		$_SESSION['social_email'] = $email;
		$_SESSION['social_id'] = $social;
		$_SESSION['social_type'] = $socialtype;
		$_SESSION['social_birthday'] = $birthday;
		$_SESSION['social_hometown'] = $hometown;
		$_SESSION['social_gender'] = $gender;
		$_SESSION['social_name'] = $first_name;
		$_SESSION['social_surname'] = $last_name;
		$response['register'] ="go";
	}
	
	

		
}elseif ($type == 'addcomment'){ 
$icerikid = $_POST["id"]; 
$comment = strip_tags(tirnak_replace($_POST["comment"])); 
if(strlen($comment) > 1500){
		$response['error'] = "Too large text. Limit 500 character"; 
		echo json_encode($response);
		exit();
		}
		
	
$msg_type = $_POST["contenttype"]; 

	$spoiler = "0";
	if (isset($_POST["spoiler"])) { 
	$spoiler = $_POST["spoiler"];
	}
	
	if ($comment=="" or $icerikid=="" or $msg_type=="" ) {
	$response['error'] = $lang["REQUEST_13A"];
	}else{
		
	$u_name = "";$u_email = "";	$out_id = "";$out_name = "";$out_link = "";$out_icon = "";
	if ($CUSER==""){
		
		if (isset($_SESSION['oturumid'])){ 
		$kullnicikim = $_SESSION["user_id"];
		$kullniciid = $_SESSION["oturumid"];
		$kullniciicon = $_SESSION["icon"];


		}else{ 
				$user_username = "";
				if (isset($_POST["user_username"])) { 
				$user_username = strip_tags(tirnak_replace($_POST["user_username"]));
				}
				$user_email = "";
				if (isset($_POST["user_email"])) { 
				$user_email = htmlentities(strip_tags(tirnak_replace($_POST["user_email"])));
				}
				$user_password = "";
				if (isset($_POST["user_password"])) { 
				$user_password = htmlentities(strip_tags(tirnak_replace($_POST["user_password"])));
				}
				if ($user_username == '' or $user_email == '') { 
					$response['error'] = $lang["REQUEST_3"];
					echo json_encode($response);
					exit();
				}
			if($user_password > ""){ 
	
				$okmu = uyeregister($user_username,$user_email,$user_password,"","","","","","","");
		
				if ($okmu == 'ok') { 
					$kullnicikim = $_SESSION["user_id"];
					$kullniciid = $_SESSION["oturumid"];
					$kullniciicon = $_SESSION["icon"];
				}else{ 
					$response['error'] = $okmu;
					echo json_encode($response);
					exit();
				}
				
			}elseif($db_guestcomment=="open"){ 
				$u_name = $user_username;
				$u_email = $user_email;
				
				$kullnicikim =$u_name;
				$kullniciid = "0";
				$kullniciicon = "";
				
			}elseif($db_guestcomment=="close"){ 
					$response['error'] = $lang["GENERAL_EXTRA_1"];
					echo json_encode($response);
					exit();
			}
		}
		
		$icon=resimcreate($kullniciicon,"s","member/avatar");

		}else{

			$kullnicikim =$CUSER_NAME;
			$kullniciid = "";
			$kullniciicon = $CUSER_ICON;
			$out_id = $CUSER_ID;
			$out_name = $CUSER_NAME;
			$out_link = $CUSER_LINK;
			$out_icon = $CUSER_ICON;

			$icon = $CUSER_ICON;


			if($out_id=="" or $out_name=="" or $out_link=="" or $out_icon==""){
				$response['error'] = $lang["GENERAL_EXTRA_1"];
				echo json_encode($response);
				exit();
			}
		}
	
	//getting clients ip address
	$u_ip = getUserIP();
		

	$return = $dbpdo->prepare("INSERT INTO comments (user_id,comment,content_id,type,domainaccess,date,spoiler,approve,u_name,u_email,u_ip,out_id,out_name,out_link,out_icon) VALUES ('$kullniciid' , '$comment' , '$icerikid' , '$msg_type' , '$domainaccess' , '$phptarih', '$spoiler', '$db_commentsappow', '$u_name', '$u_email','$u_ip','$out_id', '$out_name', '$out_link', '$out_icon')");
	$return->bindParam(":comment",$comment);
	$return = $return->execute();
	
	
	$rsqal  = $dbpdo->query("select id from comments where content_id = '$icerikid' and user_id = '$kullniciid' and type = '$msg_type'  and domainaccess = '$domainaccess' and out_id = '$out_id' and out_name = '$out_name'  order by id DESC limit 1");
	$gelenl = $rsqal->fetch();
	
	$commentid	=  $gelenl['id'];
	$comment=nl2br(cust_text(temizle_replace($comment)));
	
	
	if($kullniciid==""){
			$cevapyaniyazanusertypecolor="";$cevapyaniyazanusertypeyazi="";
		}elseif($kullniciid==0){
					$cevapyaniyazanusertypecolor='class="guest" style="cursor:default"';$cevapyaniyazanusertypeyazi='<span class="tag guest">'.$lang["COMMENT_TIP_2B"].'</span>';
				}else{
				if($_SESSION["usertype"]==1){$cevapyaniyazanusertypecolor='class="admin"';$cevapyaniyazanusertypeyazi='<span class="tag admin">'.$lang["COMMENT_TIP_2C"].'</span>';}
				elseif($_SESSION["usertype"]==5){$cevapyaniyazanusertypecolor='class="mod"';$cevapyaniyazanusertypeyazi='<span class="tag moderator">'.$lang["COMMENT_TIP_2D"].'</span>';}
				else{$cevapyaniyazanusertypecolor="";$cevapyaniyazanusertypeyazi="";}
				}									
				

				
	$response['data'] = '<div class="comment" id="comment'.$commentid.'"><span style="">
	<img class="avatar" src="'.$icon.'" alt="">
<div class="c-text"><div class="c-top"><a '.$cevapyaniyazanusertypecolor.'  href="javascript:;" data-id="'.$kullniciid.'" data-user="'.$kullnicikim.'" onmouseover="loaduserWidget(this)">'.$kullnicikim.''.$cevapyaniyazanusertypeyazi.'</a>
<span class="date"><span>•</span> '.$lang["REQUEST_13B"].'</span></div>
<p style="display: block">'.$comment.'</p>
<div class="c-alt">
	<a href="javascript:;" onclick="openSubcommentForm('.$commentid.', this)" class="open-subcomment">'.$lang["COMMENT_LINK_7A"].'</a>
 |<span class="like" onclick="comment_like('.$commentid.',\'comment\')"><abbr id="like_'.$commentid.'">0</abbr><span class="fa fa-thumbs-up"></span></span>
 <span class="dislike" onclick="comment_dislike('.$commentid.',\'comment\')"><abbr id="dislike_'.$commentid.'">0</abbr><span class="fa fa-thumbs-down"></span></span>
 </div>
 <div id="comment_content_'.$commentid.'" style="position: relative"><div class="form-loader"></div>
 <div id="comments'.$commentid.'"></div>
 </div>
 <div class="add-subcomment" id="open_add_subcomment_'.$commentid.'">
 <div class="loader-ajax"></div>
 <div class="formm"><img src="'.$icon.'" alt="">
 <div class="inner"><form id="subcomment_194880" action="" onsubmit="return false;">
 <input type="hidden" name="user_id"><input type="hidden" name="comment_id" value="'.$commentid.'">
 <textarea name="comment_text" cols="30" rows="10" placeholder="'.$lang["COMMENT_LINK_9C"].'" onclick="excomment_text(this)"></textarea>
 <div class="subcomment-alt"><button type="submit" onclick="addSubComment(this, '.$commentid.')">'.$lang["COMMENT_LINK_3C"].'</button>
 <label class="cb checkbox2"></label></div></form></div></div></div>
 </div><div class="clear"></div></span>
 </div>
';

		
	}

}elseif ($type == 'addSubComment') { 
	
	
	$comment_id = $_POST["comment_id"]; 
	$comment_text = strip_tags(tirnak_replace($_POST["comment_text"])); 
	if(strlen($comment_text) > 1500){
		$response['error'] = "Too large text. Limit 500 character"; 
		echo json_encode($response);
		exit();}
	$spoiler = "0";
	if (isset($_POST["spoiler"])) { 
	$spoiler = $_POST["spoiler"];
	}

	if ($comment_id=="" or $comment_text=="") {
		$response['error'] = $lang["REQUEST_3"];
	}else{
		
		$u_name = "";$u_email = "";	$out_id = "";$out_name = "";$out_link = "";$out_icon = "";
	if ($CUSER==""){
				
		if (isset($_SESSION['oturumid'])){ 
		$kullnicikim = $_SESSION["user_id"];
		$kullniciid = $_SESSION["oturumid"];
		$kullniciicon = $_SESSION["icon"];


		}else{ 
				$user_username = "";
				if (isset($_POST["user_username"])) { 
				$user_username = strip_tags(tirnak_replace($_POST["user_username"]));
				}
				$user_email = "";
				if (isset($_POST["user_email"])) { 
				$user_email = htmlentities(strip_tags(tirnak_replace($_POST["user_email"])));
				}
				$user_password = "";
				if (isset($_POST["user_password"])) { 
				$user_password = htmlentities(strip_tags(tirnak_replace($_POST["user_password"])));
				}
				if ($user_username == '' or $user_email == '') { 
					$response['error'] = $lang["REQUEST_3"];
					echo json_encode($response);
					exit();
				}
			if($user_password > ""){ 
	
				$okmu = uyeregister($user_username,$user_email,$user_password,"","","","","","","");
				
				if ($okmu == 'ok') { 
					$kullnicikim = $_SESSION["user_id"];
					$kullniciid = $_SESSION["oturumid"];
					$kullniciicon = $_SESSION["icon"];
				}else{ 
					$response['error'] = $okmu;
					echo json_encode($response);
					exit();
				}
				
			}elseif($db_guestcomment=="open"){ 
				$u_name = $user_username;
				$u_email = $user_email;
				
				$kullnicikim =$u_name;
				$kullniciid = "0";
				$kullniciicon = "";
				
			}elseif($db_guestcomment=="close"){ 
					$response['error'] = $lang["GENERAL_EXTRA_1"];
					echo json_encode($response);
					exit();
			}
		}


	
	
	$icon=resimcreate($kullniciicon,"s","member/avatar");
	
		}else{

		$kullnicikim =$CUSER_NAME;
		$kullniciid = "";
		$kullniciicon = $CUSER_ICON;
		$out_id = $CUSER_ID;
		$out_name = $CUSER_NAME;
		$out_link = $CUSER_LINK;
		$out_icon = $CUSER_ICON;

		$icon = $CUSER_ICON;

		if($out_id=="" or $out_name=="" or $out_link=="" or $out_icon==""){
			$response['error'] = $lang["GENERAL_EXTRA_1"];
			echo json_encode($response);
			exit();
		}


	}
	
		
	$ustcommentne  = $dbpdo->query("select type,content_id from comments where id = '$comment_id' limit 1");
	if($ustcomment = $ustcommentne->fetch()){
		$gelencommenttype=$ustcomment["type"];
		$gelencommenticerikid=$ustcomment["content_id"];
		
		if($gelencommenttype!="commentanswer" and $gelencommenttype!="commentanswerreply"){
			$yenicommenttype="commentanswer";
		}elseif($gelencommenttype=="commentanswer"){
			$yenicommenttype="commentanswerreply";
		}elseif($gelencommenttype=="commentanswerreply"){
			
			$yenicommenttype="commentanswerreply";
			$comment_id = $gelencommenticerikid;
		}
		

	$return = $dbpdo->prepare("INSERT INTO comments (user_id,comment,content_id,type,domainaccess,date,spoiler,approve,u_name,u_email,out_id,out_name,out_link,out_icon) VALUES ('$_SESSION[oturumid]' , '$comment_text' , '$comment_id' , '$yenicommenttype' , '$domainaccess' , '$phptarih', '$spoiler', '$db_commentsappow', '$u_name', '$u_email','$out_id', '$out_name', '$out_link', '$out_icon')");
	$return->bindParam(":comment",$comment_text);
	$return = $return->execute();
	

	$cevapidgetir  = $dbpdo->query("select id from comments where user_id = '$_SESSION[oturumid]' and content_id = '$comment_id' and type = '$yenicommenttype' and domainaccess = '$domainaccess' and out_id = '$out_id' and out_name = '$out_name' order by date desc");
	$gelenww = $cevapidgetir->fetch();

	$yeniidne=$gelenww["id"];

	$comment_text=nl2br(cust_text(temizle_replace($comment_text)));
	
		if($kullniciid==""){
			$cevapyaniyazanunvancolor="";$cevapyaniyazanunvanyazi="";
				}elseif($kullniciid==0){
					$cevapyaniyazanusertypecolor='class="guest" style="cursor:default"';$cevapyaniyazanusertypeyazi='<span class="tag guest">'.$lang["COMMENT_TIP_2B"].'</span>';
				}else{
				if($_SESSION["usertype"]==1){$cevapyaniyazanusertypecolor='class="admin"';$cevapyaniyazanusertypeyazi='<span class="tag admin">'.$lang["COMMENT_TIP_2C"].'</span>';}
				elseif($_SESSION["usertype"]==5){$cevapyaniyazanusertypecolor='class="mod"';$cevapyaniyazanusertypeyazi='<span class="tag moderator">'.$lang["COMMENT_TIP_2D"].'</span>';}
				else{$cevapyaniyazanusertypecolor="";$cevapyaniyazanusertypeyazi="";}
				}	
	
	
	
	
	$response['comment'] = '<div class="comment" id="comment'.$yeniidne.'"><span style="">
		<img class="avatar" src="'.resimcreate($_SESSION['icon'],"s","member/avatar").'" alt="">
	<div class="c-text"><div class="c-top"><a '.$cevapyaniyazanusertypecolor.'  href="javascipt:void();" data-id="'.$kullniciid.'"  data-user="'.$kullnicikim.'" onmouseover="loaduserWidget(this)">'.$kullnicikim.''.$cevapyaniyazanusertypeyazi.'</a>
	<span class="date"><span>•</span> '.$lang["REQUEST_13B"].'</span></div>
	<p style="display: block">'.$comment_text.'</p>
	<div class="c-alt">
		<a href="javascript:;" onclick="openSubcommentForm('.$yeniidne.', this)" class="open-subcomment">'.$lang["COMMENT_LINK_7A"].'</a>
	 |<span class="like" onclick="comment_like('.$yeniidne.',\'comment\')"><abbr id="like_'.$yeniidne.'">0</abbr><span class="fa fa-thumbs-up"></span></span>
	 <span class="dislike" onclick="comment_dislike('.$yeniidne.',\'comment\')"><abbr id="dislike_'.$yeniidne.'">0</abbr><span class="fa fa-thumbs-down"></span></span>
	 </div>
	 <div id="comment_content_'.$yeniidne.'" style="position: relative"><div class="form-loader"></div>
	 <div id="comments'.$yeniidne.'"></div>
	 </div>
	 <div class="add-subcomment" id="open_add_subcomment_'.$yeniidne.'">
	 <div class="loader-ajax"></div>
	 <div class="formm"><img src="'.resimcreate($_SESSION['icon'],"s","member/avatar").'" alt="">
	 <div class="inner"><form id="subcomment_'.$yeniidne.'" action="" onsubmit="return false;">
	 <input type="hidden" name="user_id"><input type="hidden" name="comment_id" value="'.$yeniidne.'">
	 <textarea name="comment_text" cols="30" rows="10" placeholder="'.$lang["COMMENT_LINK_9C"].'" onclick="excomment_text(this)"></textarea>
	 <div class="subcomment-alt"><button type="submit" onclick="addSubComment(this, '.$yeniidne.')">'.$lang["COMMENT_LINK_3C"].'</button>
	 <label class="cb checkbox2"></label></div></form></div></div></div>
	 </div><div class="clear"></div></span>
	 </div>
	';
	

	}else{
	$response['error'] = $lang["REQUEST_3"];
	}
		
	
	
	}

}elseif ($type == 'loadComments') { 	
require_once("commentsfunc.php"); 

$id = $_POST["id"]; 
$tif = $_POST["tif"]; 

if($tif=="0"){
$comments = commentanswerlarget($id,"full");
}elseif($tif=="1"){
$comments = commentanswerlaryanitget($id,"full");
}

$response['comments'] = $comments;

}elseif ($type == 'loadComment') { 	
require_once("commentsfunc.php"); 

$id = $_POST["id"]; 
$pagination = $_POST["pagination"]; 
$desc = $_POST["desc"]; 

$comments = commentget($id,$desc,$pagination,"tumu");


$response['comments'] = $comments;

}elseif ($type == 'loaduserdata') { 
	$user_id = $_POST["user_id"]; 
	if ($user_id==""){
	$response['error'] = $lang["REQUEST_3"];
	}else{
					$rsqa  = $dbpdo->query("Select * from users where id = '$user_id' ");
					if($gelenz = $rsqa->fetch()){
					$response['username'] = $gelenz['user_id'];
					$response['name'] = $gelenz['name'];
					$response['surname'] = $gelenz['surname'];
					$response['genre'] = $gelenz['gender'];
					$response['town'] = $gelenz['city'];
					$response['reg'] = $gelenz['registerdate'];
					$response['icon'] = resimcreate($gelenz["icon"],"m","member/avatar");
					$response['last'] = timeConvert($gelenz['last_date']);
			
					
					}else{
						$response['error'] = "No user data";
					}
		
	}
	
	
}elseif (!isset($_SESSION['oturumid']) and $CUSER != "ON"){ //if user login in
$response['error'] = $lang["GENERAL_EXTRA_1"];


}elseif ($type == 'CommentDelete'){
	$id = $_POST["id"]; 

	if ($id=="") {
	$response['error'] = $lang["REQUEST_3"];
	}else{
	
	$rsqawymzz  = $dbpdo->query("Select user_id,content_id,type from comments where id = '$id'");
	if($gelenzuye = $rsqawymzz->fetch()){
		$user_idsa=$gelenzuye["user_id"];
		$commenticerikid=$gelenzuye["content_id"];
		$commentcontenttype=$gelenzuye["type"];
	

	if ($user_idsa==$_SESSION["oturumid"] or $_SESSION["usertype"]=="1") {

		$rsqalcev  = $dbpdo->query("select date,comment,id,user_id,spoiler from comments where content_id = '".$id."' and type = 'commentanswer' and domainaccess = '$domainaccess'");
		while ($rowingelew = $rsqalcev->fetch()){	
		$commentanswerid=$rowingelew["id"];
		
			$rsqaasv  = $dbpdo->query("select date,comment,id,user_id,spoiler from comments where content_id = '".$commentanswerid."' and type = 'commentanswerreply' and domainaccess = '$domainaccess'");
			while ($roaselew = $rsqaasv->fetch()){	
			$commentanswerreplyid=$roaselew["id"];
		
			$return2 = $dbpdo->prepare("DELETE FROM flags where content_id = '$commentanswerreplyid'");
			$return2 = $return2->execute();
			$return2 = $dbpdo->prepare("DELETE FROM likes where content_id = '$commentanswerreplyid' and likestype = 'comment'");
			$return2 = $return2->execute();
			$return2 = $dbpdo->prepare("DELETE FROM comments where id = '$commentanswerreplyid'");
			$return2 = $return2->execute();
			
			}
		
			$return2 = $dbpdo->prepare("DELETE FROM flags where content_id = '$commentanswerid'");
			$return2 = $return2->execute();
			$return2 = $dbpdo->prepare("DELETE FROM likes where content_id = '$commentanswerid' and likestype = 'comment'");
			$return2 = $return2->execute();
			$return2 = $dbpdo->prepare("DELETE FROM comments where id = '$commentanswerid'");
			$return2 = $return2->execute();

		}
	
		$return2 = $dbpdo->prepare("DELETE FROM flags where content_id = '$id'");
		$return2 = $return2->execute();
		$return2 = $dbpdo->prepare("DELETE FROM likes where content_id = '$id' and likestype = 'comment'");
		$return2 = $return2->execute();
		$return2 = $dbpdo->prepare("DELETE FROM comments where id = '$id'");
		$return2 = $return2->execute();

	}else{
		$response['error'] = $lang["REQUEST_14A"];
	}



	}else{
		$response['error'] = $lang["REQUEST_14A"];
	}
	
	}
	
}elseif ($type == 'comment_report') {
	$report_id = $_POST["id"]; 
	
	if ($report_id=="") {
	$response['error'] = $lang["REQUEST_3"];
	}else{
	
	$stmt  = $dbpdo->query("select * from flags where content_id = '$report_id'");
	if($gelenwwmes = $stmt->fetch()){
	
	$response['error'] = $lang["REQUEST_14C"];
	
	
	}else{
	$dbpdo->exec("INSERT INTO flags (who,date,content_id) VALUES ('$_SESSION[oturumid]' ,  '$phptarih' , '$report_id')");
	

	$response['success'] =$lang["REQUEST_14D"];	
	}
	
	}
	
}elseif ($type == 'comment_like_func'){ 	
	
	
$comment_id = $_POST["id"]; 
$commentbegentype = $_POST["tip"];
$commentcontenttype = $_POST["contenttype"];

	if ($comment_id=="" or $commentbegentype=="" or $commentcontenttype=="") {
	$response['error'] = $lang["REQUEST_3"];
	}else{

		if($CUSER=="ON"){
			$kullkim = '1111'.$CUSER_ID;
		}else{
			$kullkim = $_SESSION['oturumid'];
		}
	
	$begenmismi  = $dbpdo->query("select typelike from likes where user_id = '$_SESSION[oturumid]' and content_id = '$comment_id' and likestype = '$commentcontenttype' and domainaccess = '$domainaccess'");
	If ($gelensd = $begenmismi->fetch()) {  
		
		$typelike=$gelensd['typelike'];
		
		if ($typelike=="like") { 
			
			if ($commentbegentype=="like") {
			$response['error'] = $lang["REQUEST_15A"];
			}else{
			$response['error'] = $lang["REQUEST_15B"];
			}
			
		}elseif($typelike=="unlike") { 
		
			if ($commentbegentype=="unlike") { 
			$response['error'] = $lang["REQUEST_15C"];
			}else{
			$response['error'] = $lang["REQUEST_15D"];
			}
		
		}
		
	}Else{
		$return = $dbpdo->prepare("INSERT INTO likes (user_id,content_id,typelike,likestype,domainaccess,date) VALUES ('$_SESSION[oturumid]' , '$comment_id' ,  '$commentbegentype' , '$commentcontenttype' , '$domainaccess' , '$phptarih')");
		$return = $return->execute();
		$response['success'] = "ok";

	}
	
	}
}


function getUserIP()
{
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];

    if(filter_var($client, FILTER_VALIDATE_IP))
    {
        $ip = $client;
    }
    elseif(filter_var($forward, FILTER_VALIDATE_IP))
    {
        $ip = $forward;
    }
    else
    {
        $ip = $remote;
    }

    return $ip;
}


echo json_encode($response);
?>