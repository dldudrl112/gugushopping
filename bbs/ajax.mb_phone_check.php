<?php
define('_PURENESS_', true);
include_once("./_common.php");

$mb = sql_fetch("select  *  from shop_member where cellphone = TRIM('".replace_tel($mb_phone)."')");

if($mb['cellphone']) {
	echo 'N';		//이미 사용중인 전화번호 입니다.
} else {
	echo 'Y';		//사용하셔도 좋은 전화번호 입니다.
}

?>