<?php
	include 'server/db_conn.php';

	echo '<h3>Sign Up</h3>';
    //if signed in
    if(isset($_POST['userName'])){
        $errors = [];
        
        //Get data 
            $uname = mysqli_real_escape_string($conn, isset($_POST['userName'])) ? $_POST['userName']: null;
            $upass = mysqli_real_escape_string($conn, isset($_POST['passWord'])) ? $_POST['passWord']: null;
            $upcheck = mysqli_real_escape_string($conn, isset($_POST['passCheck'])) ? $_POST['passCheck']: null;

            $fname = mysqli_real_escape_string($conn, isset($_POST['firstName'])) ? $_POST['firstName']: null;
            $lname = mysqli_real_escape_string($conn, isset($_POST['lastName'])) ? $_POST['lastName']: null;
            $company = mysqli_real_escape_string($conn, isset($_POST['company'])) ? $_POST['company']: null;
            $email = mysqli_real_escape_string($conn, isset($_POST['email'])) ? $_POST['email']: null;
            $gender = mysqli_real_escape_string($conn, isset($_POST['gender'])) ? $_POST['gender']: null;
    
            $day = mysqli_real_escape_string($conn, isset($_POST['day'])) ? $_POST['day']: null;
            $month = mysqli_real_escape_string($conn, isset($_POST['month'])) ? $_POST['month']: null;
            $year = mysqli_real_escape_string($conn, isset($_POST['year'])) ? $_POST['year']: null;
    
            $addOne = mysqli_real_escape_string($conn, isset($_POST['addOne'])) ? $_POST['addOne']: null;
            $addTwo = mysqli_real_escape_string($conn, isset($_POST['addTwo'])) ? $_POST['addTwo']: null;
            $post = mysqli_real_escape_string($conn, isset($_POST['postCode'])) ? $_POST['postCode']: null;
            $state = mysqli_real_escape_string($conn, isset($_POST['state'])) ? $_POST['state']: null;
            $country = mysqli_real_escape_string($conn, isset($_POST['country'])) ? $_POST['country']: null;
            
        //trim values to remove whitespace
            $uname = trim($uname);
            $upass = trim($upass);
            $upcheck = trim($upcheck);
            
            $fname = trim($fname);
            $lname = trim($lname);
            $fname = trim($fname);
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
            $company = filter_var($company, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
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
                    $errors[] .= 'Please enter a username';
                }
            //Check if username already exists, username mainly
                elseif(!empty($uname)){ 
                    $exists = mysqli_query($conn, "SELECT userName FROM software.user WHERE userName = '".$uname."'");
                    if (mysqli_num_rows($exists)){
                        $errors[] .= 'It looks like this username already exists! Please type in another username';
                    }
                }

        //Password Unique Validation
            //Empty
                elseif(empty($upass) || empty($upcheck)){
                    $errors[] .= 'Please enter a password';
                }
            //Check if both passwords match
                elseif($upass != $upcheck){
                    $errors[] .= 'Your Passwords did not match. Please try again';
                }

        //Company Unique Validation
            //Check if company already exists
                if(!empty($company)){ 
                    $exists = mysqli_query($conn, "SELECT userName FROM software.user WHERE userName = '".$company."'");
                    if (mysqli_num_rows($exists)){
                        $errors[] .= 'Your Company has already been registered!';
                    }
                }

        //Gender unique validation
            //Empty values
                elseif(empty($gender)){
                    $errors[] .= 'Please select a gender';
                }
            //Male or Female only
                elseif($gender != "Male" && $gender !="Female"){
                    $errors[] .= 'Please select either Male or Female';
                }

        //Date of Birth unique Validation
            //empty values
                elseif(empty($day) || empty($month) || empty($year)){
                    $errors[] .= 'Please enter a date.';
                }
            //1-31 only
                elseif($day > 31 || $day < 1){
                    $errors[] .= 'Please enter a valid date. Remember, dates never exceed 31 days.';
                }
            //No 30th of Febuary
                elseif($month = "Feb" && ($day > 29 || $day < 1)){
                    $errors[] .= 'February only goes from 1-28, 29 on a leap year.';
                }
            //31 vs 30
                elseif(($month = "Apr" || $month = "Jun" || $month = "Sep" || $month = "Nov")
                    && ($day > 30)){
                    $errors[] .= 'April, June, September, and November do not have 31 days.';
                }
            //Leap Year Condition
                elseif(($month = "Feb") && (($year % 4 != 0)&&($year % 100 != 0)) && ($day > 28)){
                    $errors[] .= '29th of February will not work with your selected year.';
                }
            //Year and day are numbers
                elseif(!is_numeric($day) || !is_numeric($year)){
                    $errors[] .= 'Day and Year must be numeric.';
                }

        //Adress unique validation
            //If empty values
                elseif(empty($addOne) || empty($post) || empty($state) || empty($country)){
                    $errors[] .= 'Please Enter a complete address.';
                }
            //If Post code string is less than 6 or great than 7
                elseif(strlen($post) < 6 || strlen($post > 7)){
                    $errors[] .= 'Please enter a valid post code';
                }

        //Verify for Host or User
            if (empty($company)){
                $level = 0;
            } else {
                $level = 1;
            }

        if(!empty($errors)){
        	echo '<ul>';
        	foreach ($errors as $key => $value) {
        		echo '<li>' .  $value . '</li>';
        	}
        	echo '</ul>';
        
        } else {
            //convert day month and year into single dob string with date rules
                $date = strtotime($day."-".$month."-".$year);
                $dob = date('y-m-d',$date);
            
            //finalize last variables
                $password = sha1($upass);
                $wallet = 1000;
                
            //insert address into address table
                $sqlAd = "INSERT INTO software.address(
                        addressOne, addressTwo, postCode, state, country)
                        VALUES(?, ?, ?, ?, ?)";
                
                $stmt = mysqli_prepare($conn, $sqlAd);
                mysqli_stmt_bind_param($stmt, 'sssss', $addOne, $addTwo, $post, $state, $country);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                
            //select newly inserted address ID and make variable
                $addID = mysqli_insert_id($conn);

            //Insert all data into users table
                $sql = "INSERT INTO software.user(
                    userName, userPass, userFirst, userLast, userEmail, userAge, userAddress, userLevel, userWallet, userCompany)
                VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "sssssdddds", $uname, $password, $fname, $lname, $email, $dob, $addID, $level, $wallet, $company);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
    
            mysqli_close($conn);

        //Echo a link to sign in with their details
            echo '<p>You have Successfully created an account!</p>
            <p>Click <a href="signin.php">here</a> to sign in!</p>';
	    }
    }
?>