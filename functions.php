<?php
  function pageIni($title, $stylesheet, $scriptLink){
    $pageIni = <<<PAGEINI
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" 
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
      <title>$title</title>
      <link rel="stylesheet" href="resources/css/$stylesheet" type="text/css">
      <script src="server/$scriptLink"></script>
    </head>
PAGEINI;
  $pageIni .="\n";
  return $pageIni;
  }

  function forumHead($name1, $url2, $name2, $url3, $name3){
    $forumHead = <<<FORUMHEAD
      <div id='forumNav'>
        <div>
          <h1>$name1</h1>   
          <ul>
            <li><a href='$url2' data-alt='New'>$name2 </a></li>
            <li><a href='$url3' data-alt='New'>$name3 </a></li>
          </ul>
        </div>
      </div>
FORUMHEAD;
  $forumHead .="\n";
  return $forumHead;
  }

  function navigation($home, $index, $signin, $sign, $url, $forum, $donate){
    $navigation = <<<NAVIGATION
      <div id="navigation">
        <div id="navigationLeft">
          <h1 class="nubs"><a href="$index">$home</a></h1>
        </div>
        <div id="navigationRight">
          <div id="links"> 
            <ul>
              <li><a href="$signin">$sign</a></li>
              <li><a href="$url">$forum</a></li>
              <li><a href="#donate">$donate</a></li>
              <li><a href='searchConcert.php'>SEARCH</a></li>
            </ul>                 
          </div>
        </div>
      </div>
NAVIGATION;
  $navigation .="\n";
  return $navigation;
  }

 function footer(){
  $footer = <<<FOOTER
      <footer>
      <div id="footerLeft">
        <p class ="footerNUBS">NUBS ©</p>
      </div> 
      <div id="footerRight">
        <div id="adminSignIn"> 
          <ul>
            <li><a href="signup_host.php">ADMIN SIGN UP</a></li>      
          </ul>
        </div>
      </div>
    </footer>
    </body>
    </html>
FOOTER;
  $footer .="\n";
  return $footer;
 }

?>
