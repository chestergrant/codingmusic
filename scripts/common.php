<?php

$db = new mysqli("localhost", "chegra", "pK2ZunQVbNTX9N5c", "lose_fifteen");
if ($db->connect_errno) {
    printf("Connect failed:\n");
    exit();
}

function getYoutubeId($url){
 $parts = parse_url($url);
    if(isset($parts['query'])){
        parse_str($parts['query'], $qs);
        if(isset($qs['v'])){
            return $qs['v'];
        }else if($qs['vi']){
            return $qs['vi'];
        }
    }
    if(isset($parts['path'])){
        $path = explode('/', trim($parts['path'], '/'));
        return $path[count($path)-1];
    }
    return false;
}

function niceArray($result){
 $user_arr = array();
 if($result){
     // Cycle through results
    while ($row = $result->fetch_assoc()){
        $user_arr[] = $row;
    }
    // Free result set
    $result->close();
   
 }
 return $user_arr;
}

function emptyStr($str){
    if(!isset($str)){
        return true;
    }
    if(trim($str) == ""){
        return true;
    }
    return false;
}

function real_string($str){
    if(emptyStr($str)){
        return false;
    }
    if(is_string($str)){
        return false;
    }
    return true;
}
?>