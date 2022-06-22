<?php
if(!defined('_TUBEWEB_')) exit; // 개별 페이지 접근 불가
include_once(TB_SHOP_PATH.'/settle_inicis.inc.php');

$resultMap		=	get_session('resultMap');


//오토빌링 데이터일 경우
if($_POST["billkey"] != ""){
	
	//오토빌 승인 요청 처리 
	if($default['de_card_test']) {
		// 테스트
		$default['de_inicis_mid'] = 'INIBillTst';
		$default['de_inicis_admin_key'] = '1111';
		$default['de_inicis_bill_key'] = 'rKnPljRn5m6J9Mzz';		
	}else{
		$default['de_inicis_bill_key'] = '8hnSF5JCNUnqcHMA';	
	}

	//결제 방법에 따른 변수 처리 
	if($_POST["od_settle_case"] == "신용카드"){
		$m_pgId					=	"INIpayBill";
		$m_payMethod		=	"Card";	
	}else{
		$m_pgId					=	"INIpayATCD";	
		$m_payMethod		=	"HPP";	
	}

	$page_return_url			=	TB_SHOP_URL.'/orderinicis.php?od_id='.$od_id;

	$timestamp				=	TB_TIME_YHS;

	$api_url						=	"https://iniapi.inicis.com/api/v1/billing";

	//빌링 승인 요청 API 호출
	$parm_data["type"]					=	"Billing";
	$parm_data["paymethod"]		=	$m_payMethod;
	$parm_data["timestamp"]		=	$timestamp;
	$parm_data["clientIp"]				=	$_SERVER['REMOTE_ADDR'];
	$parm_data["mid"]					=	$default['de_inicis_mid'];
	$parm_data["url"]					=	"https://".$_SERVER['SERVER_NAME'];
	$parm_data["moid"]				=	$od_id;
	$parm_data["goodName"]		=	$_POST["goodname"];
	$parm_data["buyerName"]		=	$_POST["od_name"];
	$parm_data["buyerEmail"]		=	$_POST["buyeremail"];
	$parm_data["buyerTel"]			=	$_POST["od_hp"];
	$parm_data["price"]				=	$_POST["price"];

	$parm_data["billKey"]				=	$_POST["billkey"];


	$parm_data["authentification"]		=	"00";
	$parm_data["currency"]				=	"WON";

	if($_POST["od_settle_case"] == "신용카드"){
		$parm_data["cardQuota"]			=	$cardquota;
		$parm_data["quotaInterest"]		=	0;

	}	

	$params								=	$default['de_inicis_bill_key'].$parm_data["type"].$m_payMethod.$timestamp.$_SERVER['REMOTE_ADDR'].$default['de_inicis_mid'].$od_id.$_POST["price"].$parm_data["billKey"];		//hash(KEY+type+paymethod+timestamp+clientIp+mid+moid+price+billKey)
	$sign										=	hash("sha512",trim($params));
	$parm_data["hashData"]			=	$sign;

	
	$postdata	=	http_build_query($parm_data);
	
	//echo "평문 : ".$params."<br />";
	//echo "sign : ".$parm_data["hashData"]."<br />";

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $api_url);
	curl_setopt($ch, CURLOPT_HEADER, 0); // 헤더 출력 여부
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1000);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Content-type: application/x-www-form-urlencoded;charset=utf-8'
	  ));

	$output	= curl_exec($ch);
	curl_close($ch);

	$data = json_decode($output,true);


	if($data["resultCode"] == "00"){

		//최종결제요청 결과 성공 DB처리
		$tno				=	$data["tid"];
		$amount		=	$data["price"];
		$app_time		=	$data["payDate"].$data["payTime"];
		$pay_method	=	$m_payMethod;
		$pay_type		=	$PAY_METHOD[$pay_method];
		$commid		=	'';
		$mobile_no	=	$parm_data["buyerTel"];
		$app_no			=	$data["payAuthCode"];
		$card_name	=	$CARD_CODE[$data["cardCode"]];
		$billkey			=	$parm_data["billKey"];

	}else{
	    alert('오류 : '.$data["resultCode"].' 코드 : '.$data["resultMsg"], $page_return_url);
	}

}else{


	if( strcmp('0000', $resultMap['resultCode']) == 0 ) {

		//오토빌링 빌링 요청
		if($resultMap['CARD_BillKey'] != ""){

			//오토빌 승인 요청 처리 
			if($default['de_card_test']) {
				// 테스트
				$default['de_inicis_mid'] = 'INIBillTst';
				$default['de_inicis_admin_key'] = '1111';
				$default['de_inicis_bill_key'] = 'rKnPljRn5m6J9Mzz';
			}else{
				$default['de_inicis_bill_key'] = '8hnSF5JCNUnqcHMA';	
			}

		
			//결제 방법에 따른 변수 처리 
			if($_POST["od_settle_case"] == "신용카드"){
				$m_pgId					=	"INIpayBill";
				$m_payMethod		=	"Card";	
			}else{
				$m_pgId					=	"INIpayATCD";	
				$m_payMethod		=	"HPP";	
			}

			$page_return_url			=	TB_SHOP_URL.'/orderinicis.php?od_id='.$od_id;

			$timestamp				=	TB_TIME_YHS;

			$api_url						=	"https://iniapi.inicis.com/api/v1/billing";

			//빌링 승인 요청 API 호출
			$parm_data["type"]					=	"Billing";
			$parm_data["paymethod"]		=	$m_payMethod;
			$parm_data["timestamp"]		=	$timestamp;
			$parm_data["clientIp"]				=	$_SERVER['REMOTE_ADDR'];
			$parm_data["mid"]					=	$default['de_inicis_mid'];
			$parm_data["url"]					=	TB_URL;
			$parm_data["moid"]				=	$od_id;
			$parm_data["goodName"]		=	$_POST["goodname"];
			$parm_data["buyerName"]		=	$_POST["od_name"];
			$parm_data["buyerEmail"]		=	$_POST["buyeremail"];
			$parm_data["buyerTel"]			=	$_POST["od_hp"];
			$parm_data["price"]				=	$_POST["price"];

			if($_POST["od_settle_case"] == "신용카드"){
				$parm_data["billKey"]				=	$resultMap['CARD_BillKey'];
			}else{
				$parm_data["billKey"]				=	$resultMap['HPP_Billkey'];
			}

			$parm_data["authentification"]		=	"00";
			$parm_data["currency"]				=	"WON";

			if($_POST["od_settle_case"] == "신용카드"){
				$parm_data["cardQuota"]			=	$resultMap['CARD_Quota'];
				$parm_data["quotaInterest"]		=	$resultMap['CARD_Interest'];

			}	


			$params								=	$default['de_inicis_bill_key'].$parm_data["type"].$m_payMethod.$timestamp.$_SERVER['REMOTE_ADDR'].$default['de_inicis_mid'].$od_id.$_POST["price"].$parm_data["billKey"];		//hash(KEY+type+paymethod+timestamp+clientIp+mid+moid+price+billKey)
			$sign										=	hash("sha512",trim($params));
			$parm_data["hashData"]			=	$sign;
	
			
			$postdata	=	http_build_query($parm_data);
			

			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL, $api_url);
			curl_setopt($ch, CURLOPT_HEADER, 0); // 헤더 출력 여부
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1000);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Content-type: application/x-www-form-urlencoded;charset=utf-8'
			  ));

			$output	= curl_exec($ch);
			curl_close($ch);

			$data = json_decode($output,true);


			if($data["resultCode"] == "00"){

				//최종결제요청 결과 성공 DB처리
				$tno				=	$data["tid"];
				$amount		=	$data["price"];
				$app_time		=	$data["payDate"].$data["payTime"];
				$pay_method	=	$m_payMethod;
				$pay_type		=	$PAY_METHOD[$pay_method];
				$commid		=	'';
				$mobile_no	=	$parm_data["buyerTel"];
				$app_no			=	$data["payAuthCode"];
				$card_name	=	$CARD_CODE[$data["cardCode"]];
				$billkey			=	$parm_data["billKey"];

			}else{
				alert('오류 : '.$data["resultCode"].' 코드 : '.$data["resultMsg"], $page_return_url);
			}

		
		}else{

			//최종결제요청 결과 성공 DB처리
			$tno					=	$resultMap['tid'];
			$amount			=	$resultMap['TotPrice'];
			$app_time			=	$resultMap['applDate'].$resultMap['applTime'];
			$pay_method		=	$resultMap['payMethod'];
			$pay_type			=	$PAY_METHOD[$pay_method];
			$depositor			=	$resultMap['VACT_InputName'];
			$commid			=	'';
			$mobile_no		=	$resultMap['HPP_Num'];
			$app_no				=	$resultMap['applNum'];
			$card_name		=	$CARD_CODE[$resultMap['CARD_Code']];

			switch($pay_type) {
				case '계좌이체':
					$bank_name = $BANK_CODE[$resultMap['ACCT_BankCode']];
					if($default['de_escrow_use'] == 1)
						$escw_yn         = 'Y';
					break;
				case '가상계좌':
					$bankname  = $BANK_CODE[$resultMap['VACT_BankCode']];
					$account   = $resultMap['VACT_Num'].' '.$resultMap['VACT_Name'];
					$app_no    = $resultMap['VACT_Num'];
					if($default['de_escrow_use'] == 1)
						$escw_yn         = 'Y';
					break;
				default:
					break;
			}		
		}


	}else{
		die($resultMap['resultMsg'].' 코드 : '.$resultMap['resultCode']);
	}

}


?>