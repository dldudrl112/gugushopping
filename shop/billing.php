<?php
include_once("./_common.php");

$od = sql_fetch("select O.*, G.gname from shop_order O left join shop_goods G on O.gs_id= G.index_no where O.od_id='".$_REQUEST["od_id"]."'");

if(isset($_GET['pg']) && mb_strtoupper($_GET['pg']) === 'KAKAOPAY' && !isset($_GET['pg_token'])) {
    if(!empty($od['od_app_no'])){
        alert("이미 결제된 주문입니다.", TB_URL);
        return;
    }

    if($_GET['method']==='cancel'){
        alert("결제가 취소되었습니다.", TB_URL);
    }
    if($_GET['method']==='fail'){
        alert("결제에 실패하였습니다.", TB_URL);
    }

    include_once(TB_PATH."/classes/BillingKakaopay.php");
    $pgInfo = isset($isDev) && $isDev ? [] : ['billing_id'=> $default['de_kakaopay_mid'], 'billing_key'=> $default['de_kakaopay_key']];

    $BillingKakaopay= new BillingKakaopay($od, $pgInfo);
    $nextLink= $BillingKakaopay
                ->setUser($member)
                ->setCartByOrder()
                ->init()
                ->updateTID()
                ->getNextUrl();

    if(is_null($nextLink)) {
        alert("PG사와 연결이 원할하지 않습니다.\\n잠시후 이용해주세요.", TB_URL);
        return;
    }
    goto_url($nextLink);

    //$od_app_no
    //od_tno,od_app_no
    //결제 페이지 이동
    return;
}

if(!$od['od_id']) {
    alert("결제할 주문서가 없습니다.");
}

$tb['title'] = '결제하기';

set_session('ss_order_id', $od_id);
set_session('ss_order_inicis_id', $od_id);

$stotal		=	get_order_spay($od_id); // 총계
$tot_price	=	get_session('tot_price'); // 결제금액


$order_action_url = TB_HTTPS_SHOP_URL.'/orderformresult.php';

if(isset($_GET['pg']) && mb_strtoupper($_GET['pg']) === 'KAKAOPAY'){
    //결제 후 처리
    //$_GET['pg_token']
    include_once(TB_PATH."/classes/Subscription.php");
    $isSuccessSubscription= false;
    if(empty($od['subscription_id'])){
        include_once(TB_PATH."/classes/BillingKakaopay.php");
        include_once(TB_PATH."/classes/BillingApi.php");
        $pgInfo = isset($isDev) && $isDev ? [] : ['billing_id'=> $default['de_kakaopay_mid'], 'billing_key'=> $default['de_kakaopay_key']];
        $Billing= new BillingApi(new BillingKakaopay($od, $pgInfo));
        $approve= $Billing->setUser($member)
                            ->approve($_GET['pg_token'])
                            ->value;
        if($Billing->status !== 200){
            $error= !empty($approve) && isset($approve['extras'])?  $approve['extras']['method_result_message'] : 'error';
            die($error);
        }

        if($isDev){
            $default['de_kakaopay_mid']= 'TCSUBSCRIP';
        }
        $isSuccessSubscription= Subscription::getInstance()->setOrderById($od['od_id'])->store()->syncSubscriptionId();
    }
    //$approve['sid']
    include_once(TB_THEME_PATH.'/orderinquiryview.skin.php');
    //include_once(TB_THEME_PATH.'/orderkakaopay.skin.php');
    return;
}

if(!empty($od['subscription_id'])){
    alert("이미 결제된 주문입니다.", TB_URL);
    return;
}

include_once("./_head.php");
include_once(TB_THEME_PATH.'/orderinicis.skin.php');
include_once("./_tail.php");




