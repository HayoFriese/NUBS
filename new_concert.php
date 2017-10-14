<?php
	include 'server/db_conn.php';
    session_start();
    require_once('functions.php');
    echo pageIni("New Event | NUBS", "forum.css", "new_concert.js");

    echo '<div id="container">';
        echo '<div id="content">';
        if((!isset($_SESSION['host_signed_in']) && $_SESSION['host_signed_in'] != true)){
            echo navigation("NUBS", "index.php", "signin.php", "SIGN IN / SIGN UP", "forum.php", "BOARDS", "DONATE");
            die('admin_only.php');
        } else {
            echo navigation("NUBS", "index.php", "#", "MY ACCOUNT", "forum.php", "BOARDS", "DONATE");
		echo '<div id="newConCont">
                    <div id="progressCon"></div>
                    <div id="signIn">

        <form id="newConcert" onsubmit="return false" enctype="multipart/form-data">
				<h2 id="status">Event Summary</h2>
				<div id="conSum">
                    <input type="text" id="title" name="title" placeholder="Event Title...">
                    <div id="conRight">
                        <div id="conAddress">
                            <h3>Location</h3>
                            <input type="text" id="addOne" name="addOne" placeholder="Address Line One">
                            <input type="text" id="addTwo" name="addTwo" placeholder="Address Line Two">
                            <input type="text" id="postCode" name="postCode" maxlength="7" placeholder="ABC DEF">
                            <input type="text" id="state" name="state" placeholder="State/Province...">
                            <input type="text" id="country" name="country" placeholder="Country...">
                        </div>
                        <div id="conDateTime">
                            <h3>Date & Time</h3>
                            <input type="date" id="conDate" name="conDate">
                            <input type="time" id="conTime" name="conTime">
                        </div>
                    </div>
                    <div id="conLeft">
                        <h3>Description</h3>
                        <textarea id="conDesc" name="conDesc" placeholder="Event Description..."></textarea>
                        <input type="number" id="ageRestrict" name="ageRestrict" min="0" max="25" step="1" placeholder="Age Restriction...">
                        <div id="lineupBoolean">Add Line-Up: <input type="checkbox" id="lineupBool" name="lineupBool"></div>
                    </div>
                    <div class="conNextButton">
                        <button onclick="processSummary()">Next</button>
                    </div>
                </div>

                <div id="conTicket">
                    <div id="addForm">
                	   <input type="button" id="addTicket()" onclick="addTicket()" value="+">
                	</div>
                    <div id="ticketCont">
                        <div id="ticketList">
                            <div>
                                <input type="text" id="ticketName" name="ticketName1"  data-group="ticket" placeholder="Ticket Name...">
                                <input type="number" step="0.01" min="0" id="ticketFlat" name="ticketFlat1" data-group="ticket" placeholder="Flat Price...">
                                <input type="number" step="1" min="0" max="100" id="ticketDonate" name="ticketDonate1"  data-group="ticket" placeholder="% Tax for Donation...">
                            </div>
                        </div>
                    </div>
                    <div class="conNextButton">
                    	<button onclick="processTicket()">Next</button>
                    </div>
                </div>

                <div id="conMedia">
                    <div id="mediaCont">
                        <div id="confiles"><p>Upload A Cover Image</p><input type="file" name="promo[]" id="promo"></div>
                        <div id="confiles"><p>Additional Media: </p><input type="file" name="file[]" id="file" multiple="multiple"></div>
                        <input type="text" name="vidLink" id="vidLink" placeholder="Video Link...">
                    </div>
                    <div class="conNextButton">
                        <button onclick="processMedia()">Next</button>	
                    </div>
                </div>

                <div id="conLineup">
                    <div id="addForm">
                	   <input type="button" id="addLineup()" onclick="addLineup()" value="+">
                	</div>
                    <div id="lineupCont">
                        <div id="lineupList">
                            <div>
                                <input type="text" id="lineupAct" name="lineupAct1" data-group="lineup" placeholder="Act...">
                                <input type="text" id="venue" name="venue1" data-group="lineup" placeholder="Venue...">
                                <input type="time" id="lineupTime" name="lineupTime1" data-group="lineup" placeholder="Time...">
                            </div>
                        </div>
                    </div>
                    <div class="conNextButton">
                    	<button onclick="processLineup()">Next</button>
                    </div>
                </div>

                <div id="conConfirm">
                    <div id="confirmCont">
                        <div id="confirmParts">
                            <h3>Summary</h3>
                            <p>Title: <span id="display_title"></span></p>
                            <p>Date & Time: <span id="display_conDate"></span>, <span id="display_conTime"></span></p>
                            <p>Description: <span id="display_desc"></span></p>
                            <p>Age Restriction: <span id="display_ageR"></span></p>
                            <p>Line Up? <span id="display_lineupBool"></span></p>
                            <h4>Location</h4>
                               <p>Address Line 1: <span id="display_addOne"></span></p>
                               <p>Address Line 2: <span id="display_addTwo"></span></p>
                               <p>Post Code: <span id="display_post"></span></p>
                               <p>State: <span id="display_state"></span></p>
                               <p>Country: <span id="display_country"></span></p>
                    
                        <div id="confirmParts">
                            <h3>Tickets</h3>
                            <div id="display_tickets">
                            </div>
                        </div>

                        <div id="confirmParts">
                            <h3>Media Files</h3>
                            <p>Cover File: <span id="display_mediaCover"></span></p>
                            <p>Video URL: <span id="display_mediaVid"></span></p>
                        </div>
                        
                        <div id="confirmParts">
                            <div id="display_lineup">
                                <h3>Lineup</h3>
                            </div>
                        </div>
                    </div>
                    <div class="conNextButton">
                    	<button onclick="postConcert()">Submit</button>
                    </div>
                </div>
			</form>
		</body>
		</html>
	';}


?>