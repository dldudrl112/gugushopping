<?php
include_once("./_common.php");

if(!$is_member) {
	goto_url(TB_BBS_URL.'/login.php?url='.$urlencode);
}


$tb['title'] = '마이페이지  메뉴';

// include_once(TB_PATH."/head.php");

include_once(TB_THEME_PATH.'/m_mypagelist.skin.php');

// include_once(TB_PATH."/tail.php");
?>