<?php 
if(!isset($_SESSION['userid'])){
    echo "Forbidden";
    exit();
}else if($_SESSION['userid'] != 6){
    echo "Forbidden";
    exit();
}
require_once 'scripts/common.php';
require_once 'classes/manager.php';
$manager =  new Manager($db);

if(isset($_POST['approve'])){
    $manager->approval("a", $_POST['id']);
}else if(isset($_POST['approveanon'])){
    $manager->approval("aa", $_POST['id']);
}else if(isset($_POST['reject'])){
    $manager->approval("r", $_POST['id']);
}
$name = "";
$website = "";
$quote = "";
$id = 0;
$link = "";
$pending_videos = $manager->getPending();
$has_review = false;
if (count($pending_videos) > 0){
  $has_review = true;
  $name = $pending_videos[0]['name'];
  $website = $pending_videos[0]['website'];
  $quote = $pending_videos[0]['quote'];
  $id = $pending_videos[0]['id'];
  $link = $pending_videos[0]['link'];
}


?>
<div style="width:600px;margin-left:auto;margin-right:auto;color:white"> 
     <?php if(!$has_review){ ?>
                <p>There are no more videos for you to review.</p>
                <script type="text/javascript">
                 var myVar=setTimeout(function(){window.location.replace("index.php");},10000);                 
                </script>
     <?php }else{ ?>
                <table style="background:black;">
                    <tr>
                        <td>
                            <iframe width="420" height="345" src="http://www.youtube.com/embed/<?php echo $link;?>"></iframe>               
                        <td>
                        <td valign="top" style="padding:10px;">
                            <table>
                                <tr>
                                    <td>Name:</td>
                                    <td><?php echo $name;?></td>
                                </tr>
                                <tr>
                                    <td>Website:</td>
                                    <td><a href="<?php echo $website;?>"><?php echo $website?></a></td>
                                </tr>
                                <tr>
                                    <td>Quote:</td>
                                    <td><?php echo $quote; ?></td>
                                </tr>
                            </table>
                        </td>
                    <tr>
                    <tr>
                        <td>
                            <center><div class="comment-form-wrapper">
                                <form class="comment-form" id="resetFrm" name="resetFrm" method="post" >
                                    <input type="hidden" name="id" id="id" value="<?php echo $id;?>">
                                  <table>
                                      <tr> 
                                          <td><input type="submit" value="Approve" name="approve" id="approve" class="btn btn-submit" /></td>
                                          <td><input type="submit" value="Approve Anonymously" name="approveanon" id="approveanon" class="btn btn-submit" /></td>
                                          <td><input type="submit" value="Reject" name="reject" id="reject" class="btn btn-submit" /></td>
                                      </tr>
                                  </table>     
                                </form>
                            </div></center>
                        </td>
                        <td>&nbsp;</td>
                    </tr>    
                </table>    
     <?php }?>
</div>
