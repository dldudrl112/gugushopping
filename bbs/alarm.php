<?php
include_once("./_common.php");

if(!$is_member) {
	goto_url(TB_BBS_URL.'/login.php?url='.$urlencode);
}

$tb['title'] = '알림';
// include_once("./_head.php"); 


$in_writer	=	 ", $member[index_no]";

//게시판 데이터 가져오기
$list	=	board_latest_data('43',150,'', '', ' and FROM_UNIXTIME(`wdate`,"%Y-%m-%d") >= (now()-INTERVAL 30 DAY) and writer in (1 '.$in_writer.') ');

include_once(TB_THEME_PATH.'/alarm.skin.php');

include_once("./_tail.php");
?>