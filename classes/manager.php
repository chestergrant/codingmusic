<?php
class Manager{
   private $conn;
  
  function __construct($db) {
      $this->conn = $db;     
   }
  
  function getRandomFromUser($theuserid){
      $sql = "SELECT music_id FROM playlist p, music m WHERE p.music_id = m.id and m.approved in ('a','aa') and p.active = 'y' and p.user_id = ? order by RAND() LIMIT 1";
      if($stmt = $this->conn->prepare($sql)){
                $stmt->bind_param("s", $theuserid);
                $stmt->execute();
                $stmt->store_result();
                $stmt->bind_result($music_id);
                if($stmt->num_rows > 0){
                    $stmt->fetch();                    
                    $sql2 = "SELECT * FROM music WHERE id = ".$music_id;
                    $result = $this->conn->query($sql2);
                    return niceArray($result);
                }
       }
       return array();
  } 
  
  function remove($id){
      if(emptyStr($id)){          
          return;
      }
      if(!isset($_SESSION['userid'])){          
          return;
      }
      $sql = "UPDATE playlist SET active = 'n' WHERE user_id = ? and music_id = ?";
      if($stmt = $this->conn->prepare($sql)){
            $stmt->bind_param("ii", $_SESSION['userid'],$id);
            $stmt->execute();
      }
  } 
  function approval($code, $id){
      if(emptyStr($id)){
          return;
      }
      $sql = "UPDATE music SET approved = '".$code."' WHERE id = ?";
      if($stmt = $this->conn->prepare($sql)){
            $stmt->bind_param("s", $id);
            $stmt->execute();
      }
  }
  
  function add($id){
      //get user id , if none exist create it
      if(!$this->validVideoID($id)){
          return;
      }
      if(!isset($_SESSION['userid'])){
          $this->create_guest();
      }
      if(!isset($_SESSION['userid'])){
          return;
      }
      //check if the music is already in the user's 
      if(!$this->alreadyInPlaylist($id,$_SESSION['userid'])){
          $sql = "INSERT playlist(user_id, music_id, last_played, active)VALUES(?,?,?,'y')";
          if($stmt = $this->conn->prepare($sql)){
            $stmt->bind_param("iii", $_SESSION['userid'],$id,time());
            $stmt->execute();
          }
      }
      //if not add it to the playlist
  }
  
  function validVideoID($id){
      $sql = "SELECT * FROM music WHERE id = ?";
       if($stmt = $this->conn->prepare($sql)){
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $stmt->store_result();
                if($stmt->num_rows > 0){
                    return true;
                }
       }
       return false;
  }
  function alreadyInPlaylist($videoid,$theuserid){
      $sql = "SELECT * FROM playlist WHERE user_id = ? and music_id = ? and active = 'y'";
      if($stmt = $this->conn->prepare($sql)){
                $stmt->bind_param("ii", $theuserid,$videoid);
                $stmt->execute();
                $stmt->store_result();
                if($stmt->num_rows > 0){
                    return true;
                }
       }
       return false;
  }
  function getRandom(){
      $sql = "SELECT * FROM music WHERE approved in ('a','aa') ORDER BY RAND() LIMIT 1";
      $result = $this->conn->query($sql);
      return niceArray($result);
  }
  
  function getPending(){
       $output = array();
       $sql = "SELECT id, link, name, website, quote FROM music WHERE approved = 'p'";
       $result = $this->conn->query($sql);
       $output = niceArray($result);
       return $output;
  }
  function resetPassword($password,$retypepassword,$hash){
      $email = $this->getEmailFromHash($hash);
      $theusername = $this->getUsernameFromEmail($email);
      $salt = $this->generateSalt($theusername);
      $g_password = $this->generateHash($salt,$password);              
      $sql = "UPDATE users2 SET password='".$g_password."' WHERE email='".$email."'";
      $this->conn->query($sql);
      $this->login($theusername, $password);        
  }
  function getUsernameFromEmail($email){
      $output = "";
       $sql = "SELECT username FROM users2 WHERE email = ?";
       if($stmt = $this->conn->prepare($sql)){
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $stmt->store_result();
                $stmt->bind_result($theusername);
                if($stmt->num_rows > 0){
                    $stmt->fetch();                    
                    $output = $theusername;
                }
       }
       return $output;
  }
  
