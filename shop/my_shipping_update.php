<?php
include_once("./_common.php");


if (!$od_id) {
    alert_close("정상적인 경로가 아닙니다.");
    exit;
}


$od = sql_fetch("select * from shop_order where od_id = '$od_id'");


if ($od["index_no"]) {
    include_once(TB_PATH . "/classes/Subscription.php");

    if ($od['subscription_id']) {
        $subscription= Subscription::getInstance()->show($od['subscription_id']);
        if(isset($_POST['io_id']) && $subscription['io_id'] != $io_id){
            Subscription::getInstance()->update(['io_id'=> $io_id]);//구독옵션값 변경
            $orderSql= "update shop_order set shop_memo = CONCAT(shop_memo,\"\\고객 옵션 변경({$io_id}) - ".TB_TIME_YMDHIS."\") where od_id = '$od_id'";
            sql_query($orderSql, false);
//            sql_query("update shop_cart set io_id= '{$io_id}' where od_id= '{$od_id}' and gs_id= '{$subscription['gs_id']}'");//카트 옵션값 변경
        }
    }
    // 주문서에 UPDATE
//    $sql = " update shop_order
//				set b_name		 = '$b_name'
//				  , b_cellphone	 = '" . implode("|", $cellphone) . "'
//				  , b_zip	 = '$b_zip'
//				  , b_addr1	 = '$b_addr1'
//				  , b_addr2	 = '$b_addr2'
//				  , b_addr3	 = '$b_addr3'
//				  , b_addr_jibeon	 = '$b_addr_jibeon'
//				  , memo = '".addslashes($_POST['memo'])."'
//				  {$changeOption}
//			  where od_id = '$od_id'";
//    sql_query($sql, false);

    //기본배송지 저장
    if ($delivery == 1) {

        $sql = " update shop_member
					set 
					   zip	 = '$b_zip'
					  , addr1	 = '$b_addr1'
					  , addr2	 = '$b_addr2'
					  , addr3	 = '$b_addr3'
					  , addr_jibeon	 = '$b_addr_jibeon'
				  where id = '" . $member["id"] . "'";
        sql_query($sql, false);

    }

    if ($od['subscription_id']) {
        Subscription::getInstance()->updateOrderData([
            'b_name' => $b_name,
            'b_cellphone' => implode("|", $cellphone),
            'b_zip' => $b_zip,
            'b_addr1' => $b_addr1,
            'b_addr2' => $b_addr2,
            'b_addr3' => $b_addr3,
            'b_addr_jibeon' => $b_addr_jibeon,
            'memo' => addslashes($_POST['memo'])
        ], $od['subscription_id']);
    }
}

alert_close("배송지 변경이 완료되었습니다.");
exit;