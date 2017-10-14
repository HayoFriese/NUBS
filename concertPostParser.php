<?php
	include 'server/db_conn.php';
	session_start();
    require_once('functions.php');
    echo pageIni("NUBS | Posting...", "forum.css", "new_concert.js");

    echo '<div id="container">';
    echo '<div id="content">';

	//check if userLevel = 1 aka if the user is a host
	if(isset($_SESSION['host_signed_in']) && $_SESSION['host_signed_in'] == true){
            echo navigation("NUBS", "index.php", "#", "MY ACCOUNT", "forum.php", "BOARDS", "DONATE");
            echo "<div id='loginHead'>";
            echo "<p>Welcome, <span>".$_SESSION['userName']."</span>. Not you? <a href='signout.php'>Sign out</a></p>";
        echo "</div>";
		if(isset($_POST['title'])){
			$errors = [];
			$hostID = $_SESSION['iduser'];
			
			//Summary
				//Get Data
					$title = mysqli_real_escape_string($conn, isset($_POST['title'])) ? $_POST['title']: null;
					$conDate = mysqli_real_escape_string($conn, isset($_POST['conDate'])) ? $_POST['conDate']: null;
					$conTime = mysqli_real_escape_string($conn, isset($_POST['conTime'])) ? $_POST['conTime']: null;
				
					$addOne = mysqli_real_escape_string($conn, isset($_POST['addOne'])) ? $_POST['addOne']: null;
					$addTwo = mysqli_real_escape_string($conn, isset($_POST['addTwo'])) ? $_POST['addTwo']: null;
					$post = mysqli_real_escape_string($conn, isset($_POST['postCode'])) ? $_POST['postCode']: null;
					$state = mysqli_real_escape_string($conn, isset($_POST['state'])) ? $_POST['state']: null;
					$country = mysqli_real_escape_string($conn, isset($_POST['country'])) ? $_POST['country']: null;
					
					$desc = mysqli_real_escape_string($conn, isset($_POST['conDesc'])) ? $_POST['conDesc']: null;
					$ageR = mysqli_real_escape_string($conn, isset($_POST['ageRestrict'])) ? $_POST['ageRestrict']: null;

				//Trim Data
					$title = trim($title);
					$conDate = trim($conDate);
					$conTime = trim($conTime);
					$addOne = trim($addOne);
					$addTwo = trim($addTwo);
					$post = trim($post);
					$state = trim($state);
					$country = trim($country);
					$desc = trim($desc);
					$ageR = trim($ageR);

				//Special Chars
					$title = filter_var($title, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
    	        	$desc = htmlspecialchars($desc);

    	        	$addOne = filter_var($addOne, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
    	        	$addTwo = filter_var($addTwo, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
    	        	$post = filter_var($post, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
    	        	$state = filter_var($state, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
    	        	$country = filter_var($country, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		
    	        	$desc = filter_var($desc, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
    	        	$ageR = filter_var($ageR, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

				//Validation
    	        	//only numbers and letters
						if(!is_numeric($ageR)){
							$errors[] .= 'Age Restriction can only be a number.';
						}
		
					//Title
						//Check for string length
							elseif(strlen($title) > 255){
								$errors[] .= 'The Title is limited to 255 characters!';
							}
	
						//Check if empty title
							elseif(empty($title)){
								$errors[] .= 'Please Enter a Concert Title';
							}
		
						//Check if title exists
							elseif(!empty($title)){ 
                                $sqlExists = "SELECT * FROM software.concert";
                		    	$exists = mysqli_query($conn, $sqlExists) or die(mysqli_error($conn));
                                while($rowExists = mysqli_fetch_assoc($exists)){
                                    $conTitleDB = $rowExists['concertTitle'];

                                    if($conTitleDB == $title){
                                        $errors[] .= 'It looks like this title already exists! Please type in another title';
                                    }
                                }
                			}
		
    	        	//Concert Date
                		elseif(empty($conDate)){
                			$errors[] .= 'Please enter the event Date';
                		} elseif(strlen($conDate) > 10){
                			$errors[] .= 'The date does not seem to be in the correct format!';
                		}
		
    	        	//Concert Time
                		elseif(empty($conTime)){
                			$errors[] .= 'Please enter the event time';
                		} elseif(strlen($conTime) > 5){
                			$errors[] .= 'The time does not seem to be in the correct format!';
                		}
		
    	        	//Address
                		//If empty values
                			elseif(empty($addOne) || empty($post) || empty($state) || empty($country)){
                			    $errors[] .= 'Please Enter a complete address.';
                			}
            			//If Post code string is less than 6 or great than 7
                			elseif(strlen($post) < 6 || strlen($post > 7)){
                			    $errors[] .= 'Please enter a valid post code';
                			}	
		
    	        	//Concert Description
                		//If empty Description
                			elseif(empty($desc)){
                				$errors[] .= 'Please enter a concert Description';
                			}
		
    	        	//Age restrict
                		//if empty age restrict
                			elseif(empty($ageR)){
                				$errors[] .= 'Set an age restriction. If you want it available to everyone, set the restriction value to 0';
                			} elseif($ageR > 25){
                				$errors[] .= 'Set the age restriction up to 25 years old.';
                			}

               	//Upload Data
                	//check for Errrors
    	    			if(!empty($errors)){
    	    			  	echo '<ul>';
    	    			    foreach ($errors as $key => $value){
    	    			    	echo '<li>'.$value.'</li>';
    	    			    }
    	    			    echo '</ul>';
    	    		//Post Ad, store Ad ID into own variable, Post concert summary, and get concert id
    	    			} else { 
    	    				$revenue = 0;
                			$wallet = 0;
                			$rating = 0;

                			$conDate = date('Y-m-d', strtotime(str_replace('/', '-', $conDate)));
                			$conTime = date("H:i", strtotime($conTime));

    	    				$sqlAd = "INSERT INTO software.address(
                    	    addressOne, addressTwo, postCode, state, country)
                    	    VALUES(?, ?, ?, ?, ?)";
                	
                			$stmt = mysqli_prepare($conn, $sqlAd);
                			mysqli_stmt_bind_param($stmt, 'sssss', $addOne, $addTwo, $post, $state, $country);
                			mysqli_stmt_execute($stmt);
                			mysqli_stmt_close($stmt);
	
                			$location = mysqli_insert_id($conn);

                			$sqlSum = "INSERT INTO software.concert(
                			concertTitle, concertDesc, concertLocation, concertDate, concertTime, concertHost, concertWallet, concertRevenue, concertAgeRes)
							VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)";

							$stmt = mysqli_prepare($conn, $sqlSum) or die(mysqli_error($conn));
							mysqli_stmt_bind_param($stmt, "ssdssdddd", $title, $desc, $location, $conDate, $conTime, $hostID, $wallet, $revenue, $ageR) or die(mysqli_error($conn));
							mysqli_stmt_execute($stmt) or die(mysqli_error($conn));
							mysqli_stmt_close($stmt);
	
							$concertID = mysqli_insert_id($conn);
    	    			} 

			//Tickets
				$ticketMore = TRUE;
				$x = 1;

				while($ticketMore){
					if (isset($_POST['ticketName'.$x]) && isset($_POST['ticketFlat'.$x]) && isset($_POST['ticketDonate'.$x])){
					//Get Data
						$ticketName = mysqli_real_escape_string($conn, isset($_POST['ticketName'.$x])) ? $_POST['ticketName'.$x]: null;
						$ticketFlat = mysqli_real_escape_string($conn, isset($_POST['ticketFlat'.$x])) ? $_POST['ticketFlat'.$x]: null;
						$ticketDonate = mysqli_real_escape_string($conn, isset($_POST['ticketDonate'.$x])) ? $_POST['ticketDonate'.$x]: null;

					//Trim Data
						$ticketName = trim($ticketName);
						$ticketFlat = trim($ticketFlat);
						$ticketDonate = trim($ticketDonate);

					//Special Chars
						$ticketName = filter_var($ticketName, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
						$ticketFlat = filter_var($ticketFlat, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
						$ticketDonate = filter_var($ticketDonate, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

					//Validation
						if(empty($ticketName)){
							$errors .= 'Ticket name is not filled out on ticket #'.$x.'.';
						} elseif(!is_numeric($ticketFlat) && !is_numeric($ticketDonate)){
							$errors[] .= 'Ticket Prices and Percentages are numeric only.';
						} elseif($ticketDonate > 100){
							$errors[] .= 'Ticket Donation price is a bit excessive. Limit it to 100% max.';
						} else {
							if(!empty($errors)){
    	          				echo '<ul>';
    	            			foreach ($errors as $key => $value){
    	            				echo '<li>'.$value.'</li>';
    	            			}
    	            			echo '</ul>';
    	        			} else {
					//Post Data
								$sqlTicket = "INSERT INTO software.concert_ticket(ticketName, ticketFlat, ticketDonate, ticketConcert) VALUES(?, ?, ?, ?)";

								$stmt = mysqli_prepare($conn, $sqlTicket) or die(mysqli_error($conn));
								mysqli_stmt_bind_param($stmt, "sddd", $ticketName, $ticketFlat, $ticketDonate, $concertID);
								mysqli_stmt_execute($stmt);
								mysqli_stmt_close($stmt);							
							}
						} 
					}else {
						$ticketMore = FALSE;
					}
					$x++;
				}

			//Media Files
				//Create Subdirectory
     				//Set the subdirectory name
      					$subdir = $concertID;
      				//set the directory path name
      					$dir = ('images/'.$subdir);
      				//make the directory
      					mkdir($dir, 0777);
      			
      			//Cover Image Upload
   					foreach ($_FILES['promo']['name'] as $p => $pname){
   						$allowedExts = array("gif", "jpeg", "jpg", "png");
   						$temp = explode(".", $pname);
   						$extension = end($temp);
   						if ((($_FILES['promo']['type'][$p] == "image/gif")
    						|| ($_FILES['promo']['type'][$p] == "image/jpeg")
    						|| ($_FILES['promo']['type'][$p] == "image/jpg")
    						|| ($_FILES['promo']['type'][$p] == "image/png"))
    						&& ($_FILES['promo']['size'][$p] < 1073741824)
    						&& in_array($extension, $allowedExts))
    						{
    							if ($_FILES['promo']['error'][$p] > 0){
    						  		echo "Return Code: " . $_FILES['promo']['error'][$p] . 	"<br>";
    						  	} else {
    						  		if (file_exists($dir . $pname)){
    						    		echo "<p>File Already Exists</p>";
    								} else {
    						    		$pnames = $_FILES['promo']['tmp_name'][$p];
	
    						    		//move the files you upload into the newly 	generated folder.
        				  				if (move_uploaded_file($pnames, "$dir/$pname")){
        				    				$ppathname = ($dir."/".$pname);
        				    				
        				    				//give media file a code for special or not
	        				    				$mediaCode = 1; //1 = cover image, //2 for 	normal files, //3 for forum files.
											if(!empty($errors)){
    	          								echo '<ul>';
    	            							foreach ($errors as $key => $value){
    	            								echo '<li>'.$value.'</li>';
    	            							}
    	            							echo '</ul>';
    	        							} else {
        				    					$sqlPromo = "INSERT INTO software.media (mediaLocation, mediaName, uploadBy, mediaConcert, mediaSpecial) VALUES(?, ?, ?, ?, ?)";
				
								        	    $stmt = mysqli_prepare($conn, $sqlPromo);
												mysqli_stmt_bind_param($stmt, "ssddd", $ppathname, $promoName, $hostID, $concertID, $mediaCode);
												mysqli_stmt_execute($stmt);
												mysqli_stmt_close($stmt);

												$mediaID = mysqli_insert_id($conn);

												$sqlMediaID = "UPDATE software.concert SET mediaId = ? WHERE idconcert = $concertID";
        				  					
												$stmt = mysqli_prepare($conn, $sqlMediaID) or die(mysqli_error($conn));
												mysqli_stmt_bind_param($stmt, "d", $mediaID) or die(mysqli_error($conn));
												mysqli_stmt_execute($stmt);
												mysqli_stmt_close($stmt);
        				  					} 
        				  				} else {
        				    				echo "<p>not moved</p>";
        				  				}
        				  			}
      							}
      						} else {
      							echo "Invalid file";
    						}
      					} 

				//Multiple File Upload
      				if(!empty($_FILES['file'])){
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
			
        			  				//move the files you upload into the newly generated folder.
        			  				if (move_uploaded_file($names, "$dir/$name")){
        			    				$pathname = ($dir."/".$name);
										$mediaCode = 2;

        			    				$sqlFile = "INSERT INTO software.media (mediaLocation, uploadBy, mediaConcert, mediaSpecial) VALUES(?, ?, ?, ?)";

						        	    $stmt = mysqli_prepare($conn, $sqlFile);
											mysqli_stmt_bind_param($stmt, "sddd", $pathname, $hostID, $concertID, $mediaCode);
											mysqli_stmt_execute($stmt);
											mysqli_stmt_close($stmt);
        			  				} else {
        			    				echo "<p>not moved</p>";
        			  				}
        						}
      						}
    					}
  					}
                    }

                //Video Link upload
                    if(!empty($_POST['vidLink'])){
                        $mediaVideo = mysqli_real_escape_string($conn, $_POST['vidLink']) ? $_POST['vidLink']:null;
                        $mediaVideo = trim($mediaVideo);
                        $mediaVideo = htmlspecialchars($mediaVideo);

                        $mediaCode = 5;

                        $sqlFile = "INSERT INTO software.media (mediaLocation, uploadBy, mediaConcert, mediaSpecial) VALUES(?, ?, ?, ?)";
                        $stmt = mysqli_prepare($conn, $sqlFile);
                        mysqli_stmt_bind_param($stmt, "sddd", $mediaVideo, $hostID, $concertID, $mediaCode);
                        mysqli_stmt_execute($stmt);
                        mysqli_stmt_close($stmt);
                    }

            //Lineup
  				if(isset($_POST['lineupBool'])){
  					$lineupMore = TRUE;
					$y = 1;
		
					while($lineupMore){
						if (isset($_POST['lineupAct'.$y]) && isset($_POST['venue'.$y]) && isset($_POST['lineupTime'.$y])){
						//Get Data
							$lineupAct = mysqli_real_escape_string($conn, isset($_POST['lineupAct'.$y])) ? $_POST['lineupAct'.$y]: null;
							$venue = mysqli_real_escape_string($conn, isset($_POST['venue'.$y])) ? $_POST['venue'.$y]: null;
							$lineupTime = mysqli_real_escape_string($conn, isset($_POST['lineupTime'.$y])) ? $_POST['lineupTime'.$y]: null;

						//Trim Data
							$lineupAct = trim($lineupAct);
							$venue = trim($venue);
							$lineupTime = trim($lineupTime);

						//Special Chars
							$lineupAct = filter_var($lineupAct, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
							$venue = filter_var($venue, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

						//Validation
							if(empty($venue) && empty($lineupTime)){
								$errors[] .= 'Please type in proper values.';
							} else {
								if(!empty($errors)){
    	          					echo '<ul>';
    	            				foreach ($errors as $key => $value){
    	            					echo '<li>'.$value.'</li>';
    	            				}
    	            				echo '</ul>';
    	        				} else {
    	        					$conTime = date("H:i", strtotime($conTime));
    	        					
						//Post Data
									$sqlLineup = "INSERT INTO software.concert_lineup(lineupTitle, lineupVenue, lineupTime, lineupConcert) VALUES(?, ?, ?, ?)";

									$stmt = mysqli_prepare($conn, $sqlLineup) or die(mysqli_error($conn));
									mysqli_stmt_bind_param($stmt, "sssd", $lineupAct, $venue, $lineupTime, $concertID) or die(mysqli_error($conn));
									mysqli_stmt_execute($stmt) or die(mysqli_error($conn));
									mysqli_stmt_close($stmt);
								}
							}
						}else {
							$lineupMore = FALSE;
						}
						$y++;
					}
				}
    		
            echo "<p>You are now hosting a concert titled $title! <a href='concert.php?conID=$concertID'>View here</a> or <a href='concertListAdmin.php'>Return to Concert Overview.</a></p>";
            echo '</div>';
    echo '</div>';
    echo '</div>';
    
    mysqli_close($conn);
	} 
    echo footer();
}else {
		include "admin_only.php";
    }
?>