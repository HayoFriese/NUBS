<?php
	include "server/db_conn.php";
	if(isset($_POST['submit'])){
		//Ratings
			$rateError = [];
			//Get Values for Rating
				$rateCon = mysqli_real_escape_string($conn, isset($_POST['idconcert'])) ? $_POST['idconcert']: null;
				$rateBy = mysqli_real_escape_string($conn, isset($_POST['ratingBy'])) ? $_POST['ratingBy']: null;
				$rateTitle = mysqli_real_escape_string($conn, isset($_POST['ratingTitle'])) ? $_POST['ratingTitle']: null;
				$rateNum = mysqli_real_escape_string($conn, isset($_POST['ratingNum'])) ? $_POST['ratingNum']: null;
				$rateDesc = mysqli_real_escape_string($conn, isset($_POST['ratingDesc'])) ? $_POST['ratingDesc']: null;
			
			//Trim Rating Values
				$rateCon = trim($rateCon);
				$rateBy = trim($rateBy);
				$rateTitle = trim($rateTitle);
				$rateNum = trim($rateNum);
				$rateDesc = trim($rateDesc);
			
			//Special Chars for Rating
				$rateTitle = filter_var($rateTitle, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
    	       	$rateDesc = htmlspecialchars($rateDesc);
    		
    		//Numeric
    		    if(!is_numeric($rateBy) || !is_numeric($rateNum) || !is_numeric($rateCon)){
    	    		$rateError[] .= 'User ID, Rating Number, and Concert ID Must be numeric.';
    	    	}
    		
    		//Title Length
    		    elseif(strlen($rateTitle) > 255){
    		    	$rateError[] .= 'Subject limited to 255 Characters.';
    		    }
    		
    		//Empty title or description
    		    elseif(empty($rateTitle) || empty($rateDesc) || empty($rateNum)){
    		    	$rateError[] .= 'Aside from file uploads, all fields are required';
    		    }
    		
    		//If there are errors
    		    if(!empty($rateError)){
    		      	echo '<ul>';
    		        foreach ($rateError as $key => $value){
    		        	echo '<li>'.$value.'</li>';
    		        }
    		        echo '</ul>';
    		
    		//Posting Data
    			} else {
    		    	$sqlPostRate = "INSERT INTO software.concert_rating(ratingNum, ratingTitle, ratingDesc, ratingConcert, ratingBy, ratingDate) VALUES(?, ?, ?, ?, ?, NOW())";
					
					$stmt = mysqli_prepare($conn, $sqlPostRate) or die(mysqli_error($conn));
        		    mysqli_stmt_bind_param($stmt, 'dssdd', $rateNum, $rateTitle, $rateDesc, $rateCon, $rateBy) or die(mysqli_error($conn));
            		mysqli_stmt_execute($stmt) or die(mysqli_error($conn));
            		mysqli_stmt_close($stmt);
   	    		}

   	    //Media
    		$idrate = mysqli_insert_id($conn);
   	    	$medError = [];
   	    	//Video
   	    		if(!empty($_POST['mediaVideo'])){
   	    			$mCode = 4;
   	    			$medVid = mysqli_real_escape_string($conn, isset($_POST['mediaVideo'])) ? $_POST['mediaVideo']: null;
   	    			$medVid = trim($medVid);

   	    			$sqlVid = "INSERT INTO software.media(mediaLocation, uploadBy, mediaConcert, mediaSpecial, mediaPost) VALUES(?, ?, ?, ?, ?)";
   	    			
   	    			$stmt = mysqli_prepare($conn, $sqlVid)  or die(mysqli_error($conn));
   	    			mysqli_stmt_bind_param($stmt, 'ssddd', $medVid, $rateBy, $rateCon, $mCode, $idrate) or die(mysqli_error($conn));
   	    			mysqli_stmt_execute($stmt) or die(mysqli_error($conn));
   	    			mysqli_stmt_close($stmt);
   	    		} 
   	    	//Image
   	    		if(isset($_FILES['file']['name'])){
   	    			$dir = ('images/'.$rateCon);

   	    			foreach ($_FILES['file']['name'] as $p => $name){
      					$allowedExts = array("gif", "jpeg", "jpg", "png");
   						$temp = explode(".", $name);
   						$extension = end($temp);
   						if ((($_FILES['file']['type'][$p] == "image/gif")
   						|| ($_FILES['file']['type'][$p] == "image/jpeg")
   						|| ($_FILES['file']['type'][$p] == "image/jpg")
   						|| ($_FILES['file']['type'][$p] == "image/png"))
   						&& ($_FILES['file']['size'][$p] < 1073741824)
   						&& in_array($extension, $allowedExts))
   						{
   							if ($_FILES['file']['error'][$p] > 0){
   						  		echo "Return Code: " . $_FILES['file']['error'][$p] . "<br>";
   						  	} else {
   						    //if the file exists within the directory
   						    	if (file_exists($dir . $name)){
   						    		echo "<p>File Already Exists</p>";
   								} else {
   						    		$names = $_FILES['file']['tmp_name'][$p];
									
									if (move_uploaded_file($names, "$dir/$name")){
       				    				$pathname = ($dir."/".$name);
										$mediaCode = 3;
										$sqlImg = "INSERT INTO software.media (mediaLocation, uploadBy, mediaConcert, mediaSpecial, mediaPost) VALUES(?, ?, ?, ?, ?)";
				
						        	    $stmt = mysqli_prepare($conn, $sqlImg);
										mysqli_stmt_bind_param($stmt, "ssddd", $pathname, $rateBy, $rateCon, $mediaCode, $idrate);
										mysqli_stmt_execute($stmt);
										mysqli_stmt_close($stmt);
									}
								}
   							}
   						}
   	    			}
   	    		}
   	    	echo "<p>Success! <a href='concert.php?conID=$rateCon'>Back...</a></p>";

	} else {
		if((isset($_SESSION['host_signed_in']) && $_SESSION['host_signed_in'] == true)
		|| (isset($_SESSION['user_signed_in']) && $_SESSION['user_signed_in'] == true)){
			
			$user = $_SESSION['iduser'];
			echo "<form method='post' action='review.php' enctype='multipart/form-data'>";
				echo "<input type='hidden' name='idconcert' value='$conID'>";
				echo "<input type='hidden' name='ratingBy' value='$user'>";
				echo "<div id='reviewSub'>";
					echo "<input type='text' name='ratingTitle' id='ratingTitle' placeholder='Subject...'>";
					echo "<select name='ratingNum'>
						<option value='5'>&#9733;&#9733;&#9733;&#9733;&#9733;</option>
						<option value='4'>&#9733;&#9733;&#9733;&#9733;</option>
						<option value='3'>&#9733;&#9733;&#9733;</option>
						<option value='2'>&#9733;&#9733;</option>
						<option value='1'>&#9733;</option>
					</select>";
				echo "</div>";
				echo "<textarea name='ratingDesc' id='ratingDesc' placeholder='Body...'></textarea>";
				echo "<p>";
					echo "<input type='text' id='reviewVid' name='mediaVideo' placeholder='Insert Video Link Here...'>";
					echo " Or ";
					echo "<input type='file' name='file[]' id='file'>";
				echo "</p>";
				echo "<input type='submit' name='submit' value='Post'>";
			echo "</form>";		
		} else {
			echo "<h2 id='reviewSignNo'>You need to be signed in to leave a review.</h2>";
		}

		$sqlGetPosts = "SELECT idrating, ratingNum, ratingTitle, ratingDesc, ratingConcert, userName, ratingDate 
			FROM software.concert_rating 
			INNER JOIN software.user ON software.user.iduser = software.concert_rating.ratingBy 
			WHERE ratingConcert = $conID ORDER BY ratingDate DESC";
	
		$rGetPost = mysqli_query($conn, $sqlGetPosts) or die(mysqli_error($conn));
		
		$rowCnt = mysqli_num_rows($rGetPost);
		if($rowCnt == 0){
			echo "<p>No Ratings Available</p>";
		} else {
			while ($rowPosts = mysqli_fetch_assoc($rGetPost)){
				$rateId = $rowPosts['idrating'];
				$rateNum = $rowPosts['ratingNum'];
				$rateTitle = $rowPosts['ratingTitle'];
				$rateDesc = $rowPosts['ratingDesc'];
				$rateCon = $rowPosts['ratingConcert'];
				$rateBy = $rowPosts['userName'];
				$rateDate = $rowPosts['ratingDate'];

				$sqlRateMed = "SELECT * FROM software.media WHERE (mediaSpecial = 3 OR mediaSpecial = 4) AND mediaConcert = $rateCon AND mediaPost = $rateId";
				$rRateMed = mysqli_query($conn, $sqlRateMed) or die(mysqli_error($conn));
				$rowRateMedCnt = mysqli_num_rows($rRateMed);
				
				echo "<div id='reviewPosts'>";
					echo "<h4>&quot;$rateTitle&quot;</h4>";
					echo "<div id='rateStat'>";
						echo "<p id='rateNum'>";
						for($i=0; $i < $rateNum; $i++){
							echo "&#9733;";
						}
						echo "</p>";
						echo "<p id='rateDate'>Reviewed $rateDate</p>";
					echo "</div>";

					echo "<div id='rateMain'>";
						echo "<p id='rateDesc'>$rateDesc</p>";
						echo "<p id='rateBy'>Posted by $rateBy</p>";
					echo "</div>";

					if($rowRateMedCnt != 0){
						while ($rowRateMed = mysqli_fetch_assoc($rRateMed)){
							$rateMedLoc = $rowRateMed['mediaLocation'];
							$rateMedUpBy = $rowRateMed['uploadBy'];
							$rateMedCon = $rowRateMed['mediaConcert'];
							$rateMedCode = $rowRateMed['mediaSpecial'];
							if($rateMedCode == 4){
								echo "<div id='rateVid'>Video : <a href='$rateMedLoc'>$rateMedLoc</a></div>";
							} elseif($rateMedCode == 3){
								echo "<div id='rateImg'><img src='$rateMedLoc'></div>";
							}
						}
					}
				echo "</div>";
			}
		}	
	}
	
?>