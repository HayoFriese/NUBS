<?php
	include 'server/db_conn.php';
	if(isset($_POST['userName'])){
		echo "<p>".$_POST['userName']."</p>";
		echo "<p>".$_POST['passWord']."</p>";
		echo "<p>".$_POST['firstName']."</p>";
		echo "<p>".$_POST['lastName']."</p>";
		echo "<p>".$_POST['email']."</p>";
		echo "<p>".$_POST['gender']."</p>";
		echo "<p>".$_POST['day']."</p>";
		echo "<p>".$_POST['month']."</p>";
		echo "<p>".$_POST['year']."</p>";
		echo "<p>".$_POST['addOne']."</p>";
		echo "<p>".$_POST['addTwo']."</p>";
		echo "<p>".$_POST['postCode']."</p>";
		echo "<p>".$_POST['state']."</p>";
		echo "<p>".$_POST['country']."</p>";

            echo "<p>Starting Process</p>";
            $errors = array();
            //Get data 
                $uname = mysqli_real_escape_string($conn, filter_has_var(INPUT_POST, 'userName')) ? $_GET['userName']: null;
                $upass = mysqli_real_escape_string($conn, filter_has_var(INPUT_POST, 'passWord')) ? $_GET['passWord']: null;
                $upcheck = mysqli_real_escape_string($conn, filter_has_var(INPUT_POST, 'passCheck')) ? $_GET['passCheck']: null;
    
                $fname = mysqli_real_escape_string($conn, filter_has_var(INPUT_POST, 'firstName')) ? $_GET['firstName']: null;
                $lname = mysqli_real_escape_string($conn, filter_has_var(INPUT_POST, 'lastName')) ? $_GET['lastName']: null;
                $email = mysqli_real_escape_string($conn, filter_has_var(INPUT_POST, 'email')) ? $_GET['email']: null;
                $gender = mysqli_real_escape_string($conn, filter_has_var(INPUT_POST, 'gender')) ? $_GET['gender']: null;
        
                $day = mysqli_real_escape_string($conn, filter_has_var(INPUT_POST, 'day')) ? $_GET['day']: null;
                $month = mysqli_real_escape_string($conn, filter_has_var(INPUT_POST, 'month')) ? $_GET['month']: null;
                $year = mysqli_real_escape_string($conn, filter_has_var(INPUT_POST, 'year')) ? $_GET['year']: null;
        
                $addOne = mysqli_real_escape_string($conn, filter_has_var(INPUT_POST, "addOne")) ? $_GET['addOne']: null;
                $addTwo = mysqli_real_escape_string($conn, filter_has_var(INPUT_POST, "addTwo")) ? $_GET['addTwo']: null;
                $post = mysqli_real_escape_string($conn, filter_has_var(INPUT_POST, "postCode")) ? $_GET['postCode']: null;
                $state = mysqli_real_escape_string($conn, filter_has_var(INPUT_POST, "state")) ? $_GET['state']: null;
                $country = mysqli_real_escape_string($conn, filter_has_var(INPUT_POST, "country")) ? $_GET['country']: null;
                
                echo "<p>$uname</p>";
                echo "<p>$upass</p>";
                echo "<p>$upcheck</p>";
                echo "<p>$fname</p>";
                echo "<p>$lname</p>";
                echo "<p>$email</p>";
                echo "<p>$gender</p>";
                echo "<p>$day</p>";
                echo "<p>$month</p>";
                echo "<p>$year</p>";
                echo "<p>$addOne</p>";
                echo "<p>$addTwo</p>";
                echo "<p>$post</p>";
                echo "<p>$state</p>";
                echo "<p>$country</p>";
            //trim values to remove whitespace
                $uname = trim($uname);
                $upass = trim($upass);
                $upcheck = trim($upcheck);
                
                $fname = trim($fname);
                $lname = trim($lname);
                $email = trim($email);
                $gender = trim($gender);
                
                $day = trim($day);
                $month = trim($month);
                $year = trim($year);
                
                $addOne = trim($addOne);
                $addTwo = trim($addTwo);
                $post = trim($post);
                $state = trim($state);
                $country = trim($country);
    
            //convert special characters
            $uname = filter_var($uname, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
            $upass = filter_var($upass, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
            $upcheck = filter_var($upcheck, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
            
            $fname = filter_var($fname, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
            $lname = filter_var($lname, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
            $email = filter_var($email, FILTER_SANITIZE_EMAIL);

            $year = filter_var($year, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
            
            $addOne = filter_var($addOne, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
            $addTwo = filter_var($addTwo, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
            $post = filter_var($post, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
            $state = filter_var($state, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
            $country = filter_var($country, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
    
            //username unique validation
                //empty username
                    if(empty($uname)){
                        $errors[] = 'Please enter a username';
                    }
                //Check if username already exists, username mainly
                    elseif(!empty($uname)){ 
                        $exists = mysqli_query($conn, "SELECT userName FROM software.user WHERE userName = '".$uname."'");
                        if (mysqli_num_rows($exists)){
                            $errors[] = 'It looks like this username already exists! Please type in another username';
                        }
                    }
    
            //Password Unique Validation
            //Empty
                elseif(empty($upass) || empty($upcheck)){
                    $errors[] = 'Please enter a password';
                }
            //Check if both passwords match
                elseif($upass != $upcheck){
                    $errors[] = 'Your Passwords did not match. Please try again';
                }
    
            //Gender unique validation
            //Empty values
                elseif(empty($gender)){
                    $errors[] = 'Please select a gender';
                }
            //Male or Female only
                elseif($gender != "Male" || $gender !="Female"){
                    $errors[] = 'Please select either Male or Female';
                }
    
            //Date of Birth unique Validation
            //empty values
                elseif(empty($day) || empty($month) || empty($year)){
                    $errors[] = 'Please enter a date';
                }
            //1-31 only
                elseif($day > 31 || $day < 1){
                    $errors[] = 'Please enter a valid date';
                }
            //No 30th of Febuary
                elseif($month = "Feb" && ($day > 29 || $day < 1)){
                    $errors[] = 'Please enter a valid date';
                }
            //31 vs 30
                elseif(($month = "Apr" || $month = "Jun" || $month = "Sep" || $month = "Nov")
                    && ($day > 30)){
                    $errors[] = 'Please enter a valid date';
                }
            //Leap Year Condition
                elseif(($month = "Feb") && (($year % 4 != 0)&&($year % 100 != 0)) && ($day > 28)){
                    $errors[] = 'Please enter a valid date';
                }
            //Year and day are numbers
                elseif(is_numeric($day) || is_numeric($year)){
                    $errors[] = 'Please enter a valid date';
                }
    
            //Adress unique validation
            //If empty values
                elseif(empty($addOne) || empty($post) || empty($state) || empty($country)){
                    $errors[] = 'Please Enter a complete address.';
                }
            //If Post code string is less than 6 or great than 7
                elseif(strlen($post) < 6 || strlen($post > 7)){
                    $errors[] = 'Please enter a valid post code';
                }
    
            //Can only contain letters and digits
    		elseif(!ctype_alnum($uname) || !ctype_alnum($upass) || !ctype_alnum($fname) 
                || !ctype_alnum($lname) || !ctype_alnum($gender) || !ctype_alnum($day) 
                || !ctype_alnum($month) || !ctype_alnum($year) || !ctype_alnum($addOne) 
                || !ctype_alnum($addTwo) || !ctype_alnum($post) || !ctype_alnum($state) 
                || !ctype_alnum($country)){
    			$errors[] = 'Only E-mail can contain different characters. Rest of the fields
                            can only have letters and digits.';
    		}
    
    
    	   if(!empty($errors)){
    	   	echo '<ul>';
    	   	foreach ($errors as $key => $value) {
    	   		echo '<li>' .  $value . '</li>';
    	   	}
    	   	echo '</ul>';
    	   } else {
                //convert day month and year into single dob string with date rules
    
                //finalize last variables
                    $password = sha1($upass);
                //insert address into address table
                    $sqlAd = "INSERT INTO software.address(
                            addressOne, addressTwo, postCode, state, country)
                            VALUES(?, ?, ?, ?, ?)";
                    $stmt = mysqli_prepare($conn, $sqlAd);
                    mysqli_stmt_bind_param($stmt, 'sssss', $adOne, $adTwo, $post, $state, $country);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_close($stmt);
    
                //select newly inserted address ID and make variable
                    $addID = "SELECT SCOPE_IDENTITY()";
    
    	   	$sql = "INSERT INTO software.user(
                        userName, userPass, userFirst, userLast, userEmail, userAge, userAddress, userLevel, userWallet)
    	   			VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
                $stmt = mysqli_prepare($conn, $sql);
    
                mysqli_stmt_bind_param($stmt, "sssssdddd", $uname, $password, $fname, $lname, $email, $dob, $addID, $level, $wallet);
		  	mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
    
                mysqli_close($conn);
    
		  	echo 'Successfully Registered.';
		    }
        }
?>