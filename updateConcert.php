<?php
	include 'server/db_conn.php';
	session_start();
	require_once('functions.php');
	echo pageIni("NUBS | Update Concert", "forum.css", "new_concert.js");
	
	echo "<div id='container'>";
    echo "<div id='content' class='updateNavBack'>";

	if(isset($_SESSION['host_signed_in']) && $_SESSION['host_signed_in'] == true){
		echo navigation("NUBS", "index.php", "signout.php", "SIGN OUT", "forum.php", "BOARDS", "DONATE");
        echo "<div id='leftNav'>
                <ul id='leftNavCont'>
                    <li><a href='dashboard.php'>DASHBOARD</a></li>
                    <li><a class='active' href='concertListAdmin.php'>CONCERTS</a></li>
                    <li><a href='#reviews'>REVIEWS</a></li>
                    <li><a href='#account'>ACCOUNT DETAILS</a></li>
                    <li><a href='forum.php'>FORUMS</a></li>             
                    <li><a href='#settings'>SETTINGS</a></li>
                    <li><a href='#help'>HELP</a></li>
                </ul>
            </div>";

		if(isset($_GET['conID'])){
			$conID = $_GET['conID'];

			$sqlGet = "SELECT concertTitle, concertDesc, concertLocation,
			idaddress, addressOne, addressTwo, postCode, state, country,
			concertDate, concertTime, concertAgeRes
			FROM software.concert
			INNER JOIN software.address ON software.address.idaddress = software.concert.concertLocation
			WHERE idconcert = $conID";

			$rGet = mysqli_query($conn, $sqlGet) or die(mysqli_error($conn));
			$rowGet = mysqli_fetch_assoc($rGet);

			$concertTitle = $rowGet['concertTitle'];
			$concertDesc = $rowGet['concertDesc'];
			$idaddress = $rowGet['concertLocation'];
			$addressOne = $rowGet['addressOne'];
			$addressTwo = $rowGet['addressTwo'];
			$postCode = $rowGet['postCode'];
			$state = $rowGet['state'];
			$country = $rowGet['country'];
			$concertDate = $rowGet['concertDate'];
			$concertTime = $rowGet['concertTime'];
			$concertAgeRes = $rowGet['concertAgeRes'];

			$sqlTicket = "SELECT * FROM software.concert_ticket WHERE ticketConcert = $conID";
			$rTicket = mysqli_query($conn, $sqlTicket) or die(mysqli_error($conn));

			$sqlLineup = "SELECT * FROM software.concert_lineup WHERE lineupConcert = $conID";
			$rLineup = mysqli_query($conn, $sqlLineup) or die(mysqli_error($conn));

			$sqlImage = "SELECT * FROM software.media WHERE mediaConcert= $conID";
			$rImage = mysqli_query($conn, $sqlImage) or die(mysqli_error($conn));

			$sqlVideo = "SELECT * FROM software.media WHERE mediaConcert= $conID AND mediaSpecial = 5";
			$rVideo = mysqli_query($conn, $sqlVideo) or die(mysqli_error($conn));


			echo "
	<div id='concertContainer'>
        <div id='updateConcert'>
			<form method='post' action='updateConcert.php' enctype='multipart/form-data'>
				<p id='updateBack'><a href='concertListAdmin.php'>Back...</a></p>
				<div id='info1'>
					<h4><a href='concert.php?conID=$conID'>See Front End</a></h4>
					
					<h2>Concert Summary</h2>
					<div>
                		<h3>Concert Title -</h3>
                		<input type='hidden' name='idconcert' value='$conID'>
            	    	<input type='text' name='concertTitle' id='updateconcertTitle' value='$concertTitle'>
            		</div>
					<div>  
                		<h3>Date -</h3>
                		<input type='date' id='updatedate' name='concertDate' value='$concertDate'>
                		<h3>Time -</h3>
                		<input type='time' id='updatetime' name='concertTime' value='$concertTime'>
            		</div>	
					<div>  
                		<h3>Age Restriction -</h3>
                		<input type='number' id='updateageRestriction' name='concertAgeRes' value='$concertAgeRes'>
              		</div>
              		<div>  
                		<h3>Description -</h3>
                		<textarea id='updatedescription' name='concertDesc'>$concertDesc</textarea>
              		</div> 		
				</div>

				<div id='info2'>
            	  	<div>
            	    	<h2>Concert Location</h2>
            	  	</div>
            	  	<div>
            	    	<h3>Line 1 -</h3>
            	    	<input type='hidden' name='idaddress' value='$idaddress'>
            	    	<input type='text' id='line1' name='addressOne' value='$addressOne'>
            	  	</div>
            		<div>
            	    	<h3>Line 2 -</h3>
           		    	<input type='text' id='line2' name='addressTwo' value='$addressTwo'>
            		</div>
            		<div>  
            	    	<h3>Post Code -</h3>
            	    	<input type='text' id='postcode' name='postCode' value='$postCode'>
            	    	<h3>State -</h3>
            	    	<input type='text' id='state' name='state' value='$state'>
            	    	<h3>Country -</h3>
            	    	<input type='text' id='country' name='country' value='$country'>
            		</div>
          		</div>";


				echo "<div id='info3'>";
					echo "<h2>Image Files</h2>";
					echo "<div>";
					//Echo the media file that acts as cover, with radio button checked, followed by the rest.
						while ($rowImage = mysqli_fetch_assoc($rImage)){
							$idmedia = $rowImage['idmedia'];
							$mediaName = $rowImage['mediaName'];
							$mediaLocation = $rowImage['mediaLocation'];
							$uploadBy = $rowImage['uploadBy'];
							$mediaConcert = $rowImage['mediaConcert'];
							$mediaSpecial = $rowImage['mediaSpecial'];

							if($mediaSpecial == 1){
								echo "<div>
										<img id='media1' src='$mediaLocation'>
										<input type='radio' name='mediaSpecial' id='radio' value='$idmedia' checked>
										<span>Current Cover Image</span>
									</div>";
							}elseif($mediaSpecial == 2){
								echo "<div>
										<a id='deleteImage' href='removeMedia.php?id=$idmedia'>X</a>
										<img src='$mediaLocation'>
										<input type='radio' name='mediaSpecial' id='radio' value='$idmedia'>
									</div>";
							}
						}
						echo "<p>New: <input type='file' name='file[]' id='file' multiple='multiple'></p>";
						
					echo "</div>";
				echo "</div>";

				echo "<div id='info4'>
					<h2>Video Links</h2>";
					echo "<div>";
						while($rowVideo = mysqli_fetch_assoc($rVideo)){
							$idmedia = $rowVideo['idmedia'];
							$mediaName = $rowVideo['mediaName'];
							$mediaLocation = $rowVideo['mediaLocation'];
							$uploadBy = $rowVideo['uploadBy'];
							$mediaConcert = $rowVideo['mediaConcert'];
							$mediaSpecial = $rowVideo['mediaSpecial'];

							if($mediaSpecial == 5){
								echo "<div id='videoLink'>
										<a href='removeMedia.php?id=$idmedia'>X</a>
										<input type='hidden' name='idVid[]' value='$idmedia'>
										<input type='text' name='vidLink[]' value='$mediaLocation'>
									</div>";
							}
						}
						echo "<p>Add Video: <input type='text' name='mediaVideo' id='mediaVideo'></p>
					</div>";
				echo "</div>";

				echo "<div id='info5'>";
					echo "<h2>Tickets</h2>";
					while ($rowTicket = mysqli_fetch_assoc($rTicket)){
						$idticket = $rowTicket['idticket'];
						$ticketName = $rowTicket['ticketName'];
						$ticketFlat = $rowTicket['ticketFlat'];
						$ticketDonate = $rowTicket['ticketDonate'];
						$ticketConcert = $rowTicket['ticketConcert'];
						echo "<div>
                			<h3>Ticket Name -</h3>
                				<input type='hidden' name='idticket[]' value='$idticket'>
                				<input type='text' id='ticketType' name='ticketName[]' value='$ticketName'>
                			<h3>Flat Price -</h3>
                				<input type='number' id='flatPrice' step='0.01' min='0' name='ticketFlat[]' value='$ticketFlat'>
                			<h3>% Donation Tax -</h3>
                				<input type='number' id='donationTax' name='ticketDonate[]' value='$ticketDonate'>
                				<input type='hidden' name='ticketConcert' value='$ticketConcert'>
            			</div>";
					}
				echo "</div>";

				echo "<div id='info6'>";
					echo "<h2>Line Up</h2>";
					for ($lcount=0; $rowLineup = mysqli_fetch_assoc($rLineup); $lcount++){
						$idlineup = $rowLineup['idlineup'];
						$lineUpTitle = $rowLineup['lineUpTitle'];
						$lineupVenue = $rowLineup['lineupVenue'];
						$lineupTime = $rowLineup['lineupTime'];
						$lineupConcert = $rowLineup['lineupConcert'];
						echo "<div>
                			<h3>Act -</h3>
                				<input type='hidden' name='idlineup[]' value='$idlineup'>
                				<input type='text' id='act' name='lineUpTitle[]' value='$lineUpTitle'>
                			<h3>Venue -</h3>
                				<input type='text' id='venue' name='lineupVenue[]' value='$lineupVenue'>
                			<h3>Time -</h3>
                				<input type='time' id='actTime' name='lineupTime[]' value='$lineupTime'>
                				<input type='hidden' name='lineupConcert' value='$lineupConcert'>
              			</div>";
					}
				echo "</div>";

				echo "<div id='info7'>";
					echo "<h2> List of attendees</h2>";
					echo "<div>";
					$sqlBuyers = "SELECT idbuyers, iduser, userName, userEmail, 
					buyerConcert, 
					ticketName, 
					buyerAttended, buyerCost FROM software.buyers 
					INNER JOIN software.user ON software.user.iduser = software.buyers.buyerUser
					INNER JOIN software.concert_ticket ON software.concert_ticket.idticket = software.buyers.buyerTicket 
					WHERE buyerConcert = $conID";
					
					$rBuyers = mysqli_query($conn, $sqlBuyers) or die(mysqli_error($conn));
					$rBuyCnt = mysqli_num_rows($rBuyers);
					if($rBuyCnt == 0){
						echo "<p>No Buyers Yet...</p>";
					} else{
						echo "<table>";
							echo "<tr>
								<th>ID Buyer</th>
								<th>ID User</th>
								<th>User Name</th>
								<th>Email Address</th>
								<th>Ticket Purchased</th>
								<th>Price</th>
								<th>Attending</th>
							</tr>";
							while($rowBuyers=mysqli_fetch_assoc($rBuyers)){
								$idbuy = $rowBuyers['idbuyers'];
								$iduser = $rowBuyers['iduser'];
								$userName = $rowBuyers['userName'];
								$email = $rowBuyers['userEmail'];
								$buyCon = $rowBuyers['buyerConcert'];
								$tickName = $rowBuyers['ticketName'];
								$cost = $rowBuyers['buyerCost'];
								$attending = $rowBuyers['buyerAttended'];

								echo "<tr>
									<td>$idbuy</td>
									<td>$iduser</td>
									<td>$userName</td>
									<td>$email</td>
									<td>$tickName</td>
									<td>$cost</td>
									";
									echo "<td><input type='hidden' name='idAtt[]' value='$idbuy'><input type='checkbox' name='attending[]' value='1' ";
										if($attending==1){echo "checked ";}
									echo "></td>
								</tr>";
							}
						echo "</table>";
					}
					echo "</div>";
					echo "</div>";

				echo "<div id='updateButton'>
            		<input type='submit' id='update' name='update' value='Update'>
            	</div>";
			echo "</form>";
		}
		elseif(isset($_POST['update'])){
			//Get Values
				$idCon = $_POST['idconcert'];
				$idAd = $_POST['idaddress'];

			//Title
				if(isset($_POST['concertTitle'])){
					$title = $_POST['concertTitle'];
					$title = trim($title);

					$sql = "UPDATE software.concert SET concertTitle = ? WHERE idconcert = ?";
					$stmt = mysqli_prepare($conn, $sql) or die(mysqli_error($conn));
					mysqli_stmt_bind_param($stmt, "sd", $title, $idCon) or die(mysqli_error($conn));
					mysqli_stmt_execute($stmt) or die(mysqli_error($conn));
					mysqli_stmt_close($stmt);
				}
			//Description
				if(isset($_POST['concertDesc'])){
					$desc = $_POST['concertDesc'];
					$desc = trim($desc);

					$sql = "UPDATE software.concert SET concertDesc = ? WHERE idconcert = ?";
					$stmt = mysqli_prepare($conn, $sql) or die(mysqli_error($conn));
					mysqli_stmt_bind_param($stmt, "sd", $desc, $idCon) or die(mysqli_error($conn));
					mysqli_stmt_execute($stmt) or die(mysqli_error($conn));
					mysqli_stmt_close($stmt);
				}
			//Date
				if(isset($_POST['concertDate'])){
					$conDate = $_POST['concertDate'];
					$conDate = trim($conDate);

					$sql = "UPDATE software.concert SET concertDate = ? WHERE idconcert = ?";
					$stmt = mysqli_prepare($conn, $sql) or die(mysqli_error($conn));
					mysqli_stmt_bind_param($stmt, "sd", $conDate, $idCon) or die(mysqli_error($conn));
					mysqli_stmt_execute($stmt) or die(mysqli_error($conn));
					mysqli_stmt_close($stmt);
				}
			//Time
				if(isset($_POST['concertTime'])){
					$conTime = $_POST['concertTime'];
					$conTime = trim($conTime);

					$sql = "UPDATE software.concert SET concertTime = ? WHERE idconcert = ?";

					$stmt = mysqli_prepare($conn, $sql) or die(mysqli_error($conn));
					mysqli_stmt_bind_param($stmt, "sd", $conTime, $idCon) or die(mysqli_error($conn));
					mysqli_stmt_execute($stmt) or die(mysqli_error($conn));
					mysqli_stmt_close($stmt);
				}
			//Age Restriction
				if(isset($_POST['concertAgeRes'])){
					$ageR = $_POST['concertAgeRes'];
					$ageR = trim($ageR);

					$sql = "UPDATE software.concert SET concertAgeRes = ? WHERE idconcert = ?";
					$stmt = mysqli_prepare($conn, $sql) or die(mysqli_error($conn));
					mysqli_stmt_bind_param($stmt, "dd", $ageR, $idCon) or die(mysqli_error($conn));
					mysqli_stmt_execute($stmt) or die(mysqli_error($conn));
					mysqli_stmt_close($stmt);
				}
			
			//Address Line One
				if(isset($_POST['addressOne'])){
					$addOne = $_POST['addressOne'];
					$addOne = trim($addOne);

					$sql = "UPDATE software.address SET addressOne = ? WHERE idaddress = ?";
					$stmt = mysqli_prepare($conn, $sql) or die(mysqli_error($conn));
					mysqli_stmt_bind_param($stmt, "sd", $addOne, $idAd) or die(mysqli_error($conn));
					mysqli_stmt_execute($stmt) or die(mysqli_error($conn));
					mysqli_stmt_close($stmt);
				}
			//Address Line Two
				if(isset($_POST['addressTwo'])){
					$addTwo = $_POST['addressTwo'];
					$addTwo = trim($addTwo);

					$sql = "UPDATE software.address SET addressTwo = ? WHERE idaddress = ?";
					$stmt = mysqli_prepare($conn, $sql) or die(mysqli_error($conn));
					mysqli_stmt_bind_param($stmt, "sd", $addTwo, $idAd) or die(mysqli_error($conn));
					mysqli_stmt_execute($stmt) or die(mysqli_error($conn));
					mysqli_stmt_close($stmt);
				}
			//Post Code
				if(isset($_POST['postCode'])){
					$post = $_POST['postCode'];
					$post = trim($post);

					$sql = "UPDATE software.address SET postCode = ? WHERE idaddress = ?";

					$stmt = mysqli_prepare($conn, $sql) or die(mysqli_error($conn));
					mysqli_stmt_bind_param($stmt, "sd", $post, $idAd) or die(mysqli_error($conn));
					mysqli_stmt_execute($stmt) or die(mysqli_error($conn));
					mysqli_stmt_close($stmt);
				}
			//State
				if(isset($_POST['state'])){
					$state = $_POST['state'];
					$state = trim($state);

					$sql = "UPDATE software.address SET state = ? WHERE idaddress = ?";
					$stmt = mysqli_prepare($conn, $sql) or die(mysqli_error($conn));
					mysqli_stmt_bind_param($stmt, "sd", $state, $idAd) or die(mysqli_error($conn));
					mysqli_stmt_execute($stmt) or die(mysqli_error($conn));
					mysqli_stmt_close($stmt);
				}
			//Country
				if(isset($_POST['country'])){
					$country = $_POST['country'];
					$country = trim($country);

					$sql = "UPDATE software.address SET country = ? WHERE idaddress = ?";
					$stmt = mysqli_prepare($conn, $sql) or die(mysqli_error($conn));
					mysqli_stmt_bind_param($stmt, "sd", $country, $idAd) or die(mysqli_error($conn));
					mysqli_stmt_execute($stmt) or die(mysqli_error($conn));
					mysqli_stmt_close($stmt);
				}

			//Tickets
				for($t = 0; $t < count($_POST['ticketName']); $t++){
					$idTick = $_POST['idticket'][$t]; 
					$ticketName = $_POST['ticketName'][$t];
					$ticketName = trim($ticketName);

					$ticketFlat = $_POST['ticketFlat'][$t];
					$ticketFlat = trim($ticketFlat);

					$ticketDonate = $_POST['ticketDonate'][$t];
					$ticketDonate = trim($ticketDonate);

					$sql = "UPDATE software.concert_ticket SET ticketName = ?, ticketFlat = ?, ticketDonate = ? WHERE idticket = ?";
					$stmt = mysqli_prepare($conn, $sql) or die(mysqli_error($conn));
					mysqli_stmt_bind_param($stmt, "sddd", $ticketName, $ticketFlat, $ticketDonate, $idTick) or die(mysqli_error($conn));
					mysqli_stmt_execute($stmt) or die(mysqli_error($conn));
					mysqli_stmt_close($stmt);
				}

			//Media
				//Upload New
					if(!empty($_FILES['file'])){
						$hostID = $_SESSION['iduser'];
						$dir = ('images/'.$idCon);
						foreach ($_FILES['file']['name'] as $f => $name) {
    						$allowedExts = array("gif", "jpeg", "jpg", "png");
    						$temp = explode(".", $name);
    						$extension = end($temp);
    						//Set file type and size
    						if ((($_FILES['file']['type'][$f] == "image/gif")
    						|| ($_FILES['file']['type'][$f] == "image/jpeg")
    						|| ($_FILES['file']['type'][$f] == "image/jpg")
    						|| ($_FILES['file']['type'][$f] == "image/png"))
    						&& ($_FILES['file']['size'][$f] < 1073741824)
    						&& in_array($extension, $allowedExts))
    						{
    						  	if ($_FILES['file']['error'][$f] > 0){
    						  		echo "Return Code: " . $_FILES['file']['error'][$f] . "<br>";
    						  	} else {
    						    //if the file exists within the directory
    						    	if (file_exists($dir . $name)){
    						    		echo "<p>File Already Exists</p>";
    								} else {
    						    		$names = $_FILES['file']['tmp_name'][$f];
				
        				  				if (move_uploaded_file($names, "$dir/$name")){
        				    				$pathname = ($dir."/".$name);
											$mediaCode = 2;

        			    					$sqlFile = "INSERT INTO software.media (mediaLocation, uploadBy, mediaConcert, mediaSpecial) VALUES(?, ?, ?, ?)";

						        	    	$stmt = mysqli_prepare($conn, $sqlFile) or die(mysqli_error($conn));;
												mysqli_stmt_bind_param($stmt, "sddd", $pathname, $hostID, $idCon, $mediaCode) or die(mysqli_error($conn));;
												mysqli_stmt_execute($stmt) or die(mysqli_error($conn));;
												mysqli_stmt_close($stmt) or die(mysqli_error($conn));;
        			  					} else {
        			  		  				echo "<p>not moved</p>";
        			  					}
        							}
      							}
    						}
  						}
  					} else {
  						echo "<h3>No File</h3>";
  					}
  				//Edit The Media Code
					if(isset($_POST['mediaSpecial'])){
						$one = 1;
						$two = 2;
						$mCode = $_POST['mediaSpecial'];

						$sqlTwo = "UPDATE software.media SET mediaSpecial = $two WHERE mediaConcert = $idCon AND (mediaSpecial = $two OR mediaSpecial = $one)";
						mysqli_query($conn, $sqlTwo) or die(mysqli_error($conn));

						$sqlOne = "UPDATE software.media SET mediaSpecial = ? WHERE idmedia = ?";
						$stmt = mysqli_prepare($conn, $sqlOne) or die(mysqli_error($conn));
    					mysqli_stmt_bind_param($stmt, "dd", $one, $mCode) or die(mysqli_error($conn));
    					mysqli_stmt_execute($stmt) or die(mysqli_error($conn));
    					mysqli_stmt_close($stmt);

    					$sqlThree = "UPDATE software.concert SET mediaId = ? WHERE idconcert = ?";
    					$stmt = mysqli_prepare($conn, $sqlThree) or die(mysqli_error($conn));
    					mysqli_stmt_bind_param($stmt, "dd", $mCode, $idCon) or die(mysqli_error($conn));
						mysqli_stmt_execute($stmt) or die(mysqli_error($conn));
    					mysqli_stmt_close($stmt);    					
					}
				//New Video File
					if(!empty($_POST['mediaVideo'])){
						$mediaVideo = mysqli_real_escape_string($conn, $_POST['mediaVideo']) ? $_POST['mediaVideo']:null;
						$mediaVideo = trim($mediaVideo);
						$mediaVideo = htmlspecialchars($mediaVideo);

						$hostID = $_SESSION['iduser'];
						$mediaCode = 5;

						$sqlFile = "INSERT INTO software.media (mediaLocation, uploadBy, mediaConcert, mediaSpecial) VALUES(?, ?, ?, ?)";
						$stmt = mysqli_prepare($conn, $sqlFile) or die(mysqli_error($conn));;
						mysqli_stmt_bind_param($stmt, "sddd", $mediaVideo, $hostID, $idCon, $mediaCode) or die(mysqli_error($conn));;
						mysqli_stmt_execute($stmt) or die(mysqli_error($conn));;
						mysqli_stmt_close($stmt) or die(mysqli_error($conn));;
					}
				//Edit Video Files
					if(isset($_POST['vidLink'])){
						for($v = 0; $v < count($_POST['idVid']); $v++){
							$idVid = $_POST['idVid'][$v]; 
		
							$vidLink = $_POST['vidLink'][$v];
							$vidLink = trim($vidLink);
	
							$sqlThree = "UPDATE software.media SET mediaLocation = ? WHERE idmedia = ?";
    						$stmt = mysqli_prepare($conn, $sqlThree) or die(mysqli_error($conn));
    						mysqli_stmt_bind_param($stmt, "sd", $vidLink, $idVid) or die(mysqli_error($conn));
							mysqli_stmt_execute($stmt) or die(mysqli_error($conn));
    						mysqli_stmt_close($stmt) or die(mysqli_error($conn));;  
						}
					}
 
			//Lineups
				if(isset($_POST['lineupTitle'])){
					for($l = 0; $l < count($_POST['lineUpTitle']); $l++){
						$idLine = $_POST['idlineup'][$l]; 
						$lineUpTitle = $_POST['lineUpTitle'][$l];
						$lineUpTitle = trim($lineUpTitle);

						$lineupVenue = $_POST['lineupVenue'][$l];
						$lineupVenue = trim($lineupVenue);

						$lineupTime = $_POST['lineupTime'][$l];
						$lineupTime = trim($lineupTime);

						$sql = "UPDATE software.concert_lineup SET lineUpTitle = ?, lineupVenue = ?, lineupTime= ? WHERE idlineup = ?";
						$stmt = mysqli_prepare($conn, $sql) or die(mysqli_error($conn));
						mysqli_stmt_bind_param($stmt, "sssd", $lineUpTitle, $lineupVenue, $lineupTime, $idLine) or die(mysqli_error($conn));
						mysqli_stmt_execute($stmt) or die(mysqli_error($conn));
						mysqli_stmt_close($stmt);
					}
				}
					
			//Attending or Not
				if(isset($_POST['idAtt'])){
					for($a = 0; $a < count($_POST['idAtt']); $a++){
						if(isset($_POST['attending'][$a])){
							$attendCheck = $_POST['attending'][$a];
							$idAtt = $_POST['idAtt'][$a];

							$sqlAttend = "UPDATE software.buyers SET buyerAttended = ? WHERE idbuyers = ?";
							$stmt = mysqli_prepare($conn, $sqlAttend) or die(mysqli_error($conn));
							mysqli_stmt_bind_param($stmt, "dd", $attendCheck, $idAtt) or die(mysqli_error($conn));
							mysqli_stmt_execute($stmt) or die(mysqli_error($conn));
							mysqli_stmt_close($stmt);
						} else {
							$attendCheck = 0;
							$idAtt = $_POST['idAtt'][$a];

							$sqlAttend = "UPDATE software.buyers SET buyerAttended = ? WHERE idbuyers = ?";
							$stmt = mysqli_prepare($conn, $sqlAttend) or die(mysqli_error($conn));
							mysqli_stmt_bind_param($stmt, "dd", $attendCheck, $idAtt) or die(mysqli_error($conn));
							mysqli_stmt_execute($stmt) or die(mysqli_error($conn));
							mysqli_stmt_close($stmt);
						}
					}
				}
				
			mysqli_close($conn);
			echo "<h3 style='color:white;'>You have successfully updated your concert. <a href='updateConcert.php?conID=$idCon'>Back...</a></h3>";		
			}
		} else {
			include "admin_only.php";
		}

?>
