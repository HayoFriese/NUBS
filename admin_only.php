<?php
	echo 
	"<div id='adminOnly'>
		<h2>I'm sorry,</h2>
		<h3>but this page is only available to admins.</h3>
		<p>Redirecting you to the home page...</p>
	</div>";

	echo '<meta http-equiv="refresh" content="5;url=index.php">';
?>