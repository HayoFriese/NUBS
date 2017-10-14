<?php
	include "server/db_conn.php";
	session_start();

	if((isset($_SESSION['host_signed_in']) && $_SESSION['host_signed_in'] == true)
	|| (isset($_SESSION['user_signed_in']) && $_SESSION['user_signed_in'] == true)){
			
		if(isset($_POST['idticket'])){
			$iduser = $_SESSION['iduser'];

			$sqlMonUser = "SELECT userWallet FROM software.user WHERE iduser = $iduser";
			$rMonUser = mysqli_query($conn, $sqlMonUser) or die(mysqli_error($conn));
			$rowMonUser = mysqli_fetch_assoc($rMonUser);
			$userWallet = $rowMonUser['userWallet'];

			$idticket = mysqli_real_escape_string($conn, $_POST['idticket']) ? $_POST['idticket']:null;
			$ticketPrice = mysqli_real_escape_string($conn, $_POST['ticketPrice']) ? $_POST['ticketPrice']:null;
			$ticketCon = mysqli_real_escape_string($conn, $_POST['ticketConcert']) ? $_POST['ticketConcert']:null;

			if($userWallet < $ticketPrice){
				die("<p>Insufficient Funds</p>");
			} else{
				$norm = 0;
				//Check if buyer exists
					$sqlOver = "SELECT * FROM software.buyers WHERE buyerUser = $iduser AND buyerConcert = $ticketCon";
					$rOver = mysqli_query($conn, $sqlOver) or die(mysqli_error($conn));
					$rCnt = mysqli_num_rows($rOver);
					if($rCnt!=0){
						die("<p>You are already registered to this concert.</p>");
					} else {
						//add user to software.buyers list.
							$sqlBuyer = "INSERT INTO software.buyers(buyerUser, buyerConcert, buyerTicket, buyerAttended, buyerCost)
							VALUES(?, ?, ?, ?, ?)";
							$stmt = mysqli_prepare($conn, $sqlBuyer) or die(mysqli_error($conn));
							mysqli_stmt_bind_param($stmt, "ddddd", $iduser, $ticketCon, $idticket, $norm, $ticketPrice) or die(mysqli_error($conn));
							mysqli_stmt_execute($stmt) or die(mysqli_error($conn));
							mysqli_stmt_close($stmt);
		
						//remove money from user wallet
							$sqlWithdraw = "UPDATE software.user SET userWallet = userWallet - ? WHERE iduser = ?";
							$stmt = mysqli_prepare($conn, $sqlWithdraw) or die(mysqli_error($conn));
							mysqli_stmt_bind_param($stmt, "dd", $ticketPrice, $iduser) or die(mysqli_error($conn));
							mysqli_stmt_execute($stmt) or die(mysqli_error($conn));
							mysqli_stmt_close($stmt);
		
						//update the concert wallet by adding the ticket price to it
							$sqlDeposit = "UPDATE software.concert SET concertWallet = concertWallet + ?, concertRevenue = concertRevenue + ? WHERE idconcert = ?";
							$stmt = mysqli_prepare($conn, $sqlDeposit) or die(mysqli_error($conn));
							mysqli_stmt_bind_param($stmt, "ddd", $ticketPrice, $ticketPrice, $ticketCon) or die(mysqli_error($conn));
							mysqli_stmt_execute($stmt) or die(mysqli_error($conn));
							mysqli_stmt_close($stmt);
						
						//Echo Success
							echo "<p>Purchase complete! Hope to see you there! <a href='concert.php?conID=$ticketCon'>Back...</a></p>";
					}
			}
		}
		mysqli_close($conn);
	} else {
		echo "<p>You need to be signed in to buy a ticket. <a href='signin.php'>Sign in</a> or <a href='signup_user.php'>create an account.</a></p>";
	}
?>