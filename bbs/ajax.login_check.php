<?php
define('_PURENESS_', true);
include_once("./_common.php");

$mb_id				=	trim($_POST['mb_id']);
$mb_password	=	trim($_POST['mb_password']);
if(!$mb_id || !$mb_password)
    alert('회원아이디나 비밀번호가 공백이면 안됩니다.');

$mb = get_member($mb_id);


// 가입된 회원이 아니다. 패스워드가 틀리다. 라는 메세지를 따로 보여주지 않는 이유는
// 회원아이디를 입력해 보고 맞으면 또 패스워드를 입력해보는 경우를 방지하기 위해서입니다.
// 불법사용자의 경우 회원아이디가 틀린지, 패스워드가 틀린지를 알기까지는 많은 시간이 소요되기 때문입니다.
if(!$mb['id'] || !check_password($mb_password, $mb['passwd'])) {
    alert('아이디 또는 패스워드를 확인해 주세요.');
	exit;
}else{
	echo "Y";
	exit;
}

?>