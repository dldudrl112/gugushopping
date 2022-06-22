<?php
include_once("./_common.php");

check_demo();

check_admin_token();

$count	=	count($_POST['chk']);

if(!$count) {
	alert($_POST['act_button']." 하실 항목을 하나 이상 체크하세요.");
}

if($_POST['act_button'] == "선택수정")
{
	for($i=0; $i<$count; $i++)
	{
		// 실제 번호를 넘김
		$k = $_POST['chk'][$i];

		$sql = "update shop_teacher 
				   set	use_type = '{$_POST['tc_use'][$k]}'
				 where index_no = '{$_POST['tc_no'][$k]}'";
		sql_query($sql);
	}
} 
else if($_POST['act_button'] == "선택삭제") 
{
	for($i=0; $i<$count; $i++)
	{
		// 실제 번호를 넘김
		$k = $_POST['chk'][$i];

		$tc_no = trim($_POST['tc_no'][$k]);

		$row = sql_fetch("select * from shop_teacher where index_no = '$tc_no'");

		sql_query("delete from shop_teacher where index_no='$tc_no' ");
	}
}

goto_url(TB_ADMIN_URL."/member.php?$q1&page=$page");
?>