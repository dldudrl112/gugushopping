<?php
include_once("./_common.php");

$url = $_GET['url'];

// url 체크
check_url_host($url);

// 이미 로그인 중이라면
if($is_member) {
    if($url)
        goto_url($url);
    else
        goto_url(TB_URL);
}

$tb['title'] = '회원가입 인트로';
include_once("./_head.php"); 

include_once(TB_THEME_PATH.'/register_intro.skin.php');

include_once("./_tail.php");
?>