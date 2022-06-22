<?php
include_once("./_common.php");

if(!$is_member) {
	goto_url(TB_BBS_URL.'/login.php?url='.$urlencode);
}

if(!$_GET["od_id"]){
	alert_close("정상적인 경로가 아닙니다.");
	exit;
}


$od_id=$_GET["od_id"];
$tb['title'] = '배송일 변경';

$od	=	sql_fetch("select * from shop_order where od_id = '$od_id'");

include_once(TB_PATH."/head.sub.php");

$action_url	=	TB_HTTPS_SHOP_URL.'/my_shipping_day_update.php';

include_once(TB_THEME_PATH.'/my_shipping_day.skin.php');

include_once(TB_PATH."/tail.sub.php");
?>