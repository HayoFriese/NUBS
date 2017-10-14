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

	echo forumHead("Create New Topic", "create_cat.php", "Category", "create_topic.php", "Topic");
	echo "<div id='forumHome'><p><a href='forum.php'>Back...</a></p></div>";

	if((isset($_SESSION['host_signed_in']) && $_SESSION['host_signed_in'] == true)
	|| (isset($_SESSION['user_signed_in']) && $_SESSION['user_signed_in'] == true)){
		if($_SERVER['REQUEST_METHOD'] != 'POST'){
			$sql = "SELECT idcategories, catName, catDesc FROM software.forum_categories";
			$r = mysqli_query($conn, $sql) or die(mysqli_error($conn));

			if(mysqli_num_rows($r)==0){
				if($_SESSION['userLevel']==1){
					echo "<div id='errorBody'><p>You have not created categories yet.</p></div>";
				} else {
					echo "<div id='errorBody'><p>Before you can post a topic, you must wait for an admin to create some categories.</p></div>";
				}
			} else {
				echo '<form id="postTopic" method="post" action="">
                    <input type="text" placeholder="Subject... " name="subject">'; 
                 
                echo '<select name="categories">
                		<option value="">Categories...</option>';
                    while($row = mysqli_fetch_assoc($r)){
                    	$idcat = $row['idcategories'];
                    	$catName = $row['catName'];
                        echo "<option value='$idcat'>$catName</option>";
                    }
                echo '</select>'; 
                     
                echo '<textarea name="content" placeholder="Message..."></textarea>
                    <div><input type="submit" value="Create topic"></div>
                 </form>';
			}
		} else {
			$query = "BEGIN WORK;";
			$r = mysqli_query($conn, $query) or die(mysqli_error($conn));

			if(!$r){
				echo "<div id='errorBody'><p>An error occured while creating your topic. Please try again later.</p></div>";
			} else {
				$sql = "INSERT INTO software.forum_topics(topicSubject, topicDate, topicCat, topicBy)
					VALUES('".mysqli_real_escape_string($conn, $_POST['subject'])."',
							NOW(),
							'".mysqli_real_escape_string($conn, $_POST['categories'])."',
							'".$_SESSION['iduser']."'
							)";
	
				$r = mysqli_query($conn, $sql);
				if(!$r){
        		        echo "<div id='errorBody'><p>An error occured while inserting your data. Please try again later.".mysqli_error($conn)."</p></div>";
        		        $sql = "ROLLBACK;";
        		        $r = mysqli_query($conn, $sql);
        		} else {
        			$topicid = mysqli_insert_id($conn);
		
        			$sql = "INSERT INTO software.forum_replies(replyContent, replyDate, replyTopic, replyBy)
							VALUES('" . mysqli_real_escape_string($conn, $_POST['content']) . "',
									NOW(),
									'".$topicid."',
									'".$_SESSION['iduser']."'
							)";

					$r = mysqli_query($conn, $sql);

					if(!$r){
        		            echo "<div id='errorBody'><p>An error occured while inserting your post. Please try again later.".mysqli_error($conn)."</p></div>";
        		            $sql = "ROLLBACK;";
        		          	$r = mysqli_query($conn, $sql);
        			} else {
        				$sql = "COMMIT;";
        				$r = mysqli_query($conn, $sql);
		
        				echo "<div id='errorBody'><p>You have successfully created <a href='topic.php?id=$topicid'>your new topic.</a></p></div>";
        			}
        		}
        	}
		}
	} else{
		echo "<div id='errorBody'><p>You must be signed in to create a topic!</p>";
	    echo "<p><a href='signin.php'>Sign in</a> or <a href='signup.php'>create an account</a>.</p></div>";
	}
?>