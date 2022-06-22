<?php
include_once("./_common.php");



$conn = mysqli_connect('211.47.74.10', 'gugudev', 'gugudev0710!', 'dbgugudev');
 
if($conn){
    echo "<br>접속 성공<br>";
}
else{
    echo "<br>접속 실패<br>";
}
 
$token = $_POST['Token'];

$db_sql = "INSERT INTO users(Token) values('".$token."') ON DUPLICATE KEY UPDATE Token = '".$token."';";
mysqli_query($conn, $db_sql);
 

mysqli_close($conn);

include_once(TB_THEME_PATH.'/token_signwithapple.php');

// include_once("./_tail.php");
?>
<!-- include_once 'config.php -->

 
