<?php
session_start();
require_once 'common.php';
require_once '../classes/manager.php';
$id =  0;
if(isset($_REQUEST['videoid'])){
    $id = intval($_REQUEST['videoid']);
}else{
    exit();
}
$manager =  new Manager($db);
$manager->add($id);
?>