<?php
define('_PURENESS_', true);
include_once("./_common.php");

$mb = sql_fetch("select  *  from shop_member where nickname = TRIM('$mb_name')");

if($mb['nickname']) {
	echo 'N';	//이미 사용중인 닉네임 입니다.
} else {
	echo 'Y';	//사용하셔도 좋은 닉네임 입니다.
}

?>