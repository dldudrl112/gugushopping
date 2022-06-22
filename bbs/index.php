<!doctype html>
<html lang="en">
 <head>
  <meta charset="UTF-8">
  <meta name="Generator" content="EditPlus®">
  <meta name="Author" content="">
  <meta name="Keywords" content="">
  <meta name="Description" content="">
  <title>FCM Push Example</title>
 </head>
 <body>
  
<div class="messageWrapper">
    <h2>푸쉬 알림</h2>
 
    <form action="push_notification.php" method="post">
        <textarea name="message" rows="4" cols="50" placeholder="메세지를 입력하세요"  required></textarea><br>
        <input type="submit" name="submit" value="보내기" id="submitButton">
    </form>
 
</div>
 
<?php
     include_once("./_common.php");
 
    $conn = mysqli_connect('211.47.74.10', 'gugudev', 'gugudev0710!', 'dbgugudev');
    $sql = "Select token From users";
 
    $result = mysqli_query($conn,$sql);
    while ($row = mysqli_fetch_assoc($result)) { 
?>
    <ul>
        <li><?php echo $row["token"]; ?> ...</li>
    </ul>
 
<?php
    }
?>
 </body>
</html>
