<?php
include_once("./_common.php");
include_once(TB_LIB_PATH.'/mailer.lib.php');

$app_data		=	get_session('resultMap');


// 데이터 체크
$error = "";
$sql		=	" select * from shop_point_order_data where od_id = '".$app_data["MOID"]."' ";
$row		=	sql_fetch($sql);

$data		=	unserialize(base64_decode($row['dt_data']));

if(!$data["od_id"]){
    $error .= "주문데이터를 조회할 수 없습니다.";
    alert($error);
	exit;
}

if(in_array($data["paymethod"], array('무통장','포인트'))) {
    alert("올바른 방법으로 이용해 주십시오.", TB_URL);
	exit;
}


if($data["paymethod"] == "KAKAOPAY"){

}else{
	//이니시스 결제 결과
	$tno			    =		$app_data['tid'];
	$pg_price		=		$app_data['TotPrice'];
	$app_no			=		$app_data['applNum'];
	$od_pg			=		"inicis";
}

// 주문금액과 결제금액이 일치하는지 체크
if($tno) {
    if((int)$data["tot_price"] !== (int)$pg_price) {
        $cancel_msg = '결제금액 불일치';

        switch($od_pg) {
            case 'lg':
                include TB_SHOP_PATH.'/lg/xpay_cancel.php';
                break;
            case 'inicis':
                include TB_SHOP_PATH.'/inicis/inipay_cancel.php';
                break;
            case 'KAKAOPAY':
                $_REQUEST['TID']					=	$tno;
                $_REQUEST['Amt']					=	$pg_price;
                $_REQUEST['CancelMsg']      =	$cancel_msg;
                $_REQUEST['PartialCancelCode'] = 0;
                include TB_SHOP_PATH.'/kakaopay/kakaopay_cancel.php';
                break;
            case 'kcp':
                include TB_SHOP_PATH.'/kcp/pp_ax_hub_cancel.php';
                break;
        }

        die("Receipt Amount Error");
    }
}

if($data["tot_point"]  > 0 ){ 

	//포인트 등록
	insert_point($data['email'], $data["tot_point"], '포인트 충전(주문코드 : '.$app_data["MOID"].') ');

	//결제 완료시 알림 데이터 등록
	$memo	=	number_format($data["tot_point"])."P  포인트 충전이 정상적으로 처리되었습니다.";
	boardRegData('43',$data["email"], $memo);

}

// 세션정보 삭제
set_session('resultMap', '');
set_session('payment_price', '');
set_session('payment_point', '');
set_session('payment_od_id', '');
set_session('ss_order_inicis_id', '');

goto_url(TB_SHOP_URL.'/point.php');

?>
<html>
    <head>
        <title>주문정보 기록</title>
        <script>
            // 결제 중 새로고침 방지 샘플 스크립트 (중복결제 방지)
            function noRefresh()
            {
                /* CTRL + N키 막음. */
                if((event.keyCode == 78) && (event.ctrlKey == true))
                {
                    event.keyCode = 0;
                    return false;
                }
                /* F5 번키 막음. */
                if(event.keyCode == 116)
                {
                    event.keyCode = 0;
                    return false;
                }
            }

            document.onkeydown = noRefresh ;
        </script>
    </head>
</html>