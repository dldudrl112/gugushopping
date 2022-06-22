<?php
define('_PURENESS_', true);
include_once("./_common.php");

//if(preg_match("/[^0-9a-z_]+/i", $mb_id)) {
if(!preg_match("/^[_\.0-9a-zA-Z-]+@([0-9a-zA-Z][0-9a-zA-Z-]+\.)+[a-zA-Z]{2,6}$/i", $mb_id)) {
    echo '이메일 형식의 아이디를 입력하세요.';
//} else if(strlen($mb_id) < 3) {
//    echo '최소 3자이상 입력하세요.';
}else{
	$mb = get_member($mb_id);

    if($mb['id']) {
        echo '이미 사용중인 아이디 입니다.';
    } else {
        if(preg_match("/[\,]?{$mb_id}/i", $config['prohibit_id']))
			 echo '예약어로 금지된 회원아이디 입니다.';
        else
             echo '사용하셔도 좋은 아이디 입니다.';
    }
}
?>