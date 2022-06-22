<?php
include_once("./_common.php");

if(TB_IS_MOBILE) {
	goto_url(TB_MBBS_URL.'/faq.php');
}

$tb['title'] = 'FAQ';

$sql_common = " from shop_faq ";
$sql_order  = " order by wdate desc ";

$sql = " select count(*) as cnt $sql_common";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$page=$_GET['page'];
$faqcate=$_GET['faqcate'];

$rows = 5;
$total_page = ceil($total_count / $rows); // 전체 페이지 계산
if($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함
$num = $total_count - (($page-1)*$rows);

$sql = " select * $sql_common $sql_search $sql_order limit $from_record, $rows ";
$result = sql_query($sql);
// include_once("./_head.php"); 
include_once(TB_THEME_PATH.'/faq.skin.php');
// include_once("./_tail.php");
?>
