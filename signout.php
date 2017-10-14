<?php
	session_start();
	
	
	if(isset($_SESSION['host_signed_in']) && $_SESSION['host_signed_in'] == true){
		//unset session values 
			$_SESSION = [];
		session_destroy();
		echo "You have successfully signed out.";
		header("location: index.php");
	}
	if(isset($_SESSION['user_signed_in']) && $_SESSION['user_signed_in'] == true){
		//unset session values 
			$_SESSION = [];
		session_destroy();
		echo "You have successfully signed out.";
		header("location: index.php");
	}
?>