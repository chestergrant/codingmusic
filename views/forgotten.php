<?php 
 require_once 'scripts/common.php';
 require_once 'classes/manager.php';
 $manager =  new Manager($db);
 $forgotten_errors = "";
 $email = "";
 $show_sent = false;
 if(isset($_POST['reset'])){
     if(emptyStr($_POST['email'])){
         $forgotten_errors .= "&#149; Please enter a valid email.";
     }else{
         $email = trim($_POST['email']);
         if(filter_var(trim($_POST['email']),FILTER_VALIDATE_EMAIL) == false){
             $forgotten_errors .= "&#149; Please enter a valid email.";
         }
     }
     if($forgotten_errors == ""){
         $manager->sendRecoverEmail($email);
         $show_sent = true;
     }
 }
?>
<div style="width:500px;margin-left:auto;margin-right:auto;color:white">    
    <div class="comment-form-wrapper">
        <?php if($show_sent){?>
                <p>Please check your email for a link to reset the password.</p>
        <?php }else{?>
                <h3 style="color:white;">Forgot your password?</h3>
                <p>Please enter your email for your account below.</p>
                <?php if($forgotten_errors != ""){ ?>
                   <center><span id="user-error1" class="user-error-bubble"><?php echo $forgotten_errors;?></span></center>
                <?php } ?>
                <form class="comment-form" id="forgottenFrm" name="forgottenFrm" method="post" action="#">
                  <div class="name-field">
                    <input type="text" id="email" name="email" value="<?php echo $email; ?>" placeholder="Email"/>
                  </div>

                  <input type="submit" value="Reset" name="reset" id="reset" class="btn btn-submit" />
                </form>
         <?php }?>
       </div>
</div>
