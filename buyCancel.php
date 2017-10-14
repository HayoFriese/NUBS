<?php
	include 'server/db_conn.php';
	session_start();

	if($_GET['conID']){
		if((isset($_SESSION['host_signed_in']) && $_SESSION['host_signed_in'] == true)
		|| (isset($_SESSION['user_signed_in']) && $_SESSION['user_signed_in'] == true)){

			$user = $_SESSION['iduser'];
			$con = $_GET['conID'];
			$finalcost = 0;
	
			$sqlGet = "SELECT * FROM software.buyers WHERE buyerUser = $user AND buyerConcert = $con";
			$rGet = mysqli_query($conn, $sqlGet) or die(mysqli_error($conn));
			$rCnt = mysqli_num_rows($rGet);
			
			while($row = mysqli_fetch_assoc($rGet)){
				$buyerCost = $row['buyerCost'];
				$idtick = $row['buyerTicket'];
			}
			if ($rCnt = 0 || !$idtick){
				die("<p>You Are not attending this concert yet! <a href='concert.php?conID=$con'>Back...</a></p>");
			} else{
				
				//Remove money from concert for each ticket bought
					$sqlCon = "SELECT concertWallet, concertRevenue FROM software.concert WHERE idconcert = $con";
					$rCon = mysqli_query($conn, $sqlCon) or die(mysqli_error($conn));
					$rowCon = mysqli_fetch_assoc($rCon);

					$conWallet = $rowCon['concertWallet'];
					$conRevenue = $rowCon['concertRevenue'];

					$finalConWallet = $conWallet - $buyerCost;
					$finalConRevenue = $conRevenue - $buyerCost;

					$sqlConMinus = "UPDATE software.concert SET concertWallet = ?, concertRevenue = ? WHERE idconcert = ?";
					$stmt = mysqli_prepare($conn, $sqlConMinus) or die(mysqli_error($conn));
					mysqli_stmt_bind_param($stmt, "ddd", $finalConWallet, $finalConRevenue, $con) or die(mysqli_error($conn));
					mysqli_stmt_execute($stmt) or die(mysqli_error($conn));
					mysqli_stmt_close($stmt);
				//add money to user's wallet
					$sqlUse = "SELECT userWallet FROM software.user WHERE iduser = $user";
					$rUse = mysqli_query($conn, $sqlUse) or die(mysqli_error($conn));
					$rowUse = mysqli_fetch_assoc($rUse);

					$useWallet = $rowUse['userWallet'];

					$finalUseWallet = $useWallet + $buyerCost;

					$sqlUserPlus = "UPDATE software.user SET userWallet = userWallet + ? WHERE iduser = ?";
					$stmt = mysqli_prepare($conn, $sqlUserPlus) or die(mysqli_error($conn));
					mysqli_stmt_bind_param($stmt, "dd", $finalUseWallet, $user) or die(mysqli_error($conn));
					mysqli_stmt_execute($stmt) or die(mysqli_error($conn));
					mysqli_stmt_close($stmt);
				//remove user from buyer's list
					$sqlDel = "DELETE FROM software.buyers WHERE buyerUser = $user AND buyerConcert = $con"; 
					$rDel = mysqli_query($conn, $sqlDel) or die(mysqli_error($conn));

				//Success
					echo "<p>You are no longer attending this concert. <a href='concert.php?conID=$con'>Back...</a>";
			}
		}
	}

?>