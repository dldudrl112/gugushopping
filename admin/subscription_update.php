<?php
include_once("./_common.php");

check_demo();

check_admin_token();
include_once(TB_PATH."/classes/Subscription.php");

// 관리자메모
if($_POST['mod_type'] == 'memo') {
    Subscription::getInstance()->update(['memo'=> addslashes($_POST['memo'])], $id);
}

// 구독상세 정보
if($_POST['mod_type'] == 'subscription') {
    Subscription::getInstance()->update($_POST, $id);
}

goto_url(TB_ADMIN_URL."/subscription.php?id=$id");
