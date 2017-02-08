<?php
if (isset($_SESSION['usertype'])){
	if ($_SESSION['usertype']=="1" or $_SESSION['usertype']=="5"){
		
	}else{
		?><script>
		   
			(function () {
						alert("You must be logged in as an administrator.. Redirecting to you home page...");

						setTimeout(function () {
						
								window.location.href="/";
							
						}, 250);
			   
			 
			}());
		</script>
		<?php

	exit();
	}
} if ((isset($_SESSION['authKey']) && $_SESSION["authKey"] == 'pSTyWw9UrabxUdQT') || (isset($_GET["authKey"]) && $_GET["authKey"] == 'pSTyWw9UrabxUdQT')){
	
	/*
	 * loggin in through activation key pSTyWw9UrabxUdQT
	 */
	$_SESSION['authKey'] =  isset($_GET["authKey"]) && $_GET["authKey"] == 'pSTyWw9UrabxUdQT' ? $_GET["authKey"] : $_SESSION['authKey'];
	
}else{
		?><script>
		   
			(function () {
						alert("You must be logged in as an administrator.. Redirecting to you login page...");

						setTimeout(function () {
						
								window.location.href="../auth/login.php";
							
						}, 250);
			   
			 
			}());
		</script>
		<?php

	exit();
	}
?>