<?php
define('_PURENESS_', true);
include_once("./_common.php");

$od_id = trim($_POST['od_id']);

if(!$is_member) {
	die('회원 전용 서비스입니다.');
}

if(!$od_id) {
	die('잘못된 접근입니다.');
}

$sql = " select count(*) as cnt from shop_order where mb_id = '{$member['id']}' and od_id = '{$od_id}' ";
$row = sql_fetch($sql);

if($row['cnt'] > 0) {
	$sql = " update shop_order set user_ok = 1 where mb_id = '{$member['id']}' and od_id = '{$od_id}' ";
	sql_query($sql);	

	echo "Y";
	exit;
}else{
	echo "N";
	exit;
}
?>