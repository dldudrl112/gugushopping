<?php
include_once("./_common.php");

if(!$is_member) {
	goto_url(TB_BBS_URL.'/login.php?url='.$urlencode);
}

$tb['title'] = '포인트 충전  결제하기';
include_once("./_head.php");

//결제 구분
$price_type	=	$_POST["price_type"];

$price		=	0;
$add_point	=	0;

if($price_type == 1){
	$price		=	30000;
	$add_point	=	33000;	
}else if($price_type == 2){
	$price		=	50000;
	$add_point	=	57000;	
}else if($price_type == 3){
	$price		=	80000;
	$add_point	=	90000;	
}else if($price_type == 4){
	$price		=	100000;
	$add_point	=	115000;	
}else if($price_type == 5){
	$price		=	150000;
	$add_point	=	175000;	
}else{
	$price		=	30000;
	$add_point	=	33000;
}


set_session('payment_price', (int)$price);
set_session('payment_point', (int)$add_point);

set_session('payment_od_id',get_uniqid());		//주문번호



$tot_price	=	get_session('payment_price');
$tot_point	=	get_session('payment_point');
$od_id		=	get_session('payment_od_id');

set_session('ss_order_inicis_id',$od_id);			//주문번호

$goods		=	"포인트 충전";

$od["tot_price"]			=	$tot_price;
$od["tot_point"]			=	$tot_point;
$od["od_id"]				=	$od_id;
$od["name"]				=	$member["name"];
$od["email"]				=	$member["id"];
$od["cellphone"]			=	$member["cellphone"];
$od["b_name"]			=	$member["name"];
$od["b_cellphone"]		=	$member["cellphone"];

// 복합과세처리
$comm_tax_mny	=	0; // 과세금액
$comm_vat_mny		=	0; // 부가세
$comm_free_mny	=	0; // 면세금액

if($default['de_tax_flag_use']) {
	$comm_tax_mny	=	round($tot_price/1.1);
	$comm_vat_mny		=	$tot_price-$comm_tax_mny;
}



$form_action_url = TB_HTTPS_SHOP_URL.'/point_payment_update.php';

include_once(TB_THEME_PATH.'/point_payment_result.skin.php');

include_once("./_tail.php");
?>
