<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="Find music to code to">
<meta name="author" content="Chester Grant">
<link rel="shortcut icon" href="style/images/favicon.png">
<title>Coding Music</title>
<script src="style/js/jquery.min.js"></script> 
<script type="text/javascript" src="js/dashboard.js?<?php echo time();?>"></script>    
    
<!-- Bootstrap core CSS -->
<link href="style/css/bootstrap.css?<?php echo time();?>" rel="stylesheet">
<link href="style/css/settings.css?<?php echo time();?>" rel="stylesheet">
<link href="style/css/owl.carousel.css?<?php echo time();?>" rel="stylesheet">
<link href="style/js/google-code-prettify/prettify.css?<?php echo time();?>" rel="stylesheet">
<link href="style/js/fancybox/jquery.fancybox.css?<?php echo time();?>" rel="stylesheet" type="text/css" media="all" />
<link href="style/js/fancybox/helpers/jquery.fancybox-thumbs.css?v=1.0.2" rel="stylesheet" type="text/css" />
<link href="style.css?<?php echo time();?>" rel="stylesheet">
<link href="style/css/color/blue.css?<?php echo time();?>" rel="stylesheet">
<link href='http://fonts.googleapis.com/css?family=Josefin+Sans:400,600,700,400italic,600italic,700italic' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Raleway:400,300,500,600,700,800,900' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Dosis:200,300,400,500,600,700,800' rel='stylesheet' type='text/css'>
<link href="style/type/fontello.css?<?php echo time();?>" rel="stylesheet">
<link href="style/type/budicons.css?<?php echo time();?>" rel="stylesheet">
<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
      <script src="style/js/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
      <![endif]-->
</head>
<body>
<div class="body-wrapper">
  <div class="navbar default">
    <div class="navbar-header">
      <div class="container">
        <div class="basic-wrapper"> <a class="btn responsive-menu pull-right" data-toggle="collapse" data-target=".navbar-collapse"><i class='icon-menu-1'></i></a> <a class="navbar-brand" href="index.php.html"><img src="style/images/logo.png" alt="" data-src="style/images/logo.png" data-ret="style/images/logo@2x.png" class="retina" /></a> </div>
        <nav class="collapse navbar-collapse pull-right">
          <ul class="nav navbar-nav">
            <li><a href="index.php">Home</a></li>
            <li><a href="index.php?site=playlist">My Playlist</a></li>
            <li><a href="index.php?site=submit">Submit</a></li>
            <?php if((isset($_SESSION['userid']))&&($_SESSION['userid']==6)){?>
            <li><a href="index.php?site=approve">Approve</a></li>            
            <?php }
                 if(!isset($_SESSION['loggedin'])){?>
                    <li><a href="index.php?site=login">Login</a></li> 
            <?php }else{?>
                    <li><a href="logout.php">Logout</a></li>
            <?php } ?>
          </ul>
        </nav>
      </div>
    </div>
    <!--/.nav-collapse --> 
  </div>
  <!--/.navbar -->
  <div id="home" class="section">  
      <div style="height:100px;">&nbsp;</div>
              <?php 
                    $site = "";
                    $no_match = true;
                    if(isset($_REQUEST['site'])){
                        $site = $_REQUEST['site'];
                    }
                    if($site == "submit"){
                        $no_match = false;
                        include_once 'views/submit.php';
                    }
                    if($site == "login"){
                        $no_match = false;
                        include_once 'views/login.php';
                    }
                    if($site == "forgotten"){
                        $no_match = false;
                        include_once 'views/forgotten.php';
                    }
                    if($site == "reset"){
                        $no_match = false;
                        include_once 'views/reset.php';
                    }
                    if($site == "approve"){
                        include_once 'views/approve.php';
                        $no_match = false;
                    }
                    if($site == "playlist"){
                        include_once 'views/playlist.php';
                        $no_match = false;
                    }
                    if(($site == "") || ($no_match)){
                        include_once 'views/home.php';
                    }
              ?>
           
  </div>

  <footer class="footer">
    <div class="container inner">
      <p class="pull-left">© <?php echo date("Y", time());?> Chester Grant. All rights reserved. </p>
      
    </div>
    <!-- .container --> 
  </footer>
  <!-- /footer --> 
</div>
<!-- .body-wrapper --> 

<script src="style/js/bootstrap.min.js"></script> 
<script src="style/js/twitter-bootstrap-hover-dropdown.min.js"></script> 
<script src="style/js/jquery.themepunch.plugins.min.js"></script> 
<script src="style/js/jquery.themepunch.revolution.min.js"></script> 
<script src="style/js/jquery.easytabs.min.js"></script> 
<script src="style/js/owl.carousel.min.js"></script> 
<script src="style/js/jquery.isotope.min.js"></script> 
<script src="style/js/jquery.fitvids.js"></script> 
<script src="style/js/jquery.fancybox.pack.js"></script> 
<script src="style/js/fancybox/helpers/jquery.fancybox-thumbs.js?v=1.0.2"></script> 
<script src="style/js/fancybox/helpers/jquery.fancybox-media.js?v=1.0.0"></script> 
<script src="style/js/jquery.slickforms.js"></script> 
<script src="style/js/instafeed.min.js"></script> 
<script src="style/js/retina.js"></script> 
<script src="style/js/google-code-prettify/prettify.js"></script> 
<script src="style/js/scripts.js"></script>
</body>
</html>