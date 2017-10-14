<?php
//Upload New
	if(!empty($_FILES['file'])){
		echo "<h3>File Up</h3>";
		$hostID = $_SESSION['iduser'];
		$dir = ('images/'.$idCon);
		foreach ($_FILES['file']['name'] as $f => $name) {
			echo "<h3>File selected</h3>";
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
		        	    	$stmt = mysqli_prepare($conn, $sqlFile);
								mysqli_stmt_bind_param($stmt, "sddd", $pathname, $hostID, $idCon, $mediaCode);
								mysqli_stmt_execute($stmt);
								mysqli_stmt_close($stmt);
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
?>