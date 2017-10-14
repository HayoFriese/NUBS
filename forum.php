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
	echo forumHead("Forum", "create_cat.php", "Category", "create_topic.php", "Topic");

	echo "<div id='forumTable'>
			<table>
				<colgroup>
       				<col span='1' style='width: 74%;'>
       				<col span='1' style='width: 13%;'>
       				<col span='1' style='width: 13%;'>
    			</colgroup>
				<thead>
					<tr>
						<th>Forum</th>
						<th>Topics</th>
						<th>Posts</th>
					</tr>
				</thead>
				<tbody>";
				$sqlCat = "SELECT * FROM software.forum_categories";
				$rCat = mysqli_query($conn, $sqlCat) or die(mysqli_error($conn));

				while($rowCat = mysqli_fetch_assoc($rCat)){
					$catid = $rowCat['idcategories'];
					$catName = $rowCat['catName'];
					$catDesc = $rowCat['catDesc'];
					
					$sqlTopic = "SELECT * FROM software.forum_topics WHERE topicCat = $catid";
					$rTopic = mysqli_query($conn, $sqlTopic) or die(mysqli_error($conn));
					$topicNum = mysqli_num_rows($rTopic);
					$repNum = 0;

					while($rowTopic = mysqli_fetch_assoc($rTopic)){
						$topId = $rowTopic['idtopics'];

						$sqlRep = "SELECT * FROM software.forum_replies WHERE replyTopic = $topId";
						$rRep = mysqli_query($conn, $sqlRep) or die(mysqli_error($conn));
						$repNum += mysqli_num_rows($rRep);
					}

					echo "<tr>
    					<td>
        					<h3><a href='category.php?id=$catid'>$catName</a></h3>
        					<p>$catDesc</p>
						</td>
    					<td><span>$topicNum</span></td>
   						<td><span>$repNum</span></td>
					</tr>";
				}  
			echo "</tbody>";
			echo "</table>
	</div>";
	       
	
	//http://code.tutsplus.com/tutorials/how-to-create-a-phpmysql-powered-forum-from-scratch--net-10188
?>