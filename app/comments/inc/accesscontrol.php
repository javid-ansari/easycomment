<?php
$access="";
$linenumber=0;
$domainaccess = "";

$access = explode(",", $db_allowedsites);

if(isset($_GET["access"])){
 $access = $_GET["access"]; 

	if($access==null or $access=="" or $access=="undefined"){
		die("You have no access!");
	}

	if(substr($access, -1)=='|'){

		$access = substr($access, 0, -1);

	}


 	$domainaccess = $access;

	foreach(explode(",", $db_allowedsites) as $line){
	
		$line = str_replace("http://", "", 	$line);
		$line = str_replace("https://", "", 	$line);
		
		if ($line==base64_decode($access)){
				$linenumber=$linenumber+1;
		}
	}
}else{
	die("You have no access!");
}

if($linenumber==0){ die("No access in this domain."); }

$dsdler = explode("/app", $_SERVER['REQUEST_URI']);
if(isset($dsdler[0])){
	$urlll=$dsdler[0];
}else{
	die("Could not start script. Configuration files missing or not configured. You must check /app/install.php "); //or default to a language
	exit();
}
							
							
$theme=$db_theme; //default theme
if(isset($_GET["theme"])){
 $theme = $_GET["theme"]; 
	if (file_exists($_SERVER['DOCUMENT_ROOT'].$urlll."/app/assets/themes/".$theme."/styles/main.css")) {
	   $theme = $theme;
	} else {
	   echo "<h3>Not a Valid Theme</h3>";
	   exit();
	}
}

$titlene=$lang["COMMENT_LINK_1"]; //default titte
if(isset($_GET["title"])){
 $titlene = $_GET["title"]; 
}

if(isset($_GET["C_id"])){
 $C_id = $_GET["C_id"]; 

} else {
	   echo "<h3>Not a Valid ID</h3>";
	   exit();
}


$stringim = "[ayrac1]elma[/ayrac1][ayrac2]armut[/ayrac2][ayrac1]muz[/ayrac1][ayrac2]kiraz[/ayrac2]";



function BetweenStr($InputString, $StartStr, $EndStr=0, $StartLoc=0) {
	if (($StartLoc = strpos($InputString, $StartStr, $StartLoc)) === false) { return; }
	$StartLoc += strlen($StartStr);
	if (!$EndStr) { $EndStr = $StartStr; }
	if (!$EndLoc = strpos($InputString, $EndStr, $StartLoc)) { return; }
	return substr($InputString, $StartLoc, ($EndLoc-$StartLoc));
}


$CUSER_ID="";
$CUSER_NAME="";
$CUSER_ICON="";
$CUSER_LINK="";
$CUSER="";

if(isset($_GET["user"])){

	$userhashin = base64_decode($_GET["user"]);


	$CUSER_ID = BetweenStr($userhashin,"CUSER_ID=","&");

	$CUSER_NAME = BetweenStr($userhashin,"CUSER_NAME=","&");

	$CUSER_ICON = BetweenStr($userhashin,"CUSER_ICON=","&");

	$CUSER_LINK = BetweenStr($userhashin,"CUSER_LINK=","&");


	if($CUSER_ID=="" or $CUSER_NAME=="" or $CUSER_ICON=="" or $CUSER_LINK==""){
		$CUSER="OFF";
	}else{
		$CUSER="ON";

		//check user assets
		$rsqa  = $dbpdo->query("Select id from comments where out_id = '$CUSER_ID' and out_name != '$CUSER_NAME' or out_id = '$CUSER_ID' and out_icon != '$CUSER_ICON' or out_id = '$CUSER_ID' and out_link != '$CUSER_LINK' order by id desc limit 15");
		while($gelenz = $rsqa->fetch()){
			$return1 = $dbpdo->prepare("UpDate comments Set out_name = '$CUSER_NAME' , out_link = '$CUSER_LINK' , out_icon = '$CUSER_ICON' where id = '$gelenz[id]'");
			$return1 = $return1->execute();
		}
	}


}else{

	if(substr($_GET["access"], -1)=='|'){
		die();
	}

}


$FooterLinks = "";
if(isset($_GET["FooterLinks"])){
 $FooterLinks = "Off";
}


if(isset($_GET["C_url"])){

	$C_urln = base64_decode($_GET["C_url"]);


	if(filter_var($C_urln, FILTER_VALIDATE_URL)){
	 $C_url = $C_urln;
	} else {
	   echo "<h3>Not a Valid URL</h3>";
	   exit();
	}
} else {
	   echo "<h3>Not a Valid URL</h3>";
	   exit();
}