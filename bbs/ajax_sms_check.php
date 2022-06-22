<?php
define('_PURENESS_', true);
include_once("./_common.php");

$check_type			=	trim($_POST['check_type']);


//SMS 인증 전송
if($check_type == "send"){

	$recv_phone			=	trim($_POST['recv_phone']);
	$mem					=	sql_fetch("select count(index_no) as cnt from shop_member where cellphone = '".$recv_phone."'");

	if($mem["cnt"] < 1){
		echo "N";
		exit;
	}


	//SMS 확인 문자전송
	$cert_code			=	generateRandomString(6);

	//세션 생성
	set_session('ss_cert_code', $cert_code);

	$sms_content		=	"[".$config["shop_name"]."] 인증번호는 ".$cert_code." 입니다. 정확히 입력히주세요.";

	sms_member_send($recv_phone,$sms_content,'00','','');

	echo $cert_code;
	exit;

}

//SMS 인증 전송
if($check_type == "cert"){
	
	$cert_code				=	trim($_POST['cert_code']);

	//인증번호 체크 
	$regist_cert_code	=	get_session('ss_cert_code');

	if($cert_code == $regist_cert_code){

		//세션 초기화
		set_session('ss_cert_code',"");
		echo "Y";
		exit;

	}else{
		echo "N";	
		exit;
	}

}

?>