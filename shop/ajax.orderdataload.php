<?php
include_once('./_common.php');

if(empty($_POST))   die('N');

if(!$member['id'])    die('N');

// 일정 기간이 경과된 임시 데이터 삭제

$tot_price	=	get_session('payment_price');
$tot_point	=	get_session('payment_point');
$od_id		=	get_session('payment_od_id');
$goods		=	"포인트 충전";

$od['paymethod']		=	$_POST['paymethod'];	
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


$dt_data		=	base64_encode(serialize($od));

// 동일한 주문번호가 있는지 체크
$sql = " select count(*) as cnt from shop_point_order_data where od_id = '$od_id' ";
$row = sql_fetch($sql);
if($row['cnt'])
    sql_query(" delete from shop_point_order_data where od_id = '$od_id' ");

$default_pg = 'inicis';


if($_POST['paymethod'] == 'KAKAOPAY') {
    $default_pg = 'kakaopay';
}else if($_POST['paymethod'] == '신용카드'){
    $default_pg = 'inicis';
}


$sql = " insert into shop_point_order_data
            set od_id	= '$od_id',
				mb_id   = '{$member['id']}',
                dt_pg   = '$default_pg',
                dt_data = '$dt_data',
                dt_time = '".TB_TIME_YMDHIS."' ";
sql_query($sql);

?>