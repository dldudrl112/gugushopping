<?php
include_once("./_common.php");

$tb['title'] = '선생님 선택';
include_once(TB_ADMIN_PATH."/admin_head.php");

$query_string = $qstr;
$q1 = $query_string;
$q2 = $query_string."&page=$page";

$sql_common = " from shop_teacher ";
$sql_search = " where use_type = 1 ";

if(!$orderby) {
    $filed = "index_no";
    $sod = "desc";
} else {
	$sod = $orderby;
}

$sql_order = " order by $filed $sod ";

// 테이블의 전체 레코드수만 얻음
$sql = " select count(*) as cnt $sql_common $sql_search ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = 30;
$total_page = ceil($total_count / $rows); // 전체 페이지 계산
if($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함
$num = $total_count - (($page-1)*$rows);

$sql = " select * $sql_common $sql_search $sql_order limit $from_record, $rows ";
$result = sql_query($sql);
?>

<h1 class="newp_tit"><?php echo $tb['title']; ?></h1>
<div class="new_win_body">
	<div class="local_ov mart20">
		전체 : <b class="fc_197"><?php echo number_format($total_count); ?></b> 건 조회
	</div>
	<div class="tbl_head01">
		<table>
		<colgroup>
			<col class="w50">
			<col class="w80">
			<col>
			<col class="w60">
		</colgroup>
		<thead>
		<tr>
			<th scope="col">번호</th>
			<th scope="col"><?php echo subject_sort_link('name',$q2); ?>이름</a></th>
			<th scope="col">상담분야</th>
			<th scope="col">선택</th>
		</tr>
		</thead>
		<?php
		for($i=0; $row=sql_fetch_array($result); $i++) {
			if($i==0)
				echo '<tbody class="list">'.PHP_EOL;

			$bg = 'list'.($i%2);
		?>
		<tr class="<?php echo $bg; ?>">
			<td><?php echo $num--; ?></td>
			<td><?php echo $row['name']; ?></td>
			<td class="tal"><?php echo $row['counseling']; ?></td>
			<td><button type="button" onClick="yes('<?php echo $row['index_no']; ?>', '<?php echo $row['name']; ?>')" class="btn_small grey">선택</button></td>
		</tr>
		<?php
		}
		if($i==0)
			echo '<tbody><tr><td colspan="4" class="empty_table">자료가 없습니다.</td></tr>';
		?>
		</tbody>
		</table>
	</div>

	<?php
	echo get_paging($config['write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$q1.'&page=');
	?>
</div>

<script>
function yes(tc_code,name){
	opener.document.fregform.tc_code.value = tc_code;
	opener.document.fregform.name.value = name;
	self.close();
}
</script>

<?php
include_once(TB_ADMIN_PATH.'/admin_tail.sub.php');
?>