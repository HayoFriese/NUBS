<?php
  include 'server/db_conn.php';
  session_start();
  require_once('functions.php');
  echo pageIni("NUBS | Gather For The Refugees", "forum.css", "new_concert.js");
  echo '<div id="container">';
    echo '<div id="content">';
    if(isset($_SESSION['host_signed_in']) && $_SESSION['host_signed_in'] == true){
      echo navigation("NUBS", "index.php", "dashboard.php", "MY ACCOUNT", "forum.php", "BOARDS", "DONATE");
      echo "<div id='loginHead'>";
        echo "<p>Welcome, <span><a href='dashboard.php'>".$_SESSION['userName']."</a></span>. Not you? <a href='signout.php'>Sign out</a></p>";
      echo "</div>";
    }
    elseif(isset($_SESSION['user_signed_in']) && $_SESSION['user_signed_in'] == true){
      echo navigation("NUBS", "index.php", "#", "MY ACCOUNT", "forum.php", "BOARDS", "DONATE");
      echo "<div id='loginHead'>";
        echo "<p>Welcome, <span>".$_SESSION['userName']."</span>. Not you? <a href='signout.php'>Sign out</a></p>";
      echo "</div>";
    } else{
      echo navigation("NUBS", "#", "signin.php", "SIGN IN / SIGN UP", "forum.php", "BOARDS", "DONATE");
    }
?>
      <div id="topContent">
          <img src="resources/img/crowd2.jpg">
          <div id="imageText">
            <p class="gather">GATHER FOR THE</p>
            <p class="refugees">REFUGEES</p>
        </div>
      </div>
      <div id="secondContent">
        <div id="causeHeading">
          <h2 class="theCause">The Cause</h2>
        </div>
        <div id="causeContent">
          <div id="cause">
            <h3>Raise Money</h3>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer pharetra eros eu ligula pretium, lobortis faucibus nibh mattis. </p>         
          </div>
          <div id="cause">
            <h3>Change Lives</h3>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer pharetra eros eu ligula pretium, lobortis faucibus nibh mattis. </p>
          </div>
          <div id="cause">
            <h3>Give Aid</h3>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer pharetra eros eu ligula pretium, lobortis faucibus nibh mattis. </p>
          </div>
        </div>
        <div id="causeReadMore">
          <a href="#">Read More...</a>
        </div>
      </div>
      <div id="thirdContent">
        <a href="searchConcert.php"><h2>NEW EVENTS</h2></a>
        <?php
          $sqlIndex = "SELECT idconcert, concertTitle, addressOne, postCode, concertDate FROM software.concert 
            INNER JOIN software.address ON software.address.idaddress = software.concert.concertLocation
            ORDER BY idconcert DESC
            LIMIT 3";
          $rIndex = mysqli_query($conn, $sqlIndex) or die(mysqli_error($conn));
          while($row = mysqli_fetch_assoc($rIndex)){
            $idCon = $row['idconcert'];
            $title = $row['concertTitle'];
            $addOne = $row['addressOne'];
            $post = $row['postCode'];
            $date = $row['concertDate'];
            $one = 1;

            $sqlIndexMed = "SELECT mediaLocation FROM software.media WHERE mediaConcert = $idCon AND mediaSpecial = 1";
            $rMed = mysqli_query($conn, $sqlIndexMed) or die(mysqli_error($conn));
            $row = mysqli_fetch_assoc($rMed);
            $cover = $row['mediaLocation'];

            if($idCon % 2 == 0){
              echo '<div class="center">
                <div id="contentL">
                  <img src="'.$cover.'">
                </div>
                <div id="contentR">
                  <h3>'.$title.'</h3>
                  <h4>'.$date.'</h4>
                  <h5>'.$addOne.' - '.$post.'</h5>
                  <a href="concert.php?conID='.$idCon.'" class="findOutMore">FIND OUT MORE</a>
              </div>
            </div>';
            } else {
              echo '<div class="center">
                <div id="contentL">
                  <h3>'.$title.'</h3>
                  <h4>'.$date.'</h4>
                  <h5>'.$addOne.' - '.$post.'</h5>
                  <a href="concert.php?conID='.$idCon.'" class="findOutMore">FIND OUT MORE</a>
                </div>
                <div id="contentR">
                  <img src="'.$cover.'">
              </div>
            </div>';
            }
          }
          echo "<div id='indexSearchAll'>
              <div>
              <p><a href='searchConcert.php'>View All...</a></p>
            </div>
          </div>";
        echo "</div>";
    echo '</div>';
    echo '</div>';
    echo footer();
    ?>
