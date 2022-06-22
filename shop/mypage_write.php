<?php
include_once("./_common.php");

if(TB_IS_MOBILE) {
	goto_url(TB_MSHOP_URL.'/mypage.php');
}

if(!$is_member) {
	goto_url(TB_BBS_URL.'/login.php?url='.$urlencode);
}

$tb['title'] = "프로필 수정";
include_once("./_head.php");


$token = md5(uniqid(rand(), true));
set_session("ss_token", $token);

//연락처  수정
if($member["cellphone"]){
	$arr_phone		=	explode("-",$member["cellphone"]);	
	$phone_01		=	$arr_phone[0];
	$phone_02		=	$arr_phone[1];
	$phone_03		=	$arr_phone[2];
}

$form_action_url = TB_HTTPS_BBS_URL.'/register_mod_update.php';

include_once(TB_THEME_PATH.'/mypage_write.skin.php');

include_once("./_tail.php");
?>
