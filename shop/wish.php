<?php
include_once("./_common.php");

if(TB_IS_MOBILE) {
	goto_url(TB_MSHOP_URL.'/wish.php');
}

if(!$is_member) {
	goto_url(TB_BBS_URL.'/login.php?url='.$urlencode);
}

$tb['title'] = '찜한상품';


// include_once("./_head.php");

$ca_id				=	trim($_REQUEST['ca_id']);
$len					=	strlen($ca_id);
$sql_search		=	" and left(c.gcate,$len)='$ca_id' ";

$sql  = " select a.wi_id, a.wi_time, a.gs_id, b.* 
            from shop_wish a 
			left join shop_goods b ON ( a.gs_id = b.index_no )
			left join shop_goods_cate c ON ( b.index_no = c.gs_id )
		   where a.mb_id = '{$member['id']}' $sql_search 
		   group by a.gs_id
		   order by a.wi_id desc ";
$result = sql_query($sql);
$wish_count = sql_num_rows($result);

include_once(TB_THEME_PATH.'/wish.skin.php');

// include_once("./_tail.php");
?>