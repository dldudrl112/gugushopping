<?php
include_once("./_common.php");

$conn = mysqli_connect(TB_MYSQL_HOST, TB_MYSQL_USER, TB_MYSQL_PASSWORD,TB_MYSQL_DB);

if ($conn) {
    echo "<br>접속 성공<br>";
} else {
    echo "<br>접속 실패<br>";
}

if (isset($_POST["Token"])) {

    $token = $_POST["Token"]; 
    //데이터베이스에 접속해서 토큰을 저장
    include_once("./_common.php");
   
    $query = "INSERT INTO users(Token) Values ('$token') ON DUPLICATE KEY UPDATE Token = '$token'; ";
    mysqli_query($conn, $query);

    mysqli_close($conn);

    
}
include_once(TB_THEME_PATH.'/token_signwithapple.php');
?>
<a href=""