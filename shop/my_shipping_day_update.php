<?php
include_once("./_common.php");

$delivery_type = $_POST["delivery_type"];
$od_id		   = $_POST["od_id"];
$chg_date	   = $_POST["chg_date"];


if(!$od_id){
	alert_close("정상적인 경로가 아닙니다.");
	exit;
}

$od	=	sql_fetch("select * from shop_order where od_id = '$od_id'");

if($od["index_no"]){

	if($delivery_type == 0){
			// 주문서에 UPDATE
			$sql = " update shop_order
						set delivery_date = '$chg_date'
					  where od_id = '$od_id'";

			sql_query($sql, false);	
	}else{
			// 주문서에 UPDATE
			$sql = " update shop_order
						set od_time = '$chg_date'
					  where od_id = '$od_id'";
			sql_query($sql, false);	
	}


}

alert_close("배송일 변경이 완료되었습니다.");
exit;

?>