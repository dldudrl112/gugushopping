<?php
include_once("./_common.php");

$od_id	=	$_POST["od_id"];
$size		=	$_POST["size"];


if($_POST["select_subj"]){
	$arr_subj_data		=	explode(":",$_POST["select_subj"]);
	$add_io_id				=	chr(30).$arr_subj_data[0];
	$add_ct_option		=	" / ".$_POST["select_subj"];
}
$size_arr = explode( ',', $size);

if(!$od_id){
	alert("정상적인 경로가 아닙니다.");
	exit;
}

$od	=	sql_fetch("select * from shop_cart where od_id = '$od_id'");


if($od["index_no"]){

	// 주문서에 UPDATE
	$sql = " update shop_cart
				set io_id			= '".$size_arr[0].$add_io_id."'
				  , io_type		= '$size_arr[2]'
				  , ct_option	= '".$size_arr[4].$add_ct_option."'
				  , io_price		= '$size_arr[1]'
			  where od_id		= '$od_id'";

	sql_query($sql, false);	

}
alert("옵션 정보가 변경 되었습니다.");
goto_url(TB_SHOP_URL."/myinquiry.php");
exit;

?>