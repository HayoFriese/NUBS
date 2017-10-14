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

	echo forumHead("Create New Category", "create_cat.php", "Category", "create_topic.php", "Topic");
	echo "<div id='forumHome'><p><a href='forum.php'>Back...</a></p></div>";
	
	if((isset($_SESSION['host_signed_in']) && $_SESSION['host_signed_in'] == true)){
		
		if($_SERVER['REQUEST_METHOD'] != 'POST'){

			echo '<div id="postTopic">
				<form method="post" action="">
					<input type="text" placeholder="Category Name..." name="catName">
					<textarea placeholder="Category Description..." name="catDesc"></textarea>
					<input type="submit" value="Add Category">
				</form>
			</div>';
		}else {
			$sql = "INSERT INTO software.forum_categories(catName, catDesc)
				VALUES('" . mysqli_real_escape_string($conn, $_POST['catName']) . "',
					'" . mysqli_real_escape_string($conn, $_POST['catDesc']) . "')";

			$r = mysqli_query($conn, $sql) or die(mysqli_error($con));

			echo "<div id='errorBody'><p>You have successfully created the Category. Click <a href='forum.php'>here</a> to return.</p></div>";
		}
	} else{
		echo "<div id='errorBody'><p>You must be a host to create a category</p>";
	    echo "<p><a href='signin.php'>Sign in</a> or <a href='signup.php'>create an account</a>.</p></div>";
	}
?>