<?php
    $error = "";
    $link = "";
    $name = "";
    $website = "";
    $quote = "";
    require_once 'scripts/common.php';
    require_once 'classes/manager.php';
    $manager =  new Manager($db);
    
    if(isset($_POST['submit'])){
        //Check that there is a link
        
        if((!isset($_POST['link']))){           
            $error .= "&#149; Invalid youtube link";
        }else if(emptyStr($_POST['link'])){            
            $error .= "&#149; Invalid youtube link";
        }
        //Check that link doesn't exist in our db
        if($error == ""){
            $link = getYoutubeId($_POST['link']);
            
            if($link != false){
                if($manager->idExist($link)){
                    //still need to check this
                    $error .= "&#149; Video already in our collection";
                }
            }else{
                $error .= "&#149; Invalid youtube link";
            }           
        }
        //If the user enters a name or website remember it in sessions
        //If the name entered is a new name or new website save it to the db
        if(isset($_POST['name'])){
            if(!emptyStr($_POST['name'])){
                $name = $_POST['name'];                
                $_SESSION['person_name'] = $name;
            }
        }
        if(isset($_POST['website'])){
            if(!emptyStr($_POST['website'])){
                $website = $_POST['website'];                
                $_SESSION['person_website'] = $website;
            }
        }
        if(isset($_POST['quote'])){
            if(!emptyStr($_POST['quote'])){
                $quote = $_POST['quote'];
            }
        }
       
        //Check if captcha is correct.
          require_once('scripts/recaptchalib.php');
          $privatekey = "6LfaWvYSAAAAABdDjPZFWWvYBTCqxEehu9wXum0R";
          $resp = recaptcha_check_answer ($privatekey,
                                        $_SERVER["REMOTE_ADDR"],
                                        $_POST["recaptcha_challenge_field"],
                                        $_POST["recaptcha_response_field"]);

          if (!$resp->is_valid) {
              if($error != ""){
                  $error .= "<br>";
              }
              $error .= "&#149; The reCAPTCHA wasn't entered correctly. Try again.";
          } 
        
        //If everything is ok save it to the database
        if($error == ""){
            $manager->saveData($link,$name,$website,$quote);
        }
    }
    
?>

<div style="width:500px;margin-left:auto;margin-right:auto;color:white"> 
    <?php 
        if((isset($_POST['submit']))&& ($error == "")){
            ?><p>Your video has been submitted for review. If you like to submit another video please click <a href="index.php?site=submit">here</a>.</p>
                <?php
        }else{
    ?>
        <div class="comment-form-wrapper">
            <h3 style="color:white;" >Would you like to share a music video?</h3>
            <p>Any unsuitable material will be removed. Required fields are marked *</p>
            <?php if($error != ""){ ?>
                <span id="user-error" class="user-error-bubble"><?php echo $error;?></span>
            <?php } ?>
            <form class="comment-form" id="contactFrm" name="submitFrm" method="post" action="#" >
              <div class="name-field">
                <input type="text" id="link" name="link" placeholder="Embeddable Youtube Link*"/>
              </div>
              <div class="email-field">
                <input type="text" id="name" name="name" placeholder="Your Name" <?php if(isset($_SESSION['person_name'])){ echo " value='".$_SESSION['person_name']."'";}?> />
              </div>
              <div class="website-field">
                <input type="text" id ="website" name="website" placeholder="Your Website" <?php if(isset($_SESSION['person_website'])){ echo " value='".$_SESSION['person_website']."'";}?> />
              </div>
              <div class="message-field">
                  <input type="text" id="quote" name="quote" placeholder="Interesting Quote" <?php if($quote != ""){ echo " value='".$quote."'";}?> />
              </div>
              <div>
                  <?php
                      require_once('scripts/recaptchalib.php');
                      $publickey = "6LfaWvYSAAAAAJLG7iHKTjW0NF8QgG7LOEpM-FFc"; // you got this from the signup page
                      echo recaptcha_get_html($publickey);
                  ?>
              </div>    
              <input type="submit" value="Submit" name="submit" id="submit" class="btn btn-submit" />
            </form>
          </div>
        <?php }?>
</div>
       