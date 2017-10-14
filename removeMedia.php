<?php
	include 'server/db_conn.php';
	session_start();

	if(isset($_SESSION['host_signed_in']) && $_SESSION['host_signed_in'] == true){
		if(isset($_GET['id'])){
			$idmedia = $_GET['id'];

			$sqlGet = "SELECT * FROM software.media WHERE idmedia = $idmedia";
			$rGet = mysqli_query($conn, $sqlGet) or die(mysqli_error($conn));
			$row = mysqli_fetch_assoc($rGet);
			$mLoc = $row['mediaLocation'];
			$mName = $row['mediaName'];
			$mCon = $row['mediaConcert'];
			$mCode = $row['mediaSpecial'];

			//Delete file from folder
				if($mCode == 2 || $mCode == 1){
					unlink($mLoc); // delete file
				}
			//Delete Media
				$sqlMedia = "DELETE FROM software.media WHERE idmedia = $idmedia";
				$rMedia = mysqli_query($conn, $sqlMedia) or die(mysqli_error($conn));

			mysqli_close($conn);

			echo "<p>You have successfully deleted the Media File</p>";
			echo "<a href='updateConcert.php?conID=$mCon'>Back</a>";
		}
	}	else {
		include "admin_only.php";
	}
?>