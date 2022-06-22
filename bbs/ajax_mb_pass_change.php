<?php
define('_PURENESS_', true);
include_once("./_common.php");

if(!$member['id']) {
	echo 'N';	//존재하지 않는 아이디 및 정보 
	exit;
}else{

	$passwd			=	trim($_POST['passwd']);

	unset($value);

	$value['passwd']		=	$passwd; //패스워드

	update("shop_member", $value, "where id='$member[id]'");

	echo 'Y';
	exit;
}

?>