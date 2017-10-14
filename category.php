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
	$sql = "SELECT * FROM software.forum_categories WHERE idcategories = '" . $id . "'";
	$r = mysqli_query($conn, $sql) or die(mysqli_error($conn));

	if(mysqli_num_rows($r)==0){
		echo "<div id='errorBody'><p>This category does not exist.</p></div>";
	} else {
		while($row = mysqli_fetch_assoc($r)){
			$catName = $row['catName'];
			echo forumHead("Topics in $catName", "create_cat.php", "Category", "create_topic.php", "Topic");
			echo "<div id='forumHome'><p><a href='forum.php'>Back...</a></p></div>";
		}

		$sql = "SELECT idtopics, topicSubject, topicDate, topicCat FROM software.forum_topics WHERE topicCat = $id";
		$r = mysqli_query($conn, $sql) or die(mysqli_error($conn));

		if(mysqli_num_rows($r)==0){
			echo "<div id='errorBody'><p>There are no topics in this category yet.</p></div>";
		} else {
			echo "<div id='forumTable'>
					<table>
						<colgroup>
       						<col span='1' style='width: 70%;'>
       						<col span='1' style='width: 30%;'>
    					</colgroup>
    					<thead>
							<tr>
								<th>Topic</th>
								<th>Created</th>
							</tr>
						</thead>
                	    <tbody>";
            			while($row = mysqli_fetch_assoc($r)){
            				$idtopic = $row['idtopics'];
            				$topSub = $row['topicSubject'];
            				$topDate =  date('d-m-Y', strtotime($row['topicDate']));
            				echo "<tr>
            			        <td>
            			        	<h3><a href='topic.php?id=$idtopic'>$topSub</a><h3>
            			        </td>
            			        <td>$topDate</td>
            			    </tr>";
            			}
            		echo "</tbody>
            		</table>
            	</div>";
		}
	}

?>