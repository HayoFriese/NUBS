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

	$id = mysqli_real_escape_string($conn, $_GET['id']);

	$sql = "SELECT idtopics, topicSubject, topicDate, topicCat, userName
		FROM software.forum_topics 
		INNER JOIN software.user ON software.user.iduser = software.forum_topics.topicBy 
		WHERE idtopics = $id";
	$r = mysqli_query($conn, $sql) or die(mysqli_error($conn));

	if(mysqli_num_rows($r)==0){
		echo "<div id='errorBody'><p>This Topic does not exist.</p></div>";
	} else {
		while($row = mysqli_fetch_assoc($r)){
			$idTopics = $row['idtopics'];
			$topSub = $row['topicSubject'];
			$topDate = $row['topicDate'];
			$topCat = $row['topicCat'];
			$topOP = $row['userName'];

			echo forumHead("$topSub", "create_cat.php", "Category", "create_topic.php", "Topic");
			echo "<div id='forumHome'><p><a href='category.php?id=$topCat'>Back...</a></p></div>";

				$sql2 = "SELECT
    				forum_replies.replyTopic,
    				forum_replies.replyContent,
    				forum_replies.replyDate,
    				forum_replies.replyBy,
    				user.iduser,
    				user.userName
				FROM
    				software.forum_replies
				LEFT JOIN
    				software.user
				ON
				    software.forum_replies.replyBy = software.user.iduser
				WHERE
				   	software.forum_replies.replyTopic = $id";
	
				$r2 = mysqli_query($conn, $sql2) or die(mysqli_error($conn));
	
				if(mysqli_num_rows($r2)==0){
					echo "<div id='errorBody'><p>There are no responses.</p></div>";
				} else {
					echo "<div id='forumTable'>
						<table>
							<colgroup>
       							<col span='1' style='width: 25%;'>
       							<col span='1' style='width: 75%;'>
    						</colgroup>
    						<thead>
								<tr>
									<th>By</th>
									<th>Post</th>
								</tr>
							</thead>
                		    <tbody>";
            			while($row = mysqli_fetch_assoc($r2)){
            				$replyBy = $row['userName'];
            				$replyDate = $row['replyDate'];
            				$replyContent = $row['replyContent'];
            				echo "<tr>
            			        <td id='forumPosts'>
            			        	<h3>$replyBy</h3>
            			        	<p>$replyDate</p>
            			        </td>
            			        <td id='forumPosts'>
            			            $replyContent
            			        </td>
            			    </tr>";
            			}
            			echo "</tbody>
            			</table>
            		</div>";
				}
			if((isset($_SESSION['host_signed_in']) && $_SESSION['host_signed_in'] == true)
			|| (isset($_SESSION['user_signed_in']) && $_SESSION['user_signed_in'] == true)){
				echo "<div id='replySec'>
					<h2>Post a response</h2>
					<form method='post' action='reply.php?id=$idTopics'>
    					<textarea name='reply' placeholder='Reply...'></textarea>
    					<input type='submit' value='Post'>
					</form>
				</div>";
			} else {
				echo "<div id='errorBody'><p style='opacity:0.4;'>You must sign in to respond to this post.</p></div>";
			}
		}
	}
?>