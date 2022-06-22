<?php
if(!defined('_TUBEWEB_')) exit;

include_once(TB_THEME_PATH.'/aside_my.skin.php');
?>

<div id="con_lf">
	<h2 class="pg_tit">
		<span><?php echo $tb['title']; ?></span>
		<p class="pg_nav">HOME<i>&gt;</i>My Page<i>&gt;</i><?php echo $tb['title']; ?></p>
	</h2>

	<nav id="tab_cate">
		<ul id="tab_cate_ul">
			<li<?php echo $selected1; ?>><a href="<?php echo TB_SHOP_URL; ?>/coupon.php">Available coupon</a></li>
			<li<?php echo $selected2; ?>><a href="<?php echo TB_SHOP_URL; ?>/coupon.php?sca=1">Completed and expired coupons</a></li>
		</ul>
	</nav>

	<p class="pg_cnt">
		<em>Total <?php echo number_format($total_count); ?>case</em>There is a coupon for .
	</p>

	<div class="tbl_head02 tbl_wrap">
		<table>
		<colgroup>
			<col class="w50">
			<col>
			<col>
			<col>
			<col>
			<col class="w140">
		</colgroup>
		<thead>
		<tr>
			<th scope="col">Number</th>
			<th scope="col">Discount coupon</th>
			<th scope="col">discount rate</th>
			<th scope="col">Available target</th>
			<th scope="col">service term</th>
			<th scope="col">Acquisition date</th>
		</tr>
		</thead>
		<tbody>
		<?php
		for($i=0; $row=sql_fetch_array($result); $i++) {

			// discount rate
			if($row['cp_sale_type'] == '0') {
				if($row['cp_sale_amt_max'] > 0)
					$cp_sale_amt_max = "&nbsp;(Maximum ".display_price($row['cp_sale_amt_max']).")";
				else
					$cp_sale_amt_max = "";

				$sale_amt = $row['cp_sale_percent']. '%' . $cp_sale_amt_max;
			} else {
				$sale_amt = display_price($row['cp_sale_amt']);
			}

			// coupon usage period
			if($row['cp_inv_type'] == '0') {
				if($row['cp_inv_sdate'] == '9999999999') $cp_inv_sdate = 'Unlimited';
				else $cp_inv_sdate = date_conv($row['cp_inv_sdate'],4);

				if($row['cp_inv_edate'] == '9999999999') $cp_inv_edate = 'Unlimited';
				else $cp_inv_edate = date_conv($row['cp_inv_edate'],4);

				if($row['cp_inv_sdate'] == '9999999999' && $row['cp_inv_edate'] == '9999999999')
					$inv_date = 'Unlimited';
				else
					$inv_date = $cp_inv_sdate . " ~ " . $cp_inv_edate;
			} else {
				$inv_date = 'After download ' . $row['cp_inv_day']. 'a day';
			}

			$bg = 'list'.($i%2);
		?>
		<tr class="<?php echo $bg; ?>">
			<td class="tac"><?php echo $num--; ?></td>
			<td><a href='<?php echo TB_SHOP_URL; ?>/coupon_goods.php?lo_id=<?php echo $row['lo_id']; ?>' onclick="win_open(this,'coupon_goods','800','800','yes');return false;"><?php echo get_text(cut_str($row['cp_subject'],44)); ?></a></td>
			<td><?php echo $sale_amt; ?></td>
			<td class="tac"><?php echo $u_part[$row['cp_use_part']]; ?></td>
			<td class="tac"><?php echo $inv_date; ?></td>
			<td class="tac"><?php echo $row['cp_wdate']; ?></td>
		</tr>
		<?php
		}
		if($i == 0)
			echo '<tr><td colspan="6" class="empty_list">We don\'t have any data.</td></tr>';
		?>
		</tbody>
		</table>
	</div>

	<?php
	echo get_paging($config['write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$qstr.'&page=');
	?>
</div>
