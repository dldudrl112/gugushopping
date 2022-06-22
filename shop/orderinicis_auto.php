<?php
include_once("./_common.php");

$od = sql_fetch("select * from shop_order where od_id='$od_id'");

if(!$od['od_id']) {
    alert("결제할 주문서가 없습니다.");
}

$tb['title'] = '결제하기';
include_once("./_head.php");

set_session('ss_order_id', $od_id);
set_session('ss_order_inicis_id', $od_id);

$stotal		=	get_order_spay($od_id); // 총계
$tot_price	=	get_session('tot_price'); // 결제금액

if($od["paymethod"] == "신용카드"){
	$acceptmethod = "BILLAUTH:FULLVERIFY";
}else{
	$acceptmethod = "BILLAUTH(HPP):HPP(2)";
}

if(is_mobile()){


	if($od["paymethod"] == "신용카드"){
		$order_action_url = "https://inilite.inicis.com/inibill/inibill_card.jsp";
	}else if($od["paymethod"] == "휴대폰"){
		$order_action_url = "https://inilite.inicis.com/inibill/hpp.jsp";	
	}else{
		alert("잘못된 결제수단입니다.");
		exit;
	}

}else{
	$order_action_url = TB_HTTPS_SHOP_URL.'/orderinicis_auto_result.php';
}



include_once(TB_THEME_PATH.'/orderinicis_auto.skin.php');

include_once("./_tail.php");
?>