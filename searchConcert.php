<?php
	include 'server/db_conn.php';
	session_start();
	require_once('functions.php');
	echo pageIni("NUBS ", "forum.css", "new_concert.js");
	echo '<div id="container">';
    	echo '<div id="content">';
    		if(isset($_SESSION['host_signed_in']) && $_SESSION['host_signed_in'] == true){
    		  echo navigation("NUBS", "index.php", "dashboard.php", "MY ACCOUNT", "forum.php", "BOARDS", "DONATE");
    		  echo "<div id='loginHead'>";
    		    echo "<p>Welcome, <span><a href='dashboard.php'>".$_SESSION['userName']."</a></span>. Not you? <a href='signout.php'>Sign out</a></p>";
    		  echo "</div>";
    		}
    		elseif(isset($_SESSION['user_signed_in']) && $_SESSION['user_signed_in'] == true){
    		  echo navigation("NUBS", "index.php", "#", "MY ACCOUNT", "forum.php", "BOARDS", "DONATE");
    		  echo "<div id='loginHead'>";
    		    echo "<p>Welcome, <span>".$_SESSION['userName']."</span>. Not you? <a href='signout.php'>Sign out</a></p>";
    		  echo "</div>";
    		} else{
    		  echo navigation("NUBS", "index.php", "signin.php", "SIGN IN / SIGN UP", "forum.php", "BOARDS", "DONATE");
    		}

	$sql = "SELECT idconcert, concertTitle, concertDesc, addressOne, postCode, concertDate, concertTime, mediaId, concertHost, userCompany, concertAgeRes
	FROM software.concert
	INNER JOIN software.address ON software.address.idaddress = software.concert.concertLocation
	INNER JOIN software.user ON software.user.iduser = software.concert.concertHost";

	$r = mysqli_query($conn, $sql) or die(mysqli_error($conn));

	echo "<div id='searchHead'>
		<h1>Search All Concerts</h1>
	</div>
	<div id='searchContainer'>";
			$rowCnt = mysqli_num_rows($r);
			if($rowCnt == 0){
				echo "<p>No concerts available</p>";
			} else {
				while ($row = mysqli_fetch_assoc($r)){
					$conID = $row['idconcert'];
					$conTitle = $row['concertTitle'];
					$conDesc = $row['concertDesc'];
					$conPost = $row['postCode'];
					$conDate = $row['concertDate'];
					$conAd = $row['addressOne'];
					$conMedia = $row['mediaId'];
					$conHost = $row['userCompany'];
					$conAgeR = $row['concertAgeRes'];

					$sqlconmed = "SELECT mediaLocation FROM software.media WHERE idmedia = $conMedia";
					$rconmed = mysqli_query($conn, $sqlconmed) or die(mysqli_error($conn));
					$rowMed = mysqli_fetch_assoc($rconmed);
					$conCover = $rowMed['mediaLocation'];

					echo "<div id='searchElem'>";
						echo "<div id='cover'>
							<img src='$conCover'>
							</div>";
						
						echo "<div id='searchContent'>
							<a href='concert.php?conID=$conID'><h2>$conTitle</h2></a> 
							<p>$conAd, $conPost</p>
							<p>$conDate</p>
							<p>$conHost</p>
						</div>";
					echo "</div>";	
				}
			}
	echo "</div>";
	echo '</div>';
    echo '</div>';
    echo footer();
?>