   function getEmailFromHash($hash){
       $output = "";
       $sql = "SELECT email FROM recovery2 WHERE hash = ?";
       if($stmt = $this->conn->prepare($sql)){
                $stmt->bind_param("s", $hash);
                $stmt->execute();
                $stmt->store_result();
                $stmt->bind_result($email);
                if($stmt->num_rows > 0){
                    $stmt->fetch();                    
                    $output = $email;
                }
       }
       return $output;
   }
  function validHash($hash){
       $day_ago = time() - 86400;
       $sql =  "SELECT * FROM recovery2 WHERE hash=? AND time >= ".$day_ago;
       $output = false;
       if($stmt = $this->conn->prepare($sql)){
                $stmt->bind_param("s", $hash);
                $stmt->execute();
                $stmt->store_result();
                if($stmt->num_rows > 0){
                    $output = true;
                }               
       }
       
       return $output;
   }
  function sendRecoverEmail($email){
       if($this->validRecoveryEmail($email)){
           $resetStr = $this->setRecoveryStr($email);
           $msg = $this->getRecoveryEmailMsg($resetStr);
           $subject = "Reset Password";
           $this->sendEmail($email,$msg, $subject,"no-reply@coding-music.com");
       }
   } 
   function sendEmail($email, $msg, $subject,$from){
       $headers = 'From: '.$from . "\r\n" .
                  'Reply-To: '.$from . "\r\n" ;
       $headers .= "MIME-Version: 1.0\r\n";
       $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
        
        mail($email, $subject, $msg, $headers);
   }
   
   function getRecoveryEmailMsg($resetStr){
       $output =  "<table cellspacing=0 cellpadding=0 style='border: 1px solid #AAA' border=0 align='center'>";
       $output .= "<tr colspan=3 height='36px'></tr>";
       $output .= '<tr>';
       $output .= '<td width="36px">&nbsp;';
       $output .= '</td>';
       $output .= '<td width="454px">';
       $output .= 'Hi,<br><br>';
       $output .= 'It was recently requested that you want to change your password.<br><br>';
       $output .= 'If this was indeed you, please click on the link below else ignore this email.<br><br>';
       $output .= '<center><a style="border:1px solid #2270AB; padding:14px 7px 14px 7px; margin: 0px auto 0px auto; font-size:16px; background:#33A0E8; width:210px;" href="http://www.coding-music.com/index.php?site=reset&hash='.$resetStr.'">Reset</a><br><br></center>';
       $output .= 'Thanks,<br>';
       $output .= 'Coding Music Team';
       $output .= '</td>';
       $output .= '<td width="36px">&nbsp;';
       $output .= '</td>';
       $output .= '</tr>';
       $output .= "<tr colspan=3 height='36px'></tr>";
       $output .= "</table>";
       return $output;
   }
   
   function setRecoveryStr($email){
       $output = $email.time();
       $output = md5($output);
       $sql = "INSERT INTO recovery2 (email, hash, time) VALUES(?,?,?)";
         
        if($stmt = $this->conn->prepare($sql)){
                $stmt->bind_param("ssi", $email,$output,time());
                $stmt->execute();
         }
       return $output;
   }
   
   function validRecoveryEmail($email){
       $output = true;
       if(emptyStr($email)){
           $output = false;           
       }
       if(!$this->alreadyUsed($email)){
           $output = false;
       }
       return $output;
   }
   
    function alreadyUsed($email){
       $output = false;
       $sql = "SELECT * FROM users2 WHERE email = ?";
       if($stmt = $this->conn->prepare($sql)){
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $stmt->store_result();
                if($stmt->num_rows > 0){
                    $output = true;
                }
       }
       return $output;
   }
   
  function generateHash($salt, $password) {
	$hash = crypt($password, $salt);
	$hash = substr($hash, 29);
	return $hash;
   }
   
   function generateSalt($email) {
	$salt = '$2a$13$';
	$salt = $salt . md5(strtolower($email));
	return $salt;
   } 
  function createAccount($username1_signup,$email,$password_signup){
         $salt = $this->generateSalt($username1_signup);
         $g_password = $this->generateHash($salt,$password_signup);
         //store signup data
         $sql = "INSERT INTO users2 (username,email,password,ip,sessionid) VALUES(?,?,?,?,?)";
         $ip = "1.1.1.1";
         if(filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP) != false){
               $ip = $_SERVER['REMOTE_ADDR'];
         }

