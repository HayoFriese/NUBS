<?php
	include 'server/db_conn.php';
	session_start();

	if(isset($_SESSION['host_signed_in']) && $_SESSION['host_signed_in'] == true){
		if(isset($_GET['conID'])){
			$conID = $_GET['conID'];
			//Get Address
				$sqlGetAd = "SELECT concertLocation, concertDate, concertTime FROM software.concert WHERE idconcert = $conID";
				$rGetAd = mysqli_query($conn, $sqlGetAd) or die(mysqli_error($conn));
				while ($row = mysqli_fetch_assoc($rGetAd)){
					$getAd = $row['concertLocation'];
					$getDate = $row['concertDate'];
					$getTime = $row['concertTime'];

					$date = date('m/d/Y h:i:s a', time());
					$conDate = date('Y-m-d H:i:s', strtotime("$getDate $getTime"));
				}

			//Buyers
				if($date <= $conDate){
					$sqlGetBuy = "SELECT * FROM software.buyers WHERE buyerConcert = $conID";
					$rGetBuy = mysqli_query($conn, $sqlGetBuy) or die(mysqli_error($conn));
					while ($rowBuy = mysqli_fetch_assoc($rGetBuy)){
						$idbuy = $rowBuy['idbuyers'];
						$buyUser = $rowBuy['buyerUser'];
						$buyCon = $rowBuy['buyerConcert'];
						$buyTick = $rowBuy['buyerTicket'];
						$buyCost = $rowBuy['buyerCost'];

						//Remove money from concert for each ticket bought
							$sqlConMinus = "UPDATE software.concert SET concertWallet = concertWallet - ?, concertRevenue = concertRevenue - ? WHERE idconcert = ?";
							$stmt = mysqli_prepare($conn, $sqlConMinus) or die(mysqli_error($conn));
							mysqli_stmt_bind_param($stmt, "ddd", $buyTick, $buyTick, $buyCon) or die(mysqli_error($conn));
							mysqli_stmt_execute($stmt) or die(mysqli_error($conn));
							mysqli_stmt_close($stmt);
						//add money to user's wallet
							$sqlUserPlus = "UPDATE software.user SET userWallet = userWallet + ? WHERE iduser = ?";
							$stmt = mysqli_prepare($conn, $sqlUserPlus) or die(mysqli_error($conn));
							mysqli_stmt_bind_param($stmt, "dd", $buyCost, $buyUser) or die(mysqli_error($conn));
							mysqli_stmt_execute($stmt) or die(mysqli_error($conn));
							mysqli_stmt_close($stmt);
						//remove buyers list
							$sqlMinusBuyers = "DELETE FROM software.buyers WHERE idbuyers = $idbuy";
							$rMinusBuyers = mysqli_query($conn, $sqlMinusBuyers) or die(mysqli_error($conn));
					}
					//Delete ratings
					//TicketDelete
						$sqlTicket = "DELETE FROM software.concert_ticket WHERE ticketConcert = $conID";
						$rTicket = mysqli_query($conn, $sqlTicket) or die(mysqli_error($conn));
		
					//Lineup Delete
						$sqllineup = "DELETE FROM software.concert_lineup WHERE lineupConcert = $conID";
						$rlineup = mysqli_query($conn, $sqllineup) or die(mysqli_error($conn));
		
					//Media Delete
						//Delete folder with name conID
							$files = glob('./images/'.$conID.'/*'); // get all file names
							foreach($files as $file){ // iterate files
					 			if(is_file($file))
					   		 	unlink($file); // delete file
							}
							rmdir("./images/".$conID);
		
						//Delete Media
							$sqlMedia = "DELETE FROM software.media WHERE mediaConcert = $conID";
							$rMedia = mysqli_query($conn, $sqlMedia) or die(mysqli_error($conn));
		
					//Concert Delete
						$sqlConcert = "DELETE FROM software.concert WHERE idconcert = $conID";
						$rConcert = mysqli_query($conn, $sqlConcert) or die(mysqli_error($conn));
		
					//Address
						$sqlAddress = "DELETE FROM software.address WHERE idaddress = $getAd";
						$rAddress = mysqli_query($conn, $sqlAddress) or die(mysqli_error($conn));
				} else {
					//Delete Buy
						$sqlMinusBuyers = "DELETE FROM software.buyers WHERE buyerConcert = $conID";
						$rMinusBuyers = mysqli_query($conn, $sqllineup) or die(mysqli_error($conn));
					
					//TicketDelete
						$sqlTicket = "DELETE FROM software.concert_ticket WHERE ticketConcert = $conID";
						$rTicket = mysqli_query($conn, $sqlTicket) or die(mysqli_error($conn));
		
					//Lineup Delete
						$sqllineup = "DELETE FROM software.concert_lineup WHERE lineupConcert = $conID";
						$rlineup = mysqli_query($conn, $sqllineup) or die(mysqli_error($conn));
		
					//Media Delete
						//Delete folder with name conID
							$files = glob('./images/'.$conID.'/*'); // get all file names
							foreach($files as $file){ // iterate files
					 			if(is_file($file))
					   		 	unlink($file); // delete file
							}
							rmdir("./images/".$conID);
		
						//Delete Media
							$sqlMedia = "DELETE FROM software.media WHERE mediaConcert = $conID";
							$rMedia = mysqli_query($conn, $sqlMedia) or die(mysqli_error($conn));
		
					//Concert Delete
						$sqlConcert = "DELETE FROM software.concert WHERE idconcert = $conID";
						$rConcert = mysqli_query($conn, $sqlConcert) or die(mysqli_error($conn));
		
					//Address
						$sqlAddress = "DELETE FROM software.address WHERE idaddress = $getAd";
						$rAddress = mysqli_query($conn, $sqlAddress) or die(mysqli_error($conn));
				}

			
			mysqli_close($conn);

			echo "<p>You have successfully deleted the concert</p>";
			echo "<a href='concertListAdmin.php'>Back</a>";
		}
	}else {
		include "admin_only.php";
	}
?>