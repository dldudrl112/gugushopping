<?php
if(!defined('_TUBEWEB_')) exit;
?>

<div class="new_win">
    <h1 id="win_title"><?php echo $tb['title']; ?></h1>

	<div class="win_desc marb10">
		<p class="bx-danger">
			*This is a discount coupon you can use when purchasing this product. Download and use when ordering!<br>
			* The coupon issued is <b>[My Page> Coupon management]</b> You can check at .
		</p>
	</div>
		
	<div class="tbl_head01 tbl_wrap bt_nolne">
		<table>
		<colgroup>
			<col class="w80">
			<col>
			<col class="w70">
		</colgroup>
		<tbody>
		<tr>
			<td class="tac"><?php echo get_it_image($gs['index_no'], $gs['simg1'], 60, 60); ?></td>
			<td class="td_name"><?php echo get_text($gs['gname']); ?></td>
			<td class="tac"><?php echo get_price($gs['index_no'])?></td>
		</tr>
		</tbody>
		</table>
	</div>	

	<div class="win_desc mart20">
		<p class="pg_cnt">
			<em>total <?php echo number_format($total_count); ?>the number</em> inquiry
		</p>
	</div>

	<div class="tbl_head01 tbl_wrap">
		<table>
		<colgroup>
			<col>
			<col class="w90">
		</colgroup>
		<thead>
		<tr>
			<th scope="col">Discount coupon name</th>
			<th scope="col">Receive a coupon</th>
		</tr>
		</thead>
		<tbody>
		<?php
		for($i=0; $row=sql_fetch_array($result); $i++) {
			$cp_id = $row['cp_id'];

			$str  = "";
			$str .= "<div>&#183; <strong>".get_text($row['cp_subject'])."</strong></div>";
			if($row['cp_explan'])
				$str .= "<div class='part5 fc_197'>&#183; ".get_text($row['cp_explan'])."</div>";

			// Concurrent use status
			$str .= "<div class='part5 fc_eb7'>&#183; ";
			if(!$row['cp_dups']) {
				$str .= 'Simultaneous use of other coupons on the same order';
			} else {
				$str .= 'Unable to use at the same time as other coupons on the same order (Only this coupon is available)';
			}
			$str .= "</div>";

			// Coupon issue period
			$str .= "<div class='part5'>&#183; download period : ";
			if($row['cp_type'] != '3') {
				if($row['cp_pub_sdate'] == '9999999999') $cp_pub_sdate = '';
				else $cp_pub_sdate = $row['cp_pub_sdate'];

				if($row['cp_pub_edate'] == '9999999999') $cp_pub_edate = '';
				else $cp_pub_edate = $row['cp_pub_edate'];

				if($row['cp_pub_sdate'] == '9999999999' && $row['cp_pub_edate'] == '9999999999')
					$str .= "Unlimited";
				else
					$str .= $cp_pub_sdate." ~ ".$cp_pub_edate;

				// Coupon Issue Day
				if($row['cp_type'] == '1') {
					$str .= "&nbsp;-&nbsp;Every week (".$row['cp_week_day'].")";
				}
			} else {
				$str .= "birthday (".$row['cp_pub_sday']."the other day ~ ".$row['cp_pub_eday']."until after a day)";
			}
			$str .= "</div>";


			// Coupon validity period
			$str .= "<div class='part5'>&#183; Coupon validity period : ";
			if(!$row['cp_inv_type']) {
				// date
				if($row['cp_inv_sdate'] == '9999999999') $cp_inv_sdate = '';
				else $cp_inv_sdate = $row['cp_inv_sdate'];

				if($row['cp_inv_edate'] == '9999999999') $cp_inv_edate = '';
				else $cp_inv_edate = $row['cp_inv_edate'];

				if($row['cp_inv_sdate'] == '9999999999' && $row['cp_inv_edate'] == '9999999999')
					$str .= 'Unlimited';
				else
					$str .= $cp_inv_sdate . " ~ " . $cp_inv_edate ;

				// time zone
				$str .= "&nbsp;(time zone : ";
				if($row['cp_inv_shour1'] == '99') $cp_inv_shour1 = '';
				else $cp_inv_shour1 = $row['cp_inv_shour1'] . "from city to city";

				if($row['cp_inv_shour2'] == '99') $cp_inv_shour2 = '';
				else $cp_inv_shour2 = $row['cp_inv_shour2'] . "up to the hour";

				if($row['cp_inv_shour1'] == '99' && $row['cp_inv_shour2'] == '99') $str .= 'Unlimited';
				else $str .= $cp_inv_shour1 . " ~ " . $cp_inv_shour2 ;
				$str .= ")";
			} else {
				$cp_inv_day = date("Y-m-d",strtotime("+{$row[cp_inv_day]} days",time()));
				$str .= 'After download is complete ' . $row['cp_inv_day']. 'Daily use available, Expiration date('.$cp_inv_day.')';
			}
			$str .= "</div>";

			// Benefit
			$str .= "<div class='part5'>&#183; ";
			if(!$row['cp_sale_type']) {
				if($row['cp_sale_amt_max'] > 0)
					$cp_sale_amt_max = "&nbsp;(Maximum ".display_price($row['cp_sale_amt_max'])."discount to)";
				else
					$cp_sale_amt_max = "";

				$str .= $row['cp_sale_percent']. '% discount' . $cp_sale_amt_max;
			} else {
				$str .= display_price($row['cp_sale_amt']). ' discount';
			}
			$str .= "</div>";

			// Maximum amount
			if($row['cp_low_amt'] > 0) {
				$str .= "<div class='part5'>&#183; ".display_price($row['cp_low_amt'])." When purchasing abnormally</div>";
			}

			// Available target
			$str .= "<div class='part5'>&#183; ".$gw_usepart[$row['cp_use_part']]."</div>";

			$s_upd = "<a href=\"javascript:post_update('".TB_SHOP_URL."/pop_coupon_update.php', '$cp_id');\" class=\"btn_small red\">Download</a>";

			$bg = 'list'.($i%2);
		?>
		<tr class="<?php echo $bg; ?>">
			<td><?php echo $str; ?></td>
			<td class="tac"><?php echo $s_upd; ?></td>
		</tr>
		<?php
		}
		if($i==0)
			echo '<tr><td colspan="2" class="empty_list">have no data.</td></tr>';
		?>
		</tbody>
		</table>
	</div>

	<?php
	echo get_paging($config['write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?page=');
	?>

    <div class="win_btn">
		<a href="javascript:window.close();" class="btn_lsmall bx-white">Close Window</a>
    </div>
</div>

<script>
function post_update(action_url, val) {
	var f = document.fpost;
	f.cp_id.value = val;
	f.action = action_url;
	f.submit();
}
</script>

<form name="fpost" method="post">
<input type="hidden" name="gs_id" value="<?php echo $gs_id; ?>">
<input type="hidden" name="page"  value="<?php echo $page; ?>">
<input type="hidden" name="token" value="<?php echo $token; ?>">
<input type="hidden" name="cp_id" value="">
</form>
