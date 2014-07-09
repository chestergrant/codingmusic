<?php 
require_once 'scripts/common.php';
require_once 'classes/manager.php';
$manager =  new Manager($db);

$random_video = $manager->getRandom();

$name = $random_video[0]['name'];
$website = $random_video[0]['website'];
$approved = $random_video[0]['approved'];
$quote = $random_video[0]['quote'];
$id = $random_video[0]['id'];
$link = $random_video[0]['link'];
if((emptyStr($name))&&(emptyStr($website))&&(emptyStr($quote))){
   $approved = 'aa'; 
}

?>
<div style="width:600px;margin-left:auto;margin-right:auto;color:white"> 
                <table style="background:black;">
                    <tr>
                        <td>
                            <script src="http://www.youtube.com/player_api"></script>

                            <script>

                                // create youtube player
                                var player;
                                function onYouTubePlayerAPIReady() {
                                    player = new YT.Player('player', {
                                      height: '345',
                                      width: '420',
                                      videoId: '<?php echo $link;?>',
                                      events: {
                                        'onReady': onPlayerReady,
                                        'onStateChange': onPlayerStateChange
                                      }
                                    });
                                }

                                // autoplay video
                                function onPlayerReady(event) {
                                    event.target.playVideo();
                                }

                                // when video ends
                                function onPlayerStateChange(event) {        
                                    if(event.data === 0) {          
                                        window.location.replace("index.php");
                                    }
                                }

                            </script>
                            <div id="player"></div>              
                        <td>
                        <td valign="top" style="padding:10px;min-width:150px;">
                            <?php if($approved == 'a'){?>
                                        <table>
                                            <tr><td colspan="2"><h3 style="color:white;">Submitter Details</h3></td></tr>
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
                            <?php }else{ ?>
                                        <center><h3 style="color:white;">Anonymous Submission</h3></center>
                            <?php }?>
                        </td>
                    <tr>
                    <tr>
                        <td>
                            <center><div class="comment-form-wrapper">
                                <form class="comment-form" id="feedbackFrm" name="feedbackFrm" method="post" >
                                    <input type="hidden" name="videoid" id="videoid" value="<?php echo $id;?>">
                                  <table>
                                      <tr> 
                                          <td><input type="button" onclick="addPlaylist(<?php echo $id?>)" value="Add" name="add" id="add" class="btn btn-submit" /></td>
                                          <td><input type="button" onclick="nextVideo()" value="Next" name="next" id="next" class="btn btn-submit" /></td>
                                      </tr>
                                  </table>     
                                </form>
                            </div></center>
                        </td>
                        <td>&nbsp;</td>
                    </tr>    
                </table>  
</div>
