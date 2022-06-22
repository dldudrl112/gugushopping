<?php
include_once("./_common.php");

if(TB_IS_MOBILE) {
	goto_url(TB_MSHOP_URL.'/mypage.php');
}

if(!$is_member) {
	goto_url(TB_BBS_URL.'/login.php?url='.$urlencode);
}

$tb['title'] = "마이페이지";
// include_once("./_head.php");

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

//if(isset($_GET['isDev'])){
//    $default['de_certify_use']= 1;
//    $default['de_checkplus_id']= "BV507"; // NICE로부터 부여받은 사이트 코드
//    $default['de_checkplus_pw']= "gfOxQPLMqikY"; // NICE로부터 부여받은 사이트 패스워드
//
//
//}

// 실명인증 사용시
if($default['de_certify_use']) {
//    $regReqSeq = get_session('REQ_SEQ');
//    if($regReqSeq){
//        $sql = " delete from shop_joincheck where j_key = '$regReqSeq' ";
//        sql_query($sql, FALSE);
//    }

    @include_once(TB_PLUGIN_PATH."/chekplus/checkplus_main.php");
    @include_once(TB_PLUGIN_PATH."/chekplus/ipin_main.php");
}

include_once(TB_THEME_PATH.'/mypage.skin.php');

include_once("./_tail.php");
