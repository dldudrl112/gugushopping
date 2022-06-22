<?php
include_once("./_common.php");

check_demo();

check_admin_token();


if($w == "u") {

	$value['answer']		= addslashes($_POST['answer']);

	
	update("shop_goods_review",$value,"where index_no='$gr_id'");

	goto_url(TB_ADMIN_URL."/goods.php?code=review_form&w=u&gr_id=$gr_id$qstr&page=$page");
}
?>