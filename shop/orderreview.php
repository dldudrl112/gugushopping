<?php
include_once("./_common.php");

if(!$is_member) {
    alert_close("로그인 후 작성 가능합니다.");
	exit;
}


if(!$_REQUEST["od_id"]){
    alert_close("주문정보가 정상적으로 넘어오지 않았습니다.");
	exit;
}

$tb['title'] = '구매후기 작성';
include_once(TB_PATH."/head.sub.php");

$od_id	=	trim($_REQUEST["od_id"]);

$od		=	sql_fetch("select * from shop_order where od_id = '".$od_id."'");
$gs		=	unserialize($od['od_goods']);



	$gs_id	=	$od["gs_id"];

	$review	=	sql_fetch("select count(index_no) as cnt from shop_goods_review where od_id = '".$od_id."' and gs_id = '".$gs_id."' and mb_id = '".$member[id]."'");	
	
	$it_href		= TB_SHOP_URL.'/view.php?index_no='.$gs_id;

	if($review["cnt"] > 0){
		alert_close("이미 해당 주문에 대하여 리뷰를 작성하셨습니다.",$it_href);
		exit;
	}

/*
if($od["dan"] > 2 && $od["dan"] < 6){

	$gs_id	=	$od["gs_id"];

	$review	=	sql_fetch("select count(index_no) as cnt from shop_goods_review where od_id = '".$od_id."' and gs_id = '".$gs_id."' and mb_id = '".$member[id]."'");	
	
	$it_href		= TB_SHOP_URL.'/view.php?index_no='.$gs_id;

	if($review["cnt"] > 0){
		alert_close("이미 해당 주문에 대하여 리뷰를 작성하셨습니다.",$it_href);
		exit;
	}

}else{
    alert_close("리뷰를 작성하실 권한이 없습니다.");
	exit;
} 
*/



$token = md5(uniqid(rand(), true));
set_session("ss_token", $token);

$form_action_url = TB_HTTPS_SHOP_URL.'/orderreview_update.php';
include_once(TB_THEME_PATH.'/orderreview.skin.php');

include_once(TB_PATH."/tail.sub.php");
?>