<?php
    include 'server/db_conn.php';
    session_start();
    require_once('functions.php');

    echo pageIni("NUBS | Sign Up Host", "forum.css", "signup.js");
    echo '<div id="content">';
    echo navigation("NUBS", "index.php", "signin.php", "SIGN IN / SIGN UP", "forum.php", "BOARDS", "DONATE");

    if($_SERVER['REQUEST_METHOD'] != 'POST') {
        /*the form hasn't been posted yet, display it
        not that the action will cause the form to post to the same page it is on*/
        
        echo '<div id="signCont">
                    <div id="progress"></div>
                    <div id="signIn">
                        <form id="signupHost" onsubmit="return false">
                            <h2 id="status">Set Log In Details of Representative</h2>
                            <div id="logIn">
                                <input type="text" id="userName" name="userName" placeholder="Username...">
                                <input type="password" id="passWord" name="passWord" placeholder="Password...">
                                <input type="password" id="passCheck" name="passCheck" placeholder="Confirm password...">
        
                                <button onclick="hostSignUp()">Next</button>
                            </div>
            
                            <div id="summary">
                                <input type="text" id="firstName" name="firstName" placeholder="First Name...">
                                <input type="text" id="lastName" name="lastName" placeholder="Last Name...">
                                <input type="text" id="company" name="company" placeholder="Company Name...">
                                <input type="email" id="email" name="email" placeholder="Company E-mail...">
                                    
                                <div id="genderSelect">Male<input type="radio" id="gender" name="gender" value="Male"></div>
                                <div id="genderSelect">Female<input type="radio" id="gender" name="gender" value="Female"></div>
                
                                <div id="dob">
                                    <select id="day" name="day">
                                            <option value="">-- Date --</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                            <option value="7">7</option>
                                            <option value="8">8</option>
                                            <option value="9">9</option>
                                            <option value="10">10</option>
                                            <option value="11">11</option>
                                            <option value="12">12</option>
                                            <option value="13">13</option>
                                            <option value="14">14</option>
                                            <option value="15">15</option>
                                            <option value="16">16</option>
                                            <option value="17">17</option>
                                            <option value="18">18</option>
                                            <option value="19">19</option>
                                            <option value="20">20</option>
                                            <option value="21">21</option>
                                            <option value="22">22</option>
                                            <option value="23">23</option>
                                            <option value="24">24</option>
                                            <option value="25">25</option>
                                            <option value="26">26</option>
                                            <option value="27">27</option>
                                            <option value="28">28</option>
                                            <option value="29">29</option>
                                            <option value="30">30</option>
                                            <option value="31">31</option>
                                    </select>
                                    <select id="month" name="month">
                                            <option value="">-- Month --</option>
                                            <option value="Jan">January</option>
                                            <option value="Feb">Febuary</option>
                                            <option value="Mar">March</option>
                                            <option value="Apr">April</option>
                                            <option value="May">May</option>
                                            <option value="Jun">June</option>
                                            <option value="Jul">July</option>
                                            <option value="Aug">August</option>
                                            <option value="Sep">September</option>
                                            <option value="Oct">October</option>
                                            <option value="Nov">November</option>
                                            <option value="Dec">December</option>
                                    </select>
                                    <input type="number" name="year" id="year" min="1900" max="2016" placeholder="YYYY">
                                </div>
                    
                               <button onclick="hostSummary()">Next</button>
                            </div>
                
                            <div id="address">
                                <input type="text" id="addOne" name="addOne" placeholder="Address Line One...">
                                <input type="text" id="addTwo" name="addTwo" placeholder="Address Line Two...">
                                <input type="text" id="postCode" name="postCode" maxlength="7" placeholder="ABC DEF">
                                <input type="text" id="state" name="state" placeholder="State...">
                                <input type="text" id="country" name="country" placeholder="Country">
            
                                <button onclick="hostAddress()">Sign up</button>
                            </div>
            
                            <div id="confirm">
                                <h4>Log In Details</h4>
                                    <p>Username: <span id="display_uname"></span></p>
                                    <p>Password: <span id="display_pass"></span></p>
            
                                <h4>Personal Details</h4>
                                    <p>First Name: <span id="display_fname"></span></p>
                                    <p>Last Name: <span id="display_lname"></span></p>
                                    <p>E-Mail: <span id="display_email"></span></p>
                                    <p>Gender: <span id="display_gender"></span></p>
                                    <p>Date of Birth: <span id="display_dob"></span></p>
            
                                <h4>Address</h4>
                                    <p><span id="display_adone"></span></p>
                                    <p><span id="display_adtwo"></span></p>
                                    <p><span id="display_post"></span></p>
                                    <p><span id="display_state"></span></p>
                                    <p><span id="display_country"></span></p>
            
                                <button onclick="hostSubmitForm()">Submit Data</button>
                            </div>
                        </form>
                    </div>
                </div>';
    }
    echo '</div>';
    echo footer();
?>