function nextVideo(){
    window.location.replace("index.php");
}
function nextPlaylistVideo(){
    window.location.replace("index.php?site=playlist");
}

function addPlaylist(){
    $("#add").css("display","none");
    var url = "scripts/add.php";
    $.ajax({
        type:"POST",
        url: url,      
        data: $("#feedbackFrm").serialize(),
        success: function(data){
         
        }
    });
    
    
}
function removePlaylist(){
    $("#remove").css("display","none");
    var url = "scripts/remove.php";
    $.ajax({
        type:"POST",
        url: url,      
        data: $("#feedbackFrm").serialize(),
        success: function(data){
           nextPlaylistVideo();
        }
    });  
}