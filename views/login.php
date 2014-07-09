<?php 
 require_once 'scripts/common.php';
 require_once 'classes/manager.php';
 $manager =  new Manager($db);
 $signup_errors = "";
 $username1_signup = "";
 $email = "";
 $password_signup = "";
 if(isset($_SESSION['loggedin'])){
         ?>
          <script type="text/javascript">
                window.location.replace("index.php?site=playlist");
          </script>
          <?php
 }
 if(isset($_POST['signup'])){
     //check that username is not empty,doesn't begin with an underscore
     if(emptyStr($_POST['username1'])){
         $signup_errors = "&#149; Please enter a valid username";
     }else{
         if(substr($_POST['username1'],0,1)=="_"){
             $signup_errors = "&#149; Usernames cannot begin with underscores";
         }else{
             $username1_signup = trim($_POST['username1']);
         }
     }
     //check that email isn't empty and matches and email pattern
     if(emptyStr($_POST['email'])){
         if($signup_errors != ""){
            $signup_errors .= "<br>";
         }
         $signup_errors .= "&#149; Please enter a valid email.";
     }else if(filter_var(trim($_POST['email']),FILTER_VALIDATE_EMAIL) == false){
         if($signup_errors != ""){
            $signup_errors .= "<br>";
         }
         $signup_errors .= "&#149; Please enter a valid email.";
     }else if($manager->alreadyUsed(trim($_POST['email']))){
         if($signup_errors != ""){
            $signup_errors .= "<br>";
         }
         $signup_errors .= "&#149; Someone has already created an account using that email.";
     }else{
         $email = trim($_POST[email]);
     }
     //check that password is atleast 8 characters
     if((emptyStr($_POST['password'])) || (strlen($_POST['password']) < 8)){
         if($signup_errors != ""){
            $signup_errors .= "<br>";
         }
         $signup_errors .= "&#149; Please enter a password that is atleast 8 characters long.";
     }else{
         $password_signup = $_POST['password'];
     }
     //if everything is ok then store and redirect
     if($signup_errors == ""){
         $manager->createAccount($username1_signup,$email,$password_signup);
         ?>
          <script type="text/javascript">
                window.location.replace("index.php?site=playlist");
          </script>
          <?php
         
     }
     
 }
 $login_errors = "";
 $username1_login = "";
 $password_login = "";
 if(isset($_POST['login'])){
     //Check that username isn't blank
     if(emptyStr($_POST['username1'])){
         $login_errors = "&#149; Please enter a username";
     }else if(substr($_POST['username1'],0,1) == "_"){
         $login_errors = "&#149; Please enter a valid username";
     }else{
         $username1_login = trim($_POST['username1']);
     }
     //Check that password isn't blank
     if(emptyStr($_POST['password'])){
         if($login_errors != ""){
             $login_errors .="<br>";
         }
         $login_errors .= "&#149; Please enter a password";
     }else{
         $password_login = $_POST['password'];
     }
     //Try log them in
     //If log in then redirect them to playlist
     if($login_errors == ""){
         if(!$manager->login($username1_login,$password_login)){
             $login_errors .= "&#149; Invalid username or password";
         }else{
              ?>
          <script type="text/javascript">
                window.location.replace("index.php?site=playlist");
          </script>
          <?php
         }
     }
     //If not display errors
 }
?>

<div style="width:500px;margin-left:auto;margin-right:auto;color:white"> 
      <div class="comment-form-wrapper">
            <h3 style="color:white;">Login</h3>
            <?php if($login_errors != ""){ ?>
               <center><span id="user-error1" class="user-error-bubble"><?php echo $login_errors;?></span></center>
            <?php } ?>
            <form class="comment-form" id="loginFrm" name="loginFrm" method="post" action="#">
              <div class="name-field">
                <input type="text" id="username1" name="username1" value="<?php echo $username1_login; ?>" placeholder="Username"/>
              </div>
              <div class="email-field">
                <input type="password" id="password" name="password" value="<?php echo $password_login; ?>" placeholder="Password" />
              </div>
              
              <input type="submit" value="Login" name="login" id="login" class="btn btn-submit" />&nbsp; <a href="index.php?site=forgotten">Forgot Password?</a>
            </form>
       </div>
       <hr style="margin-bottom: 20px; margin-left:150px;margin-right:150px;color:white;background:white;border-color:white;padding:0px;">
       <div class="comment-form-wrapper">            
            <h3 style="color:white;">Create Account</h3>
            <?php if($signup_errors != ""){ ?>
               <center> <span id="user-error2" class="user-error-bubble"><?php echo $signup_errors;?></span></center>
            <?php } ?>
            <form class="comment-form" id="signupFrm" name="signupFrm" method="post" action="#">
              <div class="name-field">
                <input type="text" id="username1" name="username1" value="<?php echo $username1_signup; ?>" placeholder="Username"/>
              </div>
              <div class="email-field">
                <input type="text" id="email" name="email" value="<?php echo $email; ?>" placeholder="Email"/>
              </div>  
              <div class="email-field">
                <input type="password" id="password" name="password" value="<?php echo $password_signup; ?>" placeholder="Password" />
              </div>
              
              <input type="submit" value="Create Account" name="signup" id="signup" class="btn btn-submit" />
            </form>
        </div>    
</div>    