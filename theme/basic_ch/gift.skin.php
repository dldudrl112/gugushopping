<?php
if(!defined('_TUBEWEB_')) exit;

include_once(TB_THEME_PATH.'/aside_my.skin.php');
?>

<div id="con_lf">
	<h2 class="pg_tit">
		<span><?php echo $tb['title']; ?></span>
		<p class="pg_nav">HOME<i>&gt;</i>我的页面<i>&gt;</i><?php echo $tb['title']; ?></p>
	</h2>

	<form name="fcoupon" id="fcoupon" method="post" action="<?php echo $form_action_url; ?>" onsubmit="return fcoupon_submit(this);" autocomplete="off">
	<input type="hidden" name="token" value="<?php echo $token; ?>">

	<p class="fc_e06 marb7">
		※ 优惠券编号认证结束后可实时累计积分并立即使用.
	</p>
	<p class="cp_txt_bx bt">
		1. 现金兑换券及不支持退款.<br>
		2. 券号码是对/区分大小写,可以获得的编号请输入真实.
	</p>

	<p class="cp_txt_bx tac">
		<input type="text" name="gi_num1" required itemname="优惠券号码" maxlength="4" class="frm_cp required" onkeyup="if(this.value.length==4) document.fcoupon.gi_num2.focus();">
		<span>-</span>
		<input type="text" name="gi_num2" required itemname="优惠券号码" maxlength="4" class="frm_cp required" onkeyup="if(this.value.length==4) document.fcoupon.gi_num3.focus();">
		<span>-</span>
		<input type="text" name="gi_num3" required itemname="优惠券号码" maxlength="4" class="frm_cp required" onkeyup="if(this.value.length==4) document.fcoupon.gi_num4.focus();">
		<span>-</span>
		<input type="text" name="gi_num4" required itemname="优惠券号码" maxlength="4" class="frm_cp required">
		<input type="submit" value="认证" class="btn_lsmall wset">
	</p>
	</form>

	<script>
	function fcoupon_submit(f) {
		if(confirm("想要认证请点击'确认'按钮!") == false)
			return false;

		return true;
	}
	</script>

	<?php
	$sql_common = " from shop_gift ";
	$sql_search = " where mb_id = '$member[id]' ";

	if($stx) {
		$sql_search .= " and gi_num like '$stx%' ";
	}

	$sql = " select count(*) as cnt $sql_common $sql_search ";
	$row = sql_fetch($sql);
	$total_count = $row['cnt'];

	$rows = 30;
	$total_page = ceil($total_count / $rows); // 全页计算
	if($page == "") { $page = 1; } // 没有页面的话第一页 (1 页)
	$from_record = ($page - 1) * $rows; // 
获取开始列
	$num = $total_count - (($page-1)*$rows);

	$sql = " select * $sql_common $sql_search order by no desc limit $from_record, $rows ";
	$result = sql_query($sql);
	?>

	<div class="top_sch mart20">
		<form name="fsearch2" id="fsearch2" method="post">
		<p class="fl padt10">全体 <b class="fc_255"><?php echo number_format($total_count); ?></b>有优惠券历史.</p>
		<p class="fr">
			<input type="text" name="stx" value="<?php echo $stx; ?>" class="frm_input" placeholder="优惠券号码">
			<input type="submit" value="
取回" class="btn_small grey">
		</p>
		</form>
	</div>

	<div class="tbl_head02 tbl_wrap">
		<table>
		<colgroup>
			<col width="6%">
			<col>
			<col width="12%">
			<col width="11%">
			<col width="11%">
			<col width="8%">
			<col width="18%">
		</colgroup>
		<thead>
		<tr>
			<th scope="col">号码</th>
			<th scope="col">优惠券编号</th>
			<th scope="col">金额</th>
			<th scope="col">起始日</th>
			<th scope="col">终止日</th>
			<th scope="col">认证状态</th>
			<th scope="col">登记日
</th>
		</tr>
		</thead>
		<tbody>
		<?php
		for($i=0; $row=sql_fetch_array($result); $i++) {
			if(is_null_time($row['mb_wdate'])) {
				$row['mb_wdate'] = '';
			}

			$bg = 'list'.($i%2);
		?>
		<tr class="<?php echo $bg; ?>">
			<td class="tac"><?php echo $num--; ?></td>
			<td><?php echo $row['gi_num']; ?></td>
			<td class="tac"><?php echo display_price($row['gr_price']); ?></td>
			<td class="tac"><?php echo $row['gr_sdate']; ?></td>
			<td class="tac"><?php echo $row['gr_edate']; ?></td>
			<td class="tac"><?php echo $row['gi_use']?'yes':''; ?></td>
			<td class="tac"><?php echo $row['mb_wdate']; ?></td>
		</tr>
		<?php
		}
		if($i==0)
			echo '<tr><td colspan="7" class="empty_list">没有明细。</td></tr>';
		?>
		</tbody>
		</table>
	</div>

	<?php
	echo get_paging($config['write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$qstr.'&page=');
	?>
</div>
