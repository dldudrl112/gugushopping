<?php
include_once("./_common.php");

if (TB_IS_MOBILE) {
	goto_url(TB_MURL);
  }

$tb['title'] = 'gugu_personal_android';
include_once("./_head.php");


include_once(TB_THEME_PATH.'/gugu_personal_android.php');

include_once("./_tail.php");
?>
