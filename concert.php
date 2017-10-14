<?php
include 'server/db_conn.php';
session_start();
require_once('functions.php');
echo pageIni("NUBS", "forum.css", "new_concert.js");
    if(isset($_SESSION['host_signed_in']) && $_SESSION['host_signed_in'] == true){
      echo navigation("NUBS", "index.php", "dashboard.php", "MY ACCOUNT", "forum.php", "BOARDS", "DONATE");
      echo "<div id='loginHead'>";
        echo "<p>Welcome, <span><a href='dashboard.php'>".$_SESSION['userName']."</a></span>. Not you? <a href='signout.php'>Sign out</a></p>";
      echo "</div>";
      $userId = $_SESSION['iduser'];
    }
    elseif(isset($_SESSION['user_signed_in']) && $_SESSION['user_signed_in'] == true){
      echo navigation("NUBS", "index.php", "#", "MY ACCOUNT", "forum.php", "BOARDS", "DONATE");
      echo "<div id='loginHead'>";
        echo "<p>Welcome, <span>".$_SESSION['userName']."</span>. Not you? <a href='signout.php'>Sign out</a></p>";
      echo "</div>";
      $userId = $_SESSION['iduser'];
    } else{
      echo navigation("NUBS", "index.php", "signin.php", "SIGN IN / SIGN UP", "forum.php", "BOARDS", "DONATE");
    }

	if(isset($_GET['conID'])){
		$conID = $_GET['conID'];
		//Get Concert Info, Cover Image, Address, and Company
			$sqlGet = "SELECT idconcert, concertTitle, concertDesc, postCode, concertDate, concertTime, 
				userCompany, concertAgeRes, 
				idmedia, mediaLocation, mediaName, uploadBy, mediaConcert, mediaSpecial, 
				idaddress, addressOne, addressTwo, postCode, state, country 
				FROM software.concert 
				INNER JOIN software.address ON software.address.idaddress = software.concert.concertLocation
				INNER JOIN software.user ON software.user.iduser = software.concert.concertHost 
				INNER JOIN software.media ON software.media.idmedia = software.concert.mediaId 
				WHERE idconcert = $conID";
			$rGet = mysqli_query($conn, $sqlGet) or die(mysqli_error($conn));
			$rowSum = mysqli_fetch_assoc($rGet);
		//Get All Ticket Info
			$sqlTicket = "SELECT * FROM software.concert_ticket WHERE ticketConcert = $conID";
			$rTicket = mysqli_query($conn, $sqlTicket) or die(mysqli_error($conn));
		//Get All Line up Info
			$sqlLineup = "SELECT * FROM software.concert_lineup WHERE lineupConcert = $conID";
			$rLineup = mysqli_query($conn, $sqlLineup) or die(mysqli_error($conn));
		//Get All Media Files related to the concert
			$sqlMedia = "SELECT * FROM software.media WHERE mediaConcert= $conID AND (mediaSpecial = 2 OR mediaSpecial = 1)";
			$rMedia = mysqli_query($conn, $sqlMedia) or die(mysqli_error($conn));

		//Concert Variables
			$idCon = $rowSum['idconcert'];
			$conTitle = $rowSum['concertTitle'];
			$conDesc = $rowSum['concertDesc'];
			$conDate = $rowSum['concertDate'];
			$conTime = $rowSum['concertTime'];
			$conHost = $rowSum['userCompany'];
			$conAgeR = $rowSum['concertAgeRes'];
		//Cover Image Variables
			$idMedCov = $rowSum['idmedia'];
			$medLocCov = $rowSum['mediaLocation'];
			$medNameCov = $rowSum['mediaName'];
			$medUpByCov = $rowSum['uploadBy'];
			$medConCov = $rowSum['mediaConcert'];
			$medCodeCov = $rowSum['mediaSpecial'];
		//Address Variables
			$idAd = $rowSum['idaddress'];
			$adOne = $rowSum['addressOne'];
			$adTwo = $rowSum['addressTwo'];
			$adPost = $rowSum['postCode'];
			$adState = $rowSum['state'];
			$adCtr = $rowSum['country'];

		//Echo Main Concert Information
			echo "
					<div id='topContentCP'>
          				<img src='$medLocCov' alt='$medLocCov'>
          				<div id='imageTextCP'>
            				<p class='concertName'>$conTitle</p>
          				</div>
      				</div>";
      			if((isset($_SESSION['host_signed_in']) && $_SESSION['host_signed_in'] == true)
      				|| (isset($_SESSION['user_signed_in']) && $_SESSION['user_signed_in'] == true)){
      				$sqlReg = "SELECT * FROM software.buyers WHERE buyerUser = $userId AND buyerConcert = $conID";
      				$rReg = mysqli_query($conn, $sqlReg) or die(mysqli_error($conn));
      				$numReg = mysqli_num_rows($rReg);
      				if ($numReg != 0){
      					echo "<div id='registered'>
  						<div>
							<p><a href='buyCancel.php?conID=$idCon'>You are registered to this event. Click here to delete your registration.</a></p>
						</div>
					</div>";
      				}
      			}
      				
				echo "<div id='secondContentCP'>
        			<div id='description'>
          				<p class='concertDescription'>$conDesc</p>
        			</div>

        			<div id='information'>
        				<div id='location'>
        					<h1>Location</h1>
            				<h2>$adOne</h2>
            				<h2>$adTwo</h2>
            				<h2>$adPost, $adState</h2>
            				<h2>$adCtr</h2>
          				</div>    
          				<div id='date'>
          					<h1>Starts</h1>
            				<h2>Date: $conDate</h2> 
            				<h2>Time: $conTime</h2>
          				</div>
        			</div>
        			<div id='otherInfo'>
          				<div id='age'>
            				<h1>Age Restriction</h1>
            				<p>You must at least be $conAgeR to purchase a ticket.</p>
          				</div>
          				<div id='host'>
            				<h1>Hosted By</h1>
            				<p>$conHost</p>
            			</div>
          			</div>
          		<div id='tickets'>
          			<h1>Purchase Tickets</h1>";   
          			while ($rowTicket = mysqli_fetch_assoc($rTicket)){
						$idticket = $rowTicket['idticket'];
						$ticketName = $rowTicket['ticketName'];
						$ticketFlat = $rowTicket['ticketFlat'];
						$ticketDonate = $rowTicket['ticketDonate'];
						$ticketConcert = $rowTicket['ticketConcert'];
						$ticketSum = $ticketFlat+($ticketFlat*($ticketDonate/100));
						$ticketPrice = number_format($ticketSum, 2, '.', '');
						echo "<form method='post' action='buy.php'>
							<input type='hidden' name='idticket' value='$idticket'>
							<p>$ticketName</p>
							<p>Price: &#163;$ticketPrice</p>
							<input type='hidden' name='ticketPrice' value='$ticketPrice'>
							<input type='hidden' name='ticketConcert' value='$ticketConcert'>
							<div>
								<input type='submit' name='submit' class='buyTicketsButton' value='Buy'>
							</div>
						</form>";
					}  
            	echo "
          		</div>
          		<div id='media'>
          			<div id='mediaContent'>";
          				while ($rowMedia = mysqli_fetch_assoc($rMedia)){
							$idmedia = $rowMedia['idmedia'];
							$mediaName = $rowMedia['mediaName'];
							$mediaLocation = $rowMedia['mediaLocation'];
							$uploadBy = $rowMedia['uploadBy'];
							$mediaConcert = $rowMedia['mediaConcert'];
							$mediaSpecial = $rowMedia['mediaSpecial'];
							echo "<img src='$mediaLocation'>";
						}
					echo "</div>
				</div>
				<div id='video'>";
					$five = 5;
					$sqlVid = "SELECT * FROM software.media WHERE mediaConcert= $conID AND mediaSpecial = $five";
					$rVid = mysqli_query($conn, $sqlVid) or die(mysqli_error($conn));
					$vidNum = mysqli_num_rows($rVid);
					if($vidNum != 0){
						echo "<h1>Video Links</h1>";
						echo "<div>";
					}
					while ($rowVideo = mysqli_fetch_assoc($rVid)){
						$idmedia = $rowVideo['idmedia'];
						$mediaName = $rowVideo['mediaName'];
						$mediaLocation = $rowVideo['mediaLocation'];
						$uploadBy = $rowVideo['uploadBy'];
						$mediaConcert = $rowVideo['mediaConcert'];
						$mediaSpecial = $rowVideo['mediaSpecial'];
						echo "<p><a href='$mediaLocation'>$mediaLocation</a></p>";
					}
						echo "</div>
				</div>";
        		
        			if(mysqli_num_rows($rLineup) != 0){
        				echo "<div id='lineup'>
        					<h1>Line Up</h1>
        					<div>
        						<p>Act</p>
        						<p>Venue</p>
        						<p id='linetime'>Time</p>
        					</div>";
        				while ($rowLine = mysqli_fetch_assoc($rLineup)){
							$idlineup = $rowLine['idlineup'];
							$lineUpTitle = $rowLine['lineUpTitle'];
							$lineupVenue = $rowLine['lineupVenue'];
							$lineupTime = $rowLine['lineupTime'];
							$lineupConcert = $rowLine['lineupConcert'];
							echo "<div>";
								echo "<p>$lineUpTitle</p>";
								echo "<p>$lineupVenue</p>";
								echo "<p id='lineTime'>$lineupTime</p>";
							echo "</div>";
						}
						echo "</div>";
        			}
        			
        		

		//POST REVIEW
        	echo "<div id='reviews'>";
				echo "<h1>Leave a Review!</h1>";
				include "review.php";
			echo "</div>";
	    echo '</div>';
    echo '</div>';
    echo footer();
	}
?>