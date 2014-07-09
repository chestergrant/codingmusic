<?php 
require_once 'scripts/common.php';
require_once 'classes/manager.php';
$manager =  new Manager($db);
$reset_password =  false;
$hash = "";
$reset_errors ="";
$password = "";
$retype_password = "";
if(isset($_REQUEST['hash'])){
   $hash = trim($_REQUEST['hash']);
}
if(isset($_POST['reset'])){
    //check password are equal and are greater than 8 characters    
    if(!emptyStr($_POST['password'])){
        $password = $_POST['password'];
    }
    if(!emptyStr($_POST['retype_password'])){
        $retype_password = $_POST['retype_password'];
    }
    if($password != $retype_password){
        $reset_errors .= "&#149; Passwords do not match.";
    }else if( strlen($password)< 8){
        $reset_errors .= "&#149; Password must be atleast 8 characters.";
    }
    if(($reset_errors == "")&&($manager->validHash($hash))){
        $manager->resetPassword($password,$retype_password,$hash);
        $reset_password = true;
    }
}
?>
<div style="width:500px;margin-left:auto;margin-right:auto;color:white">    
    <div class="comment-form-wrapper">
        <?php if($reset_password){?>
                <p>Your password has been reset, and you are now currently logged in. You will be directed to the Home in 10 seconds.</p>
                <script type="text/javascript">
                 var myVar=setTimeout(function(){window.location.replace("index.php");},10000);                 
                </script>
        <?php } else if(!$manager->validHash($hash)){?>
                <p>Sorry the link is invalid. A link to reset your password is only valid for 24 hours. To request a new link click <a href="index.php?site=forgotten">here</a>.</p>
        <?php }else{?>
                <h3 style="color:white;">Reset Password?</h3>
                <p>Please enter your new password.</p>
                <?php if($reset_errors != ""){ ?>
                   <center><span id="user-error1" class="user-error-bubble"><?php echo $reset_errors;?></span></center>
                <?php } ?>
                <form class="comment-form" id="resetFrm" name="resetFrm" method="post" >
                  <div class="name-field">
                    <input type="password" id="password" name="password" placeholder="Password"/>
                  </div>
                  <div class="name-field">
                    <input type="password" id="retype_password" name="retype_password" placeholder="Retype Password"/>
                  </div>
                    <input type="hidden" name="hash" id="hash" value="<?php echo $hash; ?>">
                  <input type="submit" value="Reset" name="reset" id="reset" class="btn btn-submit" />
                </form>
         <?php }?>
       </div>
</div>
