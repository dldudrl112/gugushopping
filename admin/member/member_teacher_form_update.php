<?php
include_once("./_common.php");

check_demo();

check_admin_token();


if(!$_POST['id']) {
	alert('회원아이디가 없습니다. 올바른 방법으로 이용해 주십시오.');
}


$mb	=	get_member($_POST['id']);

$index_no	=	$_POST["index_no"];

unset($value);

$value['mb_code']					=	$mb['index_no'];					//회원코드
$value['name']						=	$mb['name'];						//회원명

$value['counseling']				=	$_POST['counseling'];			//상담분야
$value['counseling_type']		=	$_POST['counseling_type'];	//상담구분
$value['counseling_time']		=	$_POST['counseling_time'];	//상담시간

$value['teacher_history']			=	addslashes($_POST['teacher_history']); // 선생님 이력

if($w == '') {

	$value['reg_date']					=	TB_TIME_YMDHIS; //가입일

	insert("shop_teacher", $value);
}else if($w == 'u'){	

	$value['mod_date']					=	TB_TIME_YMDHIS; //수정일

	update("shop_teacher", $value, "where index_no='$index_no'");
	goto_url(TB_ADMIN_URL."/member.php?code=teacher_form&w=u&index_no=$index_no");
}


alert("등록이 완료 되었습니다.", TB_ADMIN_URL."/member.php?code=teacher_list");
?>