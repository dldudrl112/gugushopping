<?php
include_once('./_common.php');
if(isset($isDev) && $isDev) {
    $default['de_card_test']= 1;
}

if($default['de_card_test']) {
	$default['de_inicis_mid'] = 'INIBillTst';
	$default['de_inicis_admin_key'] = '1111';
	$default['de_inicis_sign_key'] = 'b09LVzhuTGZVaEY1WmJoQnZzdXpRdz09';		
}


$sql = " select * from shop_order_data where od_id = '$oid' ";
$row = sql_fetch($sql);

$data = unserialize(base64_decode($row['dt_data']));



$order_action_url		=	TB_HTTPS_SHOP_URL.'/orderformresult.php';
$page_return_url		=	TB_SHOP_URL.'/orderinicis.php?od_id='.$oid;


if($oid != $_POST["orderid"]){
    alert('주문 정보가 올바르지 않습니다.\\n\\n올바른 방법으로 이용해 주십시오.', $page_return_url);
}

if($_POST['resultcode'] != '00')
    alert('오류 : '.$_POST['resultmsg'].' 코드 : '.$_POST['resultcode'], $page_return_url);


$billkey			=	$_POST["billkey"];			//	빌링키
$authkey		=	$_POST["authkey"];		//	빌링인증 트렉잭션 ID
$cardcd			=	$_POST["cardcd"];			//	카드코드
$cardkind		=	$_POST["cardkind"];		//	카드 구분(0:개인, 1:법인)
$checkFlag	=	$_POST["CheckFlag"];	//	카드 종류(0:신용, 1: 체크, 2: 기프트)

$PAY = array_map('trim', $row);

// TID, AMT 를 세션으로 주문완료 페이지 전달
$hash	=	md5($_POST['tid'].$_POST['mid'].$data['use_price']);
set_session('P_TID',  $_POST['tid']);
set_session('P_AMT',  $data['use_price']);
set_session('P_HASH', $hash);




$tb['title'] = 'KG 이니시스 결제';
$tb['body_script'] = ' onload="setPAYResult();"';
include_once(TB_MPATH.'/head.sub.php');

$exclude = array('billkey', 'authkey','cardcd', 'cardkind', 'checkFlag');

echo '<form name="forderform" method="post" action="'.$order_action_url.'" autocomplete="off">'.PHP_EOL;

echo make_order_field($data, $exclude);

echo '<input type="hidden" name="billkey"      value="'.$billkey.'">'.PHP_EOL;
echo '<input type="hidden" name="authkey"      value="'.$authkey.'">'.PHP_EOL;
echo '<input type="hidden" name="cardcd"      value="'.$cardcd.'">'.PHP_EOL;
echo '<input type="hidden" name="cardkind"      value="'.$cardkind.'">'.PHP_EOL;
echo '<input type="hidden" name="checkFlag"      value="'.$checkFlag.'">'.PHP_EOL;

echo '</form>'.PHP_EOL;
?>

<div id="pay_working" style="display:none;">
     <span style="display:block;text-align:center;margin-top:120px"><img src="<?php echo TB_MSHOP_URL; ?>/img/loading.gif" alt=""></span>
    <span style="display:block;text-align:center;margin-top:10px;font-size:14px">주문완료 중입니다. 잠시만 기다려 주십시오.</span>
</div>

<script type="text/javascript">
function setPAYResult() {
    setTimeout( function() {
        document.forderform.submit();
    }, 300);
}
</script>

<?php
include_once(TB_MPATH.'/tail.sub.php');
?>