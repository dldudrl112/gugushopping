<?php
include_once('./_common.php');

// 세션에 저장된 토큰과 폼으로 넘어온 토큰을 비교하여 틀리면 에러

$token=$_POST["token"];
$od_id=$_POST["od_id"];

if($token && get_session("ss_token") == $token) {
    // 맞으면 세션을 지워 다시 입력폼을 통해서 들어오도록 한다.
    set_session("ss_token", "");
} else {
    set_session("ss_token", "");
    alert("토큰 에러", TB_URL);
}



$od	=	sql_fetch(" select * from shop_order where od_id = '$od_id' and mb_id = '{$member['id']}' ");
$gs	=	unserialize($od['od_goods']);

$goods_item_count	=	$gs["autobill"];


if(!$od['od_id']) {
    alert("존재하는 주문이 아닙니다.");
}

if(!$od["subscription_id"]){
    alert("존재하는 빌링내역이 없습니다.");
}

include_once(TB_PATH."/classes/Subscription.php");
$Subscription= Subscription::getInstance();
$subscription= $Subscription->show($od["subscription_id"]);
if(empty($subscription) || $subscription['is_subscribed'] !== '1'){
    alert("이미 취소되었습니다.");
}

// 주문취소 가능여부 체크
/*
$od_count1 = $od_count2 = $od_cancel_price = 0;

$sql = " select dan, cancel_price from shop_order where od_id = '$od_id' order by index_no asc ";
$res = sql_query($sql);
while($row=sql_fetch_array($res)) {
	$od_count1++;
	if(in_array($row['dan'], array('1','2','3')))
		$od_count2++;

	$od_cancel_price += (int)$row['cancel_price'];
}

$uid = md5($od['od_id'].$od['od_time'].$od['od_ip']);


if($od_cancel_price > 0 || $od_count1 != $od_count2) {
	alert("취소할 수 있는 주문이 아닙니다.", TB_SHOP_URL."/myinquiry.php");
}
*/

// PG 결제 취소
/*
if($od['od_tno']) {
    switch($od['od_pg']) {
        case 'lg':
            require_once(TB_SHOP_PATH.'/settle_lg.inc.php');
            $LGD_TID = $od['od_tno']; //LG유플러스으로 부터 내려받은 거래번호(LGD_TID)

            $xpay = new XPay($configPath, $CST_PLATFORM);

            // Mert Key 설정
            $xpay->set_config_value('t'.$LGD_MID, $default['de_lg_mert_key']);
            $xpay->set_config_value($LGD_MID, $default['de_lg_mert_key']);
            $xpay->Init_TX($LGD_MID);

            $xpay->Set("LGD_TXNAME", "Cancel");
            $xpay->Set("LGD_TID", $LGD_TID);

            if($xpay->TX()) {
                //1)결제취소결과 화면처리(성공,실패 결과 처리를 하시기 바랍니다.)
 
            } else {
                //2)API 요청 실패 화면처리
                $msg = "결제 취소요청이 실패하였습니다.\\n";
                $msg .= "TX Response_code = " . $xpay->Response_Code() . "\\n";
                $msg .= "TX Response_msg = " . $xpay->Response_Msg();

                alert($msg);
            }
            break;
        case 'inicis':
            include_once(TB_SHOP_PATH.'/settle_inicis.inc.php');


			//오토빌 취소 요청 처리 
			if($default['de_card_test']) {
				// 테스트
				$mid											=		'INIBillTst';
				$default['de_inicis_admin_key']	=		'1111';
				$default['de_inicis_sign_key']		=		'rKnPljRn5m6J9Mzz';		
			}else{
				$default['de_inicis_sign_key']		=	'8hnSF5JCNUnqcHMA';	
			}

            $cancel_msg = iconv_euckr('주문자 본인 취소-'.$cancel_memo);


            $inipay->SetField("type",      "cancel");                        // 고정 (절대 수정 불가)
            $inipay->SetField("mid",       $mid);       // 상점아이디

            $inipay->SetField("admin",     $default['de_inicis_admin_key']); //비대칭 사용키 키패스워드
            $inipay->SetField("tid",       $od['od_tno']);                   // 취소할 거래의 거래아이디
            $inipay->SetField("cancelmsg", $cancel_msg);                     // 취소사유

   
            $inipay->startAction();

            $res_cd  = $inipay->getResult('ResultCode');
            $res_msg = $inipay->getResult('ResultMsg');

            if($res_cd != '00') {
                alert(iconv_utf8($res_msg).' 코드 : '.$res_cd);
            }
            break;
        case 'kcp':
            require_once(TB_SHOP_PATH.'/settle_kcp.inc.php');

            $_POST['tno'] = $od['od_tno'];
            $_POST['req_tx'] = 'mod';
            $_POST['mod_type'] = 'STSC';
            if($od['od_escrow']) {
                $_POST['req_tx'] = 'mod_escrow';
                $_POST['mod_type'] = 'STE2';
                if($od['paymethod'] == '가상계좌')
                    $_POST['mod_type'] = 'STE5';
            }
            $_POST['mod_desc'] = iconv("utf-8", "euc-kr", '주문자 본인 취소-'.$cancel_memo);
            $_POST['site_cd'] = $default['de_kcp_mid'];

            // 취소내역 한글깨짐방지
            setlocale(LC_CTYPE, 'ko_KR.euc-kr');

            include TB_SHOP_PATH.'/kcp/pp_ax_hub.php';

            // locale 설정 초기화
            setlocale(LC_CTYPE, '');
			break;
    }
}
*/


// 주문 취소
/*
$cancel_memo = addslashes(strip_tags($cancel_memo));

$sql = " select od_no, od_billkey from shop_order where od_id = '$od_id' order by index_no asc ";
$result = sql_query($sql);
while($row=sql_fetch_array($result)) {
	change_order_status_6($row['od_no']);
}

// 메모남김

$sql = " update shop_order
			set shop_memo = CONCAT(shop_memo,\"\\n주문자 본인 직접 취소 - ".TB_TIME_YMDHIS." (취소이유 : {$cancel_memo})\")
		 where od_id = '$od_id' ";
sql_query($sql);
*/

//빌링키 취소 처리
$sql = " update shop_order
			set od_billkey = ''
		 where od_billkey = '".$od["od_billkey"]."' and mb_id = '{$member['id']}' ";

sql_query($sql);

$Subscription->update(['is_subscribed'=> 0], $od["subscription_id"]);
//sms_member_send($od['cellphone'],'','02', 5, $od_id);

goto_url(TB_SHOP_URL."/myinquiry.php");