<?php 
require_once("../../comments/inc/config.php"); 

require_once("../inc/cagir.php"); 


$action = $_GET['action']; 
if ($action == 'uyeduzenle') { 

	$uyeid = $_POST["uyeid"]; 
	$user_id = $_POST["user_id"]; 
	$password = $_POST["password"]; 
	
	$yasyil = $_POST["yasyil"]; 	
	$yasay = $_POST["yasay"]; 	
	$yasgun = $_POST["yasgun"]; 	
	$name = $_POST["name"]; 
	$surname = $_POST["surname"]; 
	$seoslug = $_POST["seoslug"]; 
	$city = $_POST["city"]; 
	$usertype = $_POST["usertype"]; 
	$gender = $_POST["gender"]; 
	$email = $_POST["email"]; 
	$facebook = $_POST["facebook"]; 

	$age = $yasyil.$yasay.$yasgun; 	
	
	if ($user_id=="" or $seoslug=="" or $email=="") {
	echo "Usename can not be empty";
	exit();
	}

	$rsqa = $dbpdo->query("select * from users where user_id like '$user_id' and id not like '$uyeid' or seoslug = '$seoslug'  and id not like '$uyeid' or email like '$email' and id not like '$uyeid' ");
	$rsqaj = $rsqa->rowCount();
	if ($rsqaj>=1){
	echo "Usename or email using by another person";
	exit();
	}	
	
	$rsqa  = $dbpdo->query("select icon from users where id = '$uyeid'");
	if($yeni = $rsqa->fetch()){
	
	$haberb_resim=$yeni["icon"];
	
	$olanresim=$haberb_resim;
	}else{
	$olanresim="";
	}
	
	if (isset($_FILES['icon']['name'])){
	$resimdonen=resimgo($_FILES['icon']['name'], $_FILES['icon']['type'], $_FILES['icon']['error'], $_FILES['icon']['tmp_name'] , 'uyeimg/presim', "$seoslug","$olanresim","","");
	}else{
	$resimdonen=$olanresim;
	}

	$passwordmd5=md5($password);
	if($password>""){
	$return = $dbpdo->prepare("UpDate users Set password = '$passwordmd5' where id='$uyeid'");
	$return = $return->execute();
	}

	 $dbpdo->exec("UpDate users Set icon = '$resimdonen', user_id = '$user_id' , usertype = '$usertype', seoslug = '$seoslug', name = '$name', surname = '$surname', email = '$email', facebook = '$facebook', gender = '$gender', age = '$age', city = '$city' where id = '$uyeid'");
	
	echo "ok";

}elseif ($action == 'uyebilgicek') { 

	$uyeid = $_POST["id"]; 

	if ($uyeid=="") {
	echo "boş kalamaz";
	exit();
	}
	


	$rsqa  = $dbpdo->query("select icon,seoslug,user_id from users where id = '$uyeid'");
	if($yeni = $rsqa->fetch()){
	
	$user_id=$yeni["user_id"];
	$icon=$yeni["icon"];
	$seoslug=$yeni["seoslug"];
	
	echo $user_id."|".$icon."|".$seoslug."|".$uyeid;
	
	}
	
}elseif ($action == 'newpages') { 

$pagetip = $_POST["pagetip"];
$uzunaciklama = $_POST["uzunaciklama"];


	if ($pagetip=="" or $uzunaciklama=="") {
	echo "Alanları Doldurun";
	exit();
	}

	$rsqaj = $dbpdo->query("select * from pages where contenttype like '$pagetip'")->rowCount();
	
	
	if ($rsqaj=="") {
	
	$dbpdo->exec("INSERT INTO pages (text,contenttype,age) VALUES ('$uzunaciklama' , '$pagetip' , '$phpage')");

	echo "ok";
	exit();
	}else{
	 $dbpdo->exec("UpDate pages Set text = '$uzunaciklama', age = '$phpage' where contenttype = '$pagetip'");
	echo "edited";
	exit();
	}

}elseif ($action == 'uyebanla') { 
	$uye = $_GET['uye']; 
	$banla = $_GET['banla']; 

	if($banla=="yap"){
	$dbpdo->exec("UpDate users Set ban = '1' where seoslug = '$uye'");
	
	}elseif($banla=="kaldir"){
	 $dbpdo->exec("UpDate users Set ban = '0' where seoslug = '$uye'");
	
	}
	
	header("Location: ../users.php?user=$uye");

}elseif ($action == 'uyeyoneticiyap') { 
	$uye = $_GET['uye']; 
	$yonetici = $_GET['yonetici']; 

	if($yonetici=="yap"){
	 $dbpdo->exec("UpDate users Set usertype = '1' where seoslug = '$uye'");
	
	}elseif($yonetici=="kaldir"){
	 $dbpdo->exec("UpDate users Set usertype = '0' where seoslug = '$uye'");
	
	}
	
	header("Location: ../users.php?user=$uye");
}
?>
