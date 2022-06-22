<?php
include_once("./_common.php");

if(!$is_member) {
	goto_url(TB_BBS_URL.'/login.php?url='.$urlencode);
}

$tb['title'] = '포인트 충전';
include_once("./_head.php");


$form_action_url = TB_HTTPS_SHOP_URL.'/point_payment_result.php';

include_once(TB_THEME_PATH.'/point_payment.skin.php');

include_once("./_tail.php");
?>
