<?php
include_once('./_common.php');
include_once(TB_LIB_PATH.'/json.lib.php');

if($default['de_pg_service'] != 'inicis')
    die(json_encode(array('error'=>'올바른 방법으로 이용해 주십시오.')));

$orderNumber	=	get_session('ss_order_inicis_id');
$price				=	preg_replace('#[^0-9]#', '', $_POST['price']);

if(strlen($price) < 1)
    die(json_encode(array('error'=>'가격이 올바르지 않습니다.')));

if(isset($isDev) && $isDev){
    $default['de_card_test']= 1;
}

if($default['de_card_test']) {
	$default['de_inicis_mid'] = 'INIBillTst';
	$default['de_inicis_admin_key'] = '1111';
	$default['de_inicis_sign_key']	=	'SHVEb3RnM1JIdG04cUo0KzVDQTh0Zz09';		
	$default['de_inicis_lite_key']	=	'b09LVzhuTGZVaEY1WmJoQnZzdXpRdz09';		
}else{
	$default['de_inicis_lite_key']	=	'VlYxUlRaT0VvaTB3ZlFxcDBhL3Z3UT09';		
}


/*
  //*** 위변조 방지체크를 signature 생성 ***
  oid, price, timestamp 3개의 키와 값을
  key=value 형식으로 하여 '&'로 연결한 하여 SHA-256 Hash로 생성 된값
  ex) oid=INIpayTest_1432813606995&price=819000&timestamp=2012-02-01 09:19:04.004
 * key기준 알파벳 정렬
 * timestamp는 반드시 signature생성에 사용한 timestamp 값을 timestamp input에 그대로 사용하여야함
 */

$params	=		$default['de_inicis_mid'].$orderNumber.TB_TIME_YHS.$default['de_inicis_lite_key'];

$sign			=	hash("sha256", $params);

die(json_encode(array('error'=>'', 'mKey'=>$params, 'timestamp'=>TB_TIME_YHS, 'sign'=>$sign)));
?>