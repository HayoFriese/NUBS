<?php
	include 'server/db_conn.php';
	session_start();
	require_once('functions.php');

	echo pageIni("NUBS | Sign In", "forum.css", "new_concert.js");
	echo '<div id="content">';
	echo navigation("NUBS", "index.php", "signin.php", "SIGN IN / SIGN UP", "forum.php", "BOARDS", "DONATE");

	if((isset($_SESSION['host_signed_in']) && $_SESSION['host_signed_in'] == true)
	|| (isset($_SESSION['user_signed_in']) && $_SESSION['user_signed_in'] == true)){
		echo '<div id="signIn">
            		<p>You already signed in, sign out <a href="signout.php">here</a></p>
        		</div>';
	} else {		
		if($_SERVER['REQUEST_METHOD'] != 'POST'){
			echo '<div id="signCont">
      				<div id="signIn">
            			<h1 class="signIn">Sign in</h1>
            			<form class="login-form" method="post" action="">
              				<input type="text" placeholder="Username..." name="userName">
              				<input type="password" placeholder="Password..." name="passWord">
              				<input type="submit" value="Sign In">
              				<p id="signupLink">No account? Sign up <a href="signup_user.php">here</a></p>
            			</form>
        			</div>
        		</div>';
		} else {
			$errors = [];

			if(!isset($_POST['userName'])){
				$errors[] = 'The username field must not be empty';
			}
			if(!isset($_POST['passWord'])){
				$errors[] = 'The password field must not be empty';
			}

			if(!empty($errors)){
				echo '<ul>';
				foreach($errors as $key => $value){
					echo '<li>' . $value . '</li>';
				}
				echo '</ul>';
			}
			else{
				$uName = mysqli_real_escape_string($conn, $_POST['userName']);
				$sql = "SELECT iduser, userName, userLevel FROM software.user
						WHERE userName = '$uName'
						AND userPass = '" . sha1($_POST['passWord']) . "'";

				$r = mysqli_query($conn, $sql) or die(mysqli_error($conn));

				if(mysqli_num_rows($r) == 0){
					echo 'Wrong username and password combo. ';
					echo "<a href='signin.php'>Back...</a>";
				} else {
					while($row = mysqli_fetch_assoc($r)){
						$_SESSION['iduser']	= $row['iduser'];
						$_SESSION['userName']	= $row['userName'];
						$_SESSION['userLevel']	= $row['userLevel'];
					}
					if($_SESSION['userLevel'] == 1){
						$_SESSION['host_signed_in'] = true;
						echo 'Welcome, ' . $_SESSION['userName'] . '.';
						echo '<meta http-equiv="refresh" content="2;url=dashboard.php">';
					} else {
						$_SESSION['user_signed_in'] = true; 
						echo 'Welcome, ' . $_SESSION['userName'] . '.';
						echo '<meta http-equiv="refresh" content="2;url=index.php">';
					}
				}
			}
		}
	}
	echo '</div>';
	echo footer();
?>