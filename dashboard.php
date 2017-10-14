<?php
include 'server/db_conn.php';
session_start();
require_once('functions.php');
echo pageIni("NUBS ", "forum.css", "new_concert.js");
    echo "<div id='container'>";
    echo "<div id='content' class='updateNavBack'>";
    if(isset($_SESSION['host_signed_in']) && $_SESSION['host_signed_in'] == true){
        echo navigation("NUBS", "index.php", "signout.php", "SIGN OUT", "forum.php", "BOARDS", "DONATE");
        echo "<div id='leftNav'>
                <ul id='leftNavCont'>
                    <li><a class='active' href='dashboard.php'>DASHBOARD</a></li>
                    <li><a href='concertListAdmin.php'>CONCERTS</a></li>
                    <li><a href='#reviews'>REVIEWS</a></li>
                    <li><a href='#account'>ACCOUNT DETAILS</a></li>
                    <li><a href='forum.php'>FORUMS</a></li>             
                    <li><a href='#settings'>SETTINGS</a></li>
                    <li><a href='#help'>HELP</a></li>
                </ul>
            </div>
            <div id='adminCont'>
            	<div id='box1'>
                	<div id='revenue'>
                    	<h2>REVENUE</h2>
                    </div><!--revenue-->
            		<div id='pageviews'>
                    	<h2>PAGE VIEWS</h2>
                    </div><!--Page Views-->
            		<div id='ticketssold'>
            			<h2>TICKETS SOLD</h2>
                    </div><!--Tickets Sold-->
                    <div id='starrating'>
                    	<h2>STAR RATING</h2>
                    </div><!--Star Rating-->
                    <div id='colorrating'>
                    	<h2>OVERALL RATING</h2>
                    </div><!--Color Rating-->
                </div><!--box1-->
            	<div id='box2'>
                	<div id='expenditures'>
                    	<h3>EXPENDITURES</h3>
                    </div><!--expenditures-->
                	<div id='todo'>
                    	<h3>TO-DO LIST</h3>
                    </div><!--todo-->
                </div><!--box2-->
                <div id='box3'>
                	<div id='statistics'>
                    	<h4>STATISTICS</h4>
                    </div>
                </div>
            </div>
        </div>   
    </div>
    </body>
</html>";
} else{
        echo navigation("NUBS", "#", "signin.php", "SIGN IN / SIGN UP", "forum.php", "BOARDS", "DONATE");
        include "admin_only.php";
    }
?>