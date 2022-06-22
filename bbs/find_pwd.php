<?php
include_once("./_common.php");

if($is_member) {
    alert_close("이미 로그인중입니다.");
}

$tb['title'] = '회원정보 찾기';
include_once("./_head.php"); 

$token = md5(uniqid(rand(), true));
set_session("ss_token", $token);

$form_action_url = TB_HTTPS_BBS_URL."/find_pwd_check.php";

include_once(TB_THEME_PATH.'/find_pwd.skin.php');

include_once("./_tail.php");
?>