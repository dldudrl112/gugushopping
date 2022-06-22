<?php
include_once('../../common.php');

if($is_member) {
	alert_close("로그인 완료","/index.php");
	// alert('로그인 완료.');
	// echo '<script type="text/javascript">'; 
	// echo 'window.location.href = "/index.php";';
	// echo '</script>';
	// echo("<script>location.href='/index.php';</script>"); 
	exit; 
}

include_once('./_apikey.php');
?>

<script language='javascript'>
// window.setTimeout('window.location.reload()',1500); 
window.setTimeout('window.location.href="/index.php"',1500); 
</script>
