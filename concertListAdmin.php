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
                    <li><a href='dashboard.php'>DASHBOARD</a></li>
                    <li><a class='active' href='concertListAdmin.php'>CONCERTS</a></li>
                    <li><a href='#reviews'>REVIEWS</a></li>
                    <li><a href='#account'>ACCOUNT DETAILS</a></li>
                    <li><a href='forum.php'>FORUMS</a></li>             
                    <li><a href='#settings'>SETTINGS</a></li>
                    <li><a href='#help'>HELP</a></li>
                </ul>
            </div>
            <div id='adminCont'>
                <div id='concertsbox'>
                    <div id='concertscontainer'>
                        <div id='concerts'>
                            <div id='concertslist'>
                                <span class='addnew'><a href='new_concert.php' class='addNewButton'>+</a></span>
                                <table class= 'concerttable'>
                                    <tr>
                                        <th>Delete</th>
                                        <th>Name</th>
                                        <th>Revenue</th>
                                        <th>Tickets Sold</th>
                                        <th>Date</th>
                                        <th>Avg. Rating</th>
                                    </tr>";
                                    $hostid = $_SESSION['iduser'];
                                    $sql = "SELECT * FROM software.concert WHERE concertHost = $hostid";
                                    $r = mysqli_query($conn, $sql) or die(mysqli_error($conn));
                                    while ($row = mysqli_fetch_assoc($r)){
                                        $conID = $row['idconcert'];
                                        $conTitle = $row['concertTitle'];
                                        $conRevenue = $row['concertRevenue'];
                                        $conDate = $row['concertDate'];
                                        //Get Ratings
                                        $sqlRate = "SELECT ratingNum FROM software.concert_rating WHERE ratingConcert = $conID";
                                        $rRate = mysqli_query($conn, $sqlRate) or die(mysqli_error($conn));
                                        $rateTotal = 0;
                                        while($rowRate = mysqli_fetch_assoc($rRate)){
                                            $rateTotal += $rowRate['ratingNum'];
                                        }
                                        $rateNum = mysqli_num_rows($rRate);
                                        if($rateNum !=0){
                                            $rateAvg = $rateTotal/$rateNum;
                                        } else {
                                            $rateAvg = 'No Ratings';
                                        }
                                        

                                        //Get Ticket Sell #
                                        $sqlBuy = "SELECT idbuyers FROM software.buyers WHERE buyerConcert = $conID";
                                        $rBuy = mysqli_query($conn, $sqlBuy) or die(mysqli_error($conn));
                                        $rNum = mysqli_num_rows($rBuy);

                                        echo "<tr>
                                        <td><a href='removeConcert.php?conID=$conID'>X</a></td>
                                        <td><a href='updateConcert.php?conID=$conID'>$conTitle</a></td>
                                        <td>$conRevenue</td>     
                                        <td>$rNum</td>
                                        <td>$conDate</td>
                                        <td>$rateAvg</td>
                                    </tr> ";
                                    }                                                             

                                echo "</table>
                            </div>                          
                        </div>
                    </div>
                </div>
            </div>
        </div>  
        </div> 
    </body>
    </html>";
    }
    else{
        echo navigation("NUBS", "#", "signin.php", "SIGN IN / SIGN UP", "forum.php", "BOARDS", "DONATE");
        include "admin_only.php";
    }
?>	
