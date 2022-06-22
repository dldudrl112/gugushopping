<?php
if(!defined('_TUBEWEB_')) exit; // 개별 페이지 접근 불가
if(isset($isDev) && $isDev) {
    $default['de_card_test']= 1;
}

if($default['de_card_test']) {

    if($default['de_escrow_use'] == 1) {
        // 에스크로결제 테스트
        $default['de_inicis_mid'] = 'iniescrow0';
        $default['de_inicis_admin_key'] = '1111';
        $default['de_inicis_sign_key'] = 'SHVEb3RnM1JIdG04cUo0KzVDQTh0Zz09';
    }else {
        // 일반결제 테스트
        $default['de_inicis_mid'] = 'INIBillTst';
        $default['de_inicis_admin_key'] = '1111';
        $default['de_inicis_sign_key'] = 'SHVEb3RnM1JIdG04cUo0KzVDQTh0Zz09';
    }
    

}else {

    if($default['de_escrow_use'] == 1) {
        // 에스크로결제
        $useescrow = ':useescrow';
    }
    else {
        // 일반결제
        $useescrow = '';
    }
}

$stdpay_js_url = 'https://plugin.inicis.com/pay40_auth.js';

$mid				=	$default['de_inicis_mid'];
$admin_key	=	$default['de_inicis_admin_key'];
$signKey		=	$default['de_inicis_sign_key'];


/* 기타 */
$siteDomain	=	TB_SHOP_URL.'/inicis'; //가맹점 도메인 입력


$returnUrl		=	$siteDomain.'/orderinicis_auto_result.php';

$period			=	"M2";					//월 자동결제
$timestamp	=	TB_TIME_YHS;
$datakey		=	$mid.$od_id.$timestamp.$signKey;
$hashdata		=	hash('sha256', $datakey);


$BANK_CODE = array(
    '03' => '기업은행',
    '04' => '국민은행',
    '05' => '외환은행',
    '07' => '수협중앙회',
    '11' => '농협중앙회',
    '20' => '우리은행',
    '23' => 'SC 제일은행',
    '31' => '대구은행',
    '32' => '부산은행',
    '34' => '광주은행',
    '37' => '전북은행',
    '39' => '경남은행',
    '53' => '한국씨티은행',
    '71' => '우체국',
    '81' => '하나은행',
    '88' => '신한은행',
    'D1' => '동양종합금융증권',
    'D2' => '현대증권',
    'D3' => '미래에셋증권',
    'D4' => '한국투자증권',
    'D5' => '우리투자증권',
    'D6' => '하이투자증권',
    'D7' => 'HMC 투자증권',
    'D8' => 'SK 증권',
    'D9' => '대신증권',
    'DA' => '하나대투증권',
    'DB' => '굿모닝신한증권',
    'DC' => '동부증권',
    'DD' => '유진투자증권',
    'DE' => '메리츠증권',
    'DF' => '신영증권'
);

$CARD_CODE = array(
    '01' => '외환',
    '03' => '롯데',
    '04' => '현대',
    '06' => '국민',
    '11' => 'BC',
    '12' => '삼성',
    '14' => '신한',
    '15' => '한미',
    '16' => 'NH',
    '17' => '하나 SK',
    '21' => '해외비자',
    '22' => '해외마스터',
    '23' => 'JCB',
    '24' => '해외아멕스',
    '25' => '해외다이너스'
);

$PAY_METHOD = array(
    'VCard'      => '신용카드',
    'Card'       => '신용카드',
    'DirectBank' => '계좌이체',
    'HPP'        => '휴대폰',
    'VBank'      => '가상계좌'
);
?>