         $sessionid = session_id();
         if($stmt = $this->conn->prepare($sql)){
                $stmt->bind_param("sssss", $username1_signup,$email,$g_password,$ip,$sessionid);
                $stmt->execute();
                $this->login($username1_signup, $password_signup);
         }
  } 
  function login($username1_login,$password_login){     
         $salt = $this->generateSalt($username1_login);
         $g_password = $this->generateHash($salt,$password_login);
         
         if($stmt = $this->conn -> prepare("SELECT * FROM users2 WHERE username=? AND password=?")) {
                $stmt -> bind_param("ss", $username1_login, $g_password);

	      /* Execute it */
      		$stmt -> execute();
                
                $stmt->store_result();
                if($stmt->num_rows == 0){
                   return false;                   
                }else{
                      $this->setupSessions($username1_login);
                }
	      
     		 /* Close statement */
      		$stmt -> close(); 
         }
         return false;
  }
  
  function setupSessions($username_login){
     //get userid
     $current_userid = $this->getUserData("id", $username_login);
     //get guest userid
     if(isset($_SESSION['userid'])){
         $guest_name = $this->getUserData("name", $_SESSION['username']);
         $guest_website = $this->getUserData("website", $_SESSION['username']);
         
         if(!emptyStr($guest_name)){
              $this->updateUsers("name",$guest_name,$current_userid);
         }
         
         if(!emptyStr($guest_website)){
              $this->updateUsers("website",$guest_website,$current_userid);
         }         
         $this->replacePlaylist($current_userid,$_SESSION['userid']);
     }
     
     $_SESSION['username'] = $username_login;
     $_SESSION['userid'] = $current_userid;
     $_SESSION['loggedin'] = true;
     $_SESSION['name'] = $this->getUserData("name", $_SESSION['username']);
     $_SESSION['website'] = $this->getUserData("website", $_SESSION['username']);
     
  }
  
  function replacePlaylist($current_userid,$guest_userid){
      $sql = "UPDATE playlist SET user_id = ? WHERE user_id = ?";
       if($stmt = $this->conn->prepare($sql)){
            $stmt->bind_param("ss", $current_userid,$guest_userid);
            $stmt->execute();
       }
  }
  
  function getUserData($field,$username_login){
      $sql = "SELECT ".$field." FROM users2 WHERE username = ?";
       if($stmt = $this->conn->prepare($sql)){
            $stmt->bind_param("s", $username_login);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($ans);
            if($stmt->num_rows > 0){
                    $stmt->fetch();                    
                    return $ans;
            }
       }
       return false;
  }
  
  function idExist($link){
      $sql = "SELECT * FROM music WHERE link = ?";
       if($stmt = $this->conn->prepare($sql)){
            $stmt->bind_param("s", $link);
            $stmt->execute();
            $stmt->store_result();
            if($stmt->num_rows > 0){
                  return true;  
            }
       }
       return false;
  }
  function saveData($link,$name,$website,$quote){
      if((emptyStr($name)) &&(isset($_SESSION['person_name']))){
          $name = $_SESSION['person_name']; //x
      }
      if((emptyStr($website)) &&(isset($_SESSION['person_website']))){
          $website = $_SESSION['person_website']; //x
      }
      //check if this user has a user name
      $create_user = false;
      $session_id = session_id();
      if(!emptyStr($session_id)){
          if(!isset($_SESSION['username'])){
              $create_user = true; //x
          }
      }else{
          $create_user = true; //probably will never execute          
      }
      if($create_user){
         $this->create_guest(); //x
      }
      //if not create one
      //then store the music video
      $this->storeData($link,$name,$website,$quote);
      
  }
  
  function storeData($link,$name,$website,$quote){
      //Add to music
      //get last insert id
      $musicid = $this->addMusic($link,$name,$website,$quote);      
      //add to playlist
      $this->addPlayList($_SESSION['userid'],$musicid);
      //if name is not empty update the users name
      if(!emptyStr($name)){
          $this->updateUsers("name",$name,$_SESSION['userid']);
      }
      //if website is not empty update the users website
      if(!emptyStr($website)){
          $this->updateUsers("website",$website,$_SESSION['userid']);
      }
  }
  function updateUsers($field,$value,$id){
      $sql = "UPDATE users2 SET ".$field." = ? WHERE id = ?";
      if($stmt = $this->conn->prepare($sql)){
            $stmt->bind_param("ss", $value,$id);
            $stmt->execute();            
       }
  }
  function addPlayList($userid,$musicid){
      if(!is_numeric($musicid) ||($musicid <0)){
          return;
      }
      
      $sql = "INSERT INTO playlist (user_id,music_id,last_played,active)VALUES(?,?,0,'y')";
       if($stmt = $this->conn->prepare($sql)){
            $stmt->bind_param("ss", $userid,$musicid);
            $stmt->execute();           
       }
  }
  
  function addMusic($link,$name,$website,$quote){
      $sql = "INSERT INTO music (link,approved,name,website,quote,number_added,time_added)VALUES(?,'p',?,?,?,0,".time().")";
       if($stmt = $this->conn->prepare($sql)){
            $stmt->bind_param("ssss", $link,$name,$website,$quote);
            $stmt->execute();
            return $this->conn->insert_id;
       }
       return false;
  }
  
  function create_guest(){
      //Get max user id
      $sql = "SELECT max(id) AS id FROM users2";
      $result = $this->conn->query($sql);
      $out = niceArray($result);
      
      $id = 0;
      if(count($out)>=1){
          $id = $out[0]['id'];
      }
      if(emptyStr($id)){
          $id = 0;
      }
      //concat with the word guest to form new user
      $theusername = "_guest".$id;
      //store in user table with ip and session id
      $ip = "1.1.1.1";
       if(filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP) != false){
           $ip = $_SERVER['REMOTE_ADDR'];
       }
       
      $session_id = session_id();
      $sql = "INSERT INTO users2(username,ip,sessionid)VALUES('".$theusername."','".$ip."','".$session_id."')";
      $this->conn->query($sql);
      
      //store new username in sessions
      $_SESSION['username'] = $theusername;
      $_SESSION['userid'] = $this->conn->insert_id;
  }
}
?>