<?php
	include 'server/db_conn.php';
	session_start();
	require_once('functions.php');

	echo pageIni("NUBS | Forums", "forum.css", "new_concert.js");

	if((isset($_SESSION['host_signed_in']) && $_SESSION['host_signed_in'] == true)
	|| (isset($_SESSION['user_signed_in']) && $_SESSION['user_signed_in'] == true)){
		echo navigation("NUBS", "index.php", "#", "MY ACCOUNT", "forum.php", "BOARDS", "DONATE");
		echo "<div id='loginHead'>";
			echo "<p>Welcome, <span>".$_SESSION['userName']."</span>. Not you? <a href='signout.php'>Sign out</a></p>";
		echo "</div>";
	} else{
		echo navigation("NUBS", "index.php", "signin.php", "SIGN IN / SIGN UP", "forum.php", "BOARDS", "DONATE");
		echo "<div id='loginHead'>";
			echo "<p><a href='signin.php'>Sign in</a> or <a href='signup.php'>create an account</a>.</p>";
		echo "</div>";
	}
	
	echo "<p><a href='forum.php'>Forum Home</a></p>";
	echo "<p><a href='create_topic.php'>Create Topic</a></p>";
	echo "<p><a href='create_cat.php'>Create Category</a></p>";

	$id = mysqli_real_escape_string($conn, $_GET['id']);

	if($_SERVER['REQUEST_METHOD'] != 'POST'){
		echo "<div id='errorBody'><p>This file cannot be called directly.</p></div>";
	} else {
		if((isset($_SESSION['host_signed_in']) && $_SESSION['host_signed_in'] == true)
			|| (isset($_SESSION['user_signed_in']) && $_SESSION['user_signed_in'] == true)){

			$sql = "INSERT INTO software.forum_replies(replyContent, replyDate, replyTopic, replyBy)
					VALUES('" . $_POST['reply'] . "',
							NOW(),
							'" . $id . "',
							'" . $_SESSION['iduser'] . "')";

			$r = mysqli_query($conn, $sql) or die(mysqli_error($conn));

			header("location: topic.php?id=$id");
		} else {
			echo "<div id='errorBody'><p><a href='signin.php'>Sign in</a> or <a href='signup.php'>create an account</a>.</p></div>";
		}
	}
